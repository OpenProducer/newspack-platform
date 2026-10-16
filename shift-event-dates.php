<?php
/**
 * shift-event-dates.php
 *
 * WP-CLI eval-file payload: shifts every tribe_events post's start/end dates
 * by a single uniform offset, so the EARLIEST event lands ~N days from
 * today, while preserving every event's relative spacing, order, and
 * duration exactly (all events move by the same number of days).
 *
 * Anchored on the earliest event, not the latest (changed 2026-07-27):
 * anchoring on the latest event only guarantees THAT event clears "today" --
 * every event further in the past than the latest keeps whatever gap it
 * already had and can still land in the past after a uniform shift. Since
 * every event moves by the same offset, anchoring on the EARLIEST event
 * instead guarantees the entire set lands on or after today + N days, with
 * N kept small (a few days) purely as a buffer against execution-time
 * timing/timezone edges, not as a "how far out" spread control like the old
 * latest-anchor default of 30 was.
 *
 * Not run directly -- piped via stdin through `terminus wp <site.env> --
 * eval-file -`, driven by shift-event-dates.sh. Never touched via raw
 * `wp post meta update` -- see below for why.
 *
 * Uses tribe_update_event() (TEC's own advanced-functions API), confirmed
 * against the installed version's source at
 * wp-content/plugins/the-events-calendar/src/functions/advanced-functions/event.php
 * and Tribe__Events__API::updateEvent() / prepare_event_date_meta() in
 * wp-content/plugins/the-events-calendar/src/Tribe/API.php. Raw post-meta
 * writes would desync _EventStartDateUTC/_EventEndDateUTC/_EventDuration
 * (TEC's own internal indexes), which is exactly what tribe_update_event()
 * keeps in sync via prepare_event_date_meta().
 *
 * IMPORTANT date-format gotcha (confirmed empirically -- dev's
 * datepickerFormat option resolved to index "1" => 'n/j/Y', not 'Y-m-d'):
 * tribe_update_event()'s EventStartDate/EventEndDate args are parsed using
 * whatever tribe_get_option('datepickerFormat') resolves to via
 * Tribe__Date_Utils::datepicker_formats(). radio/podcast are separate
 * databases and may resolve differently. This script reads that option live
 * on whichever environment it's run against -- never hardcode a format.
 *
 * Also passes EventStartHour/EventStartMinute (and End) alongside
 * EventStartDate/EventEndDate: Tribe__Events__API::prepare_event_date_meta()
 * only treats a date as "provided" (and actually overwrites the stored
 * date) when a time component accompanies it -- EventStartDate alone is
 * silently ignored and the existing stored date is kept instead.
 *
 * Not invoked with trailing `eval-file` CLI args: confirmed empirically
 * (2026-07-27) that `terminus wp <site.env> -- eval-file - <extra args>`
 * silently drops everything after the `-` when relayed over SSH -- $args
 * comes back NULL even though the file content itself still runs. Instead,
 * shift-event-dates.sh prepends `$days_out`/`$apply` as plain PHP variable
 * assignments ahead of this file's content in the same stdin stream.
 *
 * Usage (via shift-event-dates.sh, do not run directly):
 *   { echo '<?php $days_out = 30; $apply = false;'; cat shift-event-dates.php; } \
 *     | terminus wp <site.env> -- eval-file -
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( "Run via wp eval-file only.\n" );
}

if ( ! isset( $days_out ) ) {
	$days_out = 3;
}
if ( ! isset( $apply ) ) {
	$apply = false;
}

if ( ! class_exists( 'Tribe__Events__Main' ) ) {
	WP_CLI::error( 'The Events Calendar is not active on this environment.' );
}

global $wpdb;
$ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'tribe_events' ORDER BY ID" );

if ( empty( $ids ) ) {
	WP_CLI::log( 'No tribe_events posts found on this environment.' );
	return;
}

$utc    = new DateTimeZone( 'UTC' );
$events = [];

foreach ( $ids as $id ) {
	$start = get_post_meta( $id, '_EventStartDate', true );
	$end   = get_post_meta( $id, '_EventEndDate', true );

	if ( ! $start || ! $end ) {
		WP_CLI::warning( "Event {$id} is missing start/end meta -- skipping." );
		continue;
	}

	// A couple of records have been observed with a fractional-seconds
	// suffix (e.g. "2026-02-23 19:30:00.000000") -- trim before parsing.
	$start = preg_replace( '/\.\d+$/', '', $start );
	$end   = preg_replace( '/\.\d+$/', '', $end );

	$start_dt = DateTime::createFromFormat( 'Y-m-d H:i:s', $start, $utc );
	$end_dt   = DateTime::createFromFormat( 'Y-m-d H:i:s', $end, $utc );

	if ( ! $start_dt || ! $end_dt ) {
		WP_CLI::warning( "Event {$id}: could not parse start/end ('{$start}' / '{$end}') -- skipping." );
		continue;
	}

	$events[] = [
		'id'       => $id,
		'title'    => get_the_title( $id ),
		'status'   => get_post_status( $id ),
		'allday'   => get_post_meta( $id, '_EventAllDay', true ) === 'yes',
		'start_dt' => $start_dt,
		'end_dt'   => $end_dt,
	];
}

if ( empty( $events ) ) {
	WP_CLI::log( 'No events with parseable dates found -- nothing to do.' );
	return;
}

// Determine the single shift, in whole days, computed off the EARLIEST
// start date's calendar date (midnight UTC), so the diff is always an exact
// multiple of 86400 seconds -- no DST ambiguity -- and applying it later
// preserves every event's time-of-day exactly. Anchoring on the earliest
// (not latest) event guarantees the WHOLE set lands on/after today +
// days_out, regardless of how wide the original spread was -- see header.
$earliest_start = null;
foreach ( $events as $e ) {
	if ( $earliest_start === null || $e['start_dt'] < $earliest_start ) {
		$earliest_start = $e['start_dt'];
	}
}

$earliest_midnight = ( clone $earliest_start )->setTime( 0, 0, 0 );
$target_midnight   = new DateTime( 'today', $utc );
$target_midnight->modify( "+{$days_out} days" );

$shift_seconds  = $target_midnight->getTimestamp() - $earliest_midnight->getTimestamp();
$shift_days     = intdiv( $shift_seconds, 86400 );
$shift_interval = DateInterval::createFromDateString( "{$shift_seconds} seconds" );

WP_CLI::log( sprintf(
	'Shift: %+d days -- earliest event start %s (UTC wall-clock) -> %s',
	$shift_days,
	$earliest_start->format( 'Y-m-d H:i:s' ),
	( clone $earliest_start )->add( $shift_interval )->format( 'Y-m-d H:i:s' )
) );
WP_CLI::log( $apply ? '*** APPLY MODE -- writes will happen via tribe_update_event() ***' : 'DRY RUN -- no changes will be made' );
WP_CLI::log( '' );

// Resolved LIVE from this environment's own DB option -- see header note.
$datepicker_format = Tribe__Date_Utils::datepicker_formats( tribe_get_option( 'datepickerFormat' ) );
WP_CLI::log( "Resolved datepickerFormat on this environment: {$datepicker_format}" );
WP_CLI::log( '' );

$now         = new DateTime( 'now', $utc );
$rows        = [];
$fail_count  = 0;
$past_events = [];

foreach ( $events as $e ) {
	$new_start = ( clone $e['start_dt'] )->add( $shift_interval );
	$new_end   = ( clone $e['end_dt'] )->add( $shift_interval );

	$rows[] = [
		'ID'        => $e['id'],
		'Title'     => $e['title'],
		'Status'    => $e['status'],
		'Old Start' => $e['start_dt']->format( 'Y-m-d H:i' ),
		'New Start' => $new_start->format( 'Y-m-d H:i' ),
		'Old End'   => $e['end_dt']->format( 'Y-m-d H:i' ),
		'New End'   => $new_end->format( 'Y-m-d H:i' ),
	];

	// An event is still "past" post-shift if it has already ENDED relative
	// to now -- matches how TEC's own upcoming/past views classify events.
	if ( $new_end < $now ) {
		$past_events[] = "{$e['id']} ({$e['title']}): ends {$new_end->format( 'Y-m-d H:i' )}";
	}

	if ( $apply ) {
		$update_args = [
			'EventStartDate'   => $new_start->format( $datepicker_format ),
			'EventStartHour'   => $new_start->format( 'H' ),
			'EventStartMinute' => $new_start->format( 'i' ),
			'EventEndDate'     => $new_end->format( $datepicker_format ),
			'EventEndHour'     => $new_end->format( 'H' ),
			'EventEndMinute'   => $new_end->format( 'i' ),
		];
		if ( $e['allday'] ) {
			$update_args['EventAllDay'] = 'yes';
		}

		$result = tribe_update_event( $e['id'], $update_args );
		if ( $result === false ) {
			WP_CLI::warning( "Event {$e['id']} ({$e['title']}): tribe_update_event() returned false." );
			$fail_count++;
		}
	}
}

WP_CLI\Utils\format_items( 'table', $rows, [ 'ID', 'Title', 'Status', 'Old Start', 'New Start', 'Old End', 'New End' ] );
WP_CLI::log( '' );

if ( empty( $past_events ) ) {
	WP_CLI::success( 'Zero events remain dated before today after the shift.' );
} else {
	WP_CLI::warning( sprintf( '%d event(s) still dated before today after the shift:', count( $past_events ) ) );
	foreach ( $past_events as $line ) {
		WP_CLI::log( "  - {$line}" );
	}
}
WP_CLI::log( '' );

if ( $apply ) {
	$ok    = count( $rows ) - $fail_count;
	$total = count( $rows );
	if ( $fail_count > 0 ) {
		WP_CLI::error( "Updated {$ok}/{$total} events -- {$fail_count} failed, see warnings above." );
	} else {
		WP_CLI::success( "Updated all {$ok} events via tribe_update_event()." );
	}
} else {
	WP_CLI::log( 'Dry run complete. No changes made.' );
}
