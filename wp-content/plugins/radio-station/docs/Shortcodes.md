# Radio Station Plugin Shortcodes

***

Note if you want to display a Shortcode within a custom Template, you can use the WordPress `do_shortcode` function. eg. `do_shortcode('[master-schedule]');`

Shortcode Output Examples can be seen on the [Radio Station Demo Site](http://demo.radiostation.pro)


## Master Schedule

### Master Schedule Shortcode

Use the shortcode `[radio-schedule]` or `[master-schedule]` on any page. This will generate a full-page schedule in one of five Views: 

* [Table](https://demo.radiostation.pro/master-schedule/table-view/) (default) - responsive program in table form
* [Tabbed](https://demo.radiostation.pro/master-schedule/tabbed-view/) - responsive styled list view with day selection tabs
* [List](https://demo.radiostation.pro/master-schedule/list-view/) - unstyled plain list view for custom development use
* [Divs](https://demo.radiostation.pro/master-schedule/divs-view/) - (deprecated - display issues) legacy unstyled div-based view 
* [Legacy](https://demo.radiostation.pro/master-schedule/legacy-view/) - (deprecated) legacy table view
* [Grid](https://demo.radiostation.pro/master-schedule/grid-view/) - [Pro] extra grid style view available in Pro version
* [Calendar](https://demo.radiostation.pro/master-schedule/calendar-view/) - [Pro] extra calendar style view available in Pro version

The above View names are linked to examples on the Demo Site.

Note that Divs and Legacy Views are considered deprecated as they do not honour Schedule Overrides, but have been kept for backwards compatibility only. The legacy Divs view also has display issues.

The following attributes are available for the shortcode:

* **Layout Display Options**
* *view* : Which View to use for display output. 'table', 'tabs', 'list', 'divs', 'legacy', 'grid', 'calendar'. Default 'table'.
* *time* : Display time format you with to use. 12 and 24. Default is Plugin Setting.
* *clock* : Display Radio Clock above schedule. (See clock shortcode.) 0 or 1. Default is Plugin Setting.
* **Show Display Attributes**
* *show_times* : Whether to display the show shift's start and end times. 0 or 1. Default 1.
* *show_link* : Display the title of the show as a link to its profile page. 0 or 1.  Default 1.
* *show_image* : Whether the display the show's avatar. 0 or 1. Default 0 (1 for Tabbed View.)
* *show_desc* : Whether to display Show Description excerpt. 0 or 1. 
* *show_hosts* : Whether to display a list of show hosts. 0 or 1. Default 0.
* *link_hosts* : Whether to link each show host to their profile page. 0 or 1. Default 0.
* *show_genres* : Whether to display a list of show genres. 0 or 1. Default 0 (1 for Tabbed View.)
* *show_encore* : Whether to display 'encore airing' for a show shift. 0 or 1. Default 1.
* *show_file* : Whether to add a link to the latest audio file. 0 or 1. Default 0.
* **Time Display Attributes**
* *days* : Display for single day or multiple days (string or 0-6, comma separated.) Default all.
* *start_day* : day of the week to start schedule (string or 0-6, or 'today') Defaults to WordPress setting.
* *display_day* : Full or short day heading ('full' or 'short') Default short for Table, full for Tabs/List.
* *display_date* : Date format for date subheading. 0 for none. Default 'jS' for Table/List, 0 for Tabs.
* *display_month* : Full or short month subheading ('full', 'short') Default 'short'.
* ** View-specific Attributes **
* *image_position* : Tabs View: Show image position placement. 'right', 'left' or 'alternate'. Default 'left'.
* *hide_past_shows* : Tabs View: Hide shows that are finished in the Schedule. 0 or 1. Default 0.
* *gridwidth* : [Pro] Grid View: Set the width, in pixels of the grid columns. Default 150.
* *divheight* : [Legacy] Divs View: Set the height, in pixels, of the individual divs. Default 45.
* *time_spaced* : [Pro] Grid View: Enabled time spacing with background images. 0 or 1. Default 0.
* *weeks* : Number of weeks to display in calendar. For Pro 'calendar' view only. Default 4.
* *previous_weeks* : Number of past weeks to display in calendar. For Pro 'calendar' view only. Default 1.

Example: Display the schedule in 24-hour time format, use `[master-schedule time="24"]`.  

##### [Pro] Multiple View Switching

In [Radio Station Pro](https://radiostation.pro), you can display multiple views which the user can switch between. This gives your visitors better access to your schedule in the way that is better suited to them. You can set the available views in the plugin settings for the automatic schedule page as well as the default view. Alternatively you can use the Master Schedule Shortcode as normal on a page and provide a comma-separated list of views (the first value provided will be used as the default view.) eg. `[master-schedule view="table,tabs,grid"]`

The default view can also be set via the plugin settings page, or by adding a `default_view` shortcode attribute if you wish to override this setting in the shortcode.

Further, if you wish to customize the attributes for each particular view, you can use the `radio_station_pro_multiview_attributes` filter. By adding a view prefix to the attribute you wish to change, this will override the attribute for that view. eg. To display the full day heading in Tabs view, but short day headings in Table view:

```
add_filter( 'radio_station_pro_multiview_atts', 'my_custom_multiview_attributes );
function my_custom_multiview_attributes( $atts ) {
	$atts['table_display_day'] = 'short';
	$atts['tabs_display_day'] = 'long';
	return $atts;
}
```

[Demo Site Example Output](https://demo.radiostation.pro/master-schedule/multiple-view-switching/)


#### Radio Timezone Shortcode

`[radio-timezone]`

Displays the Radio Station Timezone selected via Plugin Settings. There are no attributes for this shortcode. This is the default display above the Master Schedule when the Radio Clock is turned off in the Plugin Settings.


#### Radio Clock Shortcode

`[radio-clock]`

Displays the current server time and user time. Also available as a Widget.

The following attributes are available for this shortcode:

* *time_format* : Display time format you with to use. 12 and 24. Default is Plugin Setting.
* *day* : Display day after current times. 'full', 'short' or 'none'. Default 'full'.
* *date* : Display date after current times. 0 or 1. Default 1.
* *month* : Display months after current times. 'full', 'short' or 'none'. Default 'full'.
* *zone* : Display timezone after current times. 0 or 1. Default 1.
* *seconds* : Display seconds with current times. 0 or 1. Default 0.

The Radio Clock can also be displayed by default above the Master Schedule by enabling this in the Plugin Settings, or by adding `clock="1"` to the Master Schedule shortcode. (It's display attributes as part of the schedule can changed using the `radio_station_schedule_clock` filter.)


##### [Pro] User Timezone Switching

In [Radio Station Pro](https://radiostation.pro), where the Radio Clock is displayed (whether as a widget or part of the schedule) the user is given an extra link "Change Timezone" through which they can select their local timezone. The Radio Clock (and/or Schedule) will then provide an additional converted display for displayed times into the user's selected timezone.


## Archive Shortcodes

Note for ease of use either the singular or plural version of each archive shortcode will work.

### Shows Archive Shortcode

`[shows-archive]` (or `[show-archive]`)

The following attributes are available for this shortcode:

* *description* : Show description display. 'none', 'full' or 'excerpt'. Default 'excerpt'.
* *view* : Display view option. 'list' or 'grid' option. Default 'list'.
* *hide_empty* : Only display if Shows are found. 0 or 1. Default 0.
* *time* : Display time format you with to use.  Valid values are 12 and 24. Default is the Plugin Setting.
* *genre* : Genres to display (ID or slug). Separate multiple values with commas. Default empty (all)
* *language* : Languages to display (ID or slug). Separate multiple values with commas. Default empty (all)
* *status* : Query for Show status. Default 'publish'.
* *perpage* : Query for number of Shows. Default -1 (all)
* *offset* : Query for Show offset. Default '' (no offset)
* *orderby* : Query to order Show display by. Default 'title'.
* *order* : Query order for Shows. Default 'ASC'.
* *show_avatars* : Display the Show Avatar. 0 or 1. Default 1.
* *thumbnails* : Display Show Featured image if no Show Avatar. 0 or 1. Default 0.
* *with_shifts* : Only display Shows with active Shifts. 0 or 1. Default 0.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/shows-archive/)

### Overrides Archive Shortcode

`[overrides-archive]` (or `[override-archive]`)

The following attributes are available for this shortcode:

* *description* : Override description display. 'none', 'full' or 'excerpt'. Default 'excerpt'.
* *view* : Display view option. 'list' or 'grid' option. Default 'list'.
* *hide_empty* : Only display if Overides are found. 0 or 1. Default 0.
* *show_dates* : Display the Schedule Override dates and start/end times. 0 or 1. Default 1.
* *time* : Display time format you with to use.  Valid values are 12 and 24. Default is the Plugin Setting.
* *genre* : Genres to display (ID or slug). Separate multiple values with commas. Default empty (all)
* *language* : Languages to display (ID or slug). Separate multiple values with commas. Default empty (all)
* *status* : Query for Override status. Default 'publish'.
* *perpage* : Query for number of Overrides. Default -1 (all)
* *offset* : Query for Override offset. Default '' (no offset)
* *orderby* : Query to order Override display by. Default 'title'.
* *order* : Query order for Overrides. Default 'ASC'.
* *show_avatars* : Display the Override Avatar. 0 or 1. Default 1.
* *thumbnails* : Display Override Featured image if no Overide Avatar. 0 or 1. Default 0.
* *with_dates* : Only display Shows with Date set. 0 or 1. Default 0.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/overrides-archive/)

### Playlists Archive Shortcode

`[playlists-archive]` (or `[playlist-archive]`)

The following attributes are available for this shortcode:

* *description* : Playlist description display. 'none', 'full' or 'excerpt'. Default 'excerpt'.
* *view* : Display view option. 'list' or 'grid' option. Default 'list'.
* *hide_empty* : Only display if Playlists are found. 0 or 1. Default 0.
* *genre* : Genres to display (ID or slug). Separate multiple values with commas. Default empty (all)
* *language* : Languages to display (ID or slug). Separate multiple values with commas. Default empty (all)
* *status* : Query for Playlist status. Default 'publish'.
* *perpage* : Query for number of Playlists. Default -1 (all)
* *offset* : Query for Playlists offset. Default '' (no offset)
* *orderby* : Query to order Playlists display by. Default 'title'.
* *order* : Query order for Playlists. Default 'ASC'.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/playlists-archive/)

### Genres Archive Shortcode

`[genres-archive]` (or `[genre-archive]`)

The following attributes are available for this shortcode:

* *genres* : Genres to display (ID or slug). Separate multiple values with commas. Default empty (all)
* *link_genres* : Link Genre titles to term pages. 0 or 1. Default 1.
* *genre_desc* :  Display Genre term description. 0 or 1. Default 1.
* *genre_images' : [Pro] Display Genre images. 0 or 1. Default 1.
* *view* : Layout display view. 'list' or 'grid'. Default 'grid'.
* *image_width* : [Pro] Set a width style in pixels for Genre images. Default is 100.
* *hide_empty* : No output if no records to display for Genre. 0 or 1. Default 1.
* *status* : Query for Show status. Default 'publish'.
* *perpage* : Query for number of Shows. Default -1 (all)
* *offset* : Query for Show offset. Default '' (no offset)
* *orderby* : Query to order Show display by. Default 'title'.
* *order* : Query order for Shows. Default 'ASC'.
* *with_shifts* : Only display Shows with active Shifts. 0 or 1. Default 0.
* *show_avatars* : Display the Show Avatar. 0 or 1. Default 1.
* *thumbnails* : Display Show Featured image if no Show Avatar. 0 or 1. Default 0.
* *avatar_width* : * *avatar_width* : Set a width style in pixels for Show Avatars. Default is 75.
* *show_desc* : Display Show Descriptions. 'none', 'full' or 'excerpt'. Default 'none'.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/genres-archive/)

### Language Archive Shortcode

`[languages-archive]` (or `[language-archive]`)

The following attributes are available for this shortcode:

* *languages* : Genres to display (ID or slug). Separate multiple values with commas. Default empty (all)
* *link_languages* : Link Genre titles to term pages. 0 or 1. Default 1.
* *language_desc* :  Display Genre term description. 0 or 1. Default 1.
* *view* : Layout display view. 'list' or 'grid'. Default 'grid'.
* *hide_empty' : No output if no records to display for Genre. 0 or 1. Default 1.
* *status* : Query for Show status. Default 'publish'.
* *perpage* : Query for number of Shows. Default -1 (all)
* *offset* : Query for Show offset. Default '' (no offset)
* *orderby* : Query to order Show display by. Default 'title'.
* *order* : Query order for Shows. Default 'ASC'.
* *with_shifts* : Only display Shows with active Shifts. 0 or 1. Default 0.
* *show_avatars* : Display the Show Avatar. 0 or 1. Default 1.
* *thumbnails* : Display Show Featured image if no Show Avatar. 0 or 1. Default 0.
* *avatar_width* : * *avatar_width* : Set a width style in pixels for Show Avatars. Default is 75.
* *show_desc* : Display Show Descriptions. 'none', 'full' or 'excerpt'. Default 'none'.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/languages-archive/)

### [Pro] Hosts Archive Shortcode

`[host-archive]` or `[hosts-archive]`

* *description* : Profile description display. 'none', 'full' or 'excerpt'. Default 'excerpt'.
* *view* : Display view option. 'list' or 'grid' option. Default 'list'.
* *status* : Query for Host status. Default 'publish'.
* *perpage* : Query for number of Hosts. Default -1 (all)
* *offset* : Query for Host offset. Default '' (no offset)
* *orderby* : Query to order Host display by. Default 'title'.
* *order* : Query order for Hosts. Default 'ASC'.
* *thumbnails* : Display profile image. 0 or 1. Default 0.
* *social* : Display social profile icons. 0 or 1. Default 1.
* *shows* : Display a list of Shows assigned to Host. 0 or 1. Default 1.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/hosts-archive/)

### [Pro] Producers Archive Shortcode

`[producer-archive]` or `[producers-archive]`

* *description* : Profile description display. 'none', 'full' or 'excerpt'. Default 'excerpt'.
* *view* : Display view option. 'list' or 'grid' option. Default 'list'.
* *status* : Query for Producer status. Default 'publish'.
* *perpage* : Query for number of Producers. Default -1 (all)
* *offset* : Query for producer offset. Default '' (no offset)
* *orderby* : Query to order Producer display by. Default 'title'.
* *order* : Query order for Producers. Default 'ASC'.
* *thumbnails* : Display profile image. 0 or 1. Default 0.
* *social* : Display social profile icons. 0 or 1. Default 1.
* *shows* : Display a list of Shows assigned to Producer. 0 or 1. Default 1.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/producers-archive/)

### [Pro] Episodes Archive Shortcode

`[episode-archive]` or `[episodes-archive]`

* *description* : Episode description display. 'none', 'full' or 'excerpt'. Default 'excerpt'.
* *view* : Display view option. 'list' or 'grid' option. Default 'list'.
* *status* : Query for Episode status. Default 'publish'.
* *perpage* : Query for number of Episodes. Default -1 (all)
* *offset* : Query for episode offset. Default '' (no offset)
* *orderby* : Query to order Episode display by. Default 'title'.
* *order* : Query order for Episode. Default 'ASC'.
* *thumbnails* : Display Episode image. 0 or 1. Default 0.
* *show* : Display the Show the Episode is assigned to. 0 or 1. Default 1.
* *number* : Display the Episode Number. 0 or 1. Default 1.
* *air_date* : Display the Episode Air Date. 0 or 1. Default 1.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/episodes-archive/)

### [Pro] Team Archive Shortcode

`[team-archive]`

The following attributes are available for this shortcode:

* *view* : Layout display view. 'list' or 'grid'. Default list.

...

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/team-archive/)

### Show Posts Archive Shortcode

`[show-posts-archive]` (or `[show-post-archive]`)

The following attributes are available for this shortcode:

* *per_page* : Number of Show Posts to display per page. Default 15.
* *limit* : Limit of Show Posts to display. Default 0 (no limit)
* *content* : Post Content display. 'none', 'full' or 'excerpt'. Default 'excerpt'.
* *thumbnails* : Display Show Post Thumbnails. 0 or 1. Default 1.
* *pagination* : Paginate Show Post Display. 0 or 1. Default 1.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/show-posts-archive/)

### Show Playlists Archive Shortcode

`[show-playlists-archive]` (or `[show-playlist-archive]`)

The following attributes are available for this shortcode:

* *per_page* : Number of Show Playlists to display per page. Default 15.
* *limit* : Limit of Show Playlists to display. Default 0 (no limit)
* *content* : Playlist Content display. 'none', 'full' or 'excerpt. Default 'excerpt'.
* *pagination* : Paginate Show Post Display. 0 or 1. Default 1.

[Demo Site Example Output](https://demo.radiostation.pro/archive-shortcodes/show-playlists-archive/)

### [Pro] Show Episodes Archive Shortcode

`[show-episodes-archive]` (or `[show-episode-archive]`)

This shortcode is available in [Radio Station Pro](https://radiostation.pro)


## Widget Shortcodes

### Current Show Widget Shortcode

`[current-show]` - see [Current Show Widget](./Widgets.md#current-show-widget)

### Upcoming Shows Widget Shortcode

`[upcoming-shows]` - see [Upcoming Shows Widget](./Widgets.md#upcoming-shows-widget)

### Current Playlist Widget Shortcode

`[current-playlist]` - see [Current Playlist Widget](./Widgets.md#current-playlist-widget)


### Legacy Shortcodes

#### Show List

`[show-list]`

This shortcode is considered *Deprecated*. Use the [Shows Archive Shortcode](#shows-archive-shortcode) instead: `[shows-archive]`

The following attributes are available for this shortcode:

* *genre* : Displays shows only from the specified genre(s). Separate multiple genres with a comma, e.g. genre="pop,rock".

Examples: `[list-shows genre="pop"]`, `[list-shows genre="pop,rock,metal"]`

#### Show Playlists

`[show-playlists]` (or `[get-playlists]`

This shortcode is considered *Deprecated*. Use the [Show Playlists Archive Shortcode](#show-playlists-archive-shortcode) instead: `[show-playlists-archive]`

The following attributes are available for this shortcode:

* *show* : The ID of the Show to display Playlists for.
* *limit* : Maximum number of Playlists to display.

