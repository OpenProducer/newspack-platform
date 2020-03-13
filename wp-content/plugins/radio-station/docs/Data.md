# Radio Station Plugin Data

***

## Custom Post Types

### Shows

The main Post Type for Radio Station with repeater field for assignable Show Shifts. Hosts, Producers, Genres and Languages terms can also be assigned. A Featured Image, Show Avatar Image and (if turned on in plugin Settings) an alternative Show Header can be added to the Show via the Show Images metabox (all images upload to the WordPress Media Library.) Each Show also has the following meta data fields:

* *show_link*: optional URL to the Show's external website
* *show_file*: optional URL to the Show's latest audio file
* *show_email*: optional email contact for the Show
* *show_patreon*: option Patreon ID for the Show's Patreon page

### Schedule Overrides

Similar to Shows but use a date and time block instead of Shifts. A Datepicker field is used to specify the Override start date, and dropdowns for the start and end times. Hosts, Producers, Genres and Languages terms can also be assigned. A Featured Image and Override Avatar Image can also be added via the Show Images metabox (all images upload to the WordPress Media Library.)

### Playlists

A Track list assignable to a Show, with a repeater field for track information. A Playlist can be assigned to a particular Show via the Edit Playlist screen. The most recently published Playlist will be considered current for a Show when that Show is playing and display in the Current Playlist Widget (or Widget Shortcode)

For each Track the following can be specified:

* Artist (Text Field)
* Song Title (Text Field)
* Album (Text Field)
* Label (Text Field)
* Comments (Text Field)
* New Track (Checkbox)
* Queued or Played (Status Dropdown)

### Show Posts

Standard WordPress Posts are assignable to a Show via a metabox on the Post Edit screen. This is good for allowing listeners to find news and other announcements for a particular Show, for when assigned to a Show they will display in a paginated list on that Show's page.

### [Pro] Episodes

A future release of Radio Statio Pro will include Show Episodes. These will be assignable to a Show to create and archive of Episodes for specific dates that will display on the Show page.

### [Pro] Profiles

A future release of Radio Station Pro will include Show Host and Producers Profile pages. These will display a profile template rather than the standard Author template for Hosts and Producers.


## Taxonomies

### Genre Taxonomy

A flexible taxonomy allowing for the addition of Genre terms that can be assigned to a Show (or Override.) A Genre does not have to be music, as it can also be used to assign topics for talk shows for example. Having a Genre assigned allows for user highlighting on the Program Schedule, and discoverable via the plugin's data [API](./API.md) Genres Endpoint. They are displayed on the Show's page, and can also be displayed in widgets and other shortcodes. There is also a [Genres Archive Shortcode](./Shortcodes.md#genres-archive-shortcode) that lists Shows sorted by their assigned Genre terms.

### Language Taxonomy

A fixed taxonomy allowing for assigning of Language terms to a Show (or Override.) In a Show does not have a Language assigned, it is assumed to be in the main Language selected in the Plugin Settings. Languages are displayed on a Show's page, and discoverable via the plugin's data [API](./API.md) Languages Endpoint, and in future will be displayable in widgets and other shortcodes also. There will also be an addition of a Languages Archive Shortcode that will work similar to the Genre Archive Shortcode.


## Data Filters

There are filters throughout the plugin that allow you to override data values and plugin output. We employ the practice of adding as many of these as possible to allow users of the plugin to customize it's behaviour without needing to modify the plugin's code - as these kind of modifications are overwritten with plugin updates.

You can add your own custom filters via a Code Snippets plugin (which has the advantage of checking syntax for you), or in your Child Theme's `functions.php`, or in a file with a PHP extension in your `/wp-content/mu-plugins/` directory. 

Currently you can find these filters by searching the plugin code for `apply_filters`. We will be gradually adding to the list of available filters here in future documentation.

