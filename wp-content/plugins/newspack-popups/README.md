# newspack-popups

AMP-compatible popup notifications.

## Config file

Newspack Campaigns requires a custom config file to provide database credentials and other key data to the lightweight API. The file (`wp-content/newspack-popups-config.php`) should automatically be created. If it is not, manually add this file using the following template:

```
<?php
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'root' );
define( 'DB_NAME', 'local' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_PREFIX', 'wp_' );
define( 'NEWSPACK_POPUPS_DEBUG', true ); // Optional, for debugging.
```

## Segmentation features

The segmentation features rely on visit logging. This is on by default, but can be turned off by setting the `DISABLE_CAMPAIGN_EVENT_LOGGING` flag in the aforementioned file:

```
define( 'DISABLE_CAMPAIGN_EVENT_LOGGING', true );
```

The segmentation feature causes amp-access to be added to all pages whether or not prompts are present. To override this behavior use the `newspack_popups_suppress_insert_amp_access` filter. The filter receives an array of prompts for the current page. To suppress, return true, for example:

```
add_filter(
	'newspack_popups_suppress_insert_amp_access',
	function( $should_suppress, $campaigns ) {
		if ( empty( $campaigns ) ) {
			return true;
		}
		return $should_suppress;
	},
	10,
	2
);
```
