<?php
/**
 * WP_Theme_JSON_Schema_Gutenberg class
 *
 * @package Gutenberg
 * @since 5.9.0
 */

if ( class_exists( 'WP_Theme_JSON_Schema_Gutenberg' ) ) {
	return;
}

/**
 * Class that migrates a given theme.json structure to the latest schema.
 *
 * This class is for internal core usage and is not supposed to be used by extenders (plugins and/or themes).
 * This is a low-level API that may need to do breaking changes. Please,
 * use get_global_settings, get_global_styles, and get_global_stylesheet instead.
 *
 * @since 5.9.0
 * @access private
 */
#[AllowDynamicProperties]
class WP_Theme_JSON_Schema_Gutenberg {

	/**
	 * Maps old properties to their new location within the schema's settings.
	 * This will be applied at both the defaults and individual block levels.
	 */
	const V1_TO_V2_RENAMED_PATHS = array(
		'border.customRadius'         => 'border.radius',
		'spacing.customMargin'        => 'spacing.margin',
		'spacing.customPadding'       => 'spacing.padding',
		'typography.customLineHeight' => 'typography.lineHeight',
	);

	/**
	 * Function that migrates a given theme.json structure to the last version.
	 *
	 * @since 5.9.0
	 * @since 6.5.0 Migrate up to v3.
	 *
	 * @param array $theme_json The structure to migrate.
	 *
	 * @return array The structure in the last version.
	 */
	public static function migrate( $theme_json ) {
		if ( ! isset( $theme_json['version'] ) ) {
			$theme_json = array(
				'version' => WP_Theme_JSON::LATEST_SCHEMA,
			);
		}

		// Migrate each version in order starting with the current version.
		switch ( $theme_json['version'] ) {
			case 1:
				$theme_json = self::migrate_v1_to_v2( $theme_json );
				// no break
			case 2:
				$theme_json = self::migrate_v2_to_v3( $theme_json );
				// no break
		}

		return $theme_json;
	}

	/**
	 * Removes the custom prefixes for a few properties
	 * that were part of v1:
	 *
	 * 'border.customRadius'         => 'border.radius',
	 * 'spacing.customMargin'        => 'spacing.margin',
	 * 'spacing.customPadding'       => 'spacing.padding',
	 * 'typography.customLineHeight' => 'typography.lineHeight',
	 *
	 * @since 5.9.0
	 *
	 * @param array $old Data to migrate.
	 *
	 * @return array Data without the custom prefixes.
	 */
	private static function migrate_v1_to_v2( $old ) {
		// Copy everything.
		$new = $old;

		// Overwrite the things that changed.
		if ( isset( $old['settings'] ) ) {
			$new['settings'] = self::rename_paths( $old['settings'], self::V1_TO_V2_RENAMED_PATHS );
		}

		// Set the new version.
		$new['version'] = 2;

		return $new;
	}

	/**
	 * Migrates from v2 to v3.
	 *
	 * - Sets settings.typography.defaultFontSizes to false.
	 *
	 * @since 6.5.0
	 *
	 * @param array $old Data to migrate.
	 *
	 * @return array Data with defaultFontSizes set to false.
	 */
	private static function migrate_v2_to_v3( $old ) {
		// Copy everything.
		$new = $old;

		// Set the new version.
		$new['version'] = 3;

		/*
		 * Remaining changes do not need to be applied to the custom origin,
		 * as they should take on the value of the theme origin.
		 */
		if (
			isset( $new['isGlobalStylesUserThemeJSON'] ) &&
			true === $new['isGlobalStylesUserThemeJSON']
		) {
			return $new;
		}

		/*
		 * Even though defaultFontSizes is a new setting, we need to migrate
		 * it as it controls the PRESETS_METADATA prevent_override which was
		 * previously hardcoded to false. This only needs to happen when the
		 * theme provided font sizes as they could match the default ones and
		 * affect the generated CSS. And in v2 we provided default font sizes
		 * when the theme did not provide any.
		 */
		if ( isset( $new['settings']['typography']['fontSizes'] ) ) {
			if ( ! isset( $new['settings'] ) ) {
				$new['settings'] = array();
			}
			if ( ! isset( $new['settings']['typography'] ) ) {
				$new['settings']['typography'] = array();
			}
			$new['settings']['typography']['defaultFontSizes'] = false;
		}

		return $new;
	}

	/**
	 * Processes the settings subtree.
	 *
	 * @since 5.9.0
	 *
	 * @param array $settings        Array to process.
	 * @param array $paths_to_rename Paths to rename.
	 *
	 * @return array The settings in the new format.
	 */
	private static function rename_paths( $settings, $paths_to_rename ) {
		$new_settings = $settings;

		// Process any renamed/moved paths within default settings.
		self::rename_settings( $new_settings, $paths_to_rename );

		// Process individual block settings.
		if ( isset( $new_settings['blocks'] ) && is_array( $new_settings['blocks'] ) ) {
			foreach ( $new_settings['blocks'] as &$block_settings ) {
				self::rename_settings( $block_settings, $paths_to_rename );
			}
		}

		return $new_settings;
	}

	/**
	 * Processes a settings array, renaming or moving properties.
	 *
	 * @since 5.9.0
	 *
	 * @param array $settings        Reference to settings either defaults or an individual block's.
	 * @param array $paths_to_rename Paths to rename.
	 */
	private static function rename_settings( &$settings, $paths_to_rename ) {
		foreach ( $paths_to_rename as $original => $renamed ) {
			$original_path = explode( '.', $original );
			$renamed_path  = explode( '.', $renamed );
			$current_value = _wp_array_get( $settings, $original_path, null );

			if ( null !== $current_value ) {
				_wp_array_set( $settings, $renamed_path, $current_value );
				self::unset_setting_by_path( $settings, $original_path );
			}
		}
	}

	/**
	 * Removes a property from within the provided settings by its path.
	 *
	 * @since 5.9.0
	 *
	 * @param array $settings Reference to the current settings array.
	 * @param array $path Path to the property to be removed.
	 */
	private static function unset_setting_by_path( &$settings, $path ) {
		$tmp_settings = &$settings; // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$last_key     = array_pop( $path );
		foreach ( $path as $key ) {
			$tmp_settings = &$tmp_settings[ $key ];
		}

		unset( $tmp_settings[ $last_key ] );
	}
}
