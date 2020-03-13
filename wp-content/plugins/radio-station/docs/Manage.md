Radio Station Plugin Management

***

## Admin Lists

Shows, Overrides and Playlists each have their own standard list page via the WordPress admin area, which you can find via the Radio Station admin menu. Each list has relevent columns added so you can see more information about each record at a glance without having to Edit an individual page to see them, including Show Shifts, Show Override Dates, and Playlist Tracks. Also displayed are Show Hosts, Genres and Language terms, and the Show Avatar.

## Shift Editing

You can add Show Shifts from the Show's Edit page. You can click Add Shift as many times as you like to create new Shift entries. For a Shift to be valid it must have all time fields filled in. The Encore field (repeat airing) is optional. You can disable a Shift and it will be unused but still saved. Each Shift entry also has a Duplicate and Remove icon to copy that entry to a new Shift or remove it. Shifts are not altered until you save them via clicking Update for a Show.

## Shift Conflict Checking

### Shift Save Checking

Since 2.3.0, when Shifts are saved on the Show Edit page, they are checked against all other Show's (active) Shifts - and then against other Shifts for the same show - to detect for Shift Conflicts. If a conflict is found, that Shift is disabled and the specific conflict is displayed underneath the Shift after saving. Once the time is altered (or altered on the conflicting Show) so there is no conflict, you can then uncheck the Disabled box and the Shift will then be active.

### Existing Schedule Conflicts

Since shift save checking was introduced in 2.3.0, you might already have existing schedule conflicts without realizing it. Radio Station attempts to handle existing conflicts in three ways:

* An Admin Notice is generated displaying the Shift Conflicts, linked to their Shows.
* Show Shift Conflicts are highlighted in red in the Shift column on the Admin Shows list page.
* The Master Schedule display does it's best to check for conflicts and handle the overlaps.