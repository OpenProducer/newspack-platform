<?php
/**
 * Newspack Newsletter Ads
 *
 * @package Newspack
 */

defined( 'ABSPATH' ) || exit;

/**
 * Newspack Newsletters Ads Class.
 */
final class Newspack_Newsletters_Ads {
	/**
	 * CPT for Newsletter ads.
	 */
	const NEWSPACK_NEWSLETTERS_ADS_CPT = 'newspack_nl_ads_cpt';

	/**
	 * Ads admin page handler.
	 */
	const NEWSPACK_NEWSLETTERS_ADS_PAGE = 'newspack-newsletters-ads-admin';

	/**
	 * The single instance of the class.
	 *
	 * @var Newspack_Newsletters
	 */
	protected static $instance = null;

	/**
	 * Main Newspack Newsletter Ads Instance.
	 * Ensures only one instance of Newspack Ads Instance is loaded or can be loaded.
	 *
	 * @return Newspack Ads Instance - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ __CLASS__, 'register_ads_cpt' ] );
		add_action( 'init', [ __CLASS__, 'register_meta' ] );
		add_action( 'admin_menu', [ __CLASS__, 'add_ads_page' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'admin_enqueue_scripts' ] );
	}

	/**
	 * Register custom fields.
	 */
	public static function register_meta() {
		\register_meta(
			'post',
			'expiry_date',
			[
				'object_subtype' => self::NEWSPACK_NEWSLETTERS_ADS_CPT,
				'show_in_rest'   => true,
				'type'           => 'string',
				'single'         => true,
				'auth_callback'  => '__return_true',
			]
		);
	}

	/**
	 * Add ads page link.
	 */
	public static function add_ads_page() {
		add_submenu_page(
			'edit.php?post_type=' . Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT,
			__( 'Newsletters Ads', 'newspack-newsletters' ),
			__( 'Ads', 'newspack-newsletters' ),
			'manage_options',
			self::NEWSPACK_NEWSLETTERS_ADS_PAGE,
			[ __CLASS__, 'create_admin_page' ]
		);
	}

	/**
	 * Ads page callback.
	 */
	public static function create_admin_page() {
		?>
			<div class="wrap">
				<div id="newspack-newsletters-ads-admin"></div>
			</div>
		<?php
	}

	/**
	 * Enqueue ads admin scripts.
	 *
	 * @param string $handler Page handler.
	 */
	public static function admin_enqueue_scripts( $handler ) {
		if ( Newspack_Newsletters::NEWSPACK_NEWSLETTERS_CPT . '_page_' . self::NEWSPACK_NEWSLETTERS_ADS_PAGE !== $handler ) {
			return;
		};

		\wp_enqueue_script(
			self::NEWSPACK_NEWSLETTERS_ADS_PAGE,
			plugins_url( '../dist/adsAdmin.js', __FILE__ ),
			[ 'wp-components', 'wp-api-fetch', 'wp-date' ],
			filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/adsAdmin.js' ),
			true
		);
		wp_register_style(
			self::NEWSPACK_NEWSLETTERS_ADS_PAGE,
			plugins_url( '../dist/adsAdmin.css', __FILE__ ),
			[ 'wp-components' ],
			filemtime( NEWSPACK_NEWSLETTERS_PLUGIN_FILE . 'dist/adsAdmin.css' )
		);
		wp_style_add_data( self::NEWSPACK_NEWSLETTERS_ADS_PAGE, 'rtl', 'replace' );
		wp_enqueue_style( self::NEWSPACK_NEWSLETTERS_ADS_PAGE );
	}

	/**
	 * Register the custom post type for layouts.
	 */
	public static function register_ads_cpt() {
		if ( ! current_user_can( 'edit_others_posts' ) ) {
			return;
		}

		$cpt_args = [
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => false,
			'show_in_rest' => true,
			'supports'     => [ 'editor', 'title', 'custom-fields' ],
			'taxonomies'   => [],
		];
		\register_post_type( self::NEWSPACK_NEWSLETTERS_ADS_CPT, $cpt_args );
	}

}
Newspack_Newsletters_Ads::instance();
