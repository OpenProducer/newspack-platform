<?php

if ( ! function_exists( 'tribe_is_day' ) ) {
	/**
	 * Single Day Test
	 *
	 * Returns true if the query is set for single day, false otherwise
	 *
	 * @category Events
	 * @return bool
	 */
	function tribe_is_day() {
		$tribe_ecp = Tribe__Events__Main::instance();
		$is_day    = ( $tribe_ecp->displaying == 'day' ) ? true : false;

		return apply_filters( 'tribe_is_day', $is_day );
	}

}

if ( ! function_exists( 'tribe_get_day_link' ) ) {
	/**
	 * Link Event Day
	 *
	 * @category Events
	 * @param string $date
	 *
	 * @return string URL
	 */
	function tribe_get_day_link( $date = null ) {
		$tribe_ecp = Tribe__Events__Main::instance();

		return apply_filters( 'tribe_get_day_link', $tribe_ecp->getLink( 'day', $date ), $date );
	}
}

if ( ! function_exists( 'tribe_get_linked_day' ) ) {
	/**
	 * Day View Link
	 *
	 * Get a link to day view
	 *
	 * @category Events
	 * @param string $date
	 * @param string $day
	 *
	 * @return string HTML linked date
	 */
	function tribe_get_linked_day( $date, $day ) {
		$return = '';
		$return .= "<a href='" . esc_url( tribe_get_day_link( $date ) ) . "'>";
		$return .= $day;
		$return .= '</a>';

		return apply_filters( 'tribe_get_linked_day', $return );
	}
}

if ( ! function_exists( 'tribe_the_day_link' ) ) {
	/**
	 * Output an html link to a day
	 *
	 * @category Events
	 * @param string $date 'previous day', 'next day', 'yesterday', 'tomorrow', or any date string that strtotime() can parse
	 * @param string $text text for the link
	 *
	 **/
	function tribe_the_day_link( $date = null, $text = null ) {
		$html = '';

		try {
			if ( is_null( $text ) ) {
				$text = tribe_get_the_day_link_label( $date );
			}

			$date = tribe_get_the_day_link_date( $date );
			$link = tribe_get_day_link( $date );

			$earliest = tribe_events_earliest_date( Tribe__Date_Utils::DBDATEFORMAT );
			$latest   = tribe_events_latest_date( Tribe__Date_Utils::DBDATEFORMAT );

			if ( $date >= $earliest && $date <= $latest ) {
				$html = '<a href="' . esc_url( $link ) . '" data-day="' . $date . '" rel="prev">' . $text . '</a>';
			}
		} catch ( OverflowException $e ) {
		}

		echo apply_filters( 'tribe_the_day_link', $html );
	}
}

if ( ! function_exists( 'tribe_get_the_day_link_label' ) ) {
	/**
	 * Get the label for the day navigation link
	 *
	 * @category Events
	 * @param string $date_description
	 *
	 * @return string
	 */
	function tribe_get_the_day_link_label( $date_description ) {
		switch ( strtolower( $date_description ) ) {
			case null :
				return esc_html__( 'Today', 'the-events-calendar' );
			case 'previous day' :
				return '<span>&laquo;</span> ' . esc_html__( 'Previous Day', 'the-events-calendar' );
			case 'next day' :
				return esc_html__( 'Next Day', 'the-events-calendar' ) . ' <span>&raquo;</span>';
			case 'yesterday' :
				return esc_html__( 'Yesterday', 'the-events-calendar' );
			case 'tomorrow' :
				return esc_html__( 'Tomorrow', 'the-events-calendar' );
			default :
				return date_i18n( 'Y-m-d', strtotime( $date_description ) );
		}
	}
}

if ( ! function_exists( 'tribe_get_the_day_link_date' ) ) {
	/**
	 * Get the date for the day navigation link.
	 *
	 * @category Events
	 * @param string $date_description
	 *
	 * @return string
	 * @throws OverflowException
	 */
	function tribe_get_the_day_link_date( $date_description ) {
		if ( is_null( $date_description ) ) {
			return Tribe__Events__Pro__Main::instance()->todaySlug;
		}
		if ( $date_description == 'previous day' ) {
			return tribe_get_previous_day_date( get_query_var( 'start_date' ) );
		}
		if ( $date_description == 'next day' ) {
			return tribe_get_next_day_date( get_query_var( 'start_date' ) );
		}

		return date( 'Y-m-d', strtotime( $date_description ) );
	}
}

if ( ! function_exists( 'tribe_get_next_day_date' ) ) {
	/**
	 * Get the next day's date
	 *
	 * @category Events
	 * @param string $start_date
	 *
	 * @return string
	 * @throws OverflowException
	 */
	function tribe_get_next_day_date( $start_date ) {
		if ( PHP_INT_SIZE <= 4 ) {
			if ( date( 'Y-m-d', strtotime( $start_date ) ) > '2037-12-30' ) {
				throw new OverflowException( esc_html__( 'Date out of range.', 'the-events-calendar' ) );
			}
		}
		$date = date( 'Y-m-d', strtotime( $start_date . ' +1 day' ) );
		return $date;
	}
}

if ( ! function_exists( 'tribe_get_previous_day_date' ) ) {
	/**
	 * Get the previous day's date
	 *
	 * @category Events
	 * @param string $start_date
	 *
	 * @return string
	 * @throws OverflowException
	 */
	function tribe_get_previous_day_date( $start_date ) {
		if ( PHP_INT_SIZE <= 4 ) {
			if ( date( 'Y-m-d', strtotime( $start_date ) ) < '1902-01-02' ) {
				throw new OverflowException( esc_html__( 'Date out of range.', 'the-events-calendar' ) );
			}
		}
		$date = date( 'Y-m-d', strtotime( $start_date . ' -1 day' ) );
		return $date;
	}
}
