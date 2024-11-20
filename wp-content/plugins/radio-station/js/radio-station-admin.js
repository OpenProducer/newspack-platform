/* --------------------------- */
/* Radio Station Admin Scripts */
/* --------------------------- */
/* note: admin scripts are currently enqueued using wp_add_inline_script */
/* this file is necessary to ensure they are printed in the right place */

var radio_admin; radio_admin = {debug:false};

/* Cookie Value Function */
/* since @2.3.2 */
radio_cookie = {
	set: function (name, value, days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
			var expires = '; expires=' + date.toUTCString();
		} else {var expires = '';}
		document.cookie = 'radio_' + name + '=' + JSON.stringify(value) + expires + '; path=/';
	},
	get : function(name) {
		var nameeq = 'radio_' + name + '=', ca = document.cookie.split(';');
		for(var i=0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1,c.length);
				if (c.indexOf(nameeq) == 0) {
					return JSON.parse(c.substring(nameeq.length, c.length));
				}
			}
		}
		return null;
	},
	delete : function(name) {
		setCookie('radio_' + name, "", -1);
	}
}

/* Debounce Delay Callback */
var radio_resize_debounce = (function () {
	var debounce_timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) {uniqueId = "nonuniqueid";}
		if (debounce_timers[uniqueId]) {clearTimeout (debounce_timers[uniqueId]);}
		debounce_timers[uniqueId] = setTimeout(callback, ms);
	};
})();