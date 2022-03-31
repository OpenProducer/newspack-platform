<?php
/**
 * WP_REST_Newspack_Authors_Controller file.
 *
 * @package WordPress
 */

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound
/**
 * Class WP_REST_Newspack_Authors_Controller.
 */
class WP_REST_Newspack_Authors_Controller extends WP_REST_Controller {
// phpcs:enable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedClassFound

	/**
	 * Constructs the controller.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->namespace = 'newspack-blocks/v1';
		$this->rest_base = 'authors';
	}

	/**
	 * Registers the necessary REST API routes.
	 *
	 * @access public
	 */
	public function register_routes() {
		// Endpoint to get authors.
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_authors' ],
					'args'                => [
						'author_id' => [
							'sanitize_callback' => 'absint',
						],
						'offset'    => [
							'sanitize_callback' => 'absint',
						],
						'per_page'  => [
							'sanitize_callback' => 'absint',
						],
						'search'    => [
							'sanitize_callback' => 'sanitize_text_field',
						],
						'fields'    => [
							'sanitize_callback' => 'sanitize_text_field',
						],
					],
					'permission_callback' => '__return_true',
				],
			]
		);
	}

	/**
	 * Returns a list of combined authors and guest authors.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response
	 */
	public function get_authors( $request ) {
		$author_id           = ! empty( $request->get_param( 'authorId' ) ) ? $request->get_param( 'authorId' ) : 0; // Fetch a specific user or guest author by ID.
		$search              = ! empty( $request->get_param( 'search' ) ) ? $request->get_param( 'search' ) : null; // Fetch authors by search string.
		$offset              = ! empty( $request->get_param( 'offset' ) ) ? $request->get_param( 'offset' ) : 0; // Offset results (for pagination).
		$per_page            = ! empty( $request->get_param( 'perPage' ) ) ? $request->get_param( 'perPage' ) : 10; // Number of results to return per page. This is applied to each query, so the actual number of results returned may be up to 2x this number.
		$avatar_hide_default = ! empty( $request->get_param( 'avatarHideDefault' ) ) ? true : false; // Hide the default avatar if the user has no custom avatar.
		$fields              = ! empty( $request->get_param( 'fields' ) ) ? explode( ',', $request->get_param( 'fields' ) ) : [ 'id' ]; // Fields to get. Will return at least id.

		// Total number of users and guest authors.
		$guest_author_total = 0;
		$user_total         = 0;

		// Get Co-authors guest authors.
		$guest_author_args = [
			'post_type'      => 'guest-author',
			'posts_per_page' => $per_page,
			'offset'         => $offset,
		];

		if ( $search && ! $author_id ) {
			$guest_author_args['s'] = $search;
		}
		if ( $author_id ) {
			$guest_author_args['p'] = $author_id;
		}

		$guest_authors      = get_posts( $guest_author_args );
		$guest_author_total = count( $guest_authors );
		$users              = []; // We'll only get standard WP users if no guest authors were found.

		// If passed an author ID.
		if ( $author_id ) {
			if ( 0 === $guest_author_total ) {
				$user = get_user_by( 'id', $author_id ); // Get the WP user.

				// We have a WP user, let's use it.
				if ( $user ) {
					// But wait, there's more! Let's see if this user is linked to a guest author.
					$linked_guest_author = self::get_linked_guest_author( $user->user_login );

					// If it is, let's use that instead.
					if ( $linked_guest_author ) {
						$guest_authors = [ $linked_guest_author ];
					} else {
						$users = [ $user ];
					}
				}
			}
		} else {
			$user_args = [
				'role__in' => [ 'Administrator', 'Editor', 'Author', 'Contributor' ],
				'offset'   => $offset,
				'orderby'  => 'registered',
				'order'    => 'DESC',
				'number'   => $per_page,
			];

			// If passed a search string.
			if ( $search && ! $author_id ) {
				$user_args['search'] = '*' . $search . '*';
			}

			$user_query = new \WP_User_Query( $user_args );
			$users      = $user_query->get_results();
			$user_total = $user_query->get_total();
		}

		// Format and combine results.
		$combined_authors = array_merge(
			array_reduce(
				! empty( $guest_authors ) ? $guest_authors : [],
				function( $acc, $guest_author ) use ( $fields, $avatar_hide_default ) {
					if ( $guest_author ) {
						if ( class_exists( 'CoAuthors_Guest_Authors' ) ) {
							$guest_author_data = [
								'id'         => intval( $guest_author->ID ),
								'registered' => $guest_author->post_date,
								'is_guest'   => true,
							];

							$guest_author = ( new CoAuthors_Guest_Authors() )->get_guest_author_by( 'id', $guest_author->ID );

							if ( in_array( 'login', $fields, true ) ) {
								$guest_author_data['login'] = $guest_author->user_login;
							}
							if ( in_array( 'name', $fields, true ) ) {
								$guest_author_data['name'] = $guest_author->display_name;
							}
							if ( in_array( 'bio', $fields, true ) ) {
								$guest_author_data['bio'] = get_post_meta( $guest_author->ID, 'cap-description', true );
							}
							if ( in_array( 'email', $fields, true ) ) {
								$email_data = $this->get_email( $guest_author->ID );

								if ( $email_data ) {
									$guest_author_data['email'] = $email_data;
								}
							}
							if ( in_array( 'avatar', $fields, true ) && function_exists( 'coauthors_get_avatar' ) ) {
								$avatar = coauthors_get_avatar( $guest_author, 256 );

								if ( $avatar && ( false === strpos( $avatar, 'avatar-default' ) || ! $avatar_hide_default ) ) {
									$guest_author_data['avatar'] = $avatar;
								}
							}
							if ( in_array( 'url', $fields, true ) ) {
								$guest_author_data['url'] = esc_urL(
									get_site_urL( null, '?author_name=' . get_post_meta( $guest_author->ID, 'cap-user_login', true ) )
								);
							}
							if ( in_array( 'social', $fields, true ) ) {
								$guest_author_data['social'] = $this->get_social( $guest_author->ID );
							}

							$acc[] = $guest_author_data;
						}
					}
					return $acc;
				},
				[]
			),
			array_reduce(
				$users,
				function( $acc, $user ) use ( $fields, $avatar_hide_default ) {
					if ( $user ) {
						$user_data = [
							'id'         => intval( $user->data->ID ),
							'registered' => $user->data->user_registered,
							'is_guest'   => false,
						];

						if ( in_array( 'login', $fields, true ) ) {
							$user_data['login'] = $user->data->user_login;
						}
						if ( in_array( 'name', $fields, true ) ) {
							$user_data['name'] = $user->data->display_name;
						}
						if ( in_array( 'bio', $fields, true ) ) {
							$user_data['bio'] = get_the_author_meta( 'description', $user->data->ID );
						}
						if ( in_array( 'email', $fields, true ) ) {
							$email_data = $this->get_email( $user->data->ID, false, $user->data->user_email );

							if ( $email_data ) {
								$user_data['email'] = $email_data;
							}
						}
						if ( in_array( 'avatar', $fields, true ) ) {
							$avatar = get_avatar( $user->data->ID, 256 );

							if ( $avatar && ( false === strpos( $avatar, 'avatar-default' ) || ! $avatar_hide_default ) ) {
								$user_data['avatar'] = $avatar;
							}
						}
						if ( in_array( 'url', $fields, true ) ) {
							$user_data['url'] = esc_urL( get_author_posts_url( $user->data->ID ) );
						}
						if ( in_array( 'social', $fields, true ) ) {
							$user_data['social'] = $this->get_social( $user->data->ID );
						}

						$acc[] = $user_data;
					}
					return $acc;
				},
				[]
			)
		);

		// Sort combined authors array by registration date.
		usort(
			$combined_authors,
			function( $a, $b ) {
				return strtotime( $b['registered'] ) - strtotime( $a['registered'] );
			}
		);

		$response = new WP_REST_Response( $combined_authors );
		$response->header( 'x-wp-total', $user_total + $guest_author_total );

		return rest_ensure_response( $response );
	}

	/**
	 * Given a WP user login, get the linked guest author, if any.
	 *
	 * @param string $user_login WP user login name.
	 *
	 * @return WP_Post|boolean Linked guest author in post form, or false if none.
	 */
	public static function get_linked_guest_author( $user_login ) {
		$linked_guest_authors = get_posts(
			[
				'post_type'      => 'guest-author',
				'posts_per_page' => 1,
				'meta_key'       => 'cap-linked_account', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => $user_login, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			]
		);

		return 0 < count( $linked_guest_authors ) ? reset( $linked_guest_authors ) : false;
	}

	/**
	 * Get author email address and SVG icon, if available.
	 *
	 * @param int     $author_id Author ID.
	 * @param boolean $is_guest_author Is the author ID a CAP guest author?.
	 * @param string  $email_address If a standard WP user, use this email address.
	 * @return array Array with email address and SVG.
	 */
	public static function get_email( $author_id, $is_guest_author = true, $email_address = '' ) {
		$email_data    = false;
		$email_address = $is_guest_author ? get_post_meta( $author_id, 'cap-user_email', true ) : $email_address;

		if ( $email_address ) {
			$email_data = [ 'url' => 'mailto:' . $email_address ];

			if ( class_exists( 'Newspack_SVG_Icons' ) ) {
				$email_data['svg'] = Newspack_SVG_Icons::get_social_link_svg( 'mailto:' . $email_address, 24 );
			}
		}

		return $email_data;
	}

	/**
	 * Get social media URLs and SVGs, if available. Only standard WP users have this user meta.
	 *
	 * @param int $author_id Author ID.
	 * @return array Array of social links and SVGs.
	 */
	public static function get_social( $author_id ) {
		$social_profiles = [
			'facebook',
			'twitter',
			'instagram',
			'linkedin',
			'myspace',
			'pinterest',
			'soundcloud',
			'tumblr',
			'youtube',
			'wikipedia',
			'website', // This is the only "social media" link for CAP guest authors.
		];

		return array_reduce(
			$social_profiles,
			function( $acc, $profile ) use ( $author_id ) {
				$is_website = 'website' === $profile;
				$handle     = $is_website ? get_post_meta( $author_id, 'cap-website', true ) : get_the_author_meta( $profile, $author_id );

				if ( $handle ) {
					$url             = 'twitter' === $profile ? esc_url( 'https://twitter.com/' . $handle ) : esc_url( $handle );
					$acc[ $profile ] = [ 'url' => $url ];

					if ( class_exists( 'Newspack_SVG_Icons' ) ) {
						$acc[ $profile ]['svg'] = $is_website ? Newspack_SVG_Icons::get_svg( 'ui', 'link', 24 ) : Newspack_SVG_Icons::get_social_link_svg( $url, 24 );
					}
				}

				return $acc;
			},
			[]
		);
	}
}
