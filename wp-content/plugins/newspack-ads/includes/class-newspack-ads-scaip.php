<?php
/**
 * Newspack Ads SCAIP Hooks
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Newspack Ads SCAIP Class.
 */
class Newspack_Ads_SCAIP {

	// Map of SCAIP option names.
	const OPTIONS_MAP = array(
		'start'          => 'scaip_settings_start',
		'period'         => 'scaip_settings_period',
		'repetitions'    => 'scaip_settings_repetitions',
		'min_paragraphs' => 'scaip_settings_min_paragraphs',
	);

	/**
	 * Initialize SCAIP Hooks.
	 */
	public static function init() {
		add_filter( 'newspack_ads_settings_list', array( __CLASS__, 'add_settings' ) );
		add_filter( 'newspack_ads_setting_option_name', array( __CLASS__, 'map_option_name' ), 10, 2 );
	}

	/**
	 * Add SCAIP settings to the list of settings.
	 *
	 * @param array $settings_list List of settings.
	 *
	 * @return array Updated list of settings.
	 */
	public static function add_settings( $settings_list ) {

		if ( ! defined( 'SCAIP_PLUGIN_FILE' ) ) {
			return $settings_list;
		}

		$scaip_settings = array(
			array(
				'description' => __( 'Post ad inserter settings', 'newspack-ads' ),
				'help'        => __( 'Super Cool Ad Inserter plugin options', 'newspack-ads' ),
				'section'     => 'scaip',
			),
			array(
				'description' => __( 'Number of blocks before first insertion', 'newspack-ads' ),
				'section'     => 'scaip',
				'key'         => 'start',
				'type'        => 'int',
				'default'     => 3,
			),
			array(
				'description' => __( 'Number of blocks between insertions', 'newspack-ads' ),
				'section'     => 'scaip',
				'key'         => 'period',
				'type'        => 'int',
				'default'     => 3,
			),
			array(
				'description' => __( 'Number of times an ad widget area should be inserted in a post', 'newspack-ads' ),
				'section'     => 'scaip',
				'key'         => 'repetitions',
				'type'        => 'int',
				'default'     => 2,
			),
			array(
				'description' => __( 'Minimum number of blocks needed in a post to insert ads', 'newspack-ads' ),
				'section'     => 'scaip',
				'key'         => 'min_paragraphs',
				'type'        => 'int',
				'default'     => 6,
			),
		);
		return array_merge( $settings_list, $scaip_settings );
	}

	/**
	 * Map the option name to the one set on the SCAIP plugin.
	 *
	 * @param string $option_name The option name.
	 * @param array  $setting     The setting configuration array.
	 *
	 * @return string Updated option name.
	 */
	public static function map_option_name( $option_name, $setting ) {
		if ( 'scaip' === $setting['section'] && isset( $setting['key'] ) && isset( self::OPTIONS_MAP[ $setting['key'] ] ) ) {
			return self::OPTIONS_MAP[ $setting['key'] ];
		}
		return $option_name;
	}
}
Newspack_Ads_SCAIP::init();
