<?php
/**
 * Class Google\Site_Kit\Modules\Idea_Hub
 *
 * @package   Google\Site_Kit
 * @copyright 2021 Google LLC
 * @license   https://www.apache.org/licenses/LICENSE-2.0 Apache License 2.0
 * @link      https://sitekit.withgoogle.com
 */

namespace Google\Site_Kit\Modules;

use Google\Site_Kit\Core\Assets\Asset;
use Google\Site_Kit\Core\Modules\Module;
use Google\Site_Kit\Core\Modules\Module_Settings;
use Google\Site_Kit\Core\Modules\Module_With_Debug_Fields;
use Google\Site_Kit\Core\Modules\Module_With_Assets;
use Google\Site_Kit\Core\Modules\Module_With_Assets_Trait;
use Google\Site_Kit\Core\Modules\Module_With_Scopes;
use Google\Site_Kit\Core\Modules\Module_With_Scopes_Trait;
use Google\Site_Kit\Core\Modules\Module_With_Settings;
use Google\Site_Kit\Core\Modules\Module_With_Settings_Trait;
use Google\Site_Kit\Core\Assets\Script;
use Google\Site_Kit\Core\REST_API\Exception\Invalid_Datapoint_Exception;
use Google\Site_Kit\Core\REST_API\Data_Request;
use Google\Site_Kit\Core\Util\Debug_Data;
use Google\Site_Kit\Modules\Idea_Hub\Settings;
use Google\Site_Kit_Dependencies\Psr\Http\Message\RequestInterface;
use WP_Error;

/**
 * Class representing the Idea Hub module.
 *
 * @since 1.32.0
 * @access private
 * @ignore
 */
final class Idea_Hub extends Module
	implements Module_With_Scopes, Module_With_Settings, Module_With_Debug_Fields, Module_With_Assets {
	use Module_With_Assets_Trait;
	use Module_With_Scopes_Trait;
	use Module_With_Settings_Trait;

	/**
	 * Module slug name.
	 */
	const MODULE_SLUG = 'idea-hub';

	/**
	 * Registers functionality through WordPress hooks.
	 *
	 * @since 1.32.0
	 */
	public function register() {
		$this->register_scopes_hook();
	}

	/**
	 * Gets required Google OAuth scopes for the module.
	 *
	 * @since 1.32.0
	 *
	 * @return array List of Google OAuth scopes.
	 */
	public function get_scopes() {
		return array(
			'https://www.googleapis.com/auth/ideahub.read',
		);
	}

	/**
	 * Checks whether the module is connected.
	 *
	 * A module being connected means that all steps required as part of its activation are completed.
	 *
	 * @since 1.32.0
	 *
	 * @return bool True if module is connected, false otherwise.
	 */
	public function is_connected() {
		$required_keys = array(
			'ideaLocale',
		);

		$options = $this->get_settings()->get();
		foreach ( $required_keys as $required_key ) {
			if ( empty( $options[ $required_key ] ) ) {
				return false;
			}
		}

		return parent::is_connected();
	}

	/**
	 * Cleans up when the module is deactivated.
	 *
	 * @since 1.32.0
	 */
	public function on_deactivation() {
		$this->get_settings()->delete();
	}

	/**
	 * Gets an array of debug field definitions.
	 *
	 * @since 1.32.0
	 *
	 * @return array
	 */
	public function get_debug_fields() {
		$settings = $this->get_settings()->get();

		return array(
			'idea_hub_idea_locale' => array(
				'label' => __( 'Idea Hub idea locale', 'google-site-kit' ),
				'value' => $settings['ideaLocale'],
				'debug' => Debug_Data::redact_debug_value( $settings['ideaLocale'] ),
			),
		);
	}

	/**
	 * Gets map of datapoint to definition data for each.
	 *
	 * @since 1.32.0
	 *
	 * @return array Map of datapoints to their definitions.
	 */
	protected function get_datapoint_definitions() {
		return array(
			'POST:create-idea-draft-post' => array( 'service' => '' ),
			'GET:draft-post-ideas'        => array( 'service' => '' ),
			'GET:new-ideas'               => array( 'service' => '' ),
			'GET:published-post-ideas'    => array( 'service' => '' ),
			'GET:saved-ideas'             => array( 'service' => '' ),
			'POST:update-idea-state'      => array( 'service' => '' ),
		);
	}

	/**
	 * Creates a request object for the given datapoint.
	 *
	 * @since 1.32.0
	 *
	 * @param Data_Request $data Data request object.
	 * @return RequestInterface|callable|WP_Error Request object or callable on success, or WP_Error on failure.
	 *
	 * @throws Invalid_Datapoint_Exception Thrown if the datapoint does not exist.
	 */
	protected function create_data_request( Data_Request $data ) {
		switch ( "{$data->method}:{$data->datapoint}" ) {
			case 'POST:create-idea-draft-post':
				// @TODO implementation
				return function() {
					return null;
				};
			case 'GET:draft-post-ideas':
				// @TODO implementation
				return function() {
					return null;
				};
			case 'GET:new-ideas':
				// @TODO: Implement this with the real API endpoint.
				return function() {
					return array(
						array(
							'name'   => 'ideas/17450692223393508734',
							'text'   => 'Why Penguins are guanotelic?',
							'topics' =>
								array(
									array(
										'mid'          => '/m/05z6w',
										'display_name' => 'Penguins',
									),
								),
						),
						array(
							'name'   => 'ideas/14025103994557865535',
							'text'   => 'When was sushi Kalam introduced?',
							'topics' =>
								array(
									array(
										'mid'          => '/m/07030',
										'display_name' => 'Sushi',
									),
								),
						),
						array(
							'name'   => 'ideas/7612031899179595408',
							'text'   => 'How to speed up your WordPress site',
							'topics' =>
								array(
									array(
										'mid'          => '/m/09kqc',
										'display_name' => 'Websites',
									),
								),
						),
						array(
							'name'   => 'ideas/2285812891948871921',
							'text'   => 'Using Site Kit to analyze your success',
							'topics' =>
								array(
									array(
										'mid'          => '/m/080ag',
										'display_name' => 'Analytics',
									),
								),
						),
						array(
							'name'   => 'ideas/68182298994557866271',
							'text'   => 'How to make carne asada',
							'topics' =>
								array(
									array(
										'mid'          => '/m/07fhc',
										'display_name' => 'Cooking',
									),
								),
						),
					);
				};
			case 'GET:published-post-ideas':
				// @TODO implementation
				return function() {
					return null;
				};
			case 'GET:saved-ideas':
				// @TODO implementation
				return function() {
					return null;
				};
			case 'POST:update-idea-state':
				// @TODO implementation
				return function() {
					return null;
				};
		}

		return parent::create_data_request( $data );
	}

	/**
	 * Sets up information about the module.
	 *
	 * @since 1.32.0
	 *
	 * @return array Associative array of module info.
	 */
	protected function setup_info() {
		return array(
			'slug'        => self::MODULE_SLUG,
			'name'        => _x( 'Idea Hub', 'Service name', 'google-site-kit' ),
			'description' => 'TODO.',
			'cta'         => 'TODO.',
			'order'       => 7,
			'homepage'    => 'https://www.google.com',
			'learn_more'  => 'https://www.google.com',
		);
	}

	/**
	 * Sets up the module's settings instance.
	 *
	 * @since 1.32.0
	 *
	 * @return Module_Settings
	 */
	protected function setup_settings() {
		return new Settings( $this->options );
	}

	/**
	 * Sets up the module's assets to register.
	 *
	 * @since 1.32.0
	 *
	 * @return Asset[] List of Asset objects.
	 */
	protected function setup_assets() {
		$base_url = $this->context->url( 'dist/assets/' );

		return array(
			new Script(
				'googlesitekit-modules-idea-hub',
				array(
					'src'          => $base_url . 'js/googlesitekit-modules-idea-hub.js',
					'dependencies' => array(
						'googlesitekit-vendor',
						'googlesitekit-api',
						'googlesitekit-data',
						'googlesitekit-modules',
					),
				)
			),
		);
	}
}
