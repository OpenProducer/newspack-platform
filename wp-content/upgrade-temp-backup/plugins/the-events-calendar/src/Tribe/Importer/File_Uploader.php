<?php

/**
 * Class Tribe__Events__Importer__File_Uploader
 */
class Tribe__Events__Importer__File_Uploader {
	private $name = '';
	private $type = '';
	private $tmp_name = '';
	private $error = 0;
	private $size = 0;

	public function __construct( $file_array ) {
		$this->name     = $file_array['name'];
		$this->type     = $file_array['type'];
		$this->tmp_name = $file_array['tmp_name'];
		$this->error    = $file_array['error'];
		$this->size     = $file_array['size'];
	}

	public function save_file() {
		$this->validate_temporary_file();
		$this->permanently_save_file();
	}

	private function validate_temporary_file() {
		if ( ! file_exists( $this->tmp_name ) ) {
			throw new RuntimeException( sprintf( esc_html__( 'Temporary file not found. Could not save %s.', 'the-events-calendar' ), $this->name ) );
		}
	}

	private function permanently_save_file() {
		self::clear_old_files();
		$moved = move_uploaded_file( $this->tmp_name, self::get_file_path() );
		if ( ! $moved ) {
			throw new RuntimeException( sprintf( esc_html__( 'Could not save %s.', 'the-events-calendar' ), $this->name ) );
		}
	}

	public static function clear_old_files() {
		$path = self::get_file_path();
		if ( file_exists( $path ) ) {
			unlink( $path );
		}

		$dir = self::get_upload_directory();
		rmdir( $dir );
	}

	public static function get_file_path() {
		$path = trailingslashit( self::get_upload_directory() );
		$path .= 'tribe-import.csv';

		return $path;
	}

	/**
	 * Indicates if the file returned by self::get_file_path() (still) exists
	 * and is readable.
	 *
	 * @return bool
	 */
	public static function has_valid_csv_file() {
		$csv_file = self::get_file_path();
		return file_exists( $csv_file ) && is_readable( $csv_file );
	}

	private static function get_upload_directory() {
		$upload_dir_array = wp_upload_dir();
		$path             = $upload_dir_array['basedir'];
		$path             = trailingslashit( $path ) . 'tribe-importer';
		wp_mkdir_p( $path );

		return $path;
	}
}
