# Radio Station Plugin FAQ

***

### How do I get started with Radio Station?

Read the [Quickstart Guide](./index.md#quickstart-guide) for an introduction to the plugin, what features are available and how to set them up.

### Where can I find the full plugin documentation?

The latest documentation can be found online at [NetMix.com](https://netmix.com/radio-station/docs/). Documentation is also included with for the currently installed version via the Radio Station Help menu. You can find the Markdown-formatted files in the `/docs` folder of the [GitHub Repository](https://github.com/netmix/radio-station/docs/) and in the `/docs` folder of the plugin directory. 

### How do I schedule a Show? 

Simply create a new show via Add Show in the Radio Station plugin menu in the Admin area. You will be able to assign Shift timeslots to it on the Show edit page, as well as add the Show description and other meta fields, including Show images.

### How do I display a full schedule of my Station's shows? 

In the Plugin Settings, you can select a Page on which to automatically display the schedule as well as which View to display (a Table grid by default.) Alternatively, you can use the shortcode `[master-schedule]` on any page (or post.) This option allows you to use further shortcode attributes to control the what is displayed in the Schedule (see [Master Schedule Shortcode Docs](./Shortcodes.md#master-schedule-shortcode) )

### I've scheduled all my Shows, but some are not showing up on the program schedule?

Did you remember to check the "Active" checkbox for each Show? If a Show is not marked active, the plugin assumes that it's not currently in production and it is not shown on the Schedule. A Show will also not be shown if it has no active Shifts assigned to it.

### What if I want to schedule a special event?

If you have a one-off event that you need to show up in the Schedule and Widgets, you can create a Schedule Override by clicking the Schedule Override tab in the Admin menu. This will allow you to set aside a block of time on a specific date, and when the Schedule or Widget is displaying that date, the override will be used instead of the normally scheduled Show. (Note that Schedule Overrides will not display in the old Legacy Table or List Views of the Master Schedule.)

### I'm seeing 404 Not Found errors when I click on the link for a Show! 

Try re-saving your site's permalink settings via Settings -> Permalinks.  Wordpress sometimes gets confused with a new custom post type is added. Permalink rewrites are automatically flushed on plugin activation, so you can also just deactivate and reactivate the plugin.

### What if I want to change or style the plugin's displays? 

The default styles for Radio Station have intionally kept fairly minimal so as to be compatible with most themes, so you may wish to add your own styles to suit your site's look and feel. The best way to do this is to add your own `rs-custom.css` to your Child Theme's directory, and add more specific style rules that modify or override the existing styles. Radio Station will automatically detect the presence of this file and enqueue it. You can find the base styles in the `/css/` directory of the plugin.

### What Widgets are available with this plugin?

The following Widgets are available to add via the WordPress Appearance -> Widgets page:
Current Show, Upcoming Shows, Current Playlist. Radio Clock and Streaming Player Widgets will also be available in future versions. See the [Widget Documentation](./Widgets.md) for more details on these Widgets.

### What Shortcodes are available with this plugin?

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

### I need users other than just the Administrator and DJ roles to have access to the Shows and Playlists post types. How do I do that? 

There are a number of different options depending on what you are wanting to to do. You can find more information on these in the [Roles Documentation](./Roles.md)

### How do I change the Show Avatar displayed in the sidebar widget? 

The avatar is whatever image is assigned as the Show's Avatar.  All you have to do is set a new Show Avatar on the Edit page for that Show.

### Why don't any users show up in the Hosts or Producers list on the Show edit page? 

You did remember to assign the Host or Producer role to the users you want, right?

### My Show Hosts and Producers can't edit a Show page.  What do I do? 

The only Hosts and Producers that can edit a show are the ones listed as being Hosts or Producers for that Show in the respective user selection menus. This is to prevent Hosts/Producers from editing other Host/Producer's Shows without permission.

### I don't want to use Gravatar for my Host/Producer's image on their profile page. 

Then you'll need to install a plugin that lets you add a different image to your Host/Producer's user account and edit your author.php theme file accordingly.  That's a little out of the scope of this plugin. I recommend [Cimy User Extra Fields](http://wordpress.org/extend/plugins/cimy-user-extra-fields/)

### What languages other than English is the plugin available in? 

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

### Can the plugin be translated into my language? 

You may translate the plugin into another language. Please visit our [WordPress Translate project page](https://translate.wordpress.org/locale/en-gb/default/wp-plugins/radio-station/) for this plugin for further instruction. The `radio-station.pot` file is located in the `/languages` directory of the plugin. Please send the finished translation to `info@netmix.com`. We'd love to include it.
