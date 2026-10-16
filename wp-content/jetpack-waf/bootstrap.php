<?php
define( 'DISABLE_JETPACK_WAF', false );
if ( defined( 'DISABLE_JETPACK_WAF' ) && DISABLE_JETPACK_WAF ) return;
define( 'JETPACK_WAF_MODE', 'silent' );
define( 'JETPACK_WAF_SHARE_DATA', false );
define( 'JETPACK_WAF_SHARE_DEBUG_DATA', false );
define( 'JETPACK_WAF_DIR', '/code/wp-content/jetpack-waf' );
define( 'JETPACK_WAF_WPCONFIG', '/code/wp-content/../wp-config.php' );
define( 'JETPACK_WAF_ENTRYPOINT', 'rules/rules.php' );
( static function () {
	$classmap_file = '/code/wp-content/plugins/jetpack/vendor/composer/autoload_classmap.php';
	if ( ! is_file( $classmap_file ) ) {
		return;
	}
	$classmap   = require $classmap_file;
	$autoloader = static function ( $class_name ) use ( $classmap ) {
		if ( isset( $classmap[ $class_name ] ) ) {
			require $classmap[ $class_name ];
		}
	};
	spl_autoload_register( $autoloader );
	Automattic\Jetpack\Waf\Waf_Runner::initialize();
	spl_autoload_unregister( $autoloader );

	// The preloaded WAF classes keep running once WordPress starts, and the active plugin's older copy may lack a class
	// they reference. Keep resolving WAF classes from here, behind the Jetpack autoloader, which prepends itself.
	$waf_classmap = array_filter(
		$classmap,
		static function ( $class_name ) {
			return 0 === strpos( $class_name, 'Automattic\Jetpack\Waf\\' );
		},
		ARRAY_FILTER_USE_KEY
	);
	spl_autoload_register(
		static function ( $class_name ) use ( $waf_classmap ) {
			if ( isset( $waf_classmap[ $class_name ] ) ) {
				require $waf_classmap[ $class_name ];
			}
		}
	);
} )();
