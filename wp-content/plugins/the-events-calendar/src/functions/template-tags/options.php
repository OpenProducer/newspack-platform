<?php

/**
 * Helper functions for the options API
 * Used to display option descriptions after they are saved
 *
 */

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * display the events slug description
 *
 * @return string, the string to display
 */
function tribe_display_current_events_slug() {
	echo '<p class="tribe-field-indent tribe-field-description description">' . esc_html__( 'The slug used for building the events URL.', 'the-events-calendar' ) . sprintf( esc_html__( 'Your current Events URL is %s', 'the-events-calendar' ), '<code><a href="' . esc_url( tribe_get_events_link() ) . '">' . tribe_get_events_link() . '</a></code>' ) . '</p>';
}

/**
 * display the event single slug description
 *
 * @return string, the string to display
 */
function tribe_display_current_single_event_slug() {
	echo '<p class="tribe-field-indent tribe-field-description description">' . sprintf( esc_html__( 'You %1$scannot%2$s use the same slug as above. The above should ideally be plural, and this singular.%3$sYour single Event URL is like: %4$s', 'the-events-calendar' ), '<strong>', '</strong>', '<br>', '<code>' . trailingslashit( home_url() ) . tribe_get_option( 'singleEventSlug', 'event' ) . '/single-post-name/</code>' ) . '</p>';
}

/**
 * display the iCal description
 *
 * @return string, the string to display
 */
function tribe_display_current_ical_link() {
	if ( function_exists( 'tribe_get_ical_link' ) ) {
		echo '<p id="ical-link" class="tribe-field-indent tribe-field-description description">' . esc_html__( 'Here is the iCal feed URL for your events:', 'the-events-calendar' ) . ' <code>' . tribe_get_ical_link() . '</code></p>';
	}
}
