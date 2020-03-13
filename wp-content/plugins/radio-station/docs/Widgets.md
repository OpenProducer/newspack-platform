# Radio Station Plugin Widgets

***

You can add any of the Radio Station Plugin Widgets to your site's sidebar widget areas via the WordPress Appearance -> Widgets screen. 

Note Widgets are displayed via their corresponding Shortcodes to prevent code duplication and maintain display consistency. (The selected Widget options are simply converted into Shortcode attributes.) This also means if you want to display a Widget within a custom Template, you can use the corresponding shortcode in your template file. eg. `do_shortcode('[current-show]');`

[Radio Station Demo Site](http://radiostationdemo.com)


## Current Show Widget
Displays the currently playing Show - if there is one scheduled to play right now.

### Current Show Shortcode
`[current-show]` (legacy supported name `[dj-widget]`)

The following attributes are available for this shortcode:

* *title* : The title you would like to appear over the Current Show block. Default is 'Current Show'.
* *show_avatar* : Display a show's thumbnail. 0 or 1. Default is 1.
* *show_link* : Display a link to a show's page. 0 or 1 Default is 1.
* *display_hosts* : Display the names of the Hosts/DJs on the show. 0 or 1. Default is 0.
* *link_hosts* : Link Show hosts to their profile pages. 0 or 1. Default is 0.
* *default_name* : The text displayed when no show is scheduled for the current time.
* *time* : The time format used for displaying schedules. 12 or 24. Default is Plugin Setting.
* *show_sched* : Display the Show's schedule. 0 or 1. Default is 1.
* *show_playlist* : Display a link to the show's current playlist, if any. 0 or 1.  Default is 1.
* *show_all_sched* : Displays all schedules for a show if it airs on multiple days. 0 or 1. Default is 0.
* *show_desc* : Display an excerpt of the show's description. 0 or 1. Default is 0.
* *title_position* : Relative to Avatar. 'above', 'below', 'left' or 'right'. Default is 'right'.
* *avatar_width* : Set a width style in pixels for Show Avatars. Default is not to set.
* *countdown* : Display a Countdown until the current Show ends. 0 or 1. Default is 0.

Example: `[current-show title="Now On-Air" show_avatar="1" show_link="1" show_sched="1"]`

[Demo Site Example Output](https://radiostationdemo.com/extra-shortcodes/current-show-widget/)

## Upcoming Shows Widget
Displays a limited list of Upcoming Shows - if there are Shows scheduled.


### Upcoming Shows Shortcode
`[upcoming-shows]` (legacy supported name `[dj-coming-up-widget]`)

The following attributes are available for this shortcode:

* *title* : The title you would like to appear over the Upcoming Shows.
* *limit* : The number of upcoming shows to display. 0 or 1. Default is 1.
* *show_avatar* : Display upcoming Show Avatars. 0 or 1. Default is 0.
* *show_link* : Link the Show title to the Show's page. 0 or 1. Default is 0.
* *display_hosts* : Display the names of the DJs on the show. 0 or 1. Default is 0.
* *link_hosts* : Link Show hosts to their profile pages. 0 or 1. Default is 0.
* *time* : The time format used for displaying schedules. 12 or 24. Default is global Plugin Setting.
* *show_sched* : Display the show's schedules. 0 or 1.  Default is 1.
* *default_name* : The text you would like to display when no upcoming show is scheduled. Default is none.
* *title_position* : Relative to Avatar. 'above', 'below', 'left' or 'right'. Default is 'right'.
* *avatar_width* : Set a width style in pixels for Show Avatars. Default is not to set.
* *countdown* : Display the Countdown until the next Show. 0 or 1. Default is 0.

Example: `[upcoming-shows title="Coming Up On-Air" show_avatar="1" show_link="1" limit="3" time="12" schow_sched="1"]`

[Demo Site Example Output](https://radiostationdemo.com/extra-shortcodes/upcoming-shows-widget/)

## Current Playlist Widget
Displays the Playlist assigned to the currently playing Show - if there is one assigned to it.

### Current Playlist Shortcode
`[current-playlist]` (legacy supported name `[now-playing]`)

The following attributes are available for this shortcode:

* *title* : The title you would like to appear over the Playlist display block
* *artist* : Display artist name. 0 or 1  Default is 1.
* *song* : Display song name. 0 or 1. Default is 1.
* *album* : Display album name. 0 or 1. Default is 0.
* *label* : Display label name. 0 or 1.  Default is 0.
* *comments* : Display DJ comments. 0 or 1. Default is 0.
* *countdown* : Display the Playlist remaining time Countdown. 0 or 1. Default is 0.

Example: `[current-playlist title="Current Song" artist="1" song="1" album="1" label="1" comments="0"]`

[Demo Site Example Output](https://radiostationdemo.com/extra-shortcodes/current-playlist-widget/)

## Radio Clock Widget

A future version of Radio Station will include a Radio Clock Widget which will display the current Radio Station time in your selected Radio Station Timezone (via the Plugin Settings page.)

### [Pro] Timezone Switcher
A future version of Radio Station Pro will include a user Timezone Switcher in the which will allow your listener's to select a timezone and display adjusted Schedule and Show times.

## Streaming Player Widget
A future version of Radio Station will include a Streaming Player Widget.

### [Pro] Sitewide Player
A future version of Radio Station Pro will include a Sitewide Streaming Player.

