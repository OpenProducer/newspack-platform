<?php
/**
 * Tests the plugin management functionality.
 *
 * @package Newspack\Tests
 */

use Newspack\Plugin_Manager;

/**
 * Test plugin management functionality.
 */
class Newspack_Test_Plugin_Manager extends WP_UnitTestCase {

	/**
	 * Plugin slug/folder.
	 *
	 * @var string
	 */
	protected $plugin_slug = 'amp';

	/**
	 * Plugin file path.
	 *
	 * @var string
	 */
	protected $plugin_file = 'amp/amp.php';

	/**
	 * URL to plugin download.
	 *
	 * @var string
	 */
	protected $plugin_url = 'https://downloads.wordpress.org/plugin/amp.1.2.0.zip';

	/**
	 * Compatibility checks and clean up.
	 */
	public function setUp() {
		// These tests can't run on environments where we can't install plugins e.g. VIP Go.
		if ( ! Plugin_Manager::can_install_plugins() ) {
			$this->markTestSkipped( 'Plugin installation is not allowed in the environment' );
		}

		// Clean up any lingering plugin installs that may exist from a previous test.
		Plugin_Manager::uninstall( $this->plugin_file );
	}

	/**
	 * Test Plugin_Manager::get_managed_plugins.
	 */
	public function test_get_managed_plugins() {
		$managed_plugins = Plugin_Manager::get_managed_plugins();

		$this->assertArrayHasKey( 'jetpack', $managed_plugins );

		$expected_jetpack_info = [
			'Name'        => __( 'Jetpack', 'newspack' ),
			'Description' => esc_html__( 'Bring the power of the WordPress.com cloud to your self-hosted WordPress. Jetpack enables you to connect your blog to a WordPress.com account to use the powerful features normally only available to WordPress.com users.', 'newspack' ),
			'Author'      => 'Automattic',
			'PluginURI'   => 'https://jetpack.com/',
			'AuthorURI'   => 'https://automattic.com/',
			'Download'    => 'wporg',
			'EditPath'    => 'admin.php?page=jetpack',
			'HandoffLink' => 'http://example.org/wp-admin/admin.php?page=jetpack',
			'Slug'        => 'jetpack',
			'TextDomain'  => '',
			'DomainPath'  => '',
			'Version'     => '',
			'Status'      => 'uninstalled',
		];
		$this->assertEquals( $expected_jetpack_info, $managed_plugins['jetpack'] );
	}

	/**
	 * Test the plugin-slug parser.
	 */
	public function test_get_plugin_slug() {
		$this->assertEquals( 'hello-dolly', Plugin_Manager::get_plugin_slug( 'https://downloads.wordpress.org/plugin/hello-dolly.1.6.zip' ) );
		$this->assertEquals( 'hello-dolly', Plugin_Manager::get_plugin_slug( 'https://downloads.wordpress.org/plugin/hello-dolly.zip/' ) );
		$this->assertEquals( 'hello-dolly', Plugin_Manager::get_plugin_slug( 'https://downloads.wordpress.org/plugin/hello-dolly.zip?foo=blah&1' ) );
		$this->assertEquals( false, Plugin_Manager::get_plugin_slug( 'https://downloads.wordpress.org/plugin/hello-dolly' ) );
		$this->assertEquals( false, Plugin_Manager::get_plugin_slug( 'https://downloads.wordpress.org/plugin/hello-dolly.tar' ) );
		$this->assertEquals( 'hello-dolly', Plugin_Manager::get_plugin_slug( 'hello-dolly' ) );
		$this->assertEquals( false, Plugin_Manager::get_plugin_slug( '' ) );
		$this->assertEquals( false, Plugin_Manager::get_plugin_slug( true ) );
		$this->assertEquals( false, Plugin_Manager::get_plugin_slug( new WP_Error() ) );
		$this->assertEquals( false, Plugin_Manager::get_plugin_slug( 20 ) );
	}

	/**
	 * Test that activating an uninstalled plugin from a slug will install and activate the plugin.
	 * Deactivating the plugin will not uninstall the plugin.
	 */
	public function test_activate_deactivate_wporg_uninstalled() {
		$this->assertFalse( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );

		$this->assertTrue( Plugin_Manager::activate( $this->plugin_slug ) );
		$this->assertTrue( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );
		$this->assertTrue( is_plugin_active( $this->plugin_file ) );

		$this->assertTrue( Plugin_manager::deactivate( $this->plugin_file ) );
		$this->assertTrue( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );
		$this->assertFalse( is_plugin_active( $this->plugin_file ) );
	}

	/**
	 * Test that activating an uninstalled plugin from an URL will install and activate the plugin.
	 * Deactivating the plugin will not uninstall the plugin.
	 */
	public function test_activate_deactivate_url_uninstalled() {
		$this->assertFalse( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );

		$this->assertTrue( Plugin_Manager::activate( $this->plugin_url ) );
		$this->assertTrue( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );
		$this->assertTrue( is_plugin_active( $this->plugin_file ) );

		$this->assertTrue( Plugin_manager::deactivate( $this->plugin_file ) );
		$this->assertTrue( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );
		$this->assertFalse( is_plugin_active( $this->plugin_file ) );
	}

	/**
	 * Test error handling when activating/deactivating.
	 */
	public function test_activate_deactivate_errors() {
		$this->assertTrue( is_wp_error( Plugin_manager::activate( 'non-existant-plugin' ) ) );
		$this->assertTrue( is_wp_error( Plugin_manager::deactivate( 'non-existant-plugin' ) ) );

		$this->assertTrue( is_wp_error( Plugin_manager::activate( 'https://downloads.wordpress.org/plugin/non-existant-plugin.zip' ) ) );
		$this->assertTrue( is_wp_error( Plugin_manager::deactivate( 'https://downloads.wordpress.org/plugin/non-existant-plugin.zip' ) ) );

		$this->assertTrue( is_wp_error( Plugin_manager::activate( 'https://example.org/not-a-zip' ) ) );
		$this->assertTrue( is_wp_error( Plugin_manager::deactivate( 'https://example.org/not-a-zip' ) ) );
	}

	/**
	 * Test that activating/deactivating an installed plugin activates/deactivates the plugin.
	 */
	public function test_activate_deactivate_installed() {
		Plugin_Manager::install( $this->plugin_slug );

		// Activate by slug.
		$this->assertTrue( Plugin_Manager::activate( $this->plugin_slug ) );
		$this->assertTrue( is_plugin_active( $this->plugin_file ) );

		// If the plugin is already activated, activating it by slug should fail.
		$this->assertTrue( is_wp_error( Plugin_manager::activate( $this->plugin_slug ) ) );

		// Deactivate by slug.
		$this->assertTrue( Plugin_manager::deactivate( $this->plugin_slug ) );
		$this->assertFalse( is_plugin_active( $this->plugin_file ) );

		// If the plugin is already deactivated, deactivating should fail.
		$this->assertTrue( is_wp_error( Plugin_manager::deactivate( $this->plugin_file ) ) );

		// Activate by URL.
		$this->assertTrue( Plugin_Manager::activate( $this->plugin_url ) );
		$this->assertTrue( is_plugin_active( $this->plugin_file ) );

		// If the plugin is already activated, activating it by url should fail.
		$this->assertTrue( is_wp_error( Plugin_manager::activate( $this->plugin_url ) ) );

		// Deactivate by file.
		$this->assertTrue( Plugin_manager::deactivate( $this->plugin_file ) );
		$this->assertFalse( is_plugin_active( $this->plugin_file ) );
	}

	/**
	 * Test that plugins install/uninstall from WordPress.org by slug.
	 */
	public function test_plugin_install_uninstall_wporg() {
		$this->assertTrue( Plugin_Manager::install( $this->plugin_slug ) );
		$this->assertTrue( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );

		// If the plugin is already installed, installing it by slug should fail.
		$this->assertTrue( is_wp_error( Plugin_manager::install( $this->plugin_slug ) ) );

		// Uninstall by slug.
		$this->assertTrue( Plugin_Manager::uninstall( $this->plugin_slug ) );
		$this->assertFalse( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );
	}

	/**
	 * Test that plugins install/uninstall from a URL.
	 */
	public function test_plugin_install_uninstall_url() {
		$this->assertTrue( Plugin_Manager::install( $this->plugin_url ) );
		$this->assertTrue( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );

		// If the plugin is already installed, installing it by URL should fail.
		$this->assertTrue( is_wp_error( Plugin_manager::install( $this->plugin_url ) ) );

		// Uninstall by file.
		$this->assertTrue( Plugin_Manager::uninstall( $this->plugin_file ) );
		$this->assertFalse( file_exists( WP_PLUGIN_DIR . '/' . $this->plugin_file ) );
	}

	/**
	 * Test error handling when installing/uninstalling.
	 */
	public function test_plugin_install_uninstall_errors() {
		$this->assertTrue( is_wp_error( Plugin_manager::install( 'non-existant-plugin' ) ) );
		$this->assertTrue( is_wp_error( Plugin_manager::uninstall( 'non-existant-plugin' ) ) );

		$this->assertTrue( is_wp_error( Plugin_manager::install( 'https://downloads.wordpress.org/plugin/non-existant-plugin.zip' ) ) );
		$this->assertTrue( is_wp_error( Plugin_manager::uninstall( 'https://downloads.wordpress.org/plugin/non-existant-plugin.zip' ) ) );

		$this->assertTrue( is_wp_error( Plugin_manager::install( 'https://example.org/not-a-zip' ) ) );
		$this->assertTrue( is_wp_error( Plugin_manager::uninstall( 'https://example.org/not-a-zip' ) ) );
	}
}
