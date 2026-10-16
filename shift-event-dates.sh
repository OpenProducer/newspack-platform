#!/usr/bin/env bash
#
# shift-event-dates.sh
#
# Shifts every tribe_events (The Events Calendar) post's start/end dates on
# a Newspack Platform Pantheon environment by a single uniform offset, so
# the EARLIEST event lands ~N days from today, while preserving every
# event's relative spacing, order, and duration exactly.
#
# Anchored on the earliest event, not the latest (changed 2026-07-27):
# anchoring on the latest event only guarantees that one event clears
# "today" -- events further in the past than the latest keep whatever gap
# they already had and can still land in the past after a uniform shift.
# Confirmed visually on live: more past events than upcoming ones after a
# latest-anchored shift. Anchoring on the earliest event instead guarantees
# the WHOLE set lands on/after today + N days. N defaults to a small buffer
# (3 days) rather than 30 -- it now only needs to safely clear "today"
# regardless of execution timing, not control how far out things spread.
#
# All demo event content on this platform is dated in the past on every
# environment (confirmed 2026-07-27), which is why no page shows a live
# events carousel. This is a DATA fix (tribe_events post dates), not a code
# change -- it does not touch plugins/themes and is unrelated to
# sync-plugins.sh / sync-themes.sh's env:deploy promotion workflow.
#
# Confirmed 2026-07-27 (see docs/ARCHITECTURE.md):
#   - dev, test, and live held BYTE-IDENTICAL event content (same 8 post
#     IDs, same dates) -- test/live were evidently content-cloned from dev
#     at some point and never diverged for events. All three were run
#     through this script's original latest-anchored version that same day.
#     Round 2 (earliest-anchored, this version) is being run for real only
#     on live -- dev/test are being refreshed FROM live via a content sync
#     (terminus env:clone-content or the Pantheon dashboard) instead, since
#     their event data still matches live's pre-round-2 state. Don't run
#     this script against dev/test as a substitute for that sync.
#   - radio and podcast are INDEPENDENTLY SEEDED/DIVERGED: overlapping but
#     not identical post ID sets (radio has an extra "Brian Swartz" event
#     podcast and dev/test/live don't both have; podcast is missing 2 of
#     dev's events) and different dates per shared ID. They do NOT share a
#     database with dev or with each other.
#   - Because of this, terminus env:clone-content must NOT be used to push
#     live's fixed dates to radio/podcast -- it overwrites the ENTIRE target
#     database, not just events, and would destroy radio/podcast's own
#     independent content. Each environment must be run through this script
#     independently instead.
#
# Safe to re-run on top of already-shifted dates: this script always reads
# each event's CURRENT _EventStartDate/_EventEndDate from the live DB on
# every run and computes the shift off that, never off an assumed baseline.
#
# Uses tribe_update_event() via `wp eval-file -` (payload piped over stdin,
# see shift-event-dates.php) -- NOT raw `wp post meta update` -- so TEC's
# internal _EventStartDateUTC/_EventEndDateUTC/_EventDuration indexes stay
# in sync. See shift-event-dates.php's header for the full rationale and the
# per-environment datepickerFormat gotcha it works around.
#
# Environment model: unlike sync-plugins.sh/sync-themes.sh, test/live are
# NOT excluded here. That restriction is specific to CODE promotion (plugins
# ship via git + env:deploy, dev -> test -> live); this script only ever
# writes tribe_events post/meta rows via tribe_update_event() on whichever
# single environment you point it at -- there is no code-deploy path for
# event dates, so the promotion-tier concept doesn't apply. Each of dev,
# test, live, radio, podcast must be run independently regardless.
#
# Usage:
#   ./shift-event-dates.sh --env dev|test|live|radio|podcast [--site SITE] [--days-out N] [--apply] [--help]
#
# --site defaults to "newspack" (this repo's Pantheon site) for backward
# compatibility. Generalized 2026-07-28 to work against any Pantheon site --
# the underlying mechanism (tribe_update_event() via `wp eval-file -`) is
# site-agnostic; only the site machine name and valid env list were ever
# newspack-platform-specific. No hardcoded env whitelist anymore -- validity
# is checked the same way either way, via `terminus env:info` below, which
# fails cleanly on a real typo or a nonexistent environment.
#
# Always run without --apply (the default) first and review the preview
# table before re-running with --apply.

set -euo pipefail

PANTHEON_SITE="newspack"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PHP_PAYLOAD="${SCRIPT_DIR}/shift-event-dates.php"

ENV=""
DAYS_OUT=3
APPLY=false

usage() {
  cat <<EOF
Usage: $0 --env ENV [options]

Options:
  --env ENV         Target Pantheon environment (required) -- e.g. dev, test, live, radio,
                     podcast (newspack-platform) or dev, develop, playerv1 (newspack-radio-pro)
  --site SITE        Pantheon site machine name (default: ${PANTHEON_SITE})
  --days-out N        Days from today the EARLIEST event should land at (default: 3)
  --apply             Actually write the new dates (default: dry run / preview only)
  --help              Show this help text
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --env) ENV="$2"; shift 2 ;;
    --site) PANTHEON_SITE="$2"; shift 2 ;;
    --days-out) DAYS_OUT="$2"; shift 2 ;;
    --apply) APPLY=true; shift ;;
    --help) usage; exit 0 ;;
    *) echo "Unknown argument: $1"; usage; exit 1 ;;
  esac
done

if [[ -z "$ENV" ]]; then
  echo "Error: --env is required"
  usage
  exit 1
fi

if ! [[ "$DAYS_OUT" =~ ^-?[0-9]+$ ]]; then
  echo "Error: --days-out must be an integer"
  exit 1
fi

if [[ ! -f "$PHP_PAYLOAD" ]]; then
  echo "Error: could not find ${PHP_PAYLOAD}"
  exit 1
fi

SITE_ENV="${PANTHEON_SITE}.${ENV}"

echo "== shift-event-dates.sh: ${SITE_ENV} (days-out=${DAYS_OUT}, apply=${APPLY}) =="

# ---- pre-flight ----------------------------------------------------------
if ! command -v terminus >/dev/null 2>&1; then
  echo "Error: terminus CLI not found. Install it first: https://docs.pantheon.io/terminus/install"
  exit 1
fi

echo "Verifying environment exists..."
terminus env:info "$SITE_ENV" >/dev/null </dev/null || { echo "Error: could not find environment ${SITE_ENV}"; exit 1; }

if [[ "$APPLY" == true ]]; then
  echo ""
  echo "*** APPLY MODE: this will write new event dates on ${SITE_ENV} via tribe_update_event(). ***"
  read -r -p "Proceed? [y/N] " CONFIRM
  if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    echo "Aborted."
    exit 0
  fi
fi

# The PHP payload is piped over stdin into `wp eval-file -` (confirmed
# working, 2026-07-27) rather than requiring the file to exist on Pantheon's
# server. Per the stdin-drain lesson from sync-plugins.sh: this consumes
# stdin for exactly this one command, upstream of the --apply confirm
# prompt above -- it does not interfere with it since the prompt runs first.
#
# $days_out/$apply are prepended as plain PHP variable assignments ahead of
# the payload file's own content, rather than passed as trailing `eval-file`
# CLI args -- confirmed empirically (2026-07-27) that terminus silently
# drops any args after the `-` when relaying the command over SSH.
APPLY_PHP="false"
if [[ "$APPLY" == true ]]; then
  APPLY_PHP="true"
fi

{
  printf '<?php $days_out = %d; $apply = %s;\n' "$DAYS_OUT" "$APPLY_PHP"
  # Skip the payload's own opening `<?php` line -- we're already inside an
  # open PHP context from the printf above; a second opening tag would be a
  # fatal parse error.
  tail -n +2 "$PHP_PAYLOAD"
} | terminus wp "$SITE_ENV" -- eval-file -

if [[ "$APPLY" == true ]]; then
  # Content-only changes (this script writes rows directly via WP-CLI, not
  # through a code deploy) don't get the automatic cache invalidation a code
  # deploy triggers -- confirmed 2026-07-27: Pantheon kept serving a stale
  # pre-shift /events/ page on live even though the DB was already correct.
  echo ""
  echo "Clearing ${SITE_ENV}'s cache..."
  terminus env:clear-cache "$SITE_ENV" </dev/null
  echo ""
  echo "Done. Verify on ${SITE_ENV} (e.g. the /events/ page) before moving to the next environment."
else
  echo ""
  echo "Dry run complete. No changes made. Re-run with --apply to write these dates."
fi
