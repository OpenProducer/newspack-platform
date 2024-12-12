<?php
/**
 * Temporary compatibility shims for block APIs present in Gutenberg.
 *
 * @package gutenberg
 */

/**
 * Filters the block type arguments during registration to stabilize
 * experimental block supports.
 *
 * This is a temporary compatibility shim as the approach in core is for this
 * to be handled within the WP_Block_Type class rather than requiring a filter.
 *
 * @param array $args Array of arguments for registering a block type.
 * @return array Array of arguments for registering a block type.
 */
function gutenberg_stabilize_experimental_block_supports( $args ) {
	if ( empty( $args['supports'] ) ) {
		return $args;
	}

	$experimental_supports_map       = array( '__experimentalBorder' => 'border' );
	$common_experimental_properties  = array(
		'__experimentalDefaultControls'   => 'defaultControls',
		'__experimentalSkipSerialization' => 'skipSerialization',
	);
	$experimental_support_properties = array(
		'typography' => array(
			'__experimentalFontFamily'     => 'fontFamily',
			'__experimentalFontStyle'      => 'fontStyle',
			'__experimentalFontWeight'     => 'fontWeight',
			'__experimentalLetterSpacing'  => 'letterSpacing',
			'__experimentalTextDecoration' => 'textDecoration',
			'__experimentalTextTransform'  => 'textTransform',
		),
	);
	$done                            = array();

	$updated_supports = array();
	foreach ( $args['supports'] as $support => $config ) {
		/*
		 * If this support config has already been stabilized, skip it.
		 * A stable support key occurring after an experimental key, gets
		 * stabilized then so that the two configs can be merged effectively.
		 */
		if ( isset( $done[ $support ] ) ) {
			continue;
		}

		$stable_support_key = $experimental_supports_map[ $support ] ?? $support;

		/*
		 * Use the support's config as is when it's not in need of stabilization.
		 *
		 * A support does not need stabilization if:
		 * - The support key doesn't need stabilization AND
		 * - Either:
		 *     - The config isn't an array, so can't have experimental properties OR
		 *     - The config is an array but has no experimental properties to stabilize.
		 */
		if ( $support === $stable_support_key &&
			( ! is_array( $config ) ||
				( ! isset( $experimental_support_properties[ $stable_support_key ] ) &&
				empty( array_intersect_key( $common_experimental_properties, $config ) )
				)
			)
		) {
			$updated_supports[ $support ] = $config;
			continue;
		}

		$stabilize_config = function ( $unstable_config, $stable_support_key ) use ( $experimental_support_properties, $common_experimental_properties ) {
			$stable_config = array();
			foreach ( $unstable_config as $key => $value ) {
				// Get stable key from support-specific map, common properties map, or keep original.
				$stable_key = $experimental_support_properties[ $stable_support_key ][ $key ] ??
							$common_experimental_properties[ $key ] ??
							$key;

				$stable_config[ $stable_key ] = $value;

				/*
				 * The `__experimentalSkipSerialization` key needs to be kept until
				 * WP 6.8 becomes the minimum supported version. This is due to the
				 * core `wp_should_skip_block_supports_serialization` function only
				 * checking for `__experimentalSkipSerialization` in earlier versions.
				 */
				if ( '__experimentalSkipSerialization' === $key || 'skipSerialization' === $key ) {
					$stable_config['__experimentalSkipSerialization'] = $value;
				}
			}
			return $stable_config;
		};

		// Stabilize the config value.
		$stable_config = is_array( $config ) ? $stabilize_config( $config, $stable_support_key ) : $config;

		/*
		 * If a plugin overrides the support config with the `register_block_type_args`
		 * filter, both experimental and stable configs may be present. In that case,
		 * use the order keys are defined in to determine the final value.
		 *    - If config is an array, merge the arrays in their order of definition.
		 *    - If config is not an array, use the value defined last.
		 *
		 * The reason for preferring the last defined key is that after filters
		 * are applied, the last inserted key is likely the most up-to-date value.
		 * We cannot determine with certainty which value was "last modified" so
		 * the insertion order is the best guess. The extreme edge case of multiple
		 * filters tweaking the same support property will become less over time as
		 * extenders migrate existing blocks and plugins to stable keys.
		 */
		if ( $support !== $stable_support_key && isset( $args['supports'][ $stable_support_key ] ) ) {
			$key_positions      = array_flip( array_keys( $args['supports'] ) );
			$experimental_first =
				( $key_positions[ $support ] ?? PHP_INT_MAX ) <
				( $key_positions[ $stable_support_key ] ?? PHP_INT_MAX );

			if ( is_array( $args['supports'][ $stable_support_key ] ) ) {
				/*
				 * To merge the alternative support config effectively, it also needs to be
				 * stabilized before merging to keep stabilized and experimental flags in
				 * sync.
				 */
				$args['supports'][ $stable_support_key ] = $stabilize_config( $args['supports'][ $stable_support_key ], $stable_support_key );
				$stable_config                           = $experimental_first
					? array_merge( $stable_config, $args['supports'][ $stable_support_key ] )
					: array_merge( $args['supports'][ $stable_support_key ], $stable_config );
				// Prevents reprocessing this support as it was merged above.
				$done[ $stable_support_key ] = true;
			} else {
				$stable_config = $experimental_first
					? $args['supports'][ $stable_support_key ]
					: $stable_config;
			}
		}

		$updated_supports[ $stable_support_key ] = $stable_config;
	}

	$args['supports'] = $updated_supports;

	return $args;
}

add_filter( 'register_block_type_args', 'gutenberg_stabilize_experimental_block_supports', PHP_INT_MAX, 1 );
