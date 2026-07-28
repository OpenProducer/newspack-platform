#!/usr/bin/env bash
#
# sync-content.sh
#
# Pulls the WordPress database from Pantheon's `live` environment down into
# the local WordPress Studio site (see docs/ARCHITECTURE.md, "Local rapid
# development" for how that site was set up). One-way only: live -> local.
# Never pushes local content back up to Pantheon.
#
# DB only by default (2026-07-27 decision). Pass --with-files to also pull
# wp-content/uploads from live's files backup -- opt-in, since files backups
# can be large and slow. Without it, local previews will show broken images
# for real uploads; that's an accepted tradeoff for pure theme/PHP iteration.
#
# Usage:
#   ./sync-content.sh [--dry-run] [--with-files] [--local-url URL] [--help]
#
# STATUS: confirmed working end to end (DB path) 2026-07-27/28, see
# docs/ARCHITECTURE.md Progress log for the two real bugs found and fixed
# along the way (SQLite/MySQL, and wp db import's broken SOURCE mechanism).
# --with-files: the originally assumed archive layout (wp-content/uploads/
# inside the tarball) was wrong -- confirmed via a real files backup that
# Pantheon's actual layout is a single top-level files_<env>/ directory
# whose contents directly ARE the uploads directory. Fixed and not yet
# run for real end to end (rsync step) -- see NOTE at the fallback below.

set -euo pipefail

# ---- configuration -----------------------------------------------------
PANTHEON_SITE="newspack"
VALID_ENVS=("live")   # deliberately locked to live -- see docs/ARCHITECTURE.md
ENV="live"
LIVE_URL="https://live-newspack.pantheonsite.io"
STUDIO_SITE_PATH="${STUDIO_SITE_PATH:-$HOME/Dev/local/wordpress/studio/newspack-platform}"
# --------------------------------------------------------------------------

DRY_RUN=false
WITH_FILES=false
LOCAL_URL=""

usage() {
  cat <<EOF
Usage: $0 [options]

Options:
  --dry-run          Validate preconditions and show the plan; no backup, no import
  --with-files        Also pull wp-content/uploads from live's files backup (slower, opt-in)
  --local-url URL     Local Studio site URL for search-replace (e.g. http://localhost:8884)
                       If omitted, the script tries to detect it from 'studio site status'.
  --help              Show this help text

This script only ever targets Pantheon's 'live' environment as the source,
and only ever writes to the local Studio site at:
  ${STUDIO_SITE_PATH}
(override with the STUDIO_SITE_PATH env var). It never pushes local changes
back to Pantheon.
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --dry-run) DRY_RUN=true; shift ;;
    --with-files) WITH_FILES=true; shift ;;
    --local-url) LOCAL_URL="$2"; shift 2 ;;
    --help) usage; exit 0 ;;
    *) echo "Unknown argument: $1"; usage; exit 1 ;;
  esac
done

SITE_ENV="${PANTHEON_SITE}.${ENV}"

echo "== sync-content.sh: ${SITE_ENV} -> ${STUDIO_SITE_PATH} (DB only, one-way) =="

# ---- pre-flight ------------------------------------------------------------
if ! command -v terminus >/dev/null 2>&1; then
  echo "Error: terminus CLI not found. Install it first: https://docs.pantheon.io/terminus/install"
  exit 1
fi

if ! command -v studio >/dev/null 2>&1; then
  echo "Error: studio CLI not found. Enable it in the Studio app's Settings, or install standalone:"
  echo "  curl -fsSL https://wordpress.studio/install.sh | bash"
  exit 1
fi

echo "Verifying Terminus auth..."
terminus auth:whoami >/dev/null || { echo "Error: not authenticated. Run 'terminus auth:login' first."; exit 1; }

echo "Verifying ${SITE_ENV} exists..."
terminus env:info "$SITE_ENV" >/dev/null || { echo "Error: could not find environment ${SITE_ENV}"; exit 1; }

if [[ ! -d "$STUDIO_SITE_PATH" ]]; then
  echo "Error: Studio site directory not found at ${STUDIO_SITE_PATH}"
  echo "Set STUDIO_SITE_PATH or create the site first (see docs/ARCHITECTURE.md)."
  exit 1
fi

echo "Verifying local Studio site is running..."
studio site status --path "$STUDIO_SITE_PATH" || {
  echo "Error: could not get site status. Is it created? Try: studio site start --path ${STUDIO_SITE_PATH}"
  exit 1
}

# ---- resolve local URL -------------------------------------------------
if [[ -z "$LOCAL_URL" ]]; then
  echo "Detecting local site URL from 'studio site status'..."
  LOCAL_URL=$(studio site status --path "$STUDIO_SITE_PATH" 2>/dev/null | grep -Eo 'https?://localhost:[0-9]+' | head -1 || true)
  if [[ -z "$LOCAL_URL" ]]; then
    echo "Error: could not auto-detect the local URL. Pass it explicitly: --local-url http://localhost:PORT"
    exit 1
  fi
fi
echo "Local Studio URL: ${LOCAL_URL}"
echo "Live URL (search-replace source): ${LIVE_URL}"

if [[ "$DRY_RUN" == true ]]; then
  echo ""
  echo "-- Dry run: would perform the following --"
  echo "  1. terminus backup:create ${SITE_ENV} --element=db --keep-for=1"
  echo "  2. terminus backup:get ${SITE_ENV} --element=db  (resolve download URL)"
  echo "  3. Download + gunzip the db backup to a temp file"
  echo "  4. mysql --database=<from wp-config.php> < <file>  (direct import; 'wp db import' is incompatible with this mysql client, see NOTE below)"
  echo "  5. studio wp search-replace '${LIVE_URL}' '${LOCAL_URL}' --all-tables --path=${STUDIO_SITE_PATH}"
  echo "  6. studio wp cache flush --path=${STUDIO_SITE_PATH}"
  if [[ "$WITH_FILES" == true ]]; then
    echo "  7. [--with-files] terminus backup:create ${SITE_ENV} --element=files --keep-for=1"
    echo "  8. [--with-files] terminus backup:get ${SITE_ENV} --element=files  (resolve download URL)"
    echo "  9. [--with-files] Download + extract the files backup, rsync its wp-content/uploads into the local site's wp-content/uploads (--delete, so local becomes an exact copy of live's uploads)"
  fi
  echo ""
  echo "No changes made. Preconditions all passed."
  exit 0
fi

echo ""
if [[ "$WITH_FILES" == true ]]; then
  echo "This will OVERWRITE the local Studio site's database AND wp-content/uploads with a fresh copy of ${SITE_ENV}'s content."
else
  echo "This will OVERWRITE the local Studio site's database with a fresh copy of ${SITE_ENV}'s content."
fi
read -r -p "Proceed? [y/N] " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
  echo "Aborted."
  exit 0
fi

# ---- create + fetch backup --------------------------------------------
TMP_DIR="$(mktemp -d)"
trap 'rm -rf "$TMP_DIR"' EXIT

echo "Creating fresh db backup on ${SITE_ENV} (this can take a minute)..."
ATTEMPT=1
until terminus backup:create "$SITE_ENV" --element=db --keep-for=1 < /dev/null; do
  if [[ $ATTEMPT -ge 3 ]]; then
    echo "Error: backup:create failed after ${ATTEMPT} attempts."
    exit 1
  fi
  ATTEMPT=$((ATTEMPT + 1))
  sleep $((ATTEMPT * 5))
done

echo "Resolving backup download URL..."
# NOTE: verify this is the correct field/flag against real Terminus output --
# assumed here based on documented behavior ("backup:get displays the URL for
# the most recent element backup"), not yet confirmed against this account's
# Terminus version.
BACKUP_URL=$(terminus backup:get "$SITE_ENV" --element=db < /dev/null)
if [[ -z "$BACKUP_URL" ]]; then
  echo "Error: could not resolve a backup URL from 'terminus backup:get'."
  exit 1
fi

echo "Downloading db backup..."
curl -fsSL "$BACKUP_URL" -o "$TMP_DIR/db.sql.gz"
gunzip "$TMP_DIR/db.sql.gz"

# ---- import into local Studio site -------------------------------------
# NOTE: `studio wp db import` shells out to the mysql client with the SQL
# dump wrapped in a `SOURCE file;` statement passed via --execute. Modern
# mysql clients (confirmed: Homebrew mysql 9.7.1) no longer treat `SOURCE`
# as a client-side meta-command in that context -- it gets sent to the
# server as literal SQL and fails with "ERROR 1064 ... near 'SOURCE'".
# Piping the file directly into `mysql` sidesteps this entirely.
echo "Importing into local Studio site (this overwrites the local DB)..."
DB_NAME=$(studio wp config get DB_NAME --path="$STUDIO_SITE_PATH" < /dev/null)
DB_USER=$(studio wp config get DB_USER --path="$STUDIO_SITE_PATH" < /dev/null)
DB_PASSWORD=$(studio wp config get DB_PASSWORD --path="$STUDIO_SITE_PATH" < /dev/null)
DB_HOST=$(studio wp config get DB_HOST --path="$STUDIO_SITE_PATH" < /dev/null)
mysql --no-defaults --host="$DB_HOST" --user="$DB_USER" --password="$DB_PASSWORD" \
  --default-character-set=utf8mb4 --database="$DB_NAME" < "$TMP_DIR/db.sql"

echo "Rewriting URLs: ${LIVE_URL} -> ${LOCAL_URL}"
studio wp search-replace "$LIVE_URL" "$LOCAL_URL" --all-tables --path="$STUDIO_SITE_PATH"

echo "Flushing cache..."
studio wp cache flush --path="$STUDIO_SITE_PATH" || true

FILES_SYNCED=false
if [[ "$WITH_FILES" == true ]]; then
  echo ""
  echo "Creating fresh files backup on ${SITE_ENV} (this can be slow depending on media library size)..."
  ATTEMPT=1
  until terminus backup:create "$SITE_ENV" --element=files --keep-for=1 < /dev/null; do
    if [[ $ATTEMPT -ge 3 ]]; then
      echo "Error: files backup:create failed after ${ATTEMPT} attempts. DB sync above already completed successfully; files were not synced."
      exit 1
    fi
    ATTEMPT=$((ATTEMPT + 1))
    sleep $((ATTEMPT * 5))
  done

  echo "Resolving files backup download URL..."
  FILES_BACKUP_URL=$(terminus backup:get "$SITE_ENV" --element=files < /dev/null)
  if [[ -z "$FILES_BACKUP_URL" ]]; then
    echo "Error: could not resolve a files backup URL. DB sync above already completed successfully; files were not synced."
    exit 1
  fi

  echo "Downloading files backup (this may take a while)..."
  curl -fsSL "$FILES_BACKUP_URL" -o "$TMP_DIR/files.tar.gz"

  echo "Extracting..."
  mkdir -p "$TMP_DIR/files-extracted"
  tar -xzf "$TMP_DIR/files.tar.gz" -C "$TMP_DIR/files-extracted"

  # NOTE: confirmed via a real backup (2026-07-28) -- the archive's actual
  # layout is a single top-level `files_<env>/` directory (e.g. `files_live/`)
  # whose *contents* ARE wp-content/uploads directly (year folders,
  # .htaccess, _manifest.txt, .gitignore at that level). There is no
  # wp-content/ prefix and no directory literally named `uploads` anywhere
  # in the archive -- neither of the originally assumed layouts held.
  UPLOADS_SRC="$TMP_DIR/files-extracted/wp-content/uploads"
  if [[ ! -d "$UPLOADS_SRC" ]]; then
    # Pantheon's actual layout: single top-level files_<env>/ dir.
    UPLOADS_SRC=$(find "$TMP_DIR/files-extracted" -mindepth 1 -maxdepth 1 -type d -name 'files_*' | head -1)
  fi
  if [[ -z "$UPLOADS_SRC" || ! -d "$UPLOADS_SRC" ]]; then
    # last-resort fallback: a directory literally named uploads/, anywhere
    UPLOADS_SRC=$(find "$TMP_DIR/files-extracted" -type d -name uploads | head -1)
  fi
  if [[ -z "$UPLOADS_SRC" || ! -d "$UPLOADS_SRC" ]]; then
    echo "Error: could not find an uploads/ directory in the files backup archive."
    echo "Inspect $TMP_DIR/files-extracted manually before re-running. DB sync above already completed successfully."
    exit 1
  fi

  echo "Syncing uploads into local Studio site (--delete, exact copy of live's uploads)..."
  rsync -a --delete "$UPLOADS_SRC/" "$STUDIO_SITE_PATH/wp-content/uploads/"
  FILES_SYNCED=true
fi

echo ""
echo "== Summary =="
echo "Source: ${SITE_ENV}"
echo "Local site: ${STUDIO_SITE_PATH} (${LOCAL_URL})"
if [[ "$FILES_SYNCED" == true ]]; then
  echo "Database imported, URLs rewritten, and wp-content/uploads synced from live."
else
  echo "Database imported and URLs rewritten. Media/uploads were NOT synced (pass --with-files to include them)."
fi
echo "Note: this is one-way (live -> local). Local content changes are never pushed back to Pantheon."
