<?php
/**
 * Newspack plugin CLI initializer
 *
 * @package Newspack
 */

namespace Newspack\CLI;

use WP_CLI;

defined( 'ABSPATH' ) || exit;

/**
 * Initializer CLI commands
 */
class Initializer {

	/**
	 * Initialized this class and adds hooks to register CLI commands
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'register_comands' ] );
		include_once NEWSPACK_ABSPATH . 'includes/cli/class-ras.php';
		include_once NEWSPACK_ABSPATH . 'includes/cli/class-ras-esp-sync.php';
		include_once NEWSPACK_ABSPATH . 'includes/cli/class-co-authors-plus.php';
	}

	/**
	 * Adds CLI commands. Do not call directly or before init hooks
	 *
	 * @return void
	 */
	public static function register_comands() {
		if ( ! defined( 'WP_CLI' ) ) {
			return;
		}

		WP_CLI::add_command( 'newspack setup', 'Newspack\CLI\Setup' );

		// Utility commands for managing RAS data via WP CLI.
		WP_CLI::add_command(
			'newspack ras setup',
			[ 'Newspack\CLI\RAS', 'cli_setup_ras' ]
		);

		WP_CLI::add_command(
			'newspack verify-reader',
			[ 'Newspack\CLI\RAS', 'cli_verify_reader' ],
			[
				'shortdesc' => 'Verify a reader account . ',
				'synopsis'  => [
					[
						'type'        => 'positional',
						'name'        => 'user',
						'description' => 'ID or email of the user account . ',
						'optional'    => false,
						'repeating'   => false,
					],
				],
			]
		);

		WP_CLI::add_command(
			'newspack esp sync',
			[ 'Newspack\CLI\RAS_ESP_Sync', 'cli_sync_contacts' ],
			[
				'shortdesc' => __( 'Sync reader data to the connected ESP.', 'newspack-plugin' ),
				'synopsis'  => [
					[
						'type'     => 'flag',
						'name'     => 'dry-run',
						'optional' => true,
					],
					[
						'type'     => 'flag',
						'name'     => 'active-only',
						'optional' => true,
					],
					[
						'type'     => 'assoc',
						'name'     => 'migrated-subscriptions',
						'default'  => false,
						'optional' => true,
						'options'  => [ 'stripe', 'piano-csv', 'stripe-csv', false ],
					],
					[
						'type'     => 'assoc',
						'name'     => 'subscription-ids',
						'default'  => false,
						'optional' => true,
					],
					[
						'type'     => 'assoc',
						'name'     => 'user-ids',
						'default'  => false,
						'optional' => true,
					],
					[
						'type'     => 'assoc',
						'name'     => 'order-ids',
						'default'  => false,
						'optional' => true,
					],
					[
						'type'     => 'assoc',
						'name'     => 'batch-size',
						'default'  => 10,
						'optional' => true,
					],
					[
						'type'     => 'assoc',
						'name'     => 'offset',
						'default'  => 0,
						'optional' => true,
					],
					[
						'type'     => 'assoc',
						'name'     => 'max-batches',
						'default'  => 0,
						'optional' => true,
					],
					[
						'type'     => 'assoc',
						'name'     => 'sync-context',
						'optional' => true,
					],
				],
			]
		);

		WP_CLI::add_command( 'newspack migrate-co-authors-guest-authors', [ 'Newspack\CLI\Co_Authors_Plus', 'migrate_guest_authors' ] );
		WP_CLI::add_command( 'newspack backfill-non-editing-contributors', [ 'Newspack\CLI\Co_Authors_Plus', 'backfill_non_editing_contributor' ] );
		WP_CLI::add_command(
			'newspack schedule-co-authors-author-term-backfill',
			[
				'Newspack\CLI\Co_Authors_Plus',
				'schedule_author_term_backfill',
			]
		);
	}
}
