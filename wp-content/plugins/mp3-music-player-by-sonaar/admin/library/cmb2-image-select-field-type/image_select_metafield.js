window.CMB2 = (function(window, document, $, undefined){
	'use strict';
	$('ul.cmb2-image-select-list li input[type="radio"]').click(
	function(e) {
	    e.stopPropagation(); // stop the click from bubbling
	    $(this).closest('ul').find('.cmb2-image-select-selected').removeClass('cmb2-image-select-selected');
	    $(this).parent().closest('li').addClass('cmb2-image-select-selected');
	});	
})(window, document, jQuery);  
