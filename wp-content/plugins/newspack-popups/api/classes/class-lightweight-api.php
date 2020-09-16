<?php
/**
 * Newspack Campaigns lightweight API
 *
 * @package Newspack
 * @phpcs:disable WordPress.DB.RestrictedClasses.mysql__PDO
 */

/**
 * API endpoints
 */
class Lightweight_API {

	/**
	 * Database object.
	 *
	 * @var db
	 */
	public $db;

	/**
	 * Unique client ID.
	 *
	 * @var client_id
	 */
	public $client_id;

	/**
	 * Campaign ID.
	 *
	 * @var popup_id
	 */
	public $popup_id;

	/**
	 * Campaign frequency.
	 *
	 * @var frequency
	 */
	public $frequency;

	/**
	 * Campaign placement.
	 *
	 * @var placement
	 */
	public $placement;

	/**
	 * Campaign UTM Suppression.
	 *
	 * @var utm_suppression
	 */
	public $utm_suppression;

	/**
	 * Suppress Newsletter Campaigns setting.
	 *
	 * @var suppress_newsletter_campaigns
	 */
	public $suppress_newsletter_campaigns;

	/**
	 * Suppress all newsletter campaigns if one dismissed setting.
	 *
	 * @var suppress_all_newsletter_campaigns_if_one_dismissed
	 */
	public $suppress_all_newsletter_campaigns_if_one_dismissed;

	/**
	 * Campaign has Newsletter prompt.
	 *
	 * @var has_newsletter_prompt
	 */
	public $has_newsletter_prompt;

	/**
	 * Response object.
	 *
	 * @var response
	 */
	public $response = [];

	/**
	 * The referer URL.
	 *
	 * @var referer_url
	 */
	public $referer_url;

	/**
	 * Database credentials.
	 *
	 * @var credentials
	 */
	public $credentials;

	/**
	 * Debugging info.
	 *
	 * @var debug
	 */
	public $debug;

	/**
	 * Constructor.
	 */
	public function __construct() {
		if ( ! $this->verify_referer() ) {
			$this->error( 'invalid_referer' );
		}
		$this->debug = [
			'read_query_count'       => 0,
			'write_query_count'      => 0,
			'cache_count'            => 0,
			'read_empty_transients'  => 0,
			'write_empty_transients' => 0,
			'start_time'             => microtime( true ),
			'end_time'               => null,
			'duration'               => null,
		];

		$this->client_id       = ! empty( $_REQUEST['rid'] ) ? // phpcs:ignore
			filter_input( INPUT_POST | INPUT_GET, 'rid', FILTER_SANITIZE_SPECIAL_CHARS ) :
			null; // phpcs:ignore
		$this->popup_id        = ! empty( $_REQUEST['popup_id'] ) ? // phpcs:ignore
			filter_input( INPUT_POST | INPUT_GET, 'popup_id', FILTER_SANITIZE_SPECIAL_CHARS ) :
			null; // phpcs:ignore
		$this->frequency       = ! empty( $_REQUEST['frequency'] ) ? // phpcs:ignore
			filter_input( INPUT_POST | INPUT_GET, 'frequency', FILTER_SANITIZE_SPECIAL_CHARS ) :
			null; // phpcs:ignore
		$this->placement       = ! empty( $_REQUEST['placement'] ) ? // phpcs:ignore
			filter_input( INPUT_POST | INPUT_GET, 'placement', FILTER_SANITIZE_SPECIAL_CHARS ) :
			null; // phpcs:ignore
		$this->utm_suppression = ! empty( $_REQUEST['utm_suppression'] ) ? // phpcs:ignore
			filter_input( INPUT_POST | INPUT_GET, 'utm_suppression', FILTER_SANITIZE_SPECIAL_CHARS ) :
			null; // phpcs:ignore

		$this->suppress_newsletter_campaigns                      = ! empty( $_REQUEST['suppress_newsletter_campaigns'] ) ? // phpcs:ignore
			filter_input( INPUT_POST | INPUT_GET, 'suppress_newsletter_campaigns', FILTER_SANITIZE_SPECIAL_CHARS ) :
			null; // phpcs:ignore
		$this->suppress_all_newsletter_campaigns_if_one_dismissed = ! empty( $_REQUEST['suppress_all_newsletter_campaigns_if_one_dismissed'] ) ? // phpcs:ignore
			filter_input( INPUT_POST | INPUT_GET, 'suppress_all_newsletter_campaigns_if_one_dismissed', FILTER_SANITIZE_SPECIAL_CHARS ) :
			null; // phpcs:ignore
		$this->has_newsletter_prompt = ! empty( $_REQUEST['has_newsletter_prompt'] ) ? // phpcs:ignore
			filter_input( INPUT_POST | INPUT_GET, 'has_newsletter_prompt', FILTER_SANITIZE_SPECIAL_CHARS ) :
			null; // phpcs:ignore

		$this->referer_url = filter_input( INPUT_SERVER, 'HTTP_REFERER', FILTER_SANITIZE_STRING );

		if ( 'inline' !== $this->placement && 'always' === $this->frequency ) {
			$this->frequency = 'once';
		}
	}

	/**
	 * Verify referer is valid.
	 */
	public function verify_referer() {
		$http_referer = ! empty( $_SERVER['HTTP_REFERER'] ) ? parse_url( $_SERVER['HTTP_REFERER'] , PHP_URL_HOST ) : null; // phpcs:ignore
		$valid_referers = [
			$http_referer,
			// TODO: Add AMP Cache.
		];
		$http_host = ! empty( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : null; // phpcs:ignore
		return ! empty( $http_referer ) && ! empty( $http_host ) && in_array( strtolower( $http_host ), $valid_referers, true );
	}

	/**
	 * Get transient name.
	 */
	public function get_transient_name() {
		return sprintf( '%s-%s-popup', $this->client_id, $this->popup_id );
	}

	/**
	 * Get suppression-related transient value
	 *
	 * @param string $prefix transient prefix.
	 */
	public function get_suppression_data_transient_name( $prefix ) {
		return sprintf(
			'%s-%s',
			$prefix,
			$this->client_id
		);
	}

	/**
	 * Get suppression-related transient value for newsletter-campaign-suppression, which has the order of client_id and key reversed from the others.
	 *
	 * @param string $prefix transient prefix.
	 */
	public function legacy_get_suppression_data_transient_name_reversed( $prefix ) {
		return sprintf(
			'%s-%s',
			$this->client_id,
			$prefix
		);
	}

	/**
	 * Complete the API and print response.
	 */
	public function respond() {
		$this->debug['end_time'] = microtime( true );
		$this->debug['duration'] = $this->debug['end_time'] - $this->debug['start_time'];
		if ( defined( 'NEWSPACK_POPUPS_DEBUG' ) && NEWSPACK_POPUPS_DEBUG ) {
			$this->response['debug'] = $this->debug;
		}
		http_response_code( 200 );
		print json_encode( $this->response ); // phpcs:ignore
		exit;
	}

	/**
	 * Return a 400 code error.
	 *
	 * @param string $code The error code.
	 */
	public function error( $code ) {
		http_response_code( 400 );
		print json_encode( [ 'error' => $code ] ); // phpcs:ignore
		exit;
	}

	/**
	 * Get data from transient.
	 *
	 * @param string $name The transient's name.
	 */
	public function get_transient( $name ) {
		global $wpdb;
		$name = '_transient_' . $name;

		$value = wp_cache_get( $name, 'newspack-popups' );
		if ( -1 === $value ) {
			$this->debug['read_empty_transients'] += 1;
			$this->debug['cache_count'] += 1;
			return null;
		} elseif ( false === $value ) {
			$this->debug['read_query_count'] += 1;
			$value = $wpdb->get_var( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $name ) ); // phpcs:ignore
			if ( $value ) {
				wp_cache_set( $name, $value, 'newspack-popups' );
			} else {
				$this->debug['write_empty_transients'] += 1;
				wp_cache_set( $name, -1, 'newspack-popups' );
			}
		} else {
			$this->debug['cache_count'] += 1;
		}
		return maybe_unserialize( $value );
	}

	/**
	 * Upsert transient.
	 *
	 * @param string $name THe transient's name.
	 * @param string $value THe transient's value.
	 */
	public function set_transient( $name, $value ) {
		global $wpdb;
		$name             = '_transient_' . $name;
		$serialized_value = maybe_serialize( $value );
		$autoload         = 'no';
		wp_cache_set( $name, $serialized_value, 'newspack-popups' );
		$result           = $wpdb->query( $wpdb->prepare( "INSERT INTO `$wpdb->options` (`option_name`, `option_value`, `autoload`) VALUES (%s, %s, %s) ON DUPLICATE KEY UPDATE `option_name` = VALUES(`option_name`), `option_value` = VALUES(`option_value`), `autoload` = VALUES(`autoload`)", $name, $serialized_value, $autoload ) ); // phpcs:ignore

		$this->debug['write_query_count'] += 1;
	}
}
