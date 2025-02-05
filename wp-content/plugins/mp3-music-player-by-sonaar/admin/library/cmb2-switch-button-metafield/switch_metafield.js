window.CMB2 = (function(window, document, $, undefined){
	'use strict';
	$(".cmb2-enable").click(function(){
        var parent = $(this).parents('.cmb2-switch');
        $('.cmb2-disable',parent).removeClass('selected');
        $(this).addClass('selected');        
    });
    $(".cmb2-disable").click(function(){
        var parent = $(this).parents('.cmb2-switch');
        $('.cmb2-enable',parent).removeClass('selected');
        $(this).addClass('selected');
    });
})(window, document, jQuery);
