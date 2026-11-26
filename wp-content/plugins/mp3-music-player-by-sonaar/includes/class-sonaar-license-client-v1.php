<?php
/** Shared, versioned transport contract. Keep copies identical in Free, Pro and the theme. */
if ( ! class_exists( 'Sonaar_License_Client_V1', false ) ) {
class Sonaar_License_Client_V1 {
    public static function site() {
        // Keep the legacy wire identity; also work without HTTP server variables (cron/CLI).
        return ! empty( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : (string) wp_parse_url( home_url(), PHP_URL_HOST );
    }

    public static function decode( $http ) {
        if ( is_wp_error( $http ) ) {
            return $http;
        }
        if ( ! is_array( $http ) || 200 !== (int) wp_remote_retrieve_response_code( $http ) ) {
            return new WP_Error( 'sonaar_unavailable', 'The licensing service is temporarily unavailable. Your saved license has not been changed.' );
        }
        $data = json_decode( wp_remote_retrieve_body( $http ), true );
        if ( JSON_ERROR_NONE !== json_last_error() ) {
            return new WP_Error( 'sonaar_invalid_response', 'The licensing service returned an unreadable response. Please try again later.' );
        }
        return $data;
    }

    public static function license( $http ) {
        $data = self::decode( $http );
        if ( is_wp_error( $data ) ) {
            return $data;
        }
        // The original API deliberately returns JSON false for a nonexistent key.
        if ( false === $data ) {
            return array( 'success' => false, 'error' => 'invalid' );
        }
        if ( ! is_array( $data ) || ! isset( $data['success'] ) || ! is_bool( $data['success'] ) ) {
            return new WP_Error( 'sonaar_invalid_response', 'The licensing service returned an incomplete response.' );
        }
        if ( false === $data['success'] ) {
            $known = array( 'invalid', 'missing', 'expired', 'disabled', 'revoked', 'inactive', 'site_inactive', 'missing_url', 'no_activations_left', 'item_name_mismatch', 'key_mismatch', 'invalid_item_id', 'license_not_activable' );
            if ( empty( $data['error'] ) || ! in_array( $data['error'], $known, true ) ) {
                return new WP_Error( 'sonaar_unavailable', 'License verification is temporarily unavailable. Your saved license has not been changed.' );
            }
            return $data;
        }
        $info = isset( $data['license_info'] ) && is_array( $data['license_info'] ) ? $data['license_info'] : array();
        if ( ! isset( $info['status'], $info['price_id'], $info['expiration'] ) ||
            'active' !== $info['status'] || ! is_scalar( $info['price_id'] ) ||
            ( 'lifetime' !== $info['expiration'] && ! is_numeric( $info['expiration'] ) ) ) {
            return new WP_Error( 'sonaar_invalid_response', 'License verification returned incomplete license information.' );
        }
        return $data;
    }

    public static function cached_license( $cached, $key ) {
        if ( ! is_array( $cached ) || ! isset( $cached['licenceKey'], $cached['response'] ) || $cached['licenceKey'] !== $key ) {
            return false;
        }
        $data = self::license( array( 'response' => array( 'code' => 200 ), 'body' => wp_json_encode( $cached['response'] ) ) );
        if ( is_wp_error( $data ) ) {
            return false;
        }
        // Recheck an elapsed paid term; only the server can confirm whether it was renewed.
        if ( false === $data['success'] ) {
            // Old clients cached errors for 7/15 days without retaining the HTTP status.
            return isset( $cached['verified'] ) && true === $cached['verified'];
        }
        return 'lifetime' === $data['license_info']['expiration'] || (int) $data['license_info']['expiration'] > time();
    }

    public static function update_data( $data ) {
        if ( ! is_array( $data ) ) {
            return false;
        }
        if ( array() === $data ) {
            return true; // Legacy response when already up to date.
        }
        if ( empty( $data['new_version'] ) || ! is_string( $data['new_version'] ) ||
            ! preg_match( '/^[0-9][0-9A-Za-z.+_-]{0,63}$/D', $data['new_version'] ) ) {
            return false;
        }
        if ( isset( $data['package'] ) && '' !== $data['package'] &&
            ( ! is_string( $data['package'] ) || ! filter_var( $data['package'], FILTER_VALIDATE_URL ) ||
              ! in_array( strtolower( (string) wp_parse_url( $data['package'], PHP_URL_SCHEME ) ), array( 'http', 'https' ), true ) ) ) {
            return false;
        }
        return true;
    }

    public static function defer( $key, $http ) {
        $previous = get_site_transient( $key );
        $attempt = is_array( $previous ) && isset( $previous['attempt'] ) ? min( 8, (int) $previous['attempt'] + 1 ) : 1;
        $delay = min( 6 * HOUR_IN_SECONDS, 300 * pow( 2, $attempt - 1 ) + wp_rand( 0, 60 ) );
        if ( ! is_wp_error( $http ) && is_array( $http ) ) {
            $retry = wp_remote_retrieve_header( $http, 'retry-after' );
            if ( is_scalar( $retry ) && '' !== (string) $retry ) {
                $seconds = is_numeric( $retry ) ? (int) $retry : strtotime( $retry ) - time();
                $delay = max( $delay, min( DAY_IN_SECONDS, max( 0, $seconds ) ) );
            }
        }
        set_site_transient( $key, array( 'attempt' => $attempt, 'until' => time() + $delay ), DAY_IN_SECONDS );
    }

    public static function deferred( $key ) {
        $retry = get_site_transient( $key );
        return is_array( $retry ) && isset( $retry['until'] ) && $retry['until'] > time();
    }

    public static function update( $key, $identity, $url, $args ) {
        // A response may only be reused for this license, site, product and installed version.
        $fingerprint = hash( 'sha256', $identity );
        $fresh = get_site_transient( $key );
        $owner = get_site_transient( $key . '_identity' );
        if ( $owner === $fingerprint && false !== $fresh && self::update_data( (array) $fresh ) ) {
            return (array) $fresh;
        }
        $retry_key = $key . '_retry';
        if ( get_site_transient( $key . '_retry_identity' ) !== $fingerprint ) {
            delete_site_transient( $retry_key );
        }
        if ( ! self::deferred( $retry_key ) ) {
            $http = wp_remote_post( $url, $args );
            $data = self::decode( $http );
            if ( ! is_wp_error( $data ) && self::update_data( $data ) ) {
                // Older Free updaters read this cache as an object. Preserve that format.
                set_site_transient( $key, empty( $data ) ? array() : (object) $data, 8 * HOUR_IN_SECONDS );
                set_site_transient( $key . '_identity', $fingerprint, 8 * HOUR_IN_SECONDS );
                // A valid empty/no-package result replaces a previous downloadable offer too.
                set_site_transient( $key . '_last_good', array( 'identity' => $fingerprint, 'data' => $data ), DAY_IN_SECONDS );
                delete_site_transient( $retry_key );
                delete_site_transient( $key . '_retry_identity' );
                return $data;
            }
            self::defer( $retry_key, $http );
            set_site_transient( $key . '_retry_identity', $fingerprint, DAY_IN_SECONDS );
        }
        $last = get_site_transient( $key . '_last_good' );
        return is_array( $last ) && isset( $last['identity'], $last['data'] ) &&
            $fingerprint === $last['identity'] && self::update_data( $last['data'] ) ? $last['data'] : false;
    }

    public static function clear_updates( $key ) {
        foreach ( array( '', '_identity', '_last_good', '_retry', '_retry_identity' ) as $suffix ) {
            delete_site_transient( $key . $suffix );
        }
    }
}
}

// Load outside the transport class guard: an older sibling may define that class first.
require_once __DIR__ . '/class-sonaar-update-access-v1.php';
