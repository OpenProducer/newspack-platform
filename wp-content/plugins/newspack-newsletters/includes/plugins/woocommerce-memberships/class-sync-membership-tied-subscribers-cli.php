<?php
/**
 * Newspack Newsletters Membership-tied Subscribers CLI.
 *
 * @package Newspack
 */

namespace Newspack_Newsletters\CLI;

defined( 'ABSPATH' ) || exit;

/**
 * Manages Settings page.
 */
class Sync_Membership_Tied_Subscribers_CLI {
	/**
	 * Initialize the class
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'initialize_cli_commands' ] );
	}

	/**
	 * Initialize CLI commands.
	 *
	 * @codeCoverageIgnore
	 */
	public static function initialize_cli_commands() {
		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}

		\WP_CLI::add_command(
			'newspack-newsletters sync-membership-tied-subscribers',
			[ __CLASS__, 'cli_sync_membership_tied_subscribers' ],
			[
				'shortdesc' => 'Synchronizes the membership-tied newsletter lists with the memberships.
For each Membership Plan set to restrict Subscription Lists, all members\' ESP subscription statuses will be
realigned with the membership status.

Note that if a member has unsubscribed from a list, but has an active membership, they will be re-subscribed.',
			]
		);
	}

	/**
	 * Can a memberships be considered active?
	 *
	 * @param \WC_Memberships_User_Membership $user_membership User membership.
	 */
	public static function is_membership_active( $user_membership ): bool {
		$active_statuses = wc_memberships()->get_user_memberships_instance()->get_active_access_membership_statuses();
		return in_array( $user_membership->get_status(), $active_statuses );
	}

	/**
	 * CLI handler for membership-tied newsletter lists synchronization.
	 *
	 * ## OPTIONS
	 *
	 * [--live]
	 * : Run the command in live mode, updating the data.
	 *
	 * [--verbose]
	 * : More output.
	 *
	 * ## EXAMPLES
	 *
	 *     wp newspack-newsletters sync-membership-tied-subscribers
	 *
	 * @param array $args Positional arguments.
	 * @param array $assoc_args Assoc arguments.
	 * @return void
	 */
	public static function cli_sync_membership_tied_subscribers( $args, $assoc_args ) {
		\WP_CLI::log( '' );

		if ( ! function_exists( 'wc_memberships_get_membership_plans' ) ) {
			\WP_CLI::error( 'The woocommerce-memberships plugin must be active.' );
		}

		$live         = isset( $assoc_args['live'] ) ? true : false;
		$verbose      = isset( $assoc_args['verbose'] ) ? true : false;
		if ( $live ) {
			\WP_CLI::log(
				'Live mode.
Note that if a member has unsubscribed from a list, but has an active membership, they will be re-subscribed.'
			);
		} else {
			\WP_CLI::log( 'Dry run. Use --live flag to run in live mode.' );
		}
		\WP_CLI::log( '' );

		$provider = \Newspack_Newsletters::get_service_provider();
		if ( ! $provider ) {
			\WP_CLI::error( 'No ESP provider set.' );
		}

		foreach ( wc_memberships_get_membership_plans() as $plan ) {
			foreach ( $plan->get_content_restriction_rules() as $rule ) {
				if ( \Newspack\Newsletters\Subscription_Lists::CPT === $rule->get_content_type_name() ) {
					if ( $verbose ) {
						\WP_CLI::log( sprintf( 'Processing WCM plan "%s"', $plan->get_name() ) );
					}

					$restricted_lists = [];
					foreach ( $rule->get_object_ids() as $list_id ) {
						try {
							$list = new \Newspack\Newsletters\Subscription_List( $list_id );
							$restricted_lists[] = $list;
						} catch ( \Throwable $th ) {
							\WP_CLI::warning( sprintf( 'Could not get subscription list for ID %d: %s', $list_id, $th->getMessage() ) );
							continue;
						}
					}

					if ( empty( $restricted_lists ) ) {
						\WP_CLI::warning( 'No subscription lists to process for the plan, skipping.' );
						continue;
					}

					$plan_memberships = $plan->get_memberships();
					foreach ( $restricted_lists as $list ) {
						$list_public_id = $list->get_public_id();
						\WP_CLI::log( sprintf( '  - Synchronizing list "%s" (#%d, public ID: %s)', $list->get_title(), $list->get_id(), $list_public_id ) );
						foreach ( $plan_memberships as $user_membership ) {
							$user = $user_membership->get_user();
							if ( ! $user ) {
								continue;
							}
							$email = $user->user_email;
							if ( ! $email ) {
								\WP_CLI::warning( sprintf( 'No email for user #%d, skipping.', $user->ID ) );
								continue;
							}
							$membership_id = $user_membership->get_id();
							$membership_status = $user_membership->get_status();
							if ( $verbose ) {
								\WP_CLI::log( '' );
								\WP_CLI::log( sprintf( '    - Processing user %s with membership #%d of status %s.', $email, $membership_id, $membership_status ) );
							}

							$contact_lists = \Newspack_Newsletters_Subscription::get_contact_lists( $email );
							$currently_subscribed = is_array( $contact_lists ) && in_array( $list_public_id, $contact_lists, true );

							// Determine which lists to update.
							$lists_to_add = [];
							$lists_to_remove = [];
							if ( self::is_membership_active( $user_membership ) ) {
								if ( ! $currently_subscribed ) {
									$lists_to_add = [ $list_public_id ];
								}
							} elseif ( $currently_subscribed ) {
								$lists_to_remove = [ $list_public_id ];
							}

							if ( empty( $lists_to_add ) && empty( $lists_to_remove ) ) {
								if ( $verbose ) {
									\WP_CLI::log( '    - No changes needed, skipping.' );
								}
								continue;
							}

							$result = null;

							// Check user status in the ESP.
							$contact_data = \Newspack_Newsletters_Subscription::get_contact_data( $email );
							$should_create_contact = \is_wp_error( $contact_data );
							if ( $should_create_contact ) {

								// allow subscription to restricted lists.
								remove_filter( 'newspack_newsletters_contact_lists', [ 'Newspack_Newsletters\Plugins\Woocommerce_Memberships', 'filter_lists' ] );

								if ( empty( $lists_to_add ) ) {
									if ( $verbose ) {
										\WP_CLI::log( '    - Contact not found in ESP, but there are no lists to add, skipping.' );
									}
									continue;
								}
								if ( $verbose ) {
									\WP_CLI::log(
										$live ? '      - Contact not found - adding the contact in the ESP…' : '      - Contact not found - would add the contact to the ESP.'
									);
								}
								if ( $live ) {
									$result = \Newspack_Newsletters_Contacts::subscribe(
										[
											'email' => $email,
											'name'  => $user->display_name,
										],
										$lists_to_add,
										false,
										'Adding contact when running the sync-membership-tied-subscribers CLI sync script.'
									);
								}
							} else {
								if ( $verbose ) {
									\WP_CLI::log(
										$live ? '      - Updating the contact in the ESP…' : '      - Would update the contact in the ESP.'
									);
								}
								if ( $live ) {
									$result = \Newspack_Newsletters_Contacts::add_and_remove_lists(
										$email,
										$lists_to_add,
										$lists_to_remove,
										'Updating contact when running the sync-membership-tied-subscribers CLI sync script.'
									);
								}
							}

							if ( \is_wp_error( $result ) ) {
								\WP_CLI::warning( sprintf( 'Error when updating lists: %s', $result->get_error_message() ) );
							} elseif ( $result !== null ) {
								\WP_CLI::success( sprintf( 'User %s processed successfully!', $email ) );
							}
						}
					}
				}
			}
		}

		\WP_CLI::log( '' );
	}
}
Sync_Membership_Tied_Subscribers_CLI::init();
