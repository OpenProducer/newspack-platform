<?php
/** Sonaar update entitlement checks, including verification at the download boundary. */
if ( ! class_exists( 'Sonaar_Update_Access_V1', false ) ) {
class Sonaar_Update_Access_V1 {
    private static $download_context = array();
    private static $verifying = array();

    private static function key( $type ) {
        $key = get_site_option( 'plugin' === $type ? 'sonaar_music_licence' : 'sonaar_licence', '' );
        if ( 'theme' === $type && ( ! is_string( $key ) || '' === trim( $key ) || 'noLicense' === $key ) ) {
            if ( get_site_option( 'sonaar_theme_registration_removed', false ) ) {
                return ''; // Other blogs can still hold legacy keys after network removal.
            }
            $key = get_option( 'sonaar_licence', '' ); // Existing single-site theme registrations.
        }
        return is_string( $key ) && 'noLicense' !== $key ? trim( $key ) : '';
    }

    public static function has_license( $type ) {
        return '' !== self::key( $type );
    }

    public static function network_theme_removed( $option, $network_id ) {
        update_network_option( $network_id, 'sonaar_theme_registration_removed', true );
    }

    public static function record_license( $type, $key, $response ) {
        if ( $key !== self::key( $type ) || ! is_array( $response ) || ! isset( $response['success'] ) ) {
            return;
        }
        $option = 'sonaar_update_denial_' . $type;
        if ( true === $response['success'] ) {
            delete_site_option( $option );
            delete_site_transient( 'sonaar_update_verify_' . $type . '_' . md5( $key ) );
        } elseif ( isset( $response['error'] ) && in_array( $response['error'], array( 'expired', 'invalid', 'missing', 'disabled', 'revoked', 'inactive', 'site_inactive', 'missing_url', 'no_activations_left', 'item_name_mismatch', 'key_mismatch', 'invalid_item_id', 'license_not_activable' ), true ) ) {
            update_site_option( $option, array( 'key_hash' => hash( 'sha256', $key ), 'reason' => $response['error'] ) );
        }
    }

    public static function access_error( $type ) {
        $key = self::key( $type );
        if ( '' === $key ) {
            return new WP_Error( 'sonaar_license_required', 'Update cancelled: add your Sonaar license key before updating this product.' );
        }
        $reason = '';
        $denial = get_site_option( 'sonaar_update_denial_' . $type, array() );
        if ( is_array( $denial ) && isset( $denial['key_hash'], $denial['reason'] ) && hash( 'sha256', $key ) === $denial['key_hash'] ) {
            $reason = $denial['reason'];
        }
        $cached = get_site_transient( 'plugin' === $type ? 'sonaar_music_licence' : 'sonaar_licence' );
        if ( '' === $reason && is_array( $cached ) && isset( $cached['licenceKey'], $cached['response'] ) && $key === $cached['licenceKey'] && is_array( $cached['response'] ) ) {
            $response = $cached['response'];
            if ( ! empty( $cached['verified'] ) && isset( $response['success'], $response['error'] ) && false === $response['success'] ) {
                self::record_license( $type, $key, $response );
                $denial = get_site_option( 'sonaar_update_denial_' . $type, array() );
                $reason = isset( $denial['reason'] ) ? $denial['reason'] : '';
            }
            $expiration = isset( $response['license_info']['expiration'] ) ? $response['license_info']['expiration'] : null;
            if ( '' === $reason && is_numeric( $expiration ) && (int) $expiration <= time() ) {
                $reason = 'expired'; // An elapsed term must not reuse a previously authorized ZIP.
            }
        }
        if ( '' === $reason ) {
            return false; // Existing registrations without new metadata remain compatible.
        }
        return new WP_Error( 'expired' === $reason ? 'sonaar_license_expired' : 'sonaar_license_ineligible',
            'expired' === $reason ? 'Update cancelled: your Sonaar license has expired. Renew it to receive updates.' : 'Update cancelled: your Sonaar license is not eligible for this update. Check its status in the License settings.' );
    }

    public static function refresh_access( $type, $force = false ) {
        $error = self::access_error( $type );
        if ( ( ! $error && ! $force ) || ( $error && 'sonaar_license_required' === $error->get_error_code() ) ) {
            return $error;
        }
        $key = self::key( $type );
        $cached = get_site_transient( 'plugin' === $type ? 'sonaar_music_licence' : 'sonaar_licence' );
        $cached_valid = ! $error && is_array( $cached ) && isset( $cached['response']['success'] ) && true === $cached['response']['success'] && Sonaar_License_Client_V1::cached_license( $cached, $key );
        if ( isset( self::$verifying[$type] ) ) {
            return $error ? $error : ( $cached_valid ? false : new WP_Error( 'sonaar_unavailable', 'Could not verify update access. Your saved license has not been changed. Please try again later.' ) );
        }
        $retry = 'sonaar_update_verify_' . $type . '_' . md5( $key );
        if ( Sonaar_License_Client_V1::deferred( $retry ) ) {
            return $error ? $error : ( $cached_valid ? false : new WP_Error( 'sonaar_unavailable', 'Could not verify update access. Your saved license has not been changed. Please try again later.' ) );
        }
        self::$verifying[$type] = true;
        try {
            // Verify at the actual download boundary, not on each page view.
            $remote = array( 'licenceKey' => $key, 'siteUrl' => Sonaar_License_Client_V1::site() );
            if ( 'plugin' === $type ) {
                $remote['item_id'] = '5816';
            }
            $args = array( 'timeout' => 15, 'body' => array( 'remotelicence' => $remote ) );
            if ( 'plugin' === $type && defined( 'SRMP3PRO_VERSION' ) ) {
                global $wp_version;
                $args['headers'] = array( 'User-Agent' => 'SRMP3PRO/' . SRMP3PRO_VERSION . ' WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ) );
            }
            $http = wp_remote_post( 'https://sonaar.io/wp-json/wp/v2/sonaar-api/', $args );
            $response = Sonaar_License_Client_V1::license( $http );
            if ( is_wp_error( $response ) ) {
                Sonaar_License_Client_V1::defer( $retry, $http );
                return $cached_valid ? false : $response; // Keep valid cached access during an outage.
            }
            self::record_license( $type, $key, $response );
            if ( false === $response['success'] ) {
                // Storing a denial can refresh the update transient, which invokes
                // other updater callbacks. Establish backoff before those callbacks.
                Sonaar_License_Client_V1::defer( $retry, $http );
            }
            $store = 'plugin' === $type ? 'sonaar_music_store_verified_license' : 'sonaar_store_verified_license';
            if ( function_exists( $store ) ) {
                call_user_func( $store, $key, $response );
            } else {
                self::record_license( $type, $key, $response );
                set_site_transient( 'plugin' === $type ? 'sonaar_music_licence' : 'sonaar_licence', array( 'licenceKey' => $key, 'response' => $response, 'verified' => true ), 30 * MINUTE_IN_SECONDS );
            }
            return self::access_error( $type );
        } finally {
            unset( self::$verifying[$type] );
        }
    }

    public static function plugins( $updates ) {
        if ( is_object( $updates ) && ! self::has_license( 'plugin' ) ) {
            unset( $updates->response['sonaar-music-pro/sonaar-music-pro.php'], $updates->no_update['sonaar-music-pro/sonaar-music-pro.php'] );
        }
        return $updates;
    }

    public static function themes( $updates ) {
        if ( is_object( $updates ) && ! self::has_license( 'theme' ) ) {
            unset( $updates->response['sonaar'], $updates->no_update['sonaar'] );
        }
        return $updates;
    }

    public static function clear_offer( $type ) {
        $name = 'plugin' === $type ? 'update_plugins' : 'update_themes';
        $target = 'plugin' === $type ? 'sonaar-music-pro/sonaar-music-pro.php' : 'sonaar';
        $updates = get_site_transient( $name );
        if ( is_object( $updates ) ) {
            unset( $updates->response[$target], $updates->no_update[$target] );
            set_site_transient( $name, $updates ); // Preserve other products and checked versions.
        }
    }

    public static function package_options( $options ) {
        self::$download_context = is_array( $options ) && isset( $options['package'], $options['hook_extra'] )
            ? array( 'package' => $options['package'], 'hook_extra' => $options['hook_extra'] ) : array();
        return $options;
    }

    private static function check( $reply, $extra, $force = false ) {
        if ( is_wp_error( $reply ) || ! is_array( $extra ) || ( isset( $extra['action'] ) && 'update' !== $extra['action'] ) ) {
            return $reply;
        }
        // Core bulk_upgrade() also handles individual AJAX updates. Its per-item
        // hooks contain plugin/theme but omit action/type. Match the exact item.
        $type = isset( $extra['plugin'] ) && 'sonaar-music-pro/sonaar-music-pro.php' === $extra['plugin'] ? 'plugin' :
            ( isset( $extra['theme'] ) && 'sonaar' === $extra['theme'] ? 'theme' : '' );
        if ( '' !== $type && ( ! isset( $extra['type'] ) || $type === $extra['type'] ) ) {
            $error = self::refresh_access( $type, $force );
            if ( $error ) {
                return $error;
            }
        }
        return $reply;
    }

    public static function download( $reply, $package, $upgrader, $extra = array() ) {
        // Older WordPress versions omit hook_extra from upgrader_pre_download.
        if ( empty( $extra ) && isset( self::$download_context['package'], self::$download_context['hook_extra'] ) &&
            $package === self::$download_context['package'] ) {
            $extra = self::$download_context['hook_extra'];
        }
        self::$download_context = array();
        return self::check( $reply, $extra, true );
    }

    public static function install( $reply, $extra ) {
        // Also catch removal while a package was downloading, before replacing files.
        return self::check( $reply, $extra );
    }

    private static function ajax( $type, $target, $field ) {
        if ( ! isset( $_POST[$field] ) || ! is_string( $_POST[$field] ) || wp_unslash( $_POST[$field] ) !== $target ||
            ! current_user_can( 'update_' . $type . 's' ) || ! check_ajax_referer( 'updates', false, false ) ) {
            return; // Let WordPress handle unrelated requests, permissions and invalid nonces.
        }
        $error = self::refresh_access( $type );
        if ( ! $error ) {
            return;
        }
        wp_send_json_error( array(
            'update' => $type, 'slug' => 'plugin' === $type ? 'sonaar-music-pro' : 'sonaar',
            'errorCode' => $error->get_error_code(),
            'errorMessage' => $error->get_error_message(),
        ) );
    }

    public static function ajax_plugin() {
        self::ajax( 'plugin', 'sonaar-music-pro/sonaar-music-pro.php', 'plugin' );
    }

    public static function ajax_theme() {
        self::ajax( 'theme', 'sonaar', 'slug' );
    }
}
add_filter( 'site_transient_update_plugins', array( 'Sonaar_Update_Access_V1', 'plugins' ), PHP_INT_MAX );
add_filter( 'pre_set_site_transient_update_plugins', array( 'Sonaar_Update_Access_V1', 'plugins' ), PHP_INT_MAX );
add_filter( 'site_transient_update_themes', array( 'Sonaar_Update_Access_V1', 'themes' ), PHP_INT_MAX );
add_filter( 'pre_set_site_transient_update_themes', array( 'Sonaar_Update_Access_V1', 'themes' ), PHP_INT_MAX );
add_filter( 'upgrader_package_options', array( 'Sonaar_Update_Access_V1', 'package_options' ), PHP_INT_MAX );
add_filter( 'upgrader_pre_download', array( 'Sonaar_Update_Access_V1', 'download' ), PHP_INT_MAX, 4 );
add_filter( 'upgrader_pre_install', array( 'Sonaar_Update_Access_V1', 'install' ), PHP_INT_MAX, 2 );
add_action( 'wp_ajax_update-plugin', array( 'Sonaar_Update_Access_V1', 'ajax_plugin' ), 0 );
add_action( 'wp_ajax_update-theme', array( 'Sonaar_Update_Access_V1', 'ajax_theme' ), 0 );
add_action( 'delete_site_option_sonaar_licence', array( 'Sonaar_Update_Access_V1', 'network_theme_removed' ), 10, 2 );
}
