/* ------------------------------ */
/* Radio Station Countdown Script */
/* ------------------------------ */

/* Countdown Loop */
function radio_countdown() {

    radio.current_time = Math.floor( (new Date()).getTime() / 1000 );
	radio.user_time = radio.current_time + radio.user_offset;
	radio.server_time = radio.current_time + radio.timezone_offset;
	if (radio.debug) {
		console.log('Current Time: ' + (new Date()).toISOString() + '(' + radio.current_time + ')');
		console.log('User Offset: ' + radio.user_offset + ' - Server Offset: ' + radio.timezone_offset);
		userdatetime = new Date(radio.user_time * 1000);
		serverdatetime = new Date(radio.server_time * 1000);
		console.log('User Time: ' + userdatetime.toISOString() + '(' + userdatetime.getDate() + ')');
		console.log('Server Time: '+ serverdatetime.toISOString() + '(' + serverdatetime.getDate() + ')');
	}

    /* Current Show Countdown */
    jQuery('.current-show-end').each(function() {
    	showendtime = parseInt(jQuery(this).val());
        diff =  showendtime - radio.server_time;
        if (radio.debug) {
        	showend = new Date(showendtime * 1000);
        	console.log('Show End: ' + showend.toISOString() + '(' + showend.getTime() + ')');
        	console.log('Current Show Ends in: '+diff+'s');
        }
        if (diff < 1) {countdown = radio.label_showended; jQuery(this).removeClass('current-show-end');}
        else {countdown = radio_countdown_display(diff, radio.label_timeremaining);}
        jQuery(this).parent().find('.rs-countdown').html(countdown);
    });

    /* Upcoming Show Countdown */
    jQuery('.upcoming-show-times').each(function() {
        times = jQuery(this).val().split('-');
        times[0] = parseInt(times[0]); diffa = times[0] - radio.server_time;
        times[1] = parseInt(times[1]); diffb = times[1] - radio.server_time;         
        if (radio.debug) {
        	nextstart = new Date( times[0] * 1000 ); nextend = new Date( times[1] * 1000 );
            console.log('Next Show Start: ' + nextstart.toISOString() + '(' + nextstart.getTime() + ')');
            console.log('Next Show End: ' + nextend.toISOString() + '(' + nextend.getTime() + ')');
            console.log('Next Show Start in: '+diffa+'s - Next Show End in:'+diffb+'s');
        }
        if (diffa < 1) {
            if (diffb < 1) {countdown = radio.label_showended; jQuery(this).removeClass('upcoming-show-times');}
            else {countdown = radio.label_showstarted;}
        } else {countdown = radio_countdown_display(diffa, radio.label_timecommencing);}
        jQuery(this).parent().find('.rs-countdown').html(countdown);
    });

    /* Current Playlist Countdown */
    jQuery('.current-playlist-end').each(function() {
        diff = parseInt(jQuery(this).val()) - radio.server_time;
        if (radio.debug) {console.log('Current Playlist Ends in: '+diff);}
        if (diff < 1) {countdown = playlistended; jQuery(this).removeClass('current-playlist-end');}
        else {countdown = radio_countdown_display(diff, radio.label_timeremaining);}
        jQuery(this).parent().find('.rs-countdown').html(countdown);
    });
    
    /* Continue Countdown */
    if ( jQuery('.current-show-end') || jQuery('.upcoming-show-times') || jQuery('.current-playlist-end') ) {
        setTimeout('radio_countdown();', 1000);
    }
}

/* Get Countdown Display */
function radio_countdown_display(diff, label) {
    countdown = new Date(diff * 1000).toISOString();
    hours = countdown.substr(11, 2);
    if (hours.substr(0,1) == '0') {hours = hours.substr(1,1);}
    minutes = countdown.substr(14, 2);
    seconds = countdown.substr(17, 2);
    display = '<span class="rs-label">'+label+'</span>: <span class="rs-hours">'+hours+'</span><span "rs-separator">:</span>';
    display += '<span class="rs-minutes">'+minutes+'</span><span class="rs-separator">:</span><span class="rs-seconds">'+seconds+'</span>';
    return display;
}

/* Start Countdown on Pageload */
jQuery(document).ready(function() {
    radio_countdown();
});