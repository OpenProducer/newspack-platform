# Radio Station Plugin Widgets

***

You can add any of the Radio Station Plugin Widgets to your site's sidebar widget areas via the WordPress Appearance -> Widgets screen. 

Note Widgets are displayed via their corresponding Shortcodes to prevent code duplication and maintain display consistency. (The selected Widget options are converted into Shortcode attributes.) This also means if you want to display a Widget within a custom Template, you can use the corresponding shortcode in your template file. eg. `do_shortcode('[current-show]');`

[Radio Station Demo Site](http://demo.radiostation.pro)


## Radio Player Widget

Since 2.4.0, Radio Station includes a Streaming Player Widget! (see [Radio Player Widget](./Player.md#radio-player-widget))

### Radio Player Shortcode

`[radio-player]` (see [Radio Player Shortcode](./Player.md#radio-player-shortcode))

#### [Pro] Sitewide Bar Player

[Radio Station Pro](https://radiostation.pro) includes a Sitewide Bar Streaming Player. It isn't added via the Widgets Page, but is instead configured via the Plugin Settings Page under the Player tab. (see [Sitewide Bar Player](./Player.md#pro-sitewide-bar-player) )

## Current Show Widget

Displays the currently playing Show - if there is one scheduled to play right now.

### Current Show Shortcode

`[current-show]` (legacy supported name: `[dj-widget]`)

The following attribute options are available for this shortcode:

* **Load Display Attributes**
* *ajax* : Whether to AJAX load this widget/shortcode. 0 or 1. Default is Plugin Setting.
* *dynamic* : [Pro] Whether to dynamically reload on show changeover time. 0 or 1. Default 1.
* *title* : The title you would like to appear over the Current Show. Default is 'Current Show'.
* *no_shows* : The text displayed when no show is scheduled for the current time. Default empty.
* *hide_empty*: Hide the content of the widget/shortcode if there is no current show. 0 or 1. Default 0.
* **Show Display Attributes**
* *title_position* : Relative to Avatar. 'above', 'below', 'left' or 'right'. Default is 'right'.
* *show_link* : Display a link to a show's page. 0 or 1 Default is 1.
* *show_avatar* : Display a show's thumbnail. 0 or 1. Default is 1.
* *avatar_width* : Set a width style in pixels for Show Avatars. Default is not to set.
* **Time Display Attributes**
* *show_sched* : Display the Show's schedule. 0 or 1. Default is 1.
* *show_all_sched* : Displays all schedules for a show if it airs on multiple days. 0 or 1. Default is 0.
* *countdown* : Display a Countdown until the current Show ends. 0 or 1. Default is 0.
* *time_format* : The time format used for displaying schedules. 12 or 24. Default is Plugin Setting.
* **Extra Display Options**
* *display_hosts* : Display the names of the Hosts/DJs on the show. 0 or 1. Default is 0.
* *link_hosts* : Link Show hosts to their profile pages. 0 or 1. Default is 0.
* *show_desc* : Display an excerpt of the show's description. 0 or 1. Default is 0.
* *show_playlist* : Display a link to the show's current playlist, if any. 0 or 1.  Default is 1.
* *show_encore* : Display encore presentation text (when set for Show). 0 or 1. Default is 1.

Example: `[current-show title="Now On-Air" show_avatar="1" show_link="1" show_sched="1"]`

[Demo Site Example Output](https://demo.radiostation.pro/extra-shortcodes/current-show-widget/)


## Upcoming Shows Widget

Displays a limited list of Upcoming Shows - if there are Shows scheduled.

### Upcoming Shows Shortcode

`[upcoming-shows]` (legacy supported name: `[dj-coming-up-widget]`)

The following attribute options are available for this shortcode:

* **Load Display Attributes**
* *limit* : The number of upcoming shows to display. 0 or 1. Default is 1.
* *ajax* : Whether to AJAX load this widget/shortcode. 0 or 1. Default is Plugin Setting.
* *dynamic* : [Pro] Whether to dynamically reload on show changeover time. 0 or 1. Default 1.
* *title* : The title you would like to appear over the Upcoming Shows.
* *no_shows* : The text displayed when no upcoming show is scheduled. Default empty.
* *hide_empty* : Hide the content of the widget/shortcode if there is no current show. 0 or 1. Default 0.
* **Show Display Attributes**
* *show_link* : Link the Show title to the Show's page. 0 or 1. Default is 0.
* *title_position* : Relative to Avatar. 'above', 'below', 'left' or 'right'. Default is 'right'.
* *show_avatar* : Display upcoming Show Avatars. 0 or 1. Default is 0.
* *avatar_size* : The name of the (WordPress) size to use for the Show Avatar. Default 
* *avatar_width* : Set a width style in pixels for Show Avatars. Default is not to set.
* **Time Display Attributes**
* *show_sched* : Display the show's schedules. 0 or 1.  Default is 1.
* *countdown* : Display the Countdown until the next Show. 0 or 1. Default is 0.
* *time_format* : The time format used for displaying schedules. 12 or 24. Default is Plugin Setting.
* **Extra Display Attributes**
* *display_hosts* : Display the names of the DJs on the show. 0 or 1. Default is 0.
* *link_hosts* : Link Show hosts to their profile pages. 0 or 1. Default is 0.
* *show_encore* : Display encore presentation text (when set for Show). 0 or 1. Default is 1.

Example: `[upcoming-shows title="Coming Up On-Air" show_avatar="1" show_link="1" limit="3" time_format="12" schow_sched="1"]`

[Demo Site Example Output](https://demo.radiostation.pro/extra-shortcodes/upcoming-shows-widget/)


## Current Playlist Widget

Displays the Playlist assigned to the currently playing Show - if there is one assigned to it.

### Current Playlist Shortcode

`[current-playlist]` (legacy supported name `[now-playing]`)

The following attribute options are available for this shortcode:

* **Load Display Attributes**
* *ajax* : Whether to AJAX load this widget/shortcode. 0 or 1. Default is Plugin Setting.
* *dynamic* : [Pro] Whether to dynamically reload on show changeover time. 0 or 1. Default 1.
* *hide_empty*: Hide the content of the widget/shortcode if there is no current playlist. 0 or 1. Default 0.
* **Playlist Display Attributes**
* *title* : The title you would like to appear over the Playlist display. Default empty.
* *playlist_title* : Whether to display the name of the current playlist. 0 or 1. Default 0.
* *link* : Whether to link the playlist title to the playlist's page. 0 or 1. Default 1.
* *countdown* : Display the Playlist remaining time Countdown. 0 or 1. Default is 0.
* *no_shows* : The text displayed when there is no current playlist. Default empty.
* **Track Display Attributes**
* *artist* : Display artist name. 0 or 1  Default is 1.
* *song* : Display song name. 0 or 1. Default is 1.
* *album* : Display album name. 0 or 1. Default is 0.
* *label* : Display label name. 0 or 1.  Default is 0.
* *comments* : Display DJ comments. 0 or 1. Default is 0.

Example: `[current-playlist title="Current Song" artist="1" song="1" album="1" label="1" comments="0"]`

[Demo Site Example Output](https://demo.radiostation.pro/extra-shortcodes/current-playlist-widget/)


## Radio Clock Widget

`[radio-clock]` (see [Radio Clock Shortcode](./Shortcodes.md#radio-clock-shortcode))

Radio Station includes a Radio Clock Widget which will display the current Radio Station time in your selected Radio Station Timezone (via the Plugin Settings page) alongside the site visitor's current time (via browser timezone detection.)

#### [Pro] Timezone Switcher

[Radio Station Pro](https://radiostation.pro) includes a user Timezone Switcher in the which will allow your listener's to select a timezone and display adjusted Schedule and Show times.


## Page Builder Widgets

Page builder widgets have been introduced in Free 2.5.0 (Gutenberg Blocks) and Pro 2.6.0 (Elementor and Beaver Builder Modules.) All have the same settings as the standard widgets and support dynamic live previewing. (All output is rendered via the original shortcode functions, whether the settings are passed from shortcode attributes, widget options, block settings, or module settings.)

### Radio Station Blocks

Radio Blocks have been added to allow direct adding of any of the Radio Station widgets as Blocks in the WordPress Block Editor (aka Gutenberg.) You can find these by adding a new Block (pressing the blue + button.) All the Radio Blocks can be found grouped in the "Radio Station" category section (scroll down and the different categories will load.)

Once you have added a Radio Station Block, click on the Block and then click the Gear Settings icon in the top right of the Block Editor. The settings for the block will appear in the panel on the right. Changing any of the settings will reload the block preview dynamically.

#### Conditional Field Options

It is worth noting that there are a few conditional fields in some of the page builder widgets (this is true regardless of the builder.) Most significantly, some options specific to a Schedule view will appear only if that view is selected. Additionally, some Archive view options will only appear for specific post types.

### [Pro] Extra Block Options

Some of the extra features available in Pro are also available in their relevent matching page builder widgets (these options will only display when the Pro plugin is activated):

* The Radio Schedule Block has the extra Grid and Calendar Views, and multiview switching. 
* The Radio Archive Block can display Episode, Host and Producer post type archives.
* The Radio Player Block supports extra theming and color options like the Sitewide Player Bar.
* The Current Show/Playlist and Upcoming Show Blocks support dynamic reloading at Show changeovers.

### [Pro] Beaver Builder Modules

Readio Station Beaver Builder Modules can be found in the Radio Station category section of the Modules tab (ensure "Standard Modules" is selected in the dropdown.) Hover any module on the page and click the Settings spanner icon to customize the module's settings as desired. Each module has color and typography options in the Style tab. Changing any of the settings will reload the module preview dynamically.

### [Pro] Elementor Widgets

Radio Station Elementor Widgets can be found in the Radio Station category section of the Elements Tab in Elementor. Drag a Radio Station widget element to your content area as normal. Click on any element to customize the Settings in the left pane as desired. Each element widget has color and typography options in the Style tab. Changing any of the settings will reload the element preview dynamically.

