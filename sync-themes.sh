#!/usr/bin/env bash
#
# sync-themes.sh
#
# Rsyncs the custom child themes (newspack-theme-child, newspack-radio-theme)
# from their own repos into a newspack-platform branch, then commits and
# pushes to both Pantheon (origin) and GitHub. Modeled on Newspack Radio
# Pro's sync-playerv1.sh (rsync source repos -> site repo -> push to both
# remotes).
#
# Usage:
#   ./sync-themes.sh --branch master|radio|podcast [--dry-run] [--help]
#
# Environment note (confirmed via `terminus env:list newspack`, 2026-07-24):
#   Pushing to origin on the "master" branch updates the "dev" Pantheon
#   environment directly (git-connected branch->env mapping). It does NOT
#   touch test/live -- those are promotion tiers. After verifying dev,
#   promote with:
#     terminus env:deploy newspack.test --message="Promote theme sync"
#     terminus env:deploy newspack.live --message="Promote theme sync"
#   radio and podcast have no test/live tier -- pushing to those branches
#   is immediately live for that variant.
#   "donate" is out of scope for this automation for now.
#
# STATUS: draft. Adjust THEME_REPO paths below to match where you clone
# newspack-theme-child / newspack-radio-theme locally. Always run
# --dry-run first.

set -euo pipefail

# ---- configuration -----------------------------------------------------
# Local paths to the theme source repos (clone these alongside newspack-platform)
THEME_CHILD_REPO="${THEME_CHILD_REPO:-$HOME/Dev/projects/newspack-theme-child}"
RADIO_THEME_REPO="${RADIO_THEME_REPO:-$HOME/Dev/projects/newspack-radio-theme}"

# Which themes apply to which branch. Deliberately avoids `declare -A`
# (bash associative arrays) -- macOS ships bash 3.2 by default, which
# doesn't support them, and this script has no other reason to require bash 4+.
themes_for_branch() {
  case "$1" in
    master) echo "newspack-theme-child" ;;
    radio) echo "newspack-theme-child newspack-radio-theme" ;;
    podcast) echo "newspack-theme-child newspack-radio-theme" ;;
    *) return 1 ;;
  esac
}
VALID_BRANCHES="master radio podcast"
# --------------------------------------------------------------------------

BRANCH=""
DRY_RUN=false

usage() {
  cat <<EOF
Usage: $0 --branch master|radio|podcast [options]

Options:
  --branch BRANCH  Target site-repo branch (required): master, radio, or podcast
  --dry-run        Show what would sync without committing or pushing
  --help           Show this help text
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --branch) BRANCH="$2"; shift 2 ;;
    --dry-run) DRY_RUN=true; shift ;;
    --help) usage; exit 0 ;;
    *) echo "Unknown argument: $1"; usage; exit 1 ;;
  esac
done

if [[ -z "$BRANCH" ]] || ! THEMES_LINE="$(themes_for_branch "$BRANCH")"; then
  echo "Error: --branch is required and must be one of: ${VALID_BRANCHES}"
  usage
  exit 1
fi

THEMES_FOR_BRANCH=($THEMES_LINE)

SITE_REPO_ROOT="$(git rev-parse --show-toplevel 2>/dev/null || true)"
if [[ -z "$SITE_REPO_ROOT" ]]; then
  echo "Error: run this from inside the newspack-platform repo."
  exit 1
fi

echo "== sync-themes.sh: branch=${BRANCH}, themes=${THEMES_FOR_BRANCH[*]} =="

# ---- pre-flight: site repo -------------------------------------------------
CURRENT_BRANCH=$(git -C "$SITE_REPO_ROOT" rev-parse --abbrev-ref HEAD)
if [[ "$CURRENT_BRANCH" != "$BRANCH" ]]; then
  echo "Error: site repo is on '${CURRENT_BRANCH}', expected '${BRANCH}'."
  echo "Run: git -C ${SITE_REPO_ROOT} checkout ${BRANCH}"
  exit 1
fi

if [[ -n "$(git -C "$SITE_REPO_ROOT" status --porcelain)" ]]; then
  echo "Error: site repo has uncommitted changes. Commit or stash before syncing."
  exit 1
fi

# ---- pre-flight + rsync per theme ------------------------------------------
# Plain variables instead of an associative array (bash 3.2 compatibility,
# see themes_for_branch above) -- there are only ever these two themes.
THEME_CHILD_HASH=""
RADIO_THEME_HASH=""

for THEME in "${THEMES_FOR_BRANCH[@]}"; do
  if [[ "$THEME" == "newspack-theme-child" ]]; then
    SRC="$THEME_CHILD_REPO"
  elif [[ "$THEME" == "newspack-radio-theme" ]]; then
    SRC="$RADIO_THEME_REPO"
  else
    echo "Error: unknown theme '$THEME'"
    exit 1
  fi

  if [[ ! -d "$SRC/.git" ]]; then
    echo "Error: $SRC is not a git repo. Clone $THEME there first (or set its env var override)."
    exit 1
  fi

  if [[ -n "$(git -C "$SRC" status --porcelain)" ]]; then
    echo "Error: $THEME source repo ($SRC) has uncommitted changes. Commit first."
    exit 1
  fi

  HASH=$(git -C "$SRC" rev-parse --short HEAD)
  if [[ "$THEME" == "newspack-theme-child" ]]; then
    THEME_CHILD_HASH="$HASH"
  else
    RADIO_THEME_HASH="$HASH"
  fi
  DEST="$SITE_REPO_ROOT/wp-content/themes/$THEME"

  echo ""
  echo "-- $THEME (${HASH}) -> wp-content/themes/$THEME --"
  if [[ "$DRY_RUN" == true ]]; then
    rsync -av --dry-run --delete --exclude='.git' "$SRC/" "$DEST/"
  else
    rsync -a --delete --exclude='.git' "$SRC/" "$DEST/"
  fi
done

if [[ "$DRY_RUN" == true ]]; then
  echo ""
  echo "Dry run complete. No changes committed."
  exit 0
fi

echo ""
echo "Diff in site repo:"
git -C "$SITE_REPO_ROOT" diff --stat

if [[ -z "$(git -C "$SITE_REPO_ROOT" status --porcelain)" ]]; then
  echo "No changes to commit -- themes already up to date on ${BRANCH}."
  exit 0
fi

read -r -p "Commit and push these theme changes to ${BRANCH}? [y/N] " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
  echo "Aborted. Changes left uncommitted in working tree."
  exit 0
fi

COMMIT_MSG="Sync themes:"
for THEME in "${THEMES_FOR_BRANCH[@]}"; do
  if [[ "$THEME" == "newspack-theme-child" ]]; then
    HASH="$THEME_CHILD_HASH"
  else
    HASH="$RADIO_THEME_HASH"
  fi
  COMMIT_MSG="$COMMIT_MSG ${THEME}@${HASH}"
done

git -C "$SITE_REPO_ROOT" add wp-content/themes/
git -C "$SITE_REPO_ROOT" commit -m "$COMMIT_MSG"

echo "Pushing to origin (Pantheon)..."
git -C "$SITE_REPO_ROOT" push origin "$BRANCH"

echo "Pushing to github (OpenProducer)..."
git -C "$SITE_REPO_ROOT" push github "$BRANCH"

echo ""
echo "== Summary =="
echo "Branch: ${BRANCH}"
echo "Commit: ${COMMIT_MSG}"
echo "Pushed to origin and github."

if [[ "$BRANCH" == "master" ]]; then
  echo ""
  echo "This updated the 'dev' environment only. Verify dev, then promote:"
  echo "  terminus env:deploy newspack.test --message=\"Promote theme sync\""
  echo "  terminus env:deploy newspack.live --message=\"Promote theme sync\""
fi
