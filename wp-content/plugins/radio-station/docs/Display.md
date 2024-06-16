# Radio Station Plugin Displays

***

For live display examples see [Radio Station Demo Site](https://demo.radiostation.pro).

## Styling

#### Default Styles

You can find the base styles in the `/css` directory of the plugin, prefixed with `rs-`. There is a CSS file for Schedule Views, Shortcodes (also used for Widgets) and Page Templates. The default styles have intionally kept fairly minimal so as to be compatible with most themes.

#### Custom Styling

You may wish to add your own styles to suit your site's look and feel. One easy way to do this is to add your own `rs-custom.css` to your Child Theme's directory, and add more specific style rules that modify or override the existing styles. Radio Station will automatically detect the presence of this file and enqueue it. This is preferable to modifying the base style files directly, as your changes will be overwritten in a plugin update.


## Timezone Conversions

#### Automatic Display

Radio Station will now display a shift time converted to the users timezone under every shift time displayed. This conversion is based on the selected Timezone in your plugin settings and the default timezone detected in the users browser. This feature is on by default, but can also be disabled in the Plugin Settings.

#### [Pro] User Timezone Switching

In [Radio Station Pro](https://radiostation.pro), there is an additional interface displayed in the Clock/Timezone shortcode (automatically displayed above Schedules and also in the Radio Clock widget.) This allows your visitors/listeners to select a specific timezone other than what is detected in their browser. This can be useful for travellers especially, for keeping up with their home Shows, or tuning into a new local station without needing to update their computer clock.


## Automatic Pages

### Master Schedule Page

Since one of the main purposes of this plugin is to provide your listeners with a useable Schedule for your Shows, having the ability to easily assign a page and display a selected schedule View on it is a natural option to have. To do this, simply create a new WordPress Page and title it as desired, then go to the Radio Station Plugin Settings page and select that Page for this Option and Save. This will also make your Schedule page discoverable via the plugin's Data Endpoints.

The following Views are available for the Master Schedule:

* Table - [default] responsive program in table form
* Tabbed - responsive styled list view with day selection tabs
* List - unstyled plain list view for custom development use
* Divs - [display issues] legacy unstyled div based view 
* Legacy - [deprecated] legacy table grid view 

Table or Tabbed are recommended for the best ready display result. By enabling the "Automatic Display" option for this page, the selected Master Schedule View  will automatically replace the content of that page, using with default attributes. If you want to use other display options to customize what displays in the Schedule - or wish to combine it with other content on that page - then disable the Automatic Display option and instead manually place the [Master Schedule Shortcode](./Shortcodes.md#master-schedule-shortcode) `[master-schedule]` in the page content instead.

#### [Pro] Extra Master Schedule Views

In [Radio Station Pro](https://radiostation.pro), two additional schedule views are available for displaying your station schedule:

* Grid - vertically stacked responsive show grid based on days
* Calendar - responsive weekly schedule view with horizontal day show slider

You can set these are the default view in the settings, or display them via the master schedule shortcode. If using the Grid view there is also an extra option to line up the grid according to the show time slots.

### Archive Pages

Since Shows, Playlists and Genre Archives are automatically generated via the WordPress archive template specific to a Theme, this makes modifying them consistently an impossible task. Instead, the option has been creating to assign Archive Pages for these that can then use plugin shortcodes to display these Archives in a different and more meaningful way. Of course doing this is entirely optional, and not always a priority, but at least the option is there for when it is needed. 


## Page Templates

**If you are have upgraded to 2.3.0, it is recommended you read this section carefully in case you need to take action!** (New users to the plugin on the other hand probably do not need to know these details unless wanting to customize something with the way the templates operate.)

Since 2.3.0, Radio Station now uses a content filter method instead of single page templates to display the content for it's Post Types. This is because the page templates used previously were based on the default twenty-something series of WordPress themes, and so unfortunately these templates do not always play well with other themes. So to become theme-agnostic - to have a stable display output independent of the active theme - the content filtering method is now used instead. 

#### Page Template Selection

In order to do this though, a standard page template (such as `page.php`, `single.php` or `singular.php`) still needs to be used, and the content is displayed within it. You can choose which template to use for a Post Type in the Plugin Options page. The main reason this option has been made available is that it is just not possible to determine whether a Theme displays the Featured Image for a Show in the standard template or not. So having this as an option solves that problem. See [Images](#show-images) below for more details on this.

#### WordPress Template Hierarchy

Obviously, Radio Station still has to honour the WordPress Template Hierarchy. So for example, if you create your own custom `single-show.php` template for Shows and place it in your Child Theme, as expected it will be used instead of the standard page template selected. Additionally, to ensure this override really takes precedence, it should be noted that the content will *not* be filtered by default when a custom template is found and used (read on...)

#### Important Note on Legacy Templates!

What this means is also that *if you have previously copied a legacy template into your Child Theme, you will need to take action to see the updated display*. And since it is indicated you might want to do this previous version's documentation, it is quite possible that you have! In this case, there are two main options. If you have already customized a template to match your Theme, you can enable the "Combined Method" for that template on the Plugin Settings page, which will then use both the page template and the content filtering method together.  

If you have *not* customized templates to match your Theme, and yet they are present, then it is recommended that you *remove the old templates from your Child Theme entirely*. This will allow the plugin to work via the new content filter method with the standard template within your theme. The Legacy templates names that you should look for and remove are: `single-show.php`, `single-playlist.php`, `archive-playlist.php`, `playlist-archive-template.php`, `show-blog-archive-template.php`

We recognize this is may be a rather confusing and complex situation for existing users transitioning to 2.3.0, and if this is the case suggest rereading these section until it becomes clear. We are however confident that our solution going forward here is the best one, as it maintains backwards compatibility, honours the WordPress Template Hierarchy and enables implementing the revamped content displays. Fortunately, the majority of users will not be affected by these changes at all, but if you are the above options should easily resolve this.

### Show Page Content

The Show Page Content display has undergone the biggest of changes so far within the plugin, with an entirely new responsive layout for Shows that is displayed within the content area. This has required  a lot of thought to allow for in-built flexibility, because it has to account for variations of what information is supplied for a Show and what is not. In other words, whether it is bare or full it, still needs to look good! Note also that there are some Plugin Options relating to this layout, namely where the info blocks are floated, and whether the content sections below are tabbed or display in full in a series.

### Override Page Content

Previous to 2.3.0, Schedule Overrides did not have their own page display enabled. We decided to change this, so in most ways they are now closer to Shows and handled in the same way. The main difference being that they have a single specific date and time block for the scheduled override timeslot instead of Shifts. 

### Playlist Page Content

Currently this is a simple table of Tracks for the specified Playlist, taken from the Legacy template. This will receive further attention in the future to allow for a more customizable display.


## Images

### Show Images

Since 2.3.0, Featured Images and Show Avatars have been split into two different images. This was done so the Featured Image could be treated in a more standard way like in a WordPress post or page - as the wide image at the top of a post. Whereas, a Show Avatar is more likely to be smaller and square like a logo. Note than existing Show Featured Images prior to 2.3.0 are automatically transferred to Show Avatars as that was their only usage in previous versions, allowing for the new dual usage, so there is no need manually make any changes to those. 

#### Show Featured Image

A Show Featured Image is intended to display at the top of a single Show Page, just as it might on a standard post or page. However, depending on the page template you are using for Shows, it *might not* automatically display there if the template does not include it. This is one reason why there is an option to choose between the `page.php`, `single.php` and `singular.php` templates for the Show Page Template on the Plugin Settings Page. And if for some reason your chosen template does not display the Featured Image, you can use the Show Content Header option below.

#### Show Avatar

Primarily displayed on the single Show Page content layout in the first information box. Also may be displayed in the Current Show and Upcoming Show Widgets when that widget option is checked (or in the corresponding Widget Shortcodes) - with an optional display width option. There are also contextual filters available throughout the plugin which enable adjusting the image size of the Show Avatar display. Show Avatars also display (by default) in the Show Archive Shortcode and Genre Archive Shortcode. They can also be shown in the Master Schedule Shortcode if desired (the default there is to display for the Tabbed and List Views only.)

#### Show Content Header

If your chosen Show template (eg. `page.php`) does not display the Featured Image above the Show title automatically, you can enable this option via Plugin Settings Page. This will provide an additional Content Header Image selection on the Show edit screen image box (below the Show Avatar.) Adding this image to a Show will mean it is displayed as a Header on the Show page - the only difference being it will be below the Show title and not above it like a Featured Image would.

### Schedule Override Images

Since 2.3.0, image support has been added to Schedule Overrides so that they behave just like Shows in this respect. So the above sections on Show Images also now apply to Schedule Overrides.

### [Pro] Profile Images

In [Radio Station Pro](https://radiostation.pro), Show Host and Producer Profiles also support having images added, which then display on the public profile pages for the Host or Producer.

### [Pro] Episode Images

In [Radio Station Pro](https://radiostation.pro), Episodes also support having images added, which then display on the public page for that Show Episode.

### [Pro] Genre Images and Colors

In [Radio Station Pro](https://radiostation.pro), images and colors can also be assigned to Genre taxonomy terms via the WordPress Admin Taxonomy Editing interface. Images may then optionally be displayed in the [Genre Archive Shortcode](./Shortcodes.md#genre-archives-shortcode) to provide another visual level to that Show list display. Genre colors are used in the Master Schedule display when the user highlights particular Genres.


## Translations

#### Using WordPress Translations

Please note that the "Main Broadcast Language" Setting on the plugin page does NOT translate any strings, it is for assigning the default Language for Shows, which is displayed to visitors on the Show page etc. See the [Language Taxonomy](./Data.md) for more details.

Radio Station is fully translation ready. This means all the text strings within the plugin are wrapped in translation functions so that WordPress can handle the automatic translation of these strings. If your site language is set through WordPress Admin -> Settings page (under Site Language) then you will see the translated string output changed in your selected language - but only for any strings where a translation exists.

This is because some strings have never been translated into certain languages, but also because some have been translated in the past and some added to newer versions have not been translated yet. You can add to the existing translations in any Language via the [Radio Station Translation Project on WordPress.Org](https://translate.wordpress.org/projects/wp-plugins/radio-station/) By logging in to WordPress.org you can now add these string translations directly, and they will be used by the WordPress to translate the plugin.

#### Time Related Translations

Unlike other word strings, those involving dates and times are automatically translated by the plugin into your WordPress language using the `WP_Locale` class. This means you do not have to retranslate common strings for days of the week, month names, or am/pm meridian strings.

#### Using a Translation Plugin

If you wish to use a translation plugin instead, you can find the relevant language filed in the `/languages/` directory of the plugin. Follow the translation plugin documentation on how to use these files. We welcome submissions of updated language files. Please contact us via email or submit a pull request via the [Github repository](https://github.com/netmix/radio-station/).





