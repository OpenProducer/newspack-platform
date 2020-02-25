<?php
/**
 * Newspack Checklist manager.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Manages the checklists.
 */
class Checklists {

	/**
	 * Information about all of the checklists.
	 * See `init` for structure of the data.
	 *
	 * @var array
	 */
	protected static $checklists = [];

	/**
	 * Initialize the data and hooks.
	 */
	public static function init() {
		/**
		 * One checklist has the following information:
		 *     name: The checklist name.
		 *     description: The checklist description.
		 *     wizards: An array of wizard slugs in desired order, corresponding to slugs registered in Wizards class.
		 */
		self::$checklists = [
			'engagement' => [
				'name'        => esc_html__( 'Engagement', 'newspack' ),
				'description' => esc_html__( 'How do you want your audience to engage with your publication?', 'newspack' ),
				'wizards'     => [
					'newsletter-block',
				],
			],
		];

		$managed_plugins = Plugin_Manager::get_managed_plugins();

		// Only add the Mailchimp wizard if WooCommerce is active.
		if ( 'active' === $managed_plugins['woocommerce']['Status'] ) {
			array_unshift( self::$checklists['engagement']['wizards'], 'mailchimp' );
		}

		add_action( 'admin_menu', 'Newspack\Checklists::add_page' );
		add_action( 'admin_enqueue_scripts', 'Newspack\Checklists::enqueue_scripts_and_styles' );
	}

	/**
	 * Get a checklist's raw info.
	 *
	 * @param string $checklist_slug The checklist slug, as registered in the self::$checklists variable.
	 * @return array | bool The info on success, false on failure.
	 */
	public static function get_checklist( $checklist_slug ) {
		if ( isset( self::$checklists[ $checklist_slug ] ) ) {
			return self::$checklists[ $checklist_slug ];
		}

		return false;
	}

	/**
	 * Get a checklist's name.
	 *
	 * @param string $checklist_slug The checklist slug, as registered in the self::$checklists variable.
	 * @return string | bool The info on success, false on failure.
	 */
	public static function get_name( $checklist_slug ) {
		$checklist = self::get_checklist( $checklist_slug );
		if ( $checklist ) {
			return $checklist['name'];
		}

		return false;
	}

	/**
	 * Get a checklist's description.
	 *
	 * @param string $checklist_slug The checklist slug, as registered in the self::$checklists variable.
	 * @return string | bool The info on success, false on failure.
	 */
	public static function get_description( $checklist_slug ) {
		$checklist = self::get_checklist( $checklist_slug );
		if ( $checklist ) {
			return $checklist['description'];
		}

		return false;

	}

	/**
	 * Get a checklist's style.
	 *
	 * @param string $checklist_slug The checklist slug, as registered in the self::$checklists variable.
	 * @return string | bool The list style
	 */
	public static function get_style( $checklist_slug ) {
		$checklist = self::get_checklist( $checklist_slug );
		if ( $checklist ) {
			return isset( $checklist['style'] ) ? $checklist['style'] : null;
		}

		return false;
	}

	/**
	 * Get a checklist's URL.
	 *
	 * @param string $checklist_slug The checklist slug, as registered in the self::$checklists variable.
	 * @return string | bool The URL on success, false on failure.
	 */
	public static function get_url( $checklist_slug ) {
		$checklist = self::get_checklist( $checklist_slug );
		if ( $checklist ) {
			return esc_url( admin_url( 'admin.php?page=newspack-checklist&checklist=' . $checklist_slug ), null, 'link' );
		}

		return false;
	}

	/**
	 * Get all the URLs for all the checklists.
	 *
	 * @return array of slug => URL pairs.
	 */
	public static function get_urls() {
		$urls = [];
		foreach ( self::$checklists as $slug => $checklist ) {
			$urls[ $slug ] = self::get_url( $slug );
		}

		return $urls;
	}

	/**
	 * Get a checklist's status. Valid stati are: 'enabled', 'disabled', 'completed'.
	 *
	 * @todo Make this actually check for whether a checklist is completed.
	 * @param string $checklist_slug The checklist slug, as registered in the self::$checklists variable.
	 * @return string | bool The info on success, false on failure.
	 */
	public static function get_status( $checklist_slug ) {
		return 'enabled';
	}

	/**
	 * Register and add the page that the checklists will live on.
	 */
	public static function add_page() {
		add_submenu_page( null, 'Checklist', 'Checklist', 'manage_options', 'newspack-checklist', 'Newspack\Checklists::render_checklist' );
	}

	/**
	 * Render a container for the checklist to render in.
	 */
	public static function render_checklist() {
		?>
		<div id="newspack-checklist" class="newspack-checklist">
		</div>
		<?php
	}

	/**
	 * Load up the scripts when appropriate.
	 * Prepare all of the information about the requested checklist into a 'newspack_checklist' variable.
	 */
	public static function enqueue_scripts_and_styles() {
		if ( filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) !== 'newspack-checklist' ) {
			return;
		}

		$checklist_slug = filter_input( INPUT_GET, 'checklist', FILTER_SANITIZE_STRING );
		if ( ! $checklist_slug ) {
			wp_die( esc_html__( 'No checklist found.', 'newspack' ) );
		}

		$checklist = self::get_checklist( $checklist_slug );
		if ( ! $checklist ) {
			wp_die( esc_html__( 'No checklist found.', 'newspack' ) );
		}

		/**
		 * The following information is placed into the `newspack_checklist` js variable on a checklist's page:
		 * name         => String name of the checklist.
		 * description  => String description of the checklist.
		 * steps        => Array of wizards. See $checklist_data['steps'] below.
		 * dashboardURL => String link to the main dashboard, so the checklist can return to the dashboard when needed.
		 */
		$checklist_data = [
			'name'         => self::get_name( $checklist_slug ),
			'description'  => self::get_description( $checklist_slug ),
			'steps'        => [],
			'dashboardURL' => Wizards::get_url( 'dashboard' ),
			'listStyle'    => self::get_style( $checklist_slug ),
		];

		foreach ( $checklist['wizards'] as $wizard_slug ) {
			$wizard = Wizards::get_wizard( $wizard_slug );
			if ( ! $wizard ) {
				continue;
			}

			/**
			 * One checklist step has the following info:
			 * name        => String name of the step.
			 * description => String description of the step.
			 * url         => String URL of the wizard.
			 * length      => String textual description of how long the wizard is expected to take.
			 * completed   => Boolean whether the wizard has been completed.
			 */
			$checklist_data['steps'][] = [
				'name'        => $wizard->get_name(),
				'description' => $wizard->get_description(),
				'url'         => $wizard->get_url(),
				'length'      => $wizard->get_length(),
				'completed'   => $wizard->is_completed(),
			];
		}

		wp_register_script(
			'newspack-checklist',
			Newspack::plugin_url() . '/dist/checklist.js',
			[ 'wp-components' ],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/checklist.js' ),
			true
		);
		wp_localize_script( 'newspack-checklist', 'newspack_checklist', $checklist_data );
		wp_enqueue_script( 'newspack-checklist' );

		wp_register_style(
			'newspack-checklist',
			Newspack::plugin_url() . '/dist/checklist.css',
			[ 'wp-components' ],
			filemtime( dirname( NEWSPACK_PLUGIN_FILE ) . '/dist/checklist.css' )
		);
		wp_style_add_data( 'newspack-checklist', 'rtl', 'replace' );
		wp_enqueue_style( 'newspack-checklist' );
	}
}
Checklists::init();
