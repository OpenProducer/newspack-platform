<?php
		// Skip bots.
		if ( ! empty( $_SERVER["HTTP_USER_AGENT"] ) && preg_match( '/bot|crawl|slurp|spider|mediapartners/i', $_SERVER["HTTP_USER_AGENT"] )
		) {
			exit;
		}
		// Skip prefetching and previews.
		if ( ! empty( $_SERVER["HTTP_X_PURPOSE"] ) && in_array( $_SERVER["HTTP_X_PURPOSE"], [ "preview", "instant" ], true ) ) {
			exit;
		}
		if ( ! empty( $_SERVER["HTTP_X_MOZ"] ) && "prefetch" === $_SERVER["HTTP_X_MOZ"] ) {
			exit;
		}
		if ( ! empty( $_SERVER["HTTP_USER_AGENT"] ) && "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246 Mozilla/5.0" === $_SERVER["HTTP_USER_AGENT"] ) {
			exit;
		}
		if ( ! isset( $_GET["id"] ) || ! isset( $_GET["tid"] ) || ! isset( $_GET["em"] ) ) {
			exit;
		}
		$file = "/files/2023/11/newspack_newsletters_pixel_log_3DNWAw";
		$id = $_GET["id"];
		$tid = $_GET["tid"];
		$email_address = $_GET["em"];
		file_put_contents( $file, $id . "|" . $tid . "|" . $email_address . PHP_EOL, FILE_APPEND );
		header( "Cache-Control: no-cache, no-store, must-revalidate" );
		header( "Pragma: no-cache" );
		header( "Expires: 0" );
		header( "Content-Type: image/gif" );
		echo base64_decode( "R0lGODlhAQABAIAAAAUEBAAAACwAAAAAAQABAAACAkQBADs=" );
		?>