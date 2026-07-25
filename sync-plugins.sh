#!/usr/bin/env bash
#
# sync-plugins.sh
#
# Updates every WordPress.org-hosted plugin and theme on a Newspack Platform
# Pantheon environment via Terminus + WP-CLI, in SFTP mode, then commits the
# result on Pantheon. Modeled on Newspack Radio Pro's sync-plugins.sh.
#
# Guarded themes (newspack-theme-child, newspack-radio-theme) are NEVER
# touched by this script -- they are not on WordPress.org and are managed
# exclusively by sync-themes.sh.
#
# newspack-theme (+ its 5 named style variants) and newspack-block-theme are
# ALSO not on WordPress.org (confirmed 2026-07-25: Theme URI points to
# Automattic's GitHub repos, no Update URI header) -- `wp theme update` can
# never see updates for them, a real coverage gap `wp plugin/theme
# list --update=available` silently can't fill. These are checked via the
# GitHub Releases API instead (see the GH_THEME_REPOS section below) and
# folded into the same SFTP session, diff, and commit as everything else in
# this script -- they are NOT guarded/excluded like newspack-theme-child and
# newspack-radio-theme are.
#
# Environment model (confirmed via `terminus env:list newspack`, 2026-07-24):
#   dev, radio, podcast  -- this script's only valid targets.
#   test, live           -- master branch's production tiers. Code reaches
#                           them via `terminus env:deploy`, promoted from a
#                           validated dev, NOT via direct WP-CLI updates.
#                           This script refuses --env test / --env live.
#   donate               -- out of scope for this automation (2026-07-24).
#
# After a successful --env dev run and manual verification, promote with:
#   terminus env:deploy newspack.test --message="Promote plugin/theme updates"
#   # verify test, then:
#   terminus env:deploy newspack.live --message="Promote plugin/theme updates"
#
# radio and podcast have no test/live tier -- the multidev itself is the
# live site for that variant, so a sync there is immediately live.
#
# Usage:
#   ./sync-plugins.sh --env dev|radio|podcast [--dry-run] [--skip-commit] [--help]
#
# STATUS: draft. Adjust PANTHEON_SITE and paths below before first real run.
# Always run --dry-run first on each environment.

set -euo pipefail

# ---- configuration -----------------------------------------------------
PANTHEON_SITE="newspack"          # Pantheon site machine name -- confirmed via `terminus site:info newspack`
GUARDED_THEMES=("newspack-theme-child" "newspack-radio-theme")
VALID_ENVS=("dev" "radio" "podcast")   # test/live/donate deliberately excluded -- see note above

# GitHub-Releases-sourced themes: repos with no WordPress.org presence, so
# `wp theme update` never sees them. Checked via
# GET /repos/{owner}/{repo}/releases/latest -- trust GitHub's own
# prerelease/draft filtering entirely; don't reimplement semver comparison
# locally. One repo can cover multiple installed theme slugs: a single
# newspack-theme release ships 6 separately-zipped variants (the base theme
# plus 5 named style variants) as release assets, one zip per slug.
#
# KNOWN UPSTREAM QUIRK (confirmed 2026-07-25): newspack-block-theme's build
# pipeline correctly bumps a version constant in functions.php but leaves
# style.css's WP-standard `Version:` header one release behind -- e.g. the
# official v1.28.1 "stable" release zip's own style.css says
# "Version: 1.28.1-alpha.1". Since `wp theme get --field=version` (and WP
# core itself) only ever reads style.css, the version-compare step below can
# flag newspack-block-theme as needing an update even when it's already
# current. This is expected and harmless: a false-positive here just
# re-installs identical bytes, the resulting diff comes back empty, and
# nothing gets committed (see the "No changes to commit" handling near the
# end of this script). Do NOT try to "fix" this by re-checking the version
# string after install -- `wp theme install`'s own exit code/output is the
# only success signal that matters here.
GH_THEME_REPOS=("Automattic/newspack-theme" "Automattic/newspack-block-theme")

# Maps a GitHub repo to the site theme slug(s) its releases cover. Kept as a
# case statement rather than `declare -A` (bash associative arrays) --
# macOS ships bash 3.2 by default, which doesn't support them (bit us once
# already on sync-themes.sh; see docs/ARCHITECTURE.md).
slugs_for_gh_repo() {
  case "$1" in
    Automattic/newspack-theme) echo "newspack-theme newspack-joseph newspack-katharine newspack-nelson newspack-sacha newspack-scott" ;;
    Automattic/newspack-block-theme) echo "newspack-block-theme" ;;
    *) echo "" ;;
  esac
}
# --------------------------------------------------------------------------

ENV=""
DRY_RUN=false
SKIP_COMMIT=false

usage() {
  cat <<EOF
Usage: $0 --env dev|radio|podcast [options]

Options:
  --env ENV        Target Pantheon environment (required): dev, radio, or podcast
                    (test/live are promotion tiers, not valid here -- see script header)
  --dry-run        Show available updates without making any changes
  --skip-commit    Run updates but stop before committing (leaves env in SFTP mode)
  --help           Show this help text
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --env) ENV="$2"; shift 2 ;;
    --dry-run) DRY_RUN=true; shift ;;
    --skip-commit) SKIP_COMMIT=true; shift ;;
    --help) usage; exit 0 ;;
    *) echo "Unknown argument: $1"; usage; exit 1 ;;
  esac
done

if [[ -z "$ENV" ]]; then
  echo "Error: --env is required (dev, radio, or podcast)"
  usage
  exit 1
fi

if [[ "$ENV" == "test" || "$ENV" == "live" ]]; then
  echo "Error: '${ENV}' is a promotion tier, not a sync target."
  echo "Run and verify '--env dev' first, then promote with:"
  echo "  terminus env:deploy ${PANTHEON_SITE}.test --message=\"...\""
  echo "  terminus env:deploy ${PANTHEON_SITE}.live --message=\"...\""
  exit 1
fi

if [[ "$ENV" == "donate" ]]; then
  echo "Error: 'donate' is out of scope for this automation for now."
  exit 1
fi

if [[ ! " ${VALID_ENVS[*]} " =~ " ${ENV} " ]]; then
  echo "Error: --env must be one of: ${VALID_ENVS[*]}"
  exit 1
fi

SITE_ENV="${PANTHEON_SITE}.${ENV}"

echo "== sync-plugins.sh: ${SITE_ENV} =="

# ---- pre-flight ----------------------------------------------------------
if ! command -v terminus >/dev/null 2>&1; then
  echo "Error: terminus CLI not found. Install it first: https://docs.pantheon.io/terminus/install"
  exit 1
fi

echo "Verifying Terminus auth..."
terminus auth:whoami >/dev/null || { echo "Error: not authenticated. Run 'terminus auth:login' first."; exit 1; }

echo "Verifying environment exists..."
terminus env:info "$SITE_ENV" >/dev/null || { echo "Error: could not find environment ${SITE_ENV}"; exit 1; }

# ---- query available updates ---------------------------------------------
echo "Checking for plugin updates..."
PLUGIN_UPDATES_JSON=$(terminus wp "$SITE_ENV" -- plugin list --update=available --format=json)

echo "Checking for theme updates..."
THEME_UPDATES_JSON=$(terminus wp "$SITE_ENV" -- theme list --update=available --format=json)

# Filter out pre-release versions (alpha/beta/rc) -- flagged, not updated
PRERELEASE_PATTERN='alpha|beta|rc'

echo ""
echo "-- Plugin updates available --"
echo "$PLUGIN_UPDATES_JSON" | python3 -c "
import json, re, sys
data = json.load(sys.stdin)
prerelease = re.compile(r'alpha|beta|rc', re.I)
to_update, skipped = [], []
for p in data:
    v = p.get('update_version', '')
    (skipped if prerelease.search(v) else to_update).append(p)
for p in to_update:
    print(f\"  update  {p['name']}: {p['version']} -> {p['update_version']}\")
for p in skipped:
    print(f\"  SKIP (pre-release)  {p['name']}: {p.get('update_version')}\")
"

echo ""
echo "-- Theme updates available (guarded themes excluded) --"
echo "$THEME_UPDATES_JSON" | GUARDED="${GUARDED_THEMES[*]}" python3 -c "
import json, re, sys, os
data = json.load(sys.stdin)
guarded = set(os.environ['GUARDED'].split())
prerelease = re.compile(r'alpha|beta|rc', re.I)
for t in data:
    if t.get('name') in guarded:
        print(f\"  SKIP (guarded, managed by sync-themes.sh)  {t['name']}\")
        continue
    v = t.get('update_version', '')
    if prerelease.search(v):
        print(f\"  SKIP (pre-release)  {t['name']}: {v}\")
        continue
    print(f\"  update  {t['name']}: {t.get('version')} -> {v}\")
"

# ---- GitHub-release theme updates (not on WordPress.org) -----------------
# See GH_THEME_REPOS above for why this exists and the newspack-block-theme
# version-string caveat. GH_UPDATES entries are "slug|asset_url|new_version",
# queued here and installed later in the same SFTP session as everything else.
echo ""
echo "-- GitHub-release theme updates (not on WordPress.org) --"
GH_UPDATES=()
for GH_REPO in "${GH_THEME_REPOS[@]}"; do
  RELEASE_JSON=$(curl -sf "https://api.github.com/repos/${GH_REPO}/releases/latest") || {
    echo "  WARN  ${GH_REPO}: could not fetch latest release (network error or rate-limited) -- skipping"
    continue
  }
  TAG=$(echo "$RELEASE_JSON" | python3 -c "import json,sys; print(json.load(sys.stdin).get('tag_name',''))")
  if [[ -z "$TAG" ]]; then
    echo "  WARN  ${GH_REPO}: no releases found -- skipping"
    continue
  fi
  TAG_VERSION="${TAG#v}"
  for SLUG in $(slugs_for_gh_repo "$GH_REPO"); do
    INSTALLED_VERSION=$(terminus wp "$SITE_ENV" -- theme get "$SLUG" --field=version 2>/dev/null) || INSTALLED_VERSION=""
    if [[ -z "$INSTALLED_VERSION" ]]; then
      echo "  SKIP (not installed on this environment)  ${SLUG}"
      continue
    fi
    if [[ "$INSTALLED_VERSION" == "$TAG_VERSION" ]]; then
      echo "  up to date  ${SLUG}: ${INSTALLED_VERSION}"
      continue
    fi
    ASSET_URL=$(echo "$RELEASE_JSON" | SLUG="$SLUG" python3 -c "
import json, os, sys
data = json.load(sys.stdin)
slug = os.environ['SLUG']
for a in data.get('assets', []):
    if a['name'] == f'{slug}.zip':
        print(a['browser_download_url'])
        break
")
    if [[ -z "$ASSET_URL" ]]; then
      echo "  WARN  ${SLUG}: release ${TAG} has no matching ${SLUG}.zip asset -- skipping"
      continue
    fi
    echo "  update  ${SLUG}: ${INSTALLED_VERSION} -> ${TAG_VERSION}"
    GH_UPDATES+=("${SLUG}|${ASSET_URL}|${TAG_VERSION}")
  done
done

if [[ "$DRY_RUN" == true ]]; then
  echo ""
  echo "Dry run complete. No changes made."
  exit 0
fi

read -r -p "Proceed with updates on ${SITE_ENV}? [y/N] " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
  echo "Aborted."
  exit 0
fi

# ---- apply updates ---------------------------------------------------------
echo "Switching ${SITE_ENV} to SFTP mode..."
terminus connection:set "$SITE_ENV" sftp

echo "Updating plugins..."
terminus wp "$SITE_ENV" -- plugin update --all

echo "Updating themes (excluding guarded: ${GUARDED_THEMES[*]})..."
THEME_SLUGS=$(echo "$THEME_UPDATES_JSON" | GUARDED="${GUARDED_THEMES[*]}" python3 -c "
import json, sys, os
data = json.load(sys.stdin)
guarded = set(os.environ['GUARDED'].split())
print(' '.join(t['name'] for t in data if t.get('name') not in guarded))
")
if [[ -n "$THEME_SLUGS" ]]; then
  # shellcheck disable=SC2086
  terminus wp "$SITE_ENV" -- theme update $THEME_SLUGS
else
  echo "No non-guarded theme updates to apply."
fi

echo ""
if [[ ${#GH_UPDATES[@]} -gt 0 ]]; then
  echo "Installing GitHub-release theme updates..."
  for ENTRY in "${GH_UPDATES[@]}"; do
    IFS='|' read -r SLUG ASSET_URL NEW_VERSION <<< "$ENTRY"
    echo "-- ${SLUG} -> ${NEW_VERSION} --"
    terminus wp "$SITE_ENV" -- theme install "$ASSET_URL" --force
  done
else
  echo "No GitHub-release theme updates to apply."
fi

echo ""
echo "Diff on ${SITE_ENV}:"
# NOTE: this has been observed to report "No changes on server" immediately
# after a real update -- Pantheon's diff index lags the actual filesystem
# write by a few seconds. Treat this output as advisory only, not proof
# either way. The env:commit step below is the real check.
terminus env:diffstat "$SITE_ENV" || true

if [[ "$SKIP_COMMIT" == true ]]; then
  echo "Skipping commit as requested (--skip-commit). Environment left in SFTP mode."
  exit 0
fi

read -r -p "Commit these changes on ${SITE_ENV}? [y/N] " CONFIRM_COMMIT
if [[ ! "$CONFIRM_COMMIT" =~ ^[Yy]$ ]]; then
  echo "Leaving ${SITE_ENV} in SFTP mode uncommitted. Run again or commit manually."
  exit 0
fi

COMMIT_MSG="Sync plugins (${ENV}): $(date +%Y-%m-%d)"
if [[ ${#GH_UPDATES[@]} -gt 0 ]]; then
  COMMIT_MSG="${COMMIT_MSG} + GitHub theme releases:"
  for ENTRY in "${GH_UPDATES[@]}"; do
    IFS='|' read -r SLUG ASSET_URL NEW_VERSION <<< "$ENTRY"
    COMMIT_MSG="${COMMIT_MSG} ${SLUG}@${NEW_VERSION}"
  done
fi

# Pantheon can take a few seconds to register SFTP-mode filesystem changes
# before env:commit sees them -- committing too early can report "no code
# to commit" even though real changes are sitting on disk. Retry with
# backoff instead of trusting a single attempt.
COMMITTED=false
for ATTEMPT in 1 2 3; do
  echo "Committing (attempt ${ATTEMPT}/3)..."
  sleep $((ATTEMPT * 5))
  COMMIT_OUTPUT=$(terminus env:commit "$SITE_ENV" --message="$COMMIT_MSG" 2>&1) || true
  echo "$COMMIT_OUTPUT"
  if echo "$COMMIT_OUTPUT" | grep -qi "your code was committed"; then
    COMMITTED=true
    break
  fi
  if echo "$COMMIT_OUTPUT" | grep -qi "no code to commit"; then
    echo "No changes registered yet -- waiting and retrying..."
    continue
  fi
  # Any other output (a real error) -- stop immediately, don't touch mode.
  echo "Error: unexpected env:commit output. Environment left in SFTP mode -- check manually before switching modes."
  exit 1
done

if [[ "$COMMITTED" != true ]]; then
  echo ""
  echo "Error: env:commit never confirmed a commit after 3 attempts."
  echo "The plugin/theme updates may still be sitting uncommitted on ${SITE_ENV}."
  echo "DO NOT run 'terminus connection:set ${SITE_ENV} git' until you've confirmed"
  echo "via the Pantheon dashboard (Code tab) whether there's real work pending --"
  echo "switching modes with pending changes discards them without saving."
  echo "Environment left in SFTP mode."
  exit 1
fi

# Even after a confirmed commit, Pantheon's own "is this environment clean"
# check can lag behind for several seconds, surfacing a spurious "uncommitted
# changes" prompt for work that already landed. Retry with backoff, same as
# the commit step, instead of treating the first attempt as final.
SWITCHED=false
for ATTEMPT in 1 2 3; do
  echo "Switching ${SITE_ENV} back to Git mode (attempt ${ATTEMPT}/3)..."
  sleep $((ATTEMPT * 8))
  # Stdin is deliberately closed: if Terminus shows its own "are you sure,
  # this will discard uncommitted work" prompt, we want it to hit EOF and
  # default to "no" rather than hang or risk an unattended "yes" discarding
  # real changes.
  CONNSET_OUTPUT=$(terminus connection:set "$SITE_ENV" git 2>&1 < /dev/null) || true
  echo "$CONNSET_OUTPUT"
  if echo "$CONNSET_OUTPUT" | grep -qi "uncommitted changes"; then
    echo "Still reports uncommitted changes -- waiting and retrying..."
    continue
  fi
  SWITCHED=true
  break
done

if [[ "$SWITCHED" != true ]]; then
  echo ""
  echo "Error: could not switch ${SITE_ENV} back to Git mode after 3 attempts --"
  echo "Terminus keeps reporting uncommitted changes despite a confirmed commit."
  echo "Do NOT blindly confirm a mode-switch prompt by hand; check the Pantheon"
  echo "dashboard Code tab first to see what it thinks is still uncommitted."
  echo "Environment left in SFTP mode. Run 'terminus connection:set ${SITE_ENV} git'"
  echo "manually once you've confirmed it's safe."
  exit 1
fi

# NOTE: mode lives under env:info (matches the "Connection Mode" column in
# `terminus env:list`), not connection:info -- connection:info only returns
# SFTP/Git/MySQL connection strings.
MODE=$(terminus env:info "$SITE_ENV" --field=connection_mode)
if [[ "$MODE" != "git" ]]; then
  echo "Error: environment did not return to Git mode (reported: '${MODE}'). Check manually."
  exit 1
fi

echo ""
echo "== Summary =="
echo "Environment: ${SITE_ENV}"
echo "Commit: ${COMMIT_MSG}"
echo "Mode restored to Git."
echo "Note: this commits to Pantheon's internal git for ${ENV} only."
echo "Sync to the 'github' remote separately if GitHub should reflect this update (see open question in architecture doc)."

if [[ "$ENV" == "dev" ]]; then
  echo ""
  echo "Next: verify dev, then promote to test and live when ready:"
  echo "  terminus env:deploy ${PANTHEON_SITE}.test --message=\"Promote plugin/theme updates\""
  echo "  terminus env:deploy ${PANTHEON_SITE}.live --message=\"Promote plugin/theme updates\""
fi
