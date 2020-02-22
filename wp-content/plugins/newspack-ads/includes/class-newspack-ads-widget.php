<?php
/**
 * Newspack Ads Widget management
 *
 * @package Newspack
 */

/**
 * Newspack Ads Blocks Management
 */
class Newspack_Ads_Widget extends WP_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct(
			'newspack-ads-widget',
			'Newspack Ad Unit'
		);
		add_action(
			'widgets_init',
			function() {
				register_widget( 'Newspack_Ads_Widget' );
			}
		);
	}

	/**
	 * Constructor
	 *
	 * @var args Widget args.
	 */
	public $args = array(
		'before_widget' => '<div class="widget-wrap">',
		'after_widget'  => '</div></div>',
	);

	/**
	 * Widget renderer.
	 *
	 * @param object $args Args.
	 * @param object $instance The Widget instance.
	 */
	public function widget( $args, $instance ) {
		$selected_ad_unit = $instance['selected_ad_unit'];
		$ad_unit          = Newspack_Ads_Model::get_ad_unit( $selected_ad_unit );

		if ( is_wp_error( $ad_unit ) ) {
			return;
		}

		$is_amp = function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
		$code   = $is_amp ? $ad_unit['amp_ad_code'] : $ad_unit['ad_code'];

		echo $args['before_widget']; // phpcs:ignore
		echo '<div class="textwidget">';
		echo $code; // phpcs:ignore
		echo '</div>';
		echo $args['after_widget']; // phpcs:ignore
	}

	/**
	 * Widget form.
	 *
	 * @param object $instance The Widget instance.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, [ 'selected_ad_unit' => 0 ] );

		$selected_ad_unit = $instance['selected_ad_unit'];

		$ad_units = Newspack_Ads_Model::get_ad_units();
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'selected_ad_unit' ) ); ?>">
				<?php echo esc_html__( 'Ad Unit', 'newspack-ads' ); ?>
				<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'selected_ad_unit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'selected_ad_unit' ) ); ?>">
					<option value=0><?php echo esc_html( '---' ); ?></option>
					<?php foreach ( $ad_units as $ad_unit ) : ?>
						<option
							value="<?php echo esc_attr( $ad_unit['id'] ); ?>"
							<?php selected( $selected_ad_unit, $ad_unit['id'] ); ?>
						>
							<?php echo esc_html( $ad_unit['name'] ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</label>
		</p>
		<?php
	}

	/**
	 * Widget updater.
	 *
	 * @param object $new_instance New instance.
	 * @param object $old_instance Old instance.
	 */
	public function update( $new_instance, $old_instance ) {
		return [
			'selected_ad_unit' => $new_instance['selected_ad_unit'],
		];
	}
}
$newspack_ads_widget = new Newspack_Ads_Widget();
