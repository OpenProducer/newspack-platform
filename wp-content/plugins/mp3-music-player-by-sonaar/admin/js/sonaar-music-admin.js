var app = new Vue({
  el: '#sonaar_music',
})
// Define a function to import the selected template
jQuery(document).ready(function($) {
    var options = {
        valueNames: [ 'srp-tmpl-title' ],
        listClass: 'template-list',
        searchClass: 'srp_search',
      };
    
    srpTemplatesSearch =  new List('srp_templates_container', options);

    // Attach a click event handler to the import button
    $('.srmp3_import_overlay').click(function(e){
        e.preventDefault();
        var elt = jQuery(this);
        var please_wait = elt.parent().find('.srmp3_importing');
        elt.css('background-color', '#00000057');
        $('.srmp3_import_notice').hide();
        please_wait.show();
        var json_file = $(this).data('filename');
		var data = {
			action: 'import_srmp3_template',
			filename: json_file
		};

        $.post(
            ajaxurl, 
            data, 
            function(response) {
                var obj;
    
                obj = $.parseJSON(response);
                elt.show();
                please_wait.hide();
                $("html, body").animate({ scrollTop: 0 }, "slow");
                console.log(obj);
                if(obj.success === true) { 
                    $('.srmp3_import_success').show();
                } else {
                    $('.srmp3_import_error_message').remove();
                    $('.srmp3_import_failed').append('<div class="srmp3_import_error_message">' + obj.message + '</div>');
                    $('.srmp3_import_failed').show();
                }
            });

    });

});