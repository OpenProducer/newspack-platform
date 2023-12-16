<?php
/**
 * Newspack Popups Criteria System
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Criteria Class
 */
final class Newspack_Popups_Criteria {

	const SCRIPT_HANDLE = 'newspack-popups-criteria';

	/**
	 * Registered criteria.
	 *
	 * @var array
	 */
	protected static $registered_criteria = [];

	/**
	 * Default criteria config.
	 *
	 * @var array
	 */
	protected static $default_config = [
		'category'          => 'reader_activity',
		'matching_function' => 'default',
	];

	/**
	 * Initialize the hooks.
	 */
	public static function init() {
		require_once __DIR__ . '/../src/criteria/default/index.php';
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
	}

	/**
	 * Enqueue scripts.
	 */
	public static function enqueue_scripts() {
		if ( defined( 'IS_TEST_ENV' ) && IS_TEST_ENV ) {
			return;
		}
		if ( Newspack_Popups_Inserter::assess_has_disabled_popups() ) {
			return;
		}

		wp_enqueue_script(
			self::SCRIPT_HANDLE,
			plugins_url( '../dist/criteria.js', __FILE__ ),
			[],
			filemtime( dirname( NEWSPACK_POPUPS_PLUGIN_FILE ) . '/dist/criteria.js' ),
			true
		);
		wp_script_add_data( self::SCRIPT_HANDLE, 'defer', true );
		wp_localize_script(
			self::SCRIPT_HANDLE,
			'newspackPopupsCriteria',
			[
				'config' => self::get_criteria_config(),
			]
		);
	}

	/**
	 * Get registered criteria.
	 *
	 * @return array
	 */
	public static function get_registered_criteria() {
		$criteria = [];
		foreach ( self::$registered_criteria as $id => $config ) {
			$criteria[] = array_merge( [ 'id' => $id ], $config );
		}
		/**
		 * Filter the registered criteria.
		 *
		 * @param array $criteria The registered criteria.
		 */
		return apply_filters( 'newspack_popups_registered_criteria', $criteria );
	}

	/**
	 * Get registered criteria config to be used in the front-end.
	 *
	 * @return array
	 */
	public static function get_criteria_config() {
		$config = [];
		foreach ( self::get_registered_criteria() as $criteria ) {
			$config[ $criteria['id'] ] = [
				'matchingFunction'  => $criteria['matching_function'],
				'matchingAttribute' => $criteria['matching_attribute'],
			];
		}
		return $config;
	}

	/**
	 * Register a new criteria.
	 *
	 * @param string $id     The criteria id.
	 * @param array  $config {
	 *   The criteria config.
	 *
	 *   @type string $name               The criteria name. Defaults to the ID.
	 *   @type string $category           One of reader_activity, reader_engagement, or referrer_sources. Defaults to 'reader_activity'.
	 *   @type string $description        Optional description.
	 *   @type string $help               Optional help text to be used in the input.
	 *   @type array  $options            Optional array of [ label, value ] options segment configuration.
	 *   @type string $matching_function  The criteria matching function. Defaults to 'default'.
	 *   @type string $matching_attribute The criteria matching attribute. Defaults to the ID.
	 * }
	 *
	 * @return array|WP_Error The registered criteria or WP_Error if already registered.
	 */
	public static function register_criteria( $id, $config = [] ) {
		if ( isset( self::$registered_criteria[ $id ] ) ) {
			return new WP_Error( 'newspack_popups_criteria_already_registered', __( 'Criteria already registered.', 'newspack-popups' ) );
		}
		$criteria = wp_parse_args( $config, self::$default_config );
		if ( empty( $criteria['name'] ) ) {
			$criteria['name'] = ucwords( str_replace( '_', ' ', $id ) );
		}
		if ( empty( $criteria['matching_attribute'] ) ) {
			$criteria['matching_attribute'] = $id;
		}
		self::$registered_criteria[ $id ] = $criteria;
		return $criteria;
	}
}
Newspack_Popups_Criteria::init();
