<?php
/**
 * Newspack Block Theme patterns handling.
 *
 * @package Newspack_Block_Theme
 */

namespace Newspack_Block_Theme;

defined( 'ABSPATH' ) || exit;

/**
 * Main Patterns class.
 */
final class Patterns {
	/**
	 * Initializer.
	 */
	public static function init() {
		\add_action( 'init', [ __CLASS__, 'register_nested_patterns' ] );
	}

	/**
	 * Find nested PHP files.
	 *
	 * @param string $directory The base directory to search in.
	 */
	public static function find_nested_php_files( $directory ) {
		$php_files = [];
		$items = glob( $directory . '/*' );

		foreach ( $items as $item ) {
			if ( is_dir( $item ) ) {
				$php_files = array_merge( $php_files, self::find_nested_php_files( $item ) );
			} elseif ( is_file( $item ) && pathinfo( $item, PATHINFO_EXTENSION ) === 'php' ) {
				$php_files[] = $item;
			}
		}

		return $php_files;
	}

	/**
	 * Registers patterns nested in the /patterns directory. WP will only
	 * register automatically the patterns which are top-level files in this directory.
	 *
	 * @see get_block_patterns
	 */
	public static function register_nested_patterns() {
		$directory = get_stylesheet_directory() . '/patterns';
		$files = self::find_nested_php_files( $directory );

		foreach ( $files as $file ) {
			$relative_path = str_replace( $directory, '', $file );
			// Check if the path is nested. Non-nested patterns will be automatically registered by WP.
			if ( substr_count( $relative_path, '/' ) === 1 ) {
				continue;
			}
			$default_headers = [
				'title'         => 'Title',
				'slug'          => 'Slug',
				'description'   => 'Description',
				'viewportWidth' => 'Viewport Width',
				'inserter'      => 'Inserter',
				'categories'    => 'Categories',
				'keywords'      => 'Keywords',
				'blockTypes'    => 'Block Types',
				'postTypes'     => 'Post Types',
				'templateTypes' => 'Template Types',
			];
			$pattern = get_file_data( $file, $default_headers );

			if ( isset( $pattern['slug'] ) ) {
				$properties_to_parse = [
					'categories',
					'keywords',
					'blockTypes',
					'postTypes',
					'templateTypes',
				];
				// For properties of type array, parse data as comma-separated.
				foreach ( $properties_to_parse as $property ) {
					if ( ! empty( $pattern[ $property ] ) ) {
						$pattern[ $property ] = array_filter( wp_parse_list( (string) $pattern[ $property ] ) );
					} else {
						unset( $pattern[ $property ] );
					}
				}

				$pattern['filePath'] = $file;
				register_block_pattern( $pattern['slug'], $pattern );
			}
		}
	}
}

Patterns::init();
