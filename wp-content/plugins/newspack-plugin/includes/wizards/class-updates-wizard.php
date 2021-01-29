<?php
/**
 * Newspack's Updates Wizard
 *
 * @package Newspack
 */

namespace Newspack;

use \WP_Error, \WP_Query;

defined( 'ABSPATH' ) || exit;

require_once NEWSPACK_ABSPATH . '/includes/wizards/class-wizard.php';

/**
 * Easy interface for setting up general store info.
 */
class Updates_Wizard extends Wizard {

	/**
	 * The slug of this wizard.
	 *
	 * @var string
	 */
	protected $slug = 'newspack-updates-wizard';

	/**
	 * The capability required to access this wizard.
	 *
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * Whether the wizard should be displayed in the Newspack submenu.
	 *
	 * @var bool.
	 */
	protected $hidden = true;

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( 'rest_api_init', [ $this, 'register_api_endpoints' ] );
	}

	/**
	 * Get the name for this wizard.
	 *
	 * @return string The wizard name.
	 */
	public function get_name() {
		return \esc_html__( 'Updates', 'newspack' );
	}

	/**
	 * Get the description of this wizard.
	 *
	 * @return string The wizard description.
	 */
	public function get_description() {
		return \esc_html__( 'Updates to the Newspack plugins and theme', 'newspack' );
	}

	/**
	 * Get the duration of this wizard.
	 *
	 * @return string A description of the expected duration (e.g. '10 minutes').
	 */
	public function get_length() {
		return esc_html__( '10 minutes', 'newspack' );
	}

	/**
	 * Register the endpoints needed for the wizard screens.
	 */
	public function register_api_endpoints() {}

	/**
	 * Enqueue Subscriptions Wizard scripts and styles.
	 */
	public function enqueue_scripts_and_styles() {
		parent::enqueue_scripts_and_styles();

		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== $this->slug ) {
			return;
		}

		\wp_enqueue_script(
			'newspack-updates-wizard',
			Newspack::plugin_url() . '/dist/updates.js',
			[ 'wp-components', 'wp-api-fetch' ],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/updates.js' ),
			true
		);
		\wp_register_style(
			'newspack-updates-wizard',
			Newspack::plugin_url() . '/dist/updates.css',
			$this->get_style_dependencies(),
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/updates.css' )
		);
		\wp_style_add_data( 'newspack-updates-wizard', 'rtl', 'replace' );
		\wp_enqueue_style( 'newspack-updates-wizard' );

	}
}
