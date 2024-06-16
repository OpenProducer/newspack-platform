/* ------------------------- */
/* Radio Station Page Script */
/* ------------------------- */

/* Get Page Type */
function radio_page_type() {
	if (!document.getElementById('radio-page-type')) {return 'show';}
	return document.getElementById('radio-page-type').value;
}

/* Show/Hide Audio Player */
function radio_show_player() {
	prefix = radio_page_type();
    if (typeof jQuery == 'function') {jQuery('#'+prefix+'-player').fadeIn(1000);}
    else {document.getElementById(prefix+'-player').style.display = 'block';}
}

/* Switch Section Tabs */
function radio_show_tab(prefix,tab) {
	/* prefix = radio_page_type(); */
	if ( (typeof jQuery == 'function') && jQuery('#'+prefix+'-'+tab+'-tab') ) {
		jQuery('.'+prefix+'-tab').removeClass('tab-active').addClass('tab-inactive');
		jQuery('.'+prefix+'-section').removeClass('tab-active').addClass('tab-inactive');
		jQuery('#'+prefix+'-'+tab+'-tab').removeClass('tab-inactive').addClass('tab-active');
		jQuery('#'+prefix+'-'+tab).removeClass('tab-inactive').addClass('tab-active');
	} else if (document.getElementById(prefix+'-'+tab+'-tab')) {
		tabs = document.getElementsByClassName(prefix+'-tab');
		for (i = 0; i < tabs.length; i++) {
			tabs[i].className = tabs[i].className.replace('-tab-active', '-tab-inactive');
		}
		sections = document.getElementsByClassName(prefix+'-section');
		for (i = 0; i < sections.length; i++) {
			sections[i].className = sections[i].className.replace('-tab-active', '-tab-inactive');
		}
		button = document.getElementById(prefix+'-'+tab+'-tab');
		button.className = button.className.replace('-tab-inactive', '-tab-active');
		content = document.getElementById(prefix+'-'+tab);
		content.className = content.className.replace('-tab-inactive', '-tab-active');
	}
}

/* Responsive Page */
function radio_page_responsive() {
	prefix = radio_page_type();
	if (!document.getElementById(prefix+'-content')) {return;}

    /* Check to Add Narrow Class */
    if (typeof jQuery == 'function') {
        content = jQuery('#'+prefix+'-content');
        if (content.width() < 500) {content.addClass('narrow');}
        else {content.removeClass('narrow');}
    } else {
        content = document.getElementById(prefix+'-content');
        if (content.offsetWidth < 500) {content.classList.add('narrow');}
        else {content.classList.remove('narrow');}
    }

    /* Maybe Match Heights for Info and Description */
	if (document.getElementById(prefix+'-content').className.indexOf('top-blocks') < 0) {
		info = document.getElementById(prefix+'-info');
		desc = document.getElementById(prefix+'-description');
		about = document.getElementById(prefix+'-section-about');
		if (info && desc) {
			descheight = info.offsetHeight - 30;
			if (about) {descheight = descheight - about.offsetHeight;}
			if (descheight > 30) {
				if (descheight < desc.style.minHeight) {desc.style.maxHeight = '';}
				else {desc.style.maxHeight = descheight+'px';}
			} else {desc.style.maxHeight = '';}
		}
	}

    /* Maybe Display Show More Button */
    descstate = document.getElementById('show-desc-state');
    if ( descstate && (descstate.value != 'expanded') ) {
        desc = document.getElementsByClassName(prefix+'-description')[0];
        if (desc.offsetHeight < desc.scrollHeight) {
            document.getElementById('show-more-overlay').style.display = 'block';
            document.getElementById('show-desc-buttons').style.display = 'block';
            desc.style.paddingBottom = '0';
        } else {
            document.getElementById('show-more-overlay').style.display = 'none';
            document.getElementById('show-desc-buttons').style.display = 'none';
            desc.style.paddingBottom = '30px';
        }
    }
}

/* Description Show More/Less */
function radio_show_desc(moreless) {
	prefix = radio_page_type();
    if (moreless == 'more') {
        if (typeof jQuery == 'function') {jQuery('#'+prefix+'-description').addClass('expanded');}
        else {document.getElementById(prefix+'-description').classList.add('expanded');}
        document.getElementById('show-more-overlay').style.display = 'none';
        document.getElementById('show-desc-more').style.display = 'none';
        document.getElementById('show-desc-less').style.display = 'inline-block';
    }
    if (moreless == 'less') {
        if (typeof jQuery == 'function') {jQuery('#'+prefix+'-description').removeClass('expanded');}
        else {document.getElementById(prefix+'-description').classList.remove('expanded');}
        document.getElementById('show-more-overlay').style.display = 'block';
        document.getElementById('show-desc-less').style.display = 'none';
        document.getElementById('show-desc-more').style.display = '';
        radio_scroll_to(prefix+'-section-about');
    }
}

/* Section Scroll Link */
function radio_scroll_link(id) {
	prefix = radio_page_type();
    if (typeof jQuery == 'function') {
        section = jQuery('#'+prefix+'-section-'+id);
        scrolltop = section.offset().top - section.height() - 40;
        jQuery('html, body').animate({ 'scrollTop': scrolltop }, 1000);
    } else {
        radio_scroll_to(prefix+'-section-'+id);
    }
}

/* Responsive Load and Resizing */
if (typeof jQuery == 'function') {
    jQuery(document).ready(function() {
		prefix = radio_page_type();
		radio_page_responsive();
		jQuery(window).resize(function () {
			radio_resize_debounce(radio_page_responsive, 500, prefix+'page');
		});
	} );
} else {
    if (window.addEventListener) {
        document.body[addEventListener]('load', radio_page_responsive, false);
        document.body[addEventListener]('resize', radio_page_responsive, false);
    } else {
        document.body[attachEvent]('onload', radio_page_responsive, false);
        document.body[attachEvent]('onresize', radio_page_responsive, false);
    }
}
