=== Radio Station ===
Contributors: tonyzeoli, majick, nourma
Donate link: https://netmix.co/donate
Tags: dj, music, playlist, radio, shows, scheduling, broadcasting
Requires at least: 3.3.1
Tested up to: 5.3.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Radio Station let's you build and manage a Show Schedule for a radio station or Internet broadcaster's WordPress website. 

== Description ==

Radio Station is a plugin to build and manage a Show Schedule for a radio station or internet broadcaster's WordPress website. It's functionality is based on Drupal 6's Station plugin, reworked for use in Wordpress.

The main included custom post type is "Shows", schedulable blocks of time that contain a Show description, a Show shifts repeater field, assignable images and other meta information. You can also create Playlists associated with those shows, or assign standard blog posts to relate to a Show. It also supports adding Schedule Overrides for specific dates and times. The plugin has the ability to associate users (given a DJ role of "Host") to Shows, so they can be displayed for that Show and to give them edit access.

A schedule of all Shows can be generated and added to a page with a shortcode (or simple page selection in the Plugin Settings) which has a number of Layout and display options. Shows can be categorized into Genres and a Genre highlighting filter appears on the embedded Schedule view. Each Show has it's own dedicated page to display all the Show details in a responsive layout.

The plugin contains a widget to display the on-air Currentr Show linked to the Show page, with various widget display options, and further widgets for displaying Upcoming Shows and current Playlist tracks. Shortcodes are available for these widgets, as well as for displaying archive lists of any of the plugin's custom post types.

As there is a lot you can do with Radio Station, we've made an effort to provide complete [Radio Station Plugin Documentation](https://netmix.com/radio-station/docs/). You can also find a Quickstart Guide there, as well as in the section below. You can see some example displays from the plugin via the Screenshots section, and full live examples are available on the [Radio Station Plugin Demo Site](https://radiostationdemo.com).

We are actively seeking Radio Station partners and supporters to fund further development of the free, open source version of this plugin via [Patreon](https://www.patreon.com/radiostation) and are also in the process of developing more exciting features and functionality for a future [Radio Station Pro](https://netmix.com/radio-station-pro/) upgrade.

= Updating from Prior to 2.3.0 =

Since 2.3.0, the first major feature update since plugin takeover in July 2019, Radio Station has incorporated a whole bunch of enhancements (see the changelog for a full list)... but here is a shortlist of the main new features:

* an Updated Show Page Layout (based on Content Filters not Templates)
* Responsive Schedule Views (with integrated Override support)
* Revamped Schedule calculations (with Show Shift Conflict Checking)
* Producer and Show Editor Roles (for improved Show management)
* Language Taxonomy Assignments (for Shows and Overrides)
* Admin Plugin Settings Page (with a plethora of new options)
* ...and a Radio Station Data API via the WordPress REST API! 

If you have been using Radio Station prior to version 2.3.0 and want to update, it is recommended that you read [the blog post for the 2.3.0 release](https://netmix.com/2-3-0-release-announcement/). As there is quite a lot of refactoring and changes in this version, you will want to check the details of the new changes with your current usage - especially if you have been using any custom page templates in your theme or other plugin-related custom code on your site. As these are probably the most significant changes that will ever be made to the plugin in a release, we have worked hard to maintain backwards oompatibility and test the new features thoroughly, but it's important you know what is going and test things out yourself in the update process.

= Support and Contribution =

We are grateful to Nikki Blight for her contribution to creating and developing this plugin for as long as she could maintain the codebase. As of June 22, 2019, Radio Station is managed by [Tony Zeoli](https://profiles.wordpress.org/tonyzeoli/) with [Tony Hayes](https://profiles.wordpress.org/majick/) as lead developer and other contributing committers to the project.

For free version plugin support, you can ask in the [Wordpress Plugin Support Forum](https://wordpress.org/support/plugin/radio-station/). Please give 24-48 hours to answer support questions. Alternatively (and preferably) you can submit bugs, enhancement and feature requests directly to [Github Repository Issues](https://github.com/netmix/radio-station/issues/).

If you are a WordPress developer wanting to contribute to Radio Station, please join the team and follow plugin development on [Github](https://github.com/netmix/radio-station) and submit Issues and Pull Requests there. You can see the current progress via the Projects tab. Or if you would prefer to get involved even more substantially, please [Contact Us via Email](mailto:info@netmix.com) and let us know what you would like to do.

= Quickstart Guide =

Once you have installed and activated the Radio Station Plugin on your WordPress site, your WordPress Admin area will now have a new menu item titled Radio Station with submenu page items. If you are trying to do something specific, you can check out the [FAQ](https://netmix.com/radio-station/docs/FAQ.md) for Frequently Asked Questions as you may find the answer there.

Firstly, you can visit the Plugin Settings screen to adjust the default [Options](https://netmix.com/radio-station/docs/Options.md) to your liking. Here you can set your Radio Timezone and Streaming URL (if you have one) along with other global plugin settings. Also from this Settings page you may want to assign [Pages](https://netmix.com/radio-station/docs/Display.md#automatic-pages) and Views for your Program Schedule display and other optional Post Type Archive displays.

Add a New Show and assign it a Shift timeslot and Publish. Then check out how it displays on a single Show page by clicking the Show Permalink. Schedule Overrides work in a similar way but are for specific date and time blocks only. Depending on your Theme, you may wish to adjust the [Templates](https://netmix.com/radio-station/docs/Display.md#page-templates) used. You can also assign different [Images](https://netmix.com/radio-station/docs/Display.md#images) to Shows (and Schedule Overrides.) Then have a look at your Program Schedule page to see the Show displayed there also. Just keep adding Shows until you have your Schedule filled in! You can further [Manage](https://netmix.com/radio-station/docs/Manage.md) your Shows and other Station data via the WordPress Admin area.

Next you may want to give some users on your site some plugin [Roles](https://netmix.com/radio-station/docs/Roles.md). (Note that while the default interface in WordPress allows you to assign a single role to a user, it also supports multiple roles, but you need to add a plugin to get an interface for this.) Giving a Role of Host/DJ or Producer to a user will allow them to be assigned to a Show on the Show Edit Page and thus edit that particular Show also. You can also assign the Show Editor role if you have someone needs to edit all plugin records without being a site Administator.

There are a few [Widgets](https://netmix.com/radio-station/docs/Widgets.md) you can add via your Appearance -> Widgets menu. The main one will display the currently playing Show, and another will display Upcoming Shows. There is also a Current Playlist Widget for if you have created and assigned a Playlist to a Show.

Then there are also a number of other [Shortcodes](https://netmix.com/radio-station/docs/Shortcodes.md) you can use in your pages with different display options you can use in various places on your site also. There is the Master Schedule, Widget Shortcodes, and also Archive Shortcodes for each of the different data records. 

Radio Station has several in-built [Data](https://netmix.com/radio-station/docs/Data.md) types. These include [Custom Post Types](https://netmix.com/radio-station/docs/Data.md#custom-post-types) for Shows, Schedule Overrides and Playlists. There are [Taxonomies](https://netmix.com/radio-station/docs/Data.md#taxonomies) for Genres and Languages. You can override most data values and display output via custom [Data Filters](#data-filters) throughout the plugin. We have also incorporated an [API](https://netmix.com/radio-station/docs/API.md) in the plugin via REST and/or WordPress Feeds, and this data is accessible in JSON format. 

This plugin is under active development and we are continuously working to enhance the Free version available on [WordPress.Org](https://wordpress.org/plugins/radio-station/), as well as creating new feature additions for **Radio Station Pro**. Check out the [Roadmap](https://netmix.com/radio-station/docs/Roadmap.md) if you are interested in seeing what is coming up next!

= Upgrading to Radio Station Pro =

Love Radio Station and ready for more? As the free version develops, we have also been working hard to introduce new features to create a Professional version that will "level up" the plugin to make your Station's site even more useable and accessible for your listeners! [Click here to learn more about Radio Station Pro](https://netmix.com/radio-station-pro/).


== Installation ==

1. Upload plugin .zip file to the `/wp-content/plugins/` directory and unzip.
2. Activate the plugin through the 'Plugins' menu in the WordPress Admin
3. Give any users who need access to the plugin the role of "Host", "Producer" or "Show Editor". Only these roles have administrative access to the plugin's records.
4. Create Shows, add Shifts to them, and assign Images, Genres, Languages, Hosts and/or Producers.
5. Add Playlists to your Shows or assign posts to Shows as needed.
6. See the QuickStart Guide for more detailed instructions of what else is available.

== Frequently Asked Questions ==

= Where can I find the full plugin documentation? =

The latest documentation can be found online at [NetMix.com](https://netmix.com/radio-station/docs/). The Markdown-formatted files used for these are in the `/docs/` folder of the [GitHub Repository](https://github.com/netmix/radio-station/docs/) and in the `/docs` folder of the plugin directory. 

= How do I schedule a Show? =

Simply create a new show via Add Show in the Radio Station plugin menu in the Admin area. You will be able to assign Shift timeslots to it on the Show edit page, as well as add the Show description and other meta fields, including Show images.

= How do I display a full schedule of my Station's shows? =

In the Plugin Settings, you can select a Page on which to automatically display the schedule as well as which View to display (a Table grid by default.) Alternatively, you can use the shortcode `[master-schedule]` on any page (or post.) This option allows you to use further shortcode attributes to control the what is displayed in the Schedule (see [Master Schedule Shortcode Docs](./Shortcodes.md#master-schedule-shortcode) )

= I've scheduled all my Shows, but some are not showing up on the programming grid?! =

Did you remember to check the "Active" checkbox for each Show? If a Show is not marked active, the plugin assumes that it's not currently in production and it is not shown on the Schedule. A Show will also not be shown if it has no active Shifts assigned to it.

= What if I want to schedule a special event? =

If you have a one-off event that you need to show up in the Schedule and Widgets, you can create a Schedule Override by clicking the Schedule Override tab in the Admin menu. This will allow you to set aside a block of time on a specific date, and when the Schedule or Widget is displaying that date, the override will be used instead of the normally scheduled Show. (Note that Schedule Overrides will not display in the old Legacy Table or List Views of the Master Schedule.)

= I'm seeing a 404 Not Found error when I click on the link for a Show! =

Try re-saving your site's permalink settings via Settings -> Permalinks.  Wordpress sometimes gets confused with a new custom post type is added. Permalink rewrites are automatically flushed on plugin activation, so you can also just deactivate and reactivate the plugin.

= What if I want to change or style the plugin's displays? =

The default styles for Radio Station have intionally kept fairly minimal so as to be compatible with most themes, so you may wish to add your own styles to suit your site's look and feel. The best way to do this is to add your own `rs-custom.css` to your Child Theme's directory, and add more specific style rules that modify or override the existing styles. Radio Station will automatically detect the presence of this file and enqueue it. You can find the base styles in the `/css/` directory of the plugin.

= What Widgets are available with this plugin? =

The following Widgets are available to add via the WordPress Appearance -> Widgets page:
Current Show, Upcoming Shows, Current Playlist. Radio Clock and Streaming Player Widgets will also be available in future versions. See the [Widget Documentation](./Widgets.md) for more details on these Widgets.

= What Shortcodes are available with this plugin? =

See the [Shortcode Documentation](./Shortcodes.md) for more details and a full list of possible Attributes for these Shortcodes:

* `[master-schedule]` - Master Program Schedule Display
* `[current-show]` - Current Show Widget
* `[upcoming-shows]` - Upcoming Shows Widget
* `[current-playlist]` - Current Playlist Widget
* `[shows-archive]` - Archive List of Shows
* `[genres-archive]` - Archive List of Shows sorted by Genre
* `[overrides-archive]` - Archive List of Schedule overrides
* `[playlists-archive]` - Archive List of Show Playlists

Note old shortcode aliases will still work in current and future versions to prevent breakage.

= I need users other than just the Administrator and DJ roles to have access to the Shows and Playlists post types. How do I do that? =

There are a number of different options depending on what you are wanting to to do. You can find more information on these in the [Roles Documentation](./Roles.md)

= How do I change the Show Avatar displayed in the sidebar widget? =

The avatar is whatever image is assigned as the Show's Avatar.  All you have to do is set a new Show Avatar on the Edit page for that Show.

= Why don't any users show up in the Hosts or Producers list on the Show edit page? =

You did remember to assign the Host or Producer role to the users you want, right?

= My Show Hosts and Producers can't edit a Show page.  What do I do? =

The only Hosts and Producers that can edit a show are the ones listed as being Hosts or Producers for that Show in the respective user selection menus. This is to prevent Hosts/Producers from editing other Host/Producer's Shows without permission.

= I don't want to use Gravatar for my Host/Producer's image on their profile page. =

Then you'll need to install a plugin that lets you add a different image to your Host/Producer's user account and edit your author.php theme file accordingly.  That's a little out of the scope of this plugin. I recommend [Cimy User Extra Fields](http://wordpress.org/extend/plugins/cimy-user-extra-fields/)

= What languages other than English is the plugin available in? =

Right now:

* Albanian (sq_AL)
* Dutch (nl_NL)
* French (fr_FR)
* German (de_DE)
* Italian (it_IT)
* Russian (ru_RU)
* Serbian (sr_RS)
* Spanish (es_ES)
* Catalan (ca)

= Can the plugin be translated into my language? =

You may translate the plugin into another language. Please visit our [WordPress Translate project page](https://translate.wordpress.org/locale/en-gb/default/wp-plugins/radio-station/) for this plugin for further instruction. The `radio-station.pot` file is located in the `/languages` directory of the plugin. Please send the finished translation to `info@netmix.com`. We'd love to include it.

== Screenshots ==
1. Weekly Schedule View
2. Show Page Layout
3. Admin Settings Panel
4. Show Conflict Display

== Changelog ==

= 2.3.0 =
* Include: Plugin Loader (1.0.9) with plugin options and settings
* Include: Freemius SDK (2.3.0) and Freemius integration
* Feature: assign new Producer role to a Show for Show displays
* Feature: internal Schedule Show Shift Conflict checking 
* Feature: Show Shift saving completeness and conflict checking
* Feature: added Data Endpoints API via WordPress REST and Feeds
* Feature: options to set Page and default View for Master Schedule
* Feature: post type Archive Shortcodes and Show-related Shortcodes
* Feature: display Radio Timezone on Master Schedule table view
* Feature: added Show Header image to Shows for single Show display
* Feature: added Show Language Taxonomy to Shows (and Overrides)
* Feature: added Countdown clock for Show and Playlists Widgets
* Improved: new Data Model and Schedule (with Override) Calculation
* Improved: new Show Content Template layout display method
* Improved: new Playlist Content Template layout display method
* Improved: added multiple Genre highlight selection on Master Schedule
* Improved: added Custom Field and Revision support to post types
* Improved: missing output sanitization throughout the plugin
* Improved: added file hierarchy fallbacks for CSS, JS and Templates
* Improved: enqueue conditional scripts inline instead of echoing
* Improved: Master Schedule displays enhancements and styling
* Improved: add Responsiveness to Master Schedule Table and Tab View
* Improved: add View/Edit links for editing custom post types
* Improved: load Datepicker styles locally instead of via Google
* Improved: add debug function for debug display and logging
* Improved: add links from Show Posts back to Show Page
* Improved: added Duplicate Shift button to Show Shift Editing
* Roles: new Show Producer role (same capabilities as DJ / Host)
* Roles: new Show Editor role (edit permissions but not Admin)
* Roles: Changed DJ role Label to DJ / Host (for talk show usage)
* Admin: Added Plugin Settings Admin Page (via Plugin Loader)
* Admin: Added plugin Upgrade / Updated details admin notices
* Admin: Schedule conflict notice and Show conflicts in Shift column
* Admin: Show/Override content indicator columns to Admin Show list
* Admin: Show Description helper text metabox on Show edit screen
* Admin: Fix to restore Admin Bar New/Edit links for plugin post types
* Admin: Store installed version for future updates and announcements
* Disabled: automatic loading of old templates (non theme agnostic)

= 2.2.8 =
* Fix to remove strict type checking from in_array (introduced 2.2.6)
* Fix to mismatched flush rewrite rules flag function name
* Fix to undefined index warnings for new Schedule Overrides
* Fix to not 404 author pages for DJs without blog posts

= 2.2.7 =
* Dutch translation added (Thank you to André Dortmont for the file!)
* Added Tabbed Display for Master Schedule Shortcode (via Tutorial)
* Add Show list columns with active, shift, DJs and show image displays
* Add Schedule Override list columns with date sorting and filtering
* Add playlist track information labels to Now Playing Widget
* Added meridiem (am/pm) translations via WP Locale class
* Added star rating link to plugin announcement box
* Added update subscription form to plugin Help page
* Fix to checkbox value saving for On Air/Upcoming Widgets
* Fix 12 hour show time display in Upcoming Widget
* Fix PM 12 hour shot time display in On Air Widget
* Fix to schedule override date picker value visibility
* Fix to weekday and month translations to use WP Locale
* Fix to checkbox value saving in Upcoming Widget
* Split Plugin Admin Functions into separate file
* Split Post Type Admin Functions into separate include
* Revert anonymous function use in widget registrations

= 2.2.6 =
* Reorganize master-list shortcode into templates
* Add constant for plugin directory
* Use WP_Query instead of get_posts
* New posts_per_page and tax_query
* Fixes for undefined indexes
* Fixes for raw mysql queries
* Typecasting to support strict comparisons

= 2.2.5 =
* WordPress coding standards and best practices (thanks to Mike Garrett @mikengarrett)

= 2.2.4 =
* added title position and avatar width options to widgets
* added missing DJ author links as new option to widgets
* cleanup, improve and fix enqueued Widget CSS (on air/upcoming)
* improved to show Encore Presentation in show widget displays
* fix to Show shift Encore Presentation checkbox saving

= 2.2.3 =
* added flush rewrite rules on plugin activation/deactivation
* added show_admin_column and show_in_quick_edit for Genres
* added show metadata and schedule value sanitization
* fix to 00 minute validation for Schedule Override
* convert span tags to div tags in Widgets to fix line breaks

= 2.2.2 =
* shift main playlist and show metaboxes above editor
* set plugin custom post types editor to Classic Editor
* add high priority to side metaboxes for plugin post types
* added dismissable development changeover admin notice
* added simple Patreon supporter image button and blurb
* added filter for DJ Avatar size on Author page template
* fix to Schedule Override metabox value saving
* fix to Playlist track list items overflowing metabox
* fix to shift up time row on Master Schedule table view
* fix to missing weekday headings in Master Schedule table
* fix to weekday display for Upcoming DJ Widget
* fix to user display labels on select DJ metabox
* fix to file_exists check for DJ on Air stylesheet path
* fix to make DJ multi-select input full metabox width
* fix to expand admin menu when on genre taxonomy page
* fix to expand admin menu when editing plugin post types
* fix to genre submenu item link for current page
* added GitHub URI to plugin header for GitHub updater

= 2.2.1 =
* Re-commit all missing files via SVN

= 2.2.0 =
* WordPress coding standards refactoring for WP 5 (thanks to Tony Hayes @majick777)
* fixed the protocol in jQuery UI style Google URL
* reprefixed all functions for consistency (radio_station_)
* updated all the widget constructor methods
* merged the menu items into a single main menu
* updated the capability checks for the menu items
* moved the help and export pages to /templates/
* moved all the css files to /css/
* enqeued the djonair css from within the widget
* use plugins_url for all resource URLs
* added $wpdb->prepare to sanitize a query
* added some sanization for metabox save values
* added a week and month translation helper
* added a radio station antenna icon

= 2.1.3 =
* Added method for displaying schedule for only a single day (see readme section for the master-schedule shortcode for details).

= 2.1.2 =
* Compatibility fix for Wordpress 4.3.x - Updated the widgets to use PHP5 constructors instead of the deprecated PHP4 constructors.
* Catalan translation added (Thank you to Victor Riera for the file!)

= 2.1.1 = 
* Bug fix - Fixed day of the week language translation issue in master schedule shortcode
* Bug fix - Added some error checking in the sidebar widgets
* New Feature - Added ability to give schedule overrides a featured image
* New Feature - Added built-in help page

= 2.1 =
* General code cleanup, 4.1 compatibility testing, and changes for better efficiency.
* Bug fix - Fixed issue with early morning shows spanning entire column in the programming grid shortcode
* New Feature - Master programming grid can now be displayed in div format, as well as the original table and list formats.

= 2.0.16 =
* Minor revisions to German translation.
* Fixed a bug that was resetting custom-sert role capabilities for the DJ role.

= 2.0.15 =
* German translation added (Thank you to Ian Hook for the file!)

= 2.0.14 =
* Fixed issue on the master schedule where genres containing more than one work wouldn't highlight when clicked
* Added ability to display DJ names on the master schedule.
* Fixed bug in the Upcoming widget.  Override Schedule no longer display as upcoming when they are on-air.
* Verified compatibility woth WordPress 4.0

= 2.0.13 =
* Added the ability to display show avatars on the program grid.
* Added the ability to display show description in the now on-air widget and short code. 

= 2.0.12 =
* Fixed a bug in the master schedule shortcode

= 2.0.11 =
* Russian translation added (Thank you to Alexander Esin for the file!)

= 2.0.10 =
* Fixed role/capability conflict with WP User Avatar plugin.
* Added the missing leading zero to 24-hour time format on the master schedule.
* Fixed dj_get_current function so that it no longer returns shows that have been moved to the trash.
* Fixed dj_get_next function so that it no longer ignores the "Active" checkbox on a show.
* Added some CSS ids and classes to the master program schedule list format to make it more useful

= 2.0.9 = 
* Fixed broken upcoming show shortcode.
* Added ability to display DJ names along with the show title in the widgets.

= 2.0.8 =
* Fixed the display of schedules for upcoming shows in the widget and shortcode.
* Fixed a bug in the dj_get_next function that was causing it to ignore the beginning of the next week at the end of the current week.

= 2.0.7 =
* Fixed scheduling bug in shortcode function

= 2.0.6 =
* Master Schedule now displays days starting with the start_of_week option set in the WordPress General Settings panel. 
* Fixed issue with shows that have been unplublished still showing up on the master schedule.
* Fixed missing am/pm text on shows that run overnight on the master schedule.
* Fixed an issue with shows that run overnight not spanning the correct number of hours on the second day on the master schedule.
* Fixed problem in Upcoming DJ Widget that wasn't displaying the correct upcoming shift.

= 2.0.5 =
* Fixed an issue with some shows displaying in 24 hour time on master schedule grid even though 12-hour time is specified
* Fixed a bug in the On-Air widget that was preventing shows spanning two day from displaying
* Added code to enable theme support for post-thumbnails on the "show" post-type so users don't have to add it to their theme's functions.php file anymore.

= 2.0.4 =
* Master Schedule bug for shows that start at midnight and end before the hour is up fixed.

= 2.0.3 =
* Compatibility fix: Fixed a jquery conflict in the backend that was occuring in certain themes

= 2.0.2 =
* Bug fix: Scheduling issue with overnight shows fixed

= 2.0.1 =
* Bug fix: Fixed PHP error in Playlist save function that was triggered during preview
* Bug fix: Fixed PHP notice in playlist template file
* Bug fix: Fixed PHP error in dj-widget shortcode

= 2.0.0 =
* Major code reorganization for better future development
* PHP warning fix
* Enabled option to add comments on Shows and Playlists
* Added option to show either single or multiple schedules in the On Air widget

= 1.6.2 =
* Minor PHP warning fixes

= 1.6.1 =
* Bug fix: Some of the code added in the previous update uses the array_replace() function that is only available in PHP 5.3+.  Added a fallback for older PHP versions.

= 1.6.0 =
* Added the ability to override the weekly schedule to allow one-off events to be scheduled
* Added a list format option to the master schedule shortcode
* Added Italian translation (it_IT) (thank you to Cristofaro Giuseppe!)

= 1.5.4 =
* Fixed some PHP notices that were being generated when there were no playlist entries in the system.

= 1.5.3 =
* Added Serbian translation (sr_RS) (thank you to Miodarag Zivkovic!)

= 1.5.2.1 =
* Removed some debug code from one of the template files

= 1.5.2 =
* Fixed some localization bugs.
* Added Albanian translation (sq_AL) (thank you to Lorenc!)

= 1.5.1 =
* Fixed some localization bugs.
* Added French translation (fr_FR) (a big thank you to Dan over at BuddyPress France - http://bp-fr.net/)

= 1.5.0 =
* Plugin modified to allow for internationalization.
* Spanish translation (es_ES) added.

= 1.4.6 =
* Fixed a bug with shows that start at midnight not displaying in the on-air sidebar widget.
* Switched DJ/Show avatars in the widgets to use the featured image of the show instead of gravatar.
* Updated show template to get rid of a PHP warning that appeared if the show had no schedules. 
* Fixed some other areas of the code that were generating PHP notices in WordPress 3.6
* Added CSS classes to master program schedule output so CSS rules can be applied to specific shows
* Added new attribute to the list-shows shortcode to allow only specified genres to be displayed

= 1.4.5 =
* Fixed master-schedule shortcode bug that was preventing display of 12 hour time

= 1.4.4 =
* Compatibility fix for Wordpress 3.6 - fixed problem with giving alternative roles DJ capabilities
* Fixed some areas of the code that were generating PHP notices in WordPress 3.6

= 1.4.3 =
* Master schedule shortcode now displays indiviual shows in both 24 and 12 hour time
* Fixed some areas of the code that were generating PHP notices in WordPress 3.6
* Added example of how to display show schedule to single-show.php template
* Added more options to the plugin's widgets
* Added new options to the master-schedule shortcode

= 1.4.2 =
* Fixed a bug in the CSS file override from theme directory

= 1.4.1 =
* Fixed issue with templates copied to the theme directory not overriding the defaults correctly
* Fixed incorrectly implemented wp_enqueue_styles()
* Removed deprecated escape_attribute() function from the plugin widgets
* Fixed some areas of the code that were generating PHP notices

= 1.4.0 =
* Compatibility fix for WordPress 3.6

= 1.3.9 =
* Fixed a bug that was preventing sites using a non-default table prefix from seeing the list of DJs on the add/edit show pages

= 1.3.8 =
* Changes to fix the incorrect list of available shows on the Add Playlist page
* Removing Add Show links from admin menu for DJs, since they don't have permission to use them anyway.

= 1.3.7 =
* Fixed a scheduling bug in the upcoming shows widget
* By popular request, switched the order of artist and song in the now playing widget

= 1.3.6 =
* Fixed issue with shows that run overnight not showing up correctly in the sidebar widgets

= 1.3.5 =
* Fixed a time display bug in the DJ On-Air sidebar widget
* Fixed a display bug on the master schedule with overnight shows

= 1.3.4 =
* By request, added as 24-hour time format option to the master schedule and sidebar widgets.

= 1.3.3 =
* Added the ability to assign any user with the edit_shows capability as a DJ, to accomodate custom and edited roles.

= 1.3.2 =
* Fixed a bug in the DJ-on-air widget

= 1.3.1 =
* Fixed a major bug in the master schedule output

= 1.3 =
* Fixed some minor compatibility issues with WordPress 3.5
* Fixed Shows icon in Dashboard

= 1.2 =
* Fixed thumbnail bug in sidebar widgets
* Added new widget to display upcoming shows
* Added pagination options for playlists and show blogs

= 1.1 =
* Fixed playlist edit screen so that queued songs fall to the bottom of the list to maintain play order
* Reduced the size of the content field in the playlist post type
* Some minor formatting changes to default templates
* Added genre highlighter to the master programming schedule page
* Added a second Update button on the bottom of the playlist edit page for convinience.
* Added sample template for DJ user pages
* Fixed a bug in the master schedule shortcode that messed up the table for shows that are more than two hours in duration
* Fixed a bug in the master schedule shortcode to accomodate shows that run from late night into the following morning.
* Added new field to associate blog posts with shows

= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.3.0 =
* https://netmix.com/blog/2-3-0-release-announcement/
* Revamped Templates, Improved Master Schedule, Improved User Roles, 
* New Shortcodes, Admin Options, Show Producers, Improved Show Images,
* REST API Routes, Shift Conflict Checking, Extra Post Type Supports
