<?php
/**
 * Newspack Analytics features.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Manages Analytics integrations.
 */
class Analytics {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'googlesitekit_amp_gtag_opt', [ __CLASS__, 'inject_amp_events' ] );
		add_action( 'wp_footer', [ __CLASS__, 'inject_non_amp_events' ] );
	}

	/**
	 * Get data about all events.
	 *
	 * An event is largely based on the AMP analytics event spec and should contain the following fields:
	 *    'id'             => string Event ID (e.g. "popupFormDisplayed").
	 *    'on'             => string Type of action (e.g. "click" or "form-submit-success").
	 *    'amp_on'         => string Type of action for AMP if different (e.g. "amp-form-submit-success").
	 *    'element'        => string CSS selector for element (e.g. '#popup1').
	 *    'amp_element'    => string CSS selector for AMP version of element if different.
	 *    'event_name'     => string Name for event in GA (e.g. 'Clicked').
	 *    'event_label'    => string Label for event in GA (e.g. 'Popup 1')
	 *    'event_category' => string Category for event in GA (e.g. 'Newspack Announcements').
	 * There can also be other fields specific for certain events (e.g. 'scrollSpec' for 'scroll' event listener).
	 */
	public static function get_events() {
		$events = [
			[
				'id'             => 'socialShareClickedFacebook',
				'on'             => 'click',
				'element'        => 'a.share-facebook',
				'amp_element'    => 'amp-social-share[type="facebook"]',
				'event_name'     => 'social share',
				'event_label'    => 'facebook',
				'event_category' => 'NTG social',
			],
			[
				'id'             => 'socialShareClickedTwitter',
				'on'             => 'click',
				'element'        => 'a.share-twitter',
				'amp_element'    => 'amp-social-share[type="twitter"]',
				'event_name'     => 'social share',
				'event_label'    => 'twitter',
				'event_category' => 'NTG social',
			],
			[
				'id'             => 'socialShareClickedWhatsApp',
				'on'             => 'click',
				'element'        => 'a.share-jetpack-whatsapp',
				'amp_element'    => 'amp-social-share[type="whatsapp"]',
				'event_name'     => 'social share',
				'event_label'    => 'whatsapp',
				'event_category' => 'NTG social',
			],
			[
				'id'             => 'socialShareClickedLinkedIn',
				'on'             => 'click',
				'element'        => 'a.share-linkedin',
				'amp_element'    => 'amp-social-share[type="linkedin"]',
				'event_name'     => 'social share',
				'event_label'    => 'linkedin',
				'event_category' => 'NTG social',
			],
			[
				'id'             => 'articleRead25',
				'on'             => 'scroll',
				'event_name'     => '25%',
				'event_value'    => 25,
				'event_label'    => get_the_title(),
				'event_category' => 'NTG article milestone',
				'scrollSpec'     => [
					'verticalBoundaries' => [ 25 ],
				],
			],
			[
				'id'             => 'articleRead50',
				'on'             => 'scroll',
				'event_name'     => '50%',
				'event_value'    => 50,
				'event_label'    => get_the_title(),
				'event_category' => 'NTG article milestone',
				'scrollSpec'     => [
					'verticalBoundaries' => [ 50 ],
				],
			],
			[
				'id'             => 'articleRead100',
				'on'             => 'scroll',
				'event_name'     => '100%',
				'event_value'    => 100,
				'event_label'    => get_the_title(),
				'event_category' => 'NTG article milestone',
				'scrollSpec'     => [
					'verticalBoundaries' => [ 100 ],
				],
			],
		];

		/**
		 * Other integrations can add events to track using this filter.
		 */
		return apply_filters( 'newspack_analytics_events', $events );
	}

	/**
	 * Inject event listeners on AMP pages.
	 *
	 * @param array $config AMP Analytics config from Site Kit.
	 * @return array Modified $config.
	 */
	public static function inject_amp_events( $config ) {
		foreach ( self::get_events() as $event ) {
			$event_config = [
				'request' => 'event',
				'on'      => isset( $event['amp_on'] ) ? $event['amp_on'] : $event['on'],
				'vars'    => [
					'event_name'     => $event['event_name'],
					'event_label'    => $event['event_label'],
					'event_category' => $event['event_category'],
				],
			];
			if ( isset( $event['event_value'] ) ) {
				$event_config['vars']['value'] = $event['event_value'];
			}

			if ( isset( $event['amp_element'] ) || isset( $event['element'] ) ) {
				$event_config['selector'] = isset( $event['amp_element'] ) ? $event['amp_element'] : $event['element'];
			}

			// Handle other config params e.g. 'scrollSpec'.
			foreach ( $event as $key => $val ) {
				if ( ! in_array( $key, [ 'id', 'on', 'amp_on', 'element', 'amp_element', 'event_name', 'event_label', 'event_category' ] ) ) {
					$event_config[ $key ] = $val;
				}
			}

			if ( ! isset( $config['triggers'] ) ) {
				$config['triggers'] = [];
			}

			if ( 'click' !== $event_config['on'] ) {
				/**
				 * This is how non-interactive events are added to amp-analytics.
				 *
				 * @see https://github.com/ampproject/amphtml/issues/5018#issuecomment-247402181
				 */
				$event_config['extraUrlParams'] = [
					'ni' => 1,
				];
			}

			// Other integrations can use this filter if they need to modify the AMP-specific event config.
			$config['triggers'][ $event['id'] ] = apply_filters( 'newspack_analytics_amp_event_config', $event_config, $event );
		}

		return $config;
	}

	/**
	 * Inject event listeners on non-AMP pages.
	 */
	public static function inject_non_amp_events() {
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return;
		}

		$sitekit_manager = Configuration_Managers::configuration_manager_class_for_plugin_slug( 'google-site-kit' );
		if ( ! $sitekit_manager->is_module_active( 'analytics' ) ) {
			return;
		}

		foreach ( self::get_events() as $event ) {
			ob_start();
			switch ( $event['on'] ) {
				case 'click':
					self::output_js_click_event( $event );
					break;
				case 'scroll':
					self::output_js_scroll_event( $event );
					break;
				default:
					break;
			}

			// Other integrations can use this filter if they need to add a custom JS event handler.
			$event_js = apply_filters( 'newspack_analytics_event_js', ob_get_clean(), $event );
			echo $event_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Output JS for a click-based event listener.
	 *
	 * @param array $event Event info. See 'get_events'.
	 */
	protected static function output_js_click_event( $event ) {
		?>
		<script>
			( function() {
				var elementSelector = '<?php echo esc_attr( $event['element'] ); ?>';
				var elements        = Array.prototype.slice.call( document.querySelectorAll( elementSelector ) );

				for ( var i = 0; i < elements.length; ++i ) {
					elements[i].addEventListener( 'click', function() {
						gtag(
							'event',
							'<?php echo esc_attr( $event['event_name'] ); ?>',
							{
								event_category: '<?php echo esc_attr( $event['event_category'] ); ?>',
								event_label: '<?php echo esc_attr( $event['event_label'] ); ?>',
							}
						);
					} );
				}
			} )();
		</script>
		<?php
	}

	/**
	 * Output JS for a scroll-based event listener.
	 *
	 * @param array $event Event info. See 'get_events'.
	 */
	protected static function output_js_scroll_event( $event ) {
		?>
		<script>
			( function() {
				var scrollPercent = <?php echo (int) $event['scrollSpec']['verticalBoundaries'][0]; ?>;

				var eventSent = false;
				var reportEvent = function(){
					if ( eventSent ) {
						return;
					}

					var scrollPos = ( window.pageYOffset || window.scrollY ) + window.innerHeight;
					var documentHeight = document.body.clientHeight;

					if ( ( ( scrollPos / documentHeight ) * 100 ) >= scrollPercent ) {
						eventSent = true;
						gtag(
							'event',
							'<?php echo esc_attr( $event['event_name'] ); ?>',
							{
								event_category: '<?php echo esc_attr( $event['event_category'] ); ?>',
								event_label: '<?php echo esc_attr( $event['event_label'] ); ?>',
								value: scrollPercent,
								non_interaction: true,
							}
						);
					}
				}
				// Fire initially - page might be loaded with scroll offset.
				reportEvent()
				window.addEventListener( 'scroll', reportEvent );
			} )();
		</script>
		<?php
	}
}
new Analytics();
