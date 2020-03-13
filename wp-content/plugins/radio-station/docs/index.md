# Radio Station Documentation Index

*** 


| Topic | Description |
| --- | --- |
| [FAQ](./FAQ.md) | Frequently Asked Questions |
| [Options](./Options.md) | Plugin Option Details and Value Filters |
| [Display](./Display.md) | Automatic Pages, Templates and Images |
| [Manage](./Manage.md) | Admin Lists, Shift Editing, Conflict Checker |
| [Roles](./Roles.md) | Plugin Roles and related Capabilities |
| [Widgets](./Widgets.md) | Current Show, Upcoming Shows, Current Playlist, Radio Clock, Streaming Player |
| [Shortcodes](./Shortcodes.md) | Data List Archives and Master Schedule Views |
| [Data](./Data.md) | Post Types, Taxonomies and Data Filters |
| [API](./API.md) | Data API via REST and Feed Endpoints
| [Roadmap](./Roadmap.md) | Feature Roadmap for Free and Pro Versions |


### Quickstart Guide

Once you have installed and activated the Radio Station Plugin on your WordPress site, your WordPress Admin area will now have a new menu item titled Radio Station with submenu page items. If you are trying to do something specific, you can check out the [FAQ](./FAQ.md) for Frequently Asked Questions as you may find the answer there.

Firstly, you can visit the Plugin Settings screen to adjust the default [Options](./Options.md) to your liking. Here you can set your Radio Timezone and Streaming URL (if you have one) along with other global plugin settings. Also from this Settings page you may want to assign [Pages](./Display.md#automatic-pages) and Views for your Program Schedule display and other optional Post Type Archive displays.

Add a New Show and assign it a Shift timeslot and Publish. Then check out how it displays on a single Show page by clicking the Show Permalink. Schedule Overrides work in a similar way but are for specific date and time blocks only. Depending on your Theme, you may wish to adjust the [Templates](./Display.md#page-templates) used. You can also assign different [Images](./Display.md#images) to Shows (and Schedule Overrides.) Then have a look at your Program Schedule page to see the Show displayed there also. Just keep adding Shows until you have your Schedule filled in! You can further [Manage](./Manage.md) your Shows and other Station data via the WordPress Admin area.

Next you may want to give some users on your site some plugin [Roles](./Roles.md). (Note that while the default interface in WordPress allows you to assign a single role to a user, it also supports multiple roles, but you need to add a plugin to get an interface for this.) Giving a Role of Host/DJ or Producer to a user will allow them to be assigned to a Show on the Show Edit Page and thus edit that particular Show also. You can also assign the Show Editor role if you have someone needs to edit all plugin records without being a site Administator.

There are a few [Widgets](./Widgets.md) you can add via your Appearance -> Widgets menu. The main one will display the currently playing Show, and another will display Upcoming Shows. There is also a Current Playlist Widget for if you have created and assigned a Playlist to a Show.

Then there are also a number of other [Shortcodes](./Shortcodes.md) you can use in your pages with different display options you can use in various places on your site also. There is the Master Schedule, Widget Shortcodes, and also Archive Shortcodes for each of the different data records. 

Radio Station has several in-built [Data](./Data.md) types. These include [Custom Post Types](./Data.md#custom-post-types) for Shows, Schedule Overrides and Playlists. There are [Taxonomies](./Data.md#taxonomies) for Genres and Languages. You can override most data values and display output via custom [Data Filters](#data-filters) throughout the plugin. We have also incorporated an [API](./API.md) in the plugin via REST and/or WordPress Feeds, and this data is accessible in JSON format. 

This plugin is under active development and we are continuously working to enhance the Free version available on [WordPress.Org](https://wordpress.org/plugins/radio-station/), as well as creating new feature additions for [Radio Station Pro](https://netmix.com/radio-station-pro/). Check out the [Roadmap](./Roadmap.md) if you are interested in seeing what is coming up next!


#### Plugin Support and Contributing

If you are wanting to Submit a Bug or Feature Request, you can do so via the [WordPress.Org Plugin Support Forum](https://wordpress.org/support/plugin/radio-station/), but we would prefer you submit a more detailed issue via [GitHub Issues](https://github.com/netmix/radio-station/issues) where we track and prioritize these using GitHub Projects.

Similarly, you can Contribute directly to the plugin via submitting an Issue or Pull Request on the [Github Plugin Repository](https://github.com/netmix/radio-station/). Or if you would prefer to get involved in the plugin's development even more substantially, please [Contact Us via Email](mailto:info@netmix.com) and let us know what you would like to do.

