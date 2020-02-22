<?php
/**
 * Tests the plugins API functionality.
 *
 * @package Newspack\Tests
 */

use Newspack\API\Plugins_Controller;
use Newspack\Plugin_Manager;

/**
 * Test plugin API endpoints functionality.
 */
class Newspack_Test_Plugins_Controller extends WP_UnitTestCase {

	/**
	 * Plugin slug/folder.
	 *
	 * @var string
	 */
	protected $api_namespace = '/newspack/v1';

	/**
	 * Set up stuff for testing API requests.
	 */
	public function setUp() {
		parent::setUp();

		global $wp_rest_server;
		$wp_rest_server = new WP_REST_Server();
		$this->server   = $wp_rest_server;
		do_action( 'rest_api_init' );

		$this->administrator = $this->factory->user->create( [ 'role' => 'administrator' ] );

		// Delete any lingering managed plugins.
		foreach ( Plugin_Manager::get_managed_plugins() as $slug => $plugin ) {
			Plugin_Manager::uninstall( $slug );
		}
	}

	/**
	 * Test that the routes are all registered.
	 */
	public function test_register_route() {
		$routes = $this->server->get_routes();
		$this->assertArrayHasKey( $this->api_namespace . '/plugins', $routes );
	}

	/**
	 * Test unauthorized users can't retrieve plugins info.
	 */
	public function test_get_plugins_unauthorized() {
		wp_set_current_user( 0 );
		$request  = new WP_REST_Request( 'GET', $this->api_namespace . '/plugins' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 401, $response->get_status() );
	}

	/**
	 * Test retrieving plugins info.
	 */
	public function test_get_plugins_authorized() {
		wp_set_current_user( $this->administrator );
		$request  = new WP_REST_Request( 'GET', $this->api_namespace . '/plugins' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );

		$data = $response->get_data();
		$this->assertArrayHasKey( 'jetpack', $data );

		$expected_jetpack_info = [
			'Name'        => 'Jetpack',
			'Description' => 'Bring the power of the WordPress.com cloud to your self-hosted WordPress. Jetpack enables you to connect your blog to a WordPress.com account to use the powerful features normally only available to WordPress.com users.',
			'Author'      => 'Automattic',
			'PluginURI'   => 'https://jetpack.com/',
			'AuthorURI'   => 'https://automattic.com/',
			'Download'    => 'wporg',
			'TextDomain'  => '',
			'DomainPath'  => '',
			'EditPath'    => 'admin.php?page=jetpack',
			'HandoffLink' => 'http://example.org/wp-admin/admin.php?page=jetpack',
			'Slug'        => 'jetpack',
			'Status'      => 'uninstalled',
			'Version'     => '',
		];
		$this->assertEquals( $expected_jetpack_info, $data['jetpack'] );
	}

	/**
	 * Test unauthorized users can't retrieve plugin info.
	 */
	public function test_get_plugin_unauthorized() {
		wp_set_current_user( 0 );
		$request  = new WP_REST_Request( 'GET', $this->api_namespace . '/plugins/jetpack' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 401, $response->get_status() );
	}

	/**
	 * Test retrieving one plugin's info.
	 */
	public function test_get_plugin_authorized() {
		wp_set_current_user( $this->administrator );

		$request  = new WP_REST_Request( 'GET', $this->api_namespace . '/plugins/jetpack' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );

		$expected_data = [
			'Name'   => 'Jetpack',
			'Status' => 'uninstalled',
		];
		$response_data = $response->get_data();
		$this->assertEquals( $expected_data['Name'], $response_data['Name'] );
		$this->assertEquals( $expected_data['Status'], $response_data['Status'] );

		$request  = new WP_REST_Request( 'GET', $this->api_namespace . '/plugins/this-dont-exist' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 404, $response->get_status() );
	}

	/**
	 * Test unauthorized users can't activate/deactivate plugins.
	 */
	public function test_activate_deactivate_plugin_unauthorized() {
		wp_set_current_user( 0 );
		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/activate' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 401, $response->get_status() );

		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/deactivate' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 401, $response->get_status() );
	}

	/**
	 * Test activating/deactivating plugins.
	 */
	public function test_activate_deactivate_plugin_authorized() {
		wp_set_current_user( $this->administrator );

		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/activate' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );
		$expected_data = [
			'Name'   => 'AMP',
			'Status' => 'active',
		];
		$response_data = $response->get_data();
		$this->assertEquals( $expected_data['Name'], $response_data['Name'] );
		$this->assertEquals( $expected_data['Status'], $response_data['Status'] );

		// Activating the plugin again should fail.
		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/activate' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 500, $response->get_status() );

		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/deactivate' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );
		$expected_data = [
			'Name'   => 'AMP',
			'Status' => 'inactive',
		];
		$response_data = $response->get_data();
		$this->assertEquals( $expected_data['Name'], $response_data['Name'] );
		$this->assertEquals( $expected_data['Status'], $response_data['Status'] );

		// Dectivating the plugin again should fail.
		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/deactivate' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 500, $response->get_status() );

		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/this-dont-exist/activate' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 404, $response->get_status() );

		// Should not be able to activate plugins that aren't managed.
		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/hello-dolly/activate' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 404, $response->get_status() );
	}

	/**
	 * Test unauthorized users can't install/uninstall plugins.
	 */
	public function test_install_uninstall_unauthorized() {
		wp_set_current_user( 0 );
		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/install' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 401, $response->get_status() );

	}

	/**
	 * Test installing/uninstalling plugins.
	 */
	public function test_install_uninstall_authorized() {
		wp_set_current_user( $this->administrator );

		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/install' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );
		$expected_data = [
			'Name'   => 'AMP',
			'Status' => 'inactive',
		];
		$response_data = $response->get_data();
		$this->assertEquals( $expected_data['Name'], $response_data['Name'] );
		$this->assertEquals( $expected_data['Status'], $response_data['Status'] );

		// Installing the plugin again should fail.
		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/install' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 500, $response->get_status() );

		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/uninstall' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );
		$expected_data = [
			'Name'   => 'AMP',
			'Status' => 'uninstalled',
		];
		$response_data = $response->get_data();
		$this->assertEquals( $expected_data['Name'], $response_data['Name'] );
		$this->assertEquals( $expected_data['Status'], $response_data['Status'] );

		// Uninstalling the plugin again should fail.
		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/amp/uninstall' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 500, $response->get_status() );

		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/this-dont-exist/install' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 404, $response->get_status() );

		// Should not be able to install plugins that aren't managed.
		$request  = new WP_REST_Request( 'POST', $this->api_namespace . '/plugins/hello-dolly/install' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 404, $response->get_status() );
	}

	/**
	 * Test the schema.
	 */
	public function test_schema() {
		$request  = new WP_REST_Request( 'OPTIONS', $this->api_namespace . '/plugins' );
		$response = $this->server->dispatch( $request );
		$this->assertEquals( 200, $response->get_status() );

		$schema = $response->get_data();
		$this->assertEquals( 'string', $schema['schema']['properties']['Name']['type'] );
	}
}
