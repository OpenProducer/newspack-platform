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
#   terminus env:deploy newspack.test --note="Promote plugin/theme updates"
#   # verify test, then:
#   terminus env:deploy newspack.live --note="Promote plugin/theme updates"
# (env:deploy takes --note, not --message -- --message errors immediately with
# "The '--message' option does not exist." Confirmed for real 2026-08-21.)
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

# GitHub-release packages from Automattic/newspack-workspace -- covers both
# the GH-only themes (never on WordPress.org, see prior note) AND, as of
# 2026-08-14, six core Newspack plugins whose dedicated GitHub repos
# (Automattic/newspack-plugin, newspack-ads, newspack-blocks, newspack-popups,
# newspack-listings, newspack-sponsors) were archived 2026-08-06 and merged
# into this one monorepo. Those plugins were never on WordPress.org either --
# they used to be tracked by wp-content/plugins/newspack-plugin-update-checker
# (a small third-party plugin hardcoded to each old per-repo GitHub URL), which
# we deactivated rather than fix: pointing it at the monorepo would have meant
# patching the vendored YahnisElsts PluginUpdateChecker library's version-
# number derivation (it reads the tag name directly, with no hook to strip a
# "{slug}@" monorepo prefix before comparing versions -- a custom release
# filter alone isn't enough). Simpler and more visible to do this ourselves
# here, in the same place theme updates already work this way. Re-enable
# newspack-plugin-update-checker instead of this section only if it's ever
# updated upstream for monorepo tags.
#
# The monorepo publishes one shared release feed across ~20 unrelated
# packages, tagged "{slug}@{version}" (NOT "v{version}" -- there's no "v"
# prefix to strip), with heavy pre-release noise ("-alpha.N", "-hotfix-*").
# Filter by tag PREFIX and trust GitHub's own `prerelease` flag entirely --
# don't reimplement semver comparison locally. Confirmed 2026-08-14: the core
# plugin's tag prefix changed from "newspack-plugin" to "newspack", but its
# release ZIP asset is still named newspack-plugin.zip -- prefix and zip name
# differ for that one entry only. Fetched via
# GET /repos/Automattic/newspack-workspace/releases (paginated -- a single
# per_page=100 page is NOT guaranteed to contain a match for a slow-moving
# package if faster-moving packages dominate recent activity).
GH_WORKSPACE_REPO="Automattic/newspack-workspace"

# tag_prefix|type|wp_slug|zip_asset_name -- one row per zip asset. A single
# newspack-theme release covers 6 zips (base + 5 named style variants) under
# one shared tag_prefix, so it appears 6 times here, once per zip/slug.
# Rows are a flat array (not `declare -A`) -- macOS ships bash 3.2 by
# default, which has no associative arrays (bit us once already on
# sync-themes.sh; see docs/ARCHITECTURE.md).
GH_WORKSPACE_PACKAGES=(
  "newspack|plugin|newspack-plugin|newspack-plugin.zip"
  "newspack-ads|plugin|newspack-ads|newspack-ads.zip"
  "newspack-blocks|plugin|newspack-blocks|newspack-blocks.zip"
  "newspack-popups|plugin|newspack-popups|newspack-popups.zip"
  "newspack-listings|plugin|newspack-listings|newspack-listings.zip"
  "newspack-sponsors|plugin|newspack-sponsors|newspack-sponsors.zip"
  "newspack-theme|theme|newspack-theme|newspack-theme.zip"
  "newspack-theme|theme|newspack-joseph|newspack-joseph.zip"
  "newspack-theme|theme|newspack-katharine|newspack-katharine.zip"
  "newspack-theme|theme|newspack-nelson|newspack-nelson.zip"
  "newspack-theme|theme|newspack-sacha|newspack-sacha.zip"
  "newspack-theme|theme|newspack-scott|newspack-scott.zip"
  "newspack-block-theme|theme|newspack-block-theme|newspack-block-theme.zip"
)
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
  echo "  terminus env:deploy ${PANTHEON_SITE}.test --note=\"...\""
  echo "  terminus env:deploy ${PANTHEON_SITE}.live --note=\"...\""
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
terminus auth:whoami >/dev/null </dev/null || { echo "Error: not authenticated. Run 'terminus auth:login' first."; exit 1; }

echo "Verifying environment exists..."
terminus env:info "$SITE_ENV" >/dev/null </dev/null || { echo "Error: could not find environment ${SITE_ENV}"; exit 1; }

# ---- query available updates ---------------------------------------------
# NOTE: every `terminus` call below through the two confirm prompts
# explicitly closes stdin (`</dev/null`). Confirmed by direct test
# (2026-07-25): `terminus wp` -- SSH under the hood -- silently drains
# whatever is sitting on the script's own stdin even though it never asks
# for input, which starves the `read -r -p` confirm prompts further down
# when this script is fed piped answers (or run non-interactively at all).
# Without this, the script dies silently at the first `read` once enough
# terminus calls precede it -- which is exactly what happened here once the
# GitHub-release theme check below added ~7 more such calls.
echo "Checking for plugin updates..."
PLUGIN_UPDATES_JSON=$(terminus wp "$SITE_ENV" -- plugin list --update=available --format=json </dev/null)

echo "Checking for theme updates..."
THEME_UPDATES_JSON=$(terminus wp "$SITE_ENV" -- theme list --update=available --format=json </dev/null)

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

# ---- GitHub-release workspace packages (plugins + themes, not on WordPress.org) ----
# See GH_WORKSPACE_PACKAGES above for why this exists. GH_UPDATES entries are
# "type|slug|asset_url|new_version", queued here and installed later in the
# same SFTP session as everything else.
#
# One shared release feed covers ~20 unrelated packages, so we fetch it once,
# paginated, and cache the raw pages -- NOT one API call per package (would be
# both slow and easy to rate-limit). A single page is not guaranteed to
# contain every package's latest release if faster-moving packages dominate
# recent activity, so we page forward until every distinct tag_prefix in
# GH_WORKSPACE_PACKAGES has been found at least once, up to GH_MAX_PAGES.
echo ""
echo "-- GitHub-release workspace packages (plugins + themes, not on WordPress.org) --"
GH_UPDATES=()
GH_MAX_PAGES=5
GH_PER_PAGE=100
GH_RELEASES_CACHE=$(mktemp)
GH_PAGE_TMP=$(mktemp)
GH_MERGE_TMP=$(mktemp)
trap 'rm -f "$GH_RELEASES_CACHE" "$GH_PAGE_TMP" "$GH_MERGE_TMP"' EXIT

PREFIXES="$(printf '%s\n' "${GH_WORKSPACE_PACKAGES[@]}" | cut -d'|' -f1 | sort -u | tr '\n' ' ')"

echo "[]" > "$GH_RELEASES_CACHE"
for GH_PAGE in $(seq 1 "$GH_MAX_PAGES"); do
  if ! curl -sf "https://api.github.com/repos/${GH_WORKSPACE_REPO}/releases?per_page=${GH_PER_PAGE}&page=${GH_PAGE}" -o "$GH_PAGE_TMP"; then
    echo "  WARN  page ${GH_PAGE}: could not fetch (network error or rate-limited) -- stopping pagination"
    break
  fi

  # Merge this page into the running cache (both are JSON files on disk --
  # never embed API response bodies directly into inline Python/shell source,
  # that's a quoting/escaping hazard once real payloads are involved).
  python3 -c "
import json
existing = json.load(open('${GH_RELEASES_CACHE}'))
page = json.load(open('${GH_PAGE_TMP}'))
existing.extend(page)
json.dump(existing, open('${GH_MERGE_TMP}', 'w'))
" 2>/dev/null || { echo "  WARN  page ${GH_PAGE}: could not parse -- stopping pagination"; break; }
  mv "$GH_MERGE_TMP" "$GH_RELEASES_CACHE"
  GH_MERGE_TMP=$(mktemp)

  PAGE_LEN=$(python3 -c "import json; print(len(json.load(open('${GH_PAGE_TMP}'))))" 2>/dev/null || echo 0)

  # Stop early once every distinct prefix we need has turned up at least once
  # (avoids paging through all ~5700 releases on every run).
  MISSING=$(PREFIXES="$PREFIXES" python3 -c "
import json, os
releases = json.load(open('${GH_RELEASES_CACHE}'))
prefixes = os.environ['PREFIXES'].split()
# Only count a prefix as 'found' once a STABLE release (not draft, not
# prerelease) has actually turned up -- matches the per-package resolution
# loop below exactly. Counting any tag (including prereleases) here caused a
# real bug: a hotfix wave put a prerelease tag for every tracked package on
# page 1, satisfying this check for everyone and stopping pagination before
# the real stable releases (living on later pages) were ever fetched.
found = {
    r['tag_name'].split('@')[0] for r in releases
    if '@' in r.get('tag_name', '') and not r.get('draft') and not r.get('prerelease')
}
print(' '.join(p for p in prefixes if p not in found))
" 2>/dev/null || echo "")
  if [[ -z "$MISSING" ]]; then
    break
  fi
  if [[ "$PAGE_LEN" -lt "$GH_PER_PAGE" ]]; then
    echo "  WARN  reached end of release feed with no match for: ${MISSING}"
    break
  fi
done

for ENTRY in "${GH_WORKSPACE_PACKAGES[@]}"; do
  IFS='|' read -r TAG_PREFIX PKG_TYPE SLUG ZIP_NAME <<< "$ENTRY"

  RELEASE_JSON=$(TAG_PREFIX="$TAG_PREFIX" python3 -c "
import json, os
releases = json.load(open('${GH_RELEASES_CACHE}'))
prefix = os.environ['TAG_PREFIX'] + '@'
for r in releases:
    if r.get('draft'):
        continue
    if r.get('prerelease'):
        continue
    if r.get('tag_name', '').startswith(prefix):
        print(json.dumps(r))
        break
")
  if [[ -z "$RELEASE_JSON" ]]; then
    echo "  WARN  ${SLUG}: no stable release found for tag prefix '${TAG_PREFIX}@' in the first ${GH_MAX_PAGES} page(s) -- skipping"
    continue
  fi
  TAG=$(echo "$RELEASE_JSON" | python3 -c "import json,sys; print(json.load(sys.stdin)['tag_name'])")
  TAG_VERSION="${TAG#${TAG_PREFIX}@}"

  if [[ "$PKG_TYPE" == "theme" ]]; then
    INSTALLED_VERSION=$(terminus wp "$SITE_ENV" -- theme get "$SLUG" --field=version 2>/dev/null </dev/null) || INSTALLED_VERSION=""
  else
    INSTALLED_VERSION=$(terminus wp "$SITE_ENV" -- plugin get "$SLUG" --field=version 2>/dev/null </dev/null) || INSTALLED_VERSION=""
  fi
  if [[ -z "$INSTALLED_VERSION" ]]; then
    echo "  SKIP (not installed on this environment)  ${SLUG}"
    continue
  fi
  if [[ "$INSTALLED_VERSION" == "$TAG_VERSION" ]]; then
    echo "  up to date  ${SLUG}: ${INSTALLED_VERSION}"
    continue
  fi

  ASSET_URL=$(echo "$RELEASE_JSON" | ZIP_NAME="$ZIP_NAME" python3 -c "
import json, os, sys
data = json.load(sys.stdin)
zip_name = os.environ['ZIP_NAME']
for a in data.get('assets', []):
    if a['name'] == zip_name:
        print(a['browser_download_url'])
        break
")
  if [[ -z "$ASSET_URL" ]]; then
    echo "  WARN  ${SLUG}: release ${TAG} has no matching ${ZIP_NAME} asset -- skipping"
    continue
  fi
  echo "  update  ${SLUG} (${PKG_TYPE}): ${INSTALLED_VERSION} -> ${TAG_VERSION}"
  GH_UPDATES+=("${PKG_TYPE}|${SLUG}|${ASSET_URL}|${TAG_VERSION}")
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
terminus connection:set "$SITE_ENV" sftp </dev/null

echo "Updating plugins..."
terminus wp "$SITE_ENV" -- plugin update --all </dev/null

echo "Updating themes (excluding guarded: ${GUARDED_THEMES[*]})..."
THEME_SLUGS=$(echo "$THEME_UPDATES_JSON" | GUARDED="${GUARDED_THEMES[*]}" python3 -c "
import json, sys, os
data = json.load(sys.stdin)
guarded = set(os.environ['GUARDED'].split())
print(' '.join(t['name'] for t in data if t.get('name') not in guarded))
")
if [[ -n "$THEME_SLUGS" ]]; then
  # shellcheck disable=SC2086
  terminus wp "$SITE_ENV" -- theme update $THEME_SLUGS </dev/null
else
  echo "No non-guarded theme updates to apply."
fi

echo ""
if [[ ${#GH_UPDATES[@]} -gt 0 ]]; then
  echo "Installing GitHub-release workspace package updates (plugins + themes)..."
  for ENTRY in "${GH_UPDATES[@]}"; do
    IFS='|' read -r PKG_TYPE SLUG ASSET_URL NEW_VERSION <<< "$ENTRY"
    echo "-- ${SLUG} (${PKG_TYPE}) -> ${NEW_VERSION} --"
    if [[ "$PKG_TYPE" == "theme" ]]; then
      terminus wp "$SITE_ENV" -- theme install "$ASSET_URL" --force </dev/null
    else
      terminus wp "$SITE_ENV" -- plugin install "$ASSET_URL" --force </dev/null
    fi
  done
else
  echo "No GitHub-release workspace package updates to apply."
fi

echo ""
echo "Diff on ${SITE_ENV}:"
# NOTE: this has been observed to report "No changes on server" immediately
# after a real update -- Pantheon's diff index lags the actual filesystem
# write by a few seconds. Treat this output as advisory only, not proof
# either way. The env:commit step below is the real check.
terminus env:diffstat "$SITE_ENV" </dev/null || true

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
  COMMIT_MSG="${COMMIT_MSG} + newspack-workspace releases:"
  for ENTRY in "${GH_UPDATES[@]}"; do
    IFS='|' read -r PKG_TYPE SLUG ASSET_URL NEW_VERSION <<< "$ENTRY"
    # newspack-block-theme's version-compare trigger is a known false positive
    # (see docs/ARCHITECTURE.md) -- the installed code is already correct,
    # only style.css's cosmetic Version header lags. Word the commit message
    # accurately instead of implying a real version change happened.
    if [[ "$SLUG" == "newspack-block-theme" ]]; then
      COMMIT_MSG="${COMMIT_MSG} reinstall ${SLUG} (no functional change; corrects leaked dev-tooling files from release zip, see docs/ARCHITECTURE.md for the upstream style.css version-lag quirk)"
    else
      COMMIT_MSG="${COMMIT_MSG} ${SLUG}@${NEW_VERSION}"
    fi
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
  COMMIT_OUTPUT=$(terminus env:commit "$SITE_ENV" --message="$COMMIT_MSG" 2>&1 </dev/null) || true
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
MODE=$(terminus env:info "$SITE_ENV" --field=connection_mode </dev/null)
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
  echo "  terminus env:deploy ${PANTHEON_SITE}.test --note=\"Promote plugin/theme updates\""
  echo "  terminus env:deploy ${PANTHEON_SITE}.live --note=\"Promote plugin/theme updates\""
fi
