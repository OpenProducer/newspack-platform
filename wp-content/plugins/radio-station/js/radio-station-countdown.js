/* ------------------------------ */
/* Radio Station Countdown Script */
/* ------------------------------ */

/* Countdown Loop */
function radio_countdown() {

    radio.time.current = Math.floor( (new Date()).getTime() / 1000 );
	radio.time.user = radio.time.current - radio.timezone.useroffset;
	radio.time.server = radio.time.current + radio.timezone.offset;

	if (radio.clock_debug) {
		console.log('Current Time: ' + (new Date()).toISOString() + '(' + radio.time.current + ')');
		console.log('User Offset: ' + radio.timezone.useroffset + ' - Server Offset: ' + radio.timezone.offset);
		userdatetime = new Date(radio.time.user * 1000);
		serverdatetime = new Date(radio.time.server * 1000);
		console.log('User Time: ' + userdatetime.toISOString() + '(' + userdatetime.getDate() + ')');
		console.log('Server Time: '+ serverdatetime.toISOString() + '(' + serverdatetime.getDate() + ')');
	}

    /* Current Show Countdown */
    jQuery('.current-show-list.countdown .current-show-end').each(function() {
    	showendtime = parseInt(jQuery(this).val());
        diff = showendtime - radio.time.current;
        if (radio.clock_debug) {
        	showend = new Date(showendtime * 1000);
        	console.log('Show End: ' + showendtime + ' : ' + showend.toISOString() + '(' + showend.getTime() + ')');
        	console.log('Current Show Ends in: '+diff+'s');
        }
        if (diff < 1) {countdown = radio.labels.showended; jQuery(this).removeClass('current-show-end');}
        else {countdown = radio_countdown_display(diff, radio.labels.timeremaining);}
        jQuery(this).closest('.current-show-list').find('.rs-countdown').html(countdown);
    });

    /* Upcoming Show Countdown */
    jQuery('.upcoming-shows-list.countdown .upcoming-show-times').each(function() {
        times = jQuery(this).val().split('-');
        times[0] = parseInt(times[0]); diffa = times[0] - radio.time.current;
        times[1] = parseInt(times[1]); diffb = times[1] - radio.time.current;         
        if (radio.clock_debug) {
        	nextstart = new Date( times[0] * 1000 ); nextend = new Date( times[1] * 1000 );
            console.log('Next Show Start: ' + nextstart.toISOString() + '(' + nextstart.getTime() + ')');
            console.log('Next Show End: ' + nextend.toISOString() + '(' + nextend.getTime() + ')');
            console.log('Next Show Start in: '+diffa+'s - Next Show End in:'+diffb+'s');
        }
        if (diffa < 1) {
            if (diffb < 1) {countdown = radio.labels.showended; jQuery(this).removeClass('upcoming-show-times');}
            else {countdown = radio.labels.showstarted;}
        } else {countdown = radio_countdown_display(diffa, radio.labels.timecommencing);}
        jQuery(this).closest('.upcoming-shows-list').find('.rs-countdown').html(countdown);
    });

    /* Current Playlist Countdown */
    jQuery('current-playlist.countdown .current-playlist-end').each(function() {
        diff = parseInt(jQuery(this).val()) - radio.time.current;
        if (radio.clock_debug) {console.log('Current Playlist Ends in: '+diff);}
        if (diff < 1) {countdown = radio.labels.playlistended; jQuery(this).removeClass('current-playlist-end');}
        else {countdown = radio_countdown_display(diff, radio.labels.timeremaining);}
        jQuery(this).closest('.current-playlist').find('.rs-countdown').html(countdown);
    });
    
    /* Continue Countdown */
    if ( jQuery('.current-show-list.countdown .current-show-end')
		|| jQuery('.upcoming-shows-list.countdown .upcoming-show-times')
		|| jQuery('.current-playlist.countdown .current-playlist-end') ) {
        setTimeout('radio_countdown();', 1000);
    }
}

/* Get Countdown Display */
function radio_countdown_display(diff, label) {
	hours = Math.floor( diff / 3600 );
	if (hours > 0) {diff = diff - (hours * 3600);}
	minutes = Math.floor( diff / 60 );
	if (minutes > 0) {diff = diff - (minutes * 60);}
	if (minutes < 10) {minutes = '0'+minutes;}
	seconds = diff;
	if (seconds < 10) {seconds = '0'+seconds;}
    display = '<span class="rs-label">'+label+'</span>: <span class="rs-hours">'+hours+'</span><span class="rs-sep rs-time-sep">'+radio.sep+'</span>';
    display += '<span class="rs-minutes">'+minutes+'</span><span class="rs-sep rs-time-sep">'+radio.sep+'</span><span class="rs-seconds">'+seconds+'</span>';
    return display;
}

/* Start Countdown on Pageload */
jQuery(document).ready(function() {
    radio_countdown();
});