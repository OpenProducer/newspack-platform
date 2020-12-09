<?php
/**
 * REST API Admin Note Action controller
 *
 * Handles requests to the admin note action endpoint.
 */

namespace Automattic\WooCommerce\Admin\API;

defined( 'ABSPATH' ) || exit;

use Automattic\WooCommerce\Admin\Notes\Note;
use \Automattic\WooCommerce\Admin\Notes\Notes as NotesFactory;

/**
 * REST API Admin Note Action controller class.
 *
 * @extends WC_REST_CRUD_Controller
 */
class NoteActions extends Notes {

	/**
	 * Register the routes for admin notes.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/(?P<note_id>[\d-]+)/action/(?P<action_id>[\d-]+)',
			array(
				'args'   => array(
					'note_id'   => array(
						'description' => __( 'Unique ID for the Note.', 'woocommerce' ),
						'type'        => 'integer',
					),
					'action_id' => array(
						'description' => __( 'Unique ID for the Note Action.', 'woocommerce' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => \WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'trigger_note_action' ),
					// @todo - double check these permissions for taking note actions.
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Trigger a note action.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Request|WP_Error
	 */
	public function trigger_note_action( $request ) {
		$note = NotesFactory::get_note( $request->get_param( 'note_id' ) );

		if ( ! $note ) {
			return new \WP_Error(
				'woocommerce_note_invalid_id',
				__( 'Sorry, there is no resource with that ID.', 'woocommerce' ),
				array( 'status' => 404 )
			);
		}

		// Find note action by ID.
		$action_id        = $request->get_param( 'action_id' );
		$actions          = $note->get_actions( 'edit' );
		$triggered_action = false;

		foreach ( $actions as $action ) {
			if ( $action->id === $action_id ) {
				$triggered_action = $action;
			}
		}

		if ( ! $triggered_action ) {
			return new \WP_Error(
				'woocommerce_note_action_invalid_id',
				__( 'Sorry, there is no resource with that ID.', 'woocommerce' ),
				array( 'status' => 404 )
			);
		}

		/**
		 * Fires when an admin note action is taken.
		 *
		 * @param string $name The triggered action name.
		 * @param Note   $note The corresponding Note.
		 */
		do_action( 'woocommerce_note_action', $triggered_action->name, $note );

		/**
		 * Fires when an admin note action is taken.
		 * For more specific targeting of note actions.
		 *
		 * @param Note $note The corresponding Note.
		 */
		do_action( 'woocommerce_note_action_' . $triggered_action->name, $note );

		// Update the note with the status for this action.
		if ( ! empty( $triggered_action->status ) ) {
			$note->set_status( $triggered_action->status );
		}

		$note->save();

		if ( in_array( $note->get_type(), array( 'error', 'update' ) ) ) {
			$tracks_event = 'wcadmin_store_alert_action';
		} else {
			$tracks_event = 'wcadmin_inbox_action_click';
		}

		wc_admin_record_tracks_event(
			$tracks_event,
			array(
				'note_name'    => $note->get_name(),
				'note_type'    => $note->get_type(),
				'note_title'   => $note->get_title(),
				'note_content' => $note->get_content(),
				'action_name'  => $triggered_action->name,
				'action_label' => $triggered_action->label,
				'screen'       => $this->get_screen_name(),
			)
		);

		$data = $note->get_data();
		$data = $this->prepare_item_for_response( $data, $request );
		$data = $this->prepare_response_for_collection( $data );

		return rest_ensure_response( $data );
	}

	/**
	 * Get screen name.
	 *
	 * @return string The screen name.
	 */
	public function get_screen_name() {
		$screen_name = '';

		if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			parse_str( wp_parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_QUERY ), $queries ); // phpcs:ignore sanitization ok.
		}
		if ( isset( $queries ) ) {
			$page      = isset( $queries['page'] ) ? $queries['page'] : null;
			$path      = isset( $queries['path'] ) ? $queries['path'] : null;
			$post_type = isset( $queries['post_type'] ) ? $queries['post_type'] : null;
			$post      = isset( $queries['post'] ) ? get_post_type( $queries['post'] ) : null;
		}

		if ( isset( $page ) ) {
			$current_page = 'wc-admin' === $page ? 'home_screen' : $page;
			$screen_name  = isset( $path ) ? substr( str_replace( '/', '_', $path ), 1 ) : $current_page;
		} elseif ( isset( $post_type ) ) {
			$screen_name = $post_type;
		} elseif ( isset( $post ) ) {
			$screen_name = $post;
		}
		return $screen_name;
	}
}
