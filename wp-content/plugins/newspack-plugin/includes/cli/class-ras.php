<?php
/**
 * Newspack setup wizard. Required plugins, introduction, and data collection.
 *
 * @package Newspack
 */

namespace Newspack\CLI;

use WP_CLI;
use Newspack\Reader_Activation;

defined( 'ABSPATH' ) || exit;

/**
 * Convenience scripts for Reader Activation System (RAS) setup and debugging.
 */
class RAS {
	/**
	 * Verify the given user, bypassing the need to complete the email-based verification flow.
	 */
	public static function cli_setup_ras() {
		if ( Reader_Activation::is_ras_campaign_configured() ) {
			WP_CLI::error( __( 'RAS is already configured for this site.', 'newspack-plugin' ) );
		}

		if ( ! class_exists( '\Newspack_Popups_Presets' ) ) {
			WP_CLI::error( __( 'Newspack Campaigns plugin not found.', 'newspack-plugin' ) );
		}

		
		if ( ! class_exists( '\Newspack_Newsletters_Subscription' ) ) {
			WP_CLI::error( __( 'Newspack Newsletters plugin not found.', 'newspack-plugin' ) );
		}

		if ( \is_wp_error( \Newspack_Newsletters_Subscription::get_lists() ) ) {
			WP_CLI::error( __( 'Newspack Newsletters provider not set.', 'newspack-plugin' ) );
		}

		$result = \Newspack_Popups_Presets::activate_ras_presets();
		
		if ( ! $result ) {
			WP_CLI::error( __( 'Something went wrong. Please check for required plugins and try again.', 'newspack-plugin' ) );
			exit;
		}

		WP_CLI::success( __( 'RAS enabled with default prompts.', 'newspack-plugin' ) );
	}

	/**
	 * Verify the given user, bypassing the need to complete the email-based verification flow.
	 *
	 * @param array $args Positional args.
	 * @param array $assoc_args Associative args.
	 */
	public static function cli_verify_reader( $args, $assoc_args ) {
		$user_id_or_email = ! empty( $args ) ? reset( $args ) : false;
		if ( ! $user_id_or_email ) {
			WP_CLI::error( __( 'Please provide a user ID or email address.', 'newspack-plugin' ) );
			exit;
		}

		$get_by = is_numeric( $user_id_or_email ) ? 'id' : 'email';
		$user   = \get_user_by( $get_by, $user_id_or_email );

		if ( ! $user ) {
			WP_CLI::error( __( 'User not found.', 'newspack-plugin' ) );
			exit;
		}

		Reader_Activation::set_reader_verified( $user );

		WP_CLI::success(
			sprintf(
				// Translators: email address of user that was verified.
				__( 'Verified user %s.', 'newspack-plugin' ),
				$user->user_email
			)
		);
	}
}
