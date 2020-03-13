Radio Station Plugin API

***

Since 2.3.0, Radio Station includes an API for all of your Station data.

## Data Endpoints

Data Endpoints for Schedule and Show Data are available via WordPress REST API Routes (if option enabled.)
Alternatively, they are also available via WordPress Feed Links (again if that option is enabled.)  
Note the default output format for all data endpoints is JSON.

#### Station Data

All Endpoints add the following top level Station data to the output: 

* *timezone*: Radio Timezone (via Plugin Settings)
* *stream_url*: Streaming URL (via Plugin Settings)
* *station_url*: Station Home URL (via `site_url()`)
* *schedule_url*: Schedule Page URL (via Plugin Settings)
* *language*: Main Station Language Code (eg. en_US)
* *timestamp*: UTC Generated Timestamp for the Request
* *date_time*: Generated Date Time Stamp (YY-mm-dd H:i:s)
* *endpoints*: List of data endpoints and URLs (array) Note this list will be REST API Routes or Feed URLs depending on which is used to make the request.
* *success*: set to true (1) on request success and false (0) on failure

#### Show Data

Each `[Show]` data array referenced in the below Endpoints has the following keys:

* *id*: Show ID
* *name*: Show Title
* *slug*: Show Slug
* *url*: Show Permalink URL
* *latest*: Latest Audio URL
* *website*: Show Website URL
* *hosts*: Array of Show Hosts (display *name* and profile *url*)
* *producers*: Array of Show Producers (display *name* and profile *url*)
* *genres*: Array of Show Genres 
* *languages*: Array of Show Languages


### Endpoint List

#### Radio Discovery Endpoint

**/wp-json/radio/** and/or **/feed/radio/**

This is the base discovery Endpoint for all the other Endpoints. If REST URL is used, this will list the REST Routes, and if Feed URL is used this will list the Feed Endpoints.

* *namespace*: The base namespace for all Endpoints (default is */radio*)
* *routes*: Data array of all the available Endpoints URLs




#### Station Endpoint

**/wp-json/radio/station/** and/or **/feed/radio/station/**

An Endpoint for retrieving *ALL* available Station data at once.

* *station*: Data array containing the Data from every other Endpoint below.

The Station data has the following keys, each containing the data for that Endpoint:

* *broadcast*: see Broadcast Endpoint
* *schedule*: see Schedule Endpoint
* *shows*: see Shows Endpoint
* *genres*: see Genres Endpoint
* *languages*: see Languages Endpoint

#### Broadcast Endpoint

**/wp-json/radio/broadcast/** and/or **/feed/radio/broadcast/**

Lists the Current Show and Next Scheduled Show.

* *current_show*: Currently Scheduled `[Show]` Details
* *next_show*: Next Scheduled `[Show]` Details

#### Schedule Endpoint

**/wp-json/radio/schedule/** and/or **/feed/radio/schedule/**

* *schedule*: Data array of weekly Show Schedule by Day (ordered by Shift Times)

Each Scheduled Shift has the following keys:

* *day*: Scheduled Shift Day
* *start*: Scheduled Shift Start Time
* *end*: Scheduled Show End Time
* *updated*: Last Updated Timestamp for the Show
* *encore*: Whether the Scheduled Shift is an encore (repeat) airing. 0 or 1
* *split*: Whether the Scheduled Shift is split overnight. 0 or 1
* *override*: Whether the Shift is a Schedule Override. 0 or 1
* *show*: Data array of `[Show]` Details for this Shift

#### Shows Endpoint

**/wp-json/radio/shows/** and/or **/feed/radio/shows/**

Optionally, a single or comma-separated `show` argument will retrieve specific Show information.

* *shows*: Data array of all `[Shows]` (ordered by Show Name)

#### Genres Endpoint

**/wp-json/radio/genres/** and/or **/feed/radio/genres/**

Optionally, a single or comma-separated `genre` argument will retrieve specific Genre information.

* *genres*: Data array of all Genres with all the `[Shows]` for each Genre.

#### Languages Endpoint

**/wp-json/radio/languages/** and/or **/feed/radio/languages/**

Optionally, a single or comma-separated `language` argument will retrieve specific Language information.

* *languages*: Data array of all Languages with all the `[Shows]` for each Language.
