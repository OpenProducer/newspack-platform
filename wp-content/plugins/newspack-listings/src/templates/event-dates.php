<?php
/**
 * Template for an event listing date range.
 *
 * @global array $attributes Block attributes.
 * @package WordPress
 */

use \Newspack_Listings\Newspack_Listings_Core as Core;
use \Newspack_Listings\Utils as Utils;

call_user_func(
	function( $data ) {
		$attributes = $data['attributes'];

		if ( empty( $attributes ) ) {
			return;
		}

		$date_format   = 'F j, Y';
		$time_format   = get_option( 'time_format', 'g:i A' );
		$is_date_range = ! empty( $attributes['endDate'] ) && ! empty( $attributes['showEnd'] );
		$start_date    = new \DateTime( $attributes['startDate'] );
		$end_date      = ! empty( $attributes['endDate'] ) ? new \DateTime( $attributes['endDate'] ) : false;
		$show_time     = ! empty( $attributes['showTime'] ) ? $attributes['showTime'] : false;
		$is_same_day   = empty( $end_date ) ? true : Utils\is_same_day( $start_date, $end_date );

		$start_date_format = $date_format;
		$end_date_format   = $date_format;

		if ( $is_date_range && ! $show_time ) {
			if ( Utils\is_same_year( $start_date, $end_date ) && empty( $is_same_day ) ) {
				$start_date_format = 'F j';
			}

			if ( Utils\is_same_month( $start_date, $end_date ) ) {
				$end_date_format = 'j, Y';
			}
		}

		$the_start_date = $start_date->format( $start_date_format );
		$the_end_date   = '';

		if ( ! empty( $end_date ) ) {
			$the_end_date = $end_date->format( $end_date_format );
		}
		?>
	<div class="newspack-listings__event-dates">
		<time
			class="newspack-listings__event-date"
			datetime="<?php echo esc_attr( $show_time ? $attributes['startDate'] : Utils\strip_time( $attributes['startDate'] ) ); ?>"
		>
			<?php echo esc_html( $the_start_date ); ?>

			<?php if ( $show_time ) : ?>
				<span class="newspack-listings__event-date-time">
					<?php echo esc_html( $start_date->format( $time_format ) ); ?>
				</span>
			<?php endif; ?>
		</time>
		<?php if ( $is_date_range ) : ?>
			<?php if ( empty( $is_same_day ) || $show_time ) : ?>
				<span class="newspack-listings__event-time">
					<?php echo esc_html( _x( '–', 'Date separator', 'newspack-listings' ) ); ?>
				</span>
			<?php endif; ?>
			<time
				class="newspack-listings__event-date"
				datetime="<?php echo esc_attr( $show_time ? $attributes['endDate'] : Utils\strip_time( $attributes['endDate'] ) ); ?>"
			>
				<?php
				if ( empty( $is_same_day ) ) {
					echo esc_html( $the_end_date );
				}
				?>
				<?php if ( $show_time ) : ?>
					<span class="newspack-listings__event-time">
						<?php echo esc_html( $end_date->format( $time_format ) ); ?>
					</span>
				<?php endif; ?>
			</time>
		<?php endif; ?>
	</div>
		<?php
	},
	$data // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
);
