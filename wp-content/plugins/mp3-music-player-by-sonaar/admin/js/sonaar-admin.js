(function($) {

	document.addEventListener('keydown', function(e) {
		if (!$("[class*='option-srmp3_settings_']").length) {
			return;
		}
		// Check if Ctrl (or Cmd on Mac) + S is pressed
		if ((e.ctrlKey || e.metaKey) && e.keyCode === 83) {
			e.preventDefault();  // Prevent the default browser behavior (save dialog)
			$('#submit-cmb').trigger('click');  // Trigger the button click for submit
		}
	});
	$( document ).on('change', '#inspector-select-control-0, #inspector-toggle-control-0, #inspector-toggle-control-1, #inspector-toggle-control-2, #inspector-toggle-control-3, #inspector-toggle-control-4', function (e) {
		// For Gutenberg
		setTimeout(function(){ 
			IRON.players = []
			$('.iron-audioplayer').each(function(){

				var player = Object.create(  IRON.audioPlayer )
				player.init($(this))

				IRON.players.push(player)
			})
		}, 2500);
	});

	var post_audiopreview_all_ready = false;
	$('#post_audiopreview_all').on('change', function() {
		if(post_audiopreview_all_ready){
		// Get the selected value from the main selector
		var selectedValue = $(this).val();
		// Set the value of all other selectors to match the main selector's value
		$('select[name^="alb_tracklist["][name$="[post_audiopreview]"]').val(selectedValue).trigger('change');
		}
		post_audiopreview_all_ready = true;
	});


	var hasChanges = false;

	$(document).ready(function() {
		init_adminSubMenu_separator();
		var $myRepeatGroup = $('#alb_tracklist_repeat');

		if ($myRepeatGroup.length) {

			//only execute if we are in presence of album repeater group.
			init_TrackTitleOnRepeater();
			addTrackTitletoTrackRepeater();
			init_toggleTracklistBox();
			hideShowTracklistStorelist();
			init_CheckMaxInputVars();
			
			$( document ).on('cmb2_add_row', function (event, newRow) {
				init_TrackTitleOnRepeater(newRow);
			});

			
		}

		init_srmp3_generate_bt();
		init_srmp3_tools();
		init_srmp3_audioPreview();
		init_srmp3_importTemplates();
		init_srmp3_fixed_ui();		
		init_shortcodeBuilder();
		 // Function to add a separator after the specified menu items
		 
	});

	function init_adminSubMenu_separator(){
		function addSeparator(selector) {
			$(selector).each(function() {
				var separator = $('<li class="sr-adminmenu-separator"></li>');
				$(this).closest('li').after(separator);
			});
		}
	
		// Add separators after specified menu items
		addSeparator('#menu-posts-sr_playlist li a[href^="edit-tags.php?taxonomy=playlist-tag&post_type=sr_playlist"]');
		addSeparator('#menu-posts-album li a[href^="edit-tags.php?taxonomy=playlist-tag&post_type=album"]');
		//addSeparator('#menu-posts-sr_playlist li a[href^="edit.php?post_type=sr_email_submission"]');
		addSeparator('#menu-posts-sr_playlist li a[href^="edit.php?post_type=usage-terms"]');
		addSeparator('#menu-posts-sr_playlist li a[href^="edit.php?post_type=sr_playlist&page=srmp3_settings_shortcodebuilder"]');
		addSeparator('#menu-posts-album li a[href^="edit.php?post_type=album&page=srmp3_settings_shortcodebuilder"]');
		
	}
	function init_shortcodeBuilder() {
		if(!$('#cmb2-metabox-srmp3_settings_shortcodebuilder').length) return;

		var notices = $('#wpbody-content .wrap').find('.notice, .update-nag');
		if (notices.length) {
			$('#cmb2-metabox-srmp3_settings_shortcodebuilder').prepend(notices);
			//show the notice
			notices.show();
			
		}
		// Run once when the page loads
		init_shortcodeBuilder_ResponsiveUI();
		init_shortcodeBuilder_Accordeons();
		init_shortcodeBuilder_colorPicker();
		init_shortcodeBuilder_copyShortcode();
		init_shortcodeBuilder_buttons();
		
		// Bind to window resize event with debounce
		$(window).resize(debounce_shortcodeBuilder_UI(init_shortcodeBuilder_ResponsiveUI, 250)); // 250 ms debounce period
	
		var isSaving = true;
		// Event listener for the save button
		document.getElementById('submit-cmb').addEventListener('click', function() {
			isSaving = true; // Set flag when save button is clicked
		});
	
		// 'beforeunload' event to prompt the user when leaving the page
		window.addEventListener('beforeunload', function (e) {
			if (!isSaving) { // Check if the saving process is not happening
				e.preventDefault();
				e.returnValue = ''; // Necessary for the prompt to trigger
			}
			
			//
		});
		
		
		if($('#save_template_name').val() === ''){
			$('#shortcode-delete-template-bt').hide();
			$('#shortcode-export-template-bt').hide();
		}else{
			$('#shortcode-delete-template-bt').show();
			$('#shortcode-export-template-bt').show();
		}

		const fields = ['save_template_name', 'player_class', 'id'];
		fields.forEach(function(fieldId) {
			const field = document.getElementById(fieldId);
			if (field) {
				field.addEventListener('input', function(e) {
					const regex = /[^a-zA-Z0-9-_ ]/g;
					e.target.value = e.target.value.replace(regex, '');
				});
			}
		});

		$('.cmb2-wrap input[type="text"], .cmb2-wrap input[type="number"], .cmb2-wrap textarea').on('keydown', function(e) {
			// Check if the pressed key is Enter (key code 13)
			 if (e.which === 13) {
				e.preventDefault();
				$(this).trigger('change');
			}
		});

		//If Category is empty, hide the category mutilcheck
		if(! $('#category1').length ){
			$('.cmb2-id-category').hide();
		}
		
		function init_JSFields_Handles() {
			  $('#source').each(function() {
				$(this).next('p').remove();

				const selectedValue = $(this).val();
				if (selectedValue === 'from_current_post') {
					var text = sonaar_admin_ajax.translations.notice_from_current_post;
					const $newTextElement = $('<p style="color:green;">' + text + '</p>');
					$(this).after($newTextElement);
				}
				if (selectedValue === 'from_cat' && !$('#category1').length) { //If Category is empty, display notification
					var text = sonaar_admin_ajax.translations.category_not_found;
					const $newTextElement = $('<p style="color:red;">' + text + '</p>');
					$(this).after($newTextElement);
				}
			  });
			  
			$('.cmb2-id-player-meta-group .cmb-repeatable-grouping').each(function() {
				var group = $(this);
				var selectedOptionText = group.find('.cmb-type-select select:first').find(':selected').text().trim();
				//add the second select option if it exists
				var selectedOptionText2 = group.find('.cmb-type-select select:last').find(':selected').text().trim();
				if(selectedOptionText2){
					selectedOptionText += ' [' + selectedOptionText2 + ']';
				}

				// Remove 'div' from the end of the text
				//selectedOptionText = selectedOptionText.replace(/div$/i, '').trim();
				var heading = group.find('.cmb-group-title span');
				heading.text(selectedOptionText);
			});
			$('[data-target-selector*="{#}"]').each(function() {
				var input = $(this);
				var id = input.attr('id');
				var newValue;
			
				// First, handle the special cases for switches where IDs end with 'inline1' or 'inline2'
				if (/inline[12]$/.test(id)) { //WIP inline is the ID of he CMB2 switch
					// Extract the group number from the name attribute
					var nameAttr = input.attr('name');
					var groupNumberMatch = nameAttr.match(/\[(\d+)\]/); // Matches the first digit inside brackets
					if (groupNumberMatch && groupNumberMatch[1]) {
						newValue = input.data('target-selector').replace(/{#}/g, groupNumberMatch[1]);
					}
				} else {
					// For other cases, use the existing logic to replace based on group number in the ID
					var match = id.match(/_group_(\d+)_/);
					if (match && match[1]) {
						newValue = input.data('target-selector').replace(/{#}/g, match[1]);
					}
				}
			
				// If a new target selector has been defined, update the data attribute
				if (newValue) {
					input.attr('data-target-selector', newValue);
				}
			});

			
			var $sourceSelect = $('#source');
			var $titleElement = $('#shortcode-source-title');
			var selectedOptionText = $sourceSelect.find('option:selected').text();
			$titleElement.text('Audio Source : ' + selectedOptionText);
			
		
		
			
		}
		   // Initial update on page load
		   init_JSFields_Handles();

		// Attach event delegation to the parent container
		$('#cmb2-metabox-srmp3_settings_shortcodebuilder').on('change', 'input, select, textarea', function() {
			//prevent if the field is id save_template_name
			init_JSFields_Handles();

			
			if($(this).attr('data-target-unit')){
				var unit = $(this).attr('data-target-unit');
				if($(this).val() && !$(this).val().includes(unit)){
					$(this).val($(this).val() + unit);
				}
			}

			if($(this).attr('id') === 'save_template_name' || $(this).attr('id') === 'load_template') {
				if($('#save_template_name').val() === ''){
					$('#shortcode-delete-template-bt').hide();
					$('#shortcode-export-template-bt').hide();
				}else{
					
					if($('#load_template option[value="' + $('#save_template_name').val() + '"]').length){
						$('#shortcode-delete-template-bt').show();
						$('#shortcode-export-template-bt').show();
					}else{
						$('#shortcode-delete-template-bt').hide();
						$('#shortcode-export-template-bt').hide();
					}
				}
				return;
			}
			
			isSaving = false;
			sendAjaxRequest_for_builder();
		});
		

		// Listen for the CMB2 row shift completion event
		$('.cmb-repeatable-group').on('cmb2_shift_rows_complete cmb2_remove_row cmb2_add_row', function (event, instance) {
			init_JSFields_Handles();
			isSaving = false;  // Set the flag to true to indicate an operation is in progress
			sendAjaxRequest_for_builder();
		});
	
		// Your existing DOM manipulation code
		var cmbRows = $('#cmb2-metabox-srmp3_settings_shortcodebuilder .srmp3-player-preview-container');
		var navTabWrapper = $('#shortcode_builder');
		if (navTabWrapper.length > 0) {
			navTabWrapper.append(cmbRows);
			//$('body').append(navTabWrapper);
		} else {
			console.error('nav-tab-wrapper not found.');
		}
	
		
		// display the layout
		$('.nav-tab-wrapper').css('opacity', '1');
		$('#shortcode_builder').css('opacity', '1');
		$('#srmp3_settings_shortcodebuilder').css('opacity', '1');

		
		// Initialize the PerfectScrollbar
		var ps = new PerfectScrollbar( document.querySelector('#srmp3_settings_shortcodebuilder .cmb2-wrap.form-table'), {
			wheelSpeed: 1.25,
			swipeEasing: false,
			wheelPropagation: false,
			minScrollbarLength: 20,
			suppressScrollX: true,
			maxScrollbarLength: 350,
		});
		// Initialize the PerfectScrollbar
		var pss = new PerfectScrollbar( document.querySelector('#shortcode_builder'), {
			wheelSpeed: 1.25,
			swipeEasing: false,
			wheelPropagation: false,
			minScrollbarLength: 20,
			suppressScrollX: true,
			maxScrollbarLength: 100,
		});
		var psss = new PerfectScrollbar( document.querySelector('#srmp3-admin-shortcode'), {
			wheelSpeed: 1.25,
			swipeEasing: false,
			wheelPropagation: false,
			minScrollbarLength: 20,
			suppressScrollX: true,
			maxScrollbarLength: 50,
		});
		// Initial AJAX call
		sendAjaxRequest_for_builder();




		//set time out 2000ms
		setTimeout(function(){
			setBuilderBackgroundColor();
		}, 100);
	}
	function init_shortcodeBuilder_buttons() {
		$('#shortcode-delete-template-bt').on('click', function(e) {
			e.preventDefault();
			var templateName = $('#save_template_name').val();
			
			var userConfirmation = confirm('Are you sure you want to delete "' + $('#save_template_name').val() + '" template?');
			if (!userConfirmation) {
				return;
			}

			var data = {
				action: 'delete_srmp3_template',
				template_name: templateName,
				nonce: sonaar_admin_ajax.ajax.ajax_nonce
			};
			$.post(sonaar_admin_ajax.ajax.ajax_url, data, function(response) {
				if (response.success) {
					$('#srmp3-reset-shortcode').trigger('click');
				} else {
					alert('Failed to delete template: ' + response.message);
				}
			}).fail(function(jqXHR, textStatus, errorThrown) {
				console.error("AJAX Error: " + textStatus + ': ' + errorThrown);
			});
		});

		$('#shortcode-import-open-textarea-bt').on('click', function(e) {
			e.preventDefault();
			$('#shortcode-import-container').show();
		});
		$('#shortcode-import-template-bt').on('click', function(e) {
			e.preventDefault();
			var template_settings = $('#shortcode-import-textarea').val();
			
			try {
				var settings_json = JSON.parse(template_settings);
				console.log('JSON object:', settings_json);
			} catch (e) {
				console.error('Error parsing JSON:', e);
			}
			$('#shortcode_preloader_templates').show();
			
			var data = {
				action: 'import_shortcode_template',
				template_settings: settings_json,
				nonce: sonaar_admin_ajax.ajax.ajax_nonce
			};
			$.post(sonaar_admin_ajax.ajax.ajax_url, data, function(response) {
				if (response.success) {
					//LOAD THE TEMPLATE
					templateName = response.data.template_name;
					var data = {
						action: 'load_srmp3_template',
						template_name: templateName,
						nonce: sonaar_admin_ajax.ajax.ajax_nonce
					};
					
					$.post(sonaar_admin_ajax.ajax.ajax_url, data, function(response) {
						if (response.success) {
							alert(sonaar_admin_ajax.translations.shortcode_template_imported);
    						location.reload();
						} else {
							$('#shortcode_preloader_templates').hide();
							alert('Failed to load template: ' + response.message);
						}
					}).fail(function(jqXHR, textStatus, errorThrown) {
						console.error("AJAX Error: " + textStatus + ': ' + errorThrown);
					});
					//load the template

				} else {
					$('#shortcode_preloader_templates').hide();

					alert('Failed to import template: ' + response.message);
				}
			}).fail(function(jqXHR, textStatus, errorThrown) {
				console.error("AJAX Error: " + textStatus + ': ' + errorThrown);
			});
		});
		$('#shortcode-export-template-bt').on('click', function(e) {
			e.preventDefault();
			var templateName = $('#save_template_name').val();

			var data = {
				action: 'export_srmp3_template',
				template_name: templateName,
				nonce: sonaar_admin_ajax.ajax.ajax_nonce
			};
			$.post(sonaar_admin_ajax.ajax.ajax_url, data, function(response) {
				if (response.success) {
				
					//change the button text html to Exported to clipboard
			$('#shortcode-export-template-bt .export-text').text('Exported to clipboard');
			//remove class dashicons-upload and add dashicons-yes
			$('#shortcode-export-template-bt .dashicons').removeClass('dashicons-upload').addClass('dashicons-yes');
			//change button background color to green
			$('#shortcode-export-template-bt').css('background-color', '#30c5c8');

					

					navigator.clipboard.writeText(JSON.stringify(response.data.template_values)).then(() => {
						// Temporarily change button text in the span
						alert('Template copied to clipboard! You can paste it in your import template box.');
					}).catch(err => {
						console.error('Failed to copy text: ', err);
					});

					
					//$('#srmp3-reset-shortcode').trigger('click');
				} else {
					alert('Failed to export template: ' + response.message);
				}
			}).fail(function(jqXHR, textStatus, errorThrown) {
				console.error("AJAX Error: " + textStatus + ': ' + errorThrown);
			});
		});

		// Function to get URL parameter
		function getUrlParameter(name) {
			name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
			var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
			var results = regex.exec(location.search);
			return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
		}
		function removeUrlParameter(param) {
			var url = window.location.href;
			var regex = new RegExp('[\\?&]' + param + '=[^&#]*');
			url = url.replace(regex, '');
			url = url.replace(/[&?]$/, '');
			history.replaceState(null, '', url);
		}
		
		var templateName = getUrlParameter('tmpl');
		if (templateName) {
			removeUrlParameter('tmpl');
			loadTemplate(templateName);
		}

		$('#load_template').change(function() {
			var templateName = $('#load_template').val();
			if (templateName === '') {
				alert('Please select a template to load.');
				return;
			}

		 	loadTemplate(templateName);
		});

		function loadTemplate(templateName) {
			$('#shortcode_preloader_templates').show();
	
			var data = {
				action: 'load_srmp3_template',
				template_name: templateName,
				nonce: sonaar_admin_ajax.ajax.ajax_nonce
			};
	
			$.post(sonaar_admin_ajax.ajax.ajax_url, data, function(response) {
				if (response.success) {
					location.reload();
				} else {
					$('#shortcode_preloader_templates').hide();
					alert('Failed to load template: ' + response.message);
				}
			}).fail(function(jqXHR, textStatus, errorThrown) {
				console.error("AJAX Error: " + textStatus + ': ' + errorThrown);
			});
		}
		
		$('#srmp3-reset-shortcode').on('click', function(){
			//add browser alert message to confirm the reset
			var userConfirmation = confirm(sonaar_admin_ajax.translations.reset_shortcode);
			if (!userConfirmation) {
				return;
			}
			$('#shortcode_preloader_templates').show();
			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'POST',
				data: {
					action: 'reset_shortcode',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce
				},
				
				success: function(response) {
					//reload the page
					location.reload();
				}
			});
		});
	}
	function init_shortcodeBuilder_copyShortcode() {
		const copyBtn = document.getElementById('srmp3-copy-shortcode');
		// Correctly find the span element for changing text
		const copyTextSpan = copyBtn.querySelector('.srmp3-copy-shortcode-text');
		const shortcodeContainer = document.getElementById('srmp3-admin-shortcode');
	
		copyBtn.addEventListener('click', function() {
			// Get the current text content of the shortcode when the button is clicked
			const shortcodeText = shortcodeContainer.textContent;
	
			// Using the Clipboard API to copy text
			navigator.clipboard.writeText(shortcodeText).then(() => {
				// Temporarily change button text in the span
				const originalText = copyTextSpan.textContent;
				copyTextSpan.textContent = 'Copied!';
	
				// Set timeout to revert button text after 2 seconds
				setTimeout(() => {
					copyTextSpan.textContent = originalText;
				}, 2000);
			}).catch(err => {
				console.error('Failed to copy text: ', err);
			});
		});
	}
	function setBuilderBackgroundColor() {
        var bgColor = $('.cmb2-id-preview-bg-color .wp-color-result').css('background-color');
		document.getElementById('shortcode_builder').style.setProperty('background-color', bgColor, 'important');
		if (bgColor !== 'rgb(246, 247, 247)'){
			// This is a user input color and not the default one. Do the magic
			document.getElementById('shortcode_builder').style.setProperty('background-image', 'unset', 'important');
		}else{
			document.getElementById('shortcode_builder').style.removeProperty('background-image');
		}
    }
	function init_shortcodeBuilder_colorPicker() {
		let debounceTimer;
		var container = $('#cmb2-metabox-srmp3_settings_shortcodebuilder');
	
		// Helper function to manage opacity and class
		function manageOpacityAndClass(element) {
			const parentElement = $(element).closest('.wp-picker-container');
			const inputElement = parentElement.find('input.wp-color-picker').not('[data-alpha="true"]');;
			const inputVal = inputElement.val();
			
			if (inputVal === '' || inputVal === '#') {
				$(element).addClass('srp-colopicker-no-background-color');
			} else {
				$(element).removeClass('srp-colopicker-no-background-color');
			}
		}
	
		// Handle dynamically added color pickers
		const observer = new MutationObserver((mutations) => {
			mutations.forEach((mutation) => {
				if (mutation.type === 'childList') {
					$(mutation.addedNodes).find('.wp-color-result, .color-alpha').each(function() {
						styleObserver.observe(this, { attributes: true, attributeFilter: ['style'] });
						
						if (!$(this).find('.srp-colopicker-inner-wrapper').length) {
							//so we can apply a checkergrid withut affecting the select color button
							$(this).find('span.wp-color-result-text').wrap('<span class="srp-colopicker-inner-wrapper"></span>');
						}

						manageOpacityAndClass(this);
					});
				}
			});
		});

		observer.observe(container[0], { childList: true, subtree: true });
	
		// Handle style changes on existing and dynamically added elements
		const styleObserver = new MutationObserver((mutations) => {
			mutations.forEach((mutation) => {
				if (mutation.attributeName === 'style') {
					clearTimeout(debounceTimer);
					debounceTimer = setTimeout(() => {
						manageOpacityAndClass(mutation.target);

						// If it's from cmb2-id-preview-bg-color, change the background color of the builder, else refresh shortcode
						if ($(mutation.target).closest('.cmb2-id-preview-bg-color').length) {
							setBuilderBackgroundColor();
						} else {
							isSaving = false;
							sendAjaxRequest_for_builder();
						}
					}, 250); // Debounce AJAX calls by 250ms
				}
			});
		});
	
		// Initially observe all existing .wp-color-result elements
		$('.wp-color-result').each(function() {
			styleObserver.observe(this, { attributes: true, attributeFilter: ['style'] });
		});
	}
	
	
	function iinit_shortcodeBuilder_colorPicker() {
		let debounceTimer;
		var container = $('#cmb2-metabox-srmp3_settings_shortcodebuilder');
	
		// Observer for childList mutations to handle dynamically added color pickers
		var observer = new MutationObserver(function(mutations) {
			mutations.forEach(function(mutation) {
				if (mutation.type === 'childList') {
					mutation.addedNodes.forEach(function(node) {
						if ($(node).is('.wp-color-result') || $(node).find('.wp-color-result').length > 0){
							$(node).find('.wp-color-result, .color-alpha').each(function() {
								styleObserver.observe(this, { attributes: true, attributeFilter: ['style'] });

								if ($(this).find('.srp-colopicker-inner-wrapper').length === 0) {
									$(this).find('span.wp-color-result-text').wrap('<span class="srp-colopicker-inner-wrapper"></span>');
								}
								if($(node).find('input.wp-color-picker').val() === '' || $(node).find('input.wp-color-picker').val() === '#'){
									$(this).css('opacity', '0.8');
									$(this).addClass('srp-colopicker-no-background-color');
								}else{
									$(this).removeClass('srp-colopicker-no-background-color');
									$(this)[0].style.removeProperty('opacity');
								}
							});
						}
					});
				}
			});
		});
	
		observer.observe(container[0], { childList: true, subtree: true });
	
		// Observer for style changes on existing and dynamically added .wp-color-result elements
		var styleObserver = new MutationObserver(function(mutations) {
			mutations.forEach(function(mutation) {
				//console.log($(mutation.target));
				if (mutation.attributeName === 'style') {
					clearTimeout(debounceTimer);
					debounceTimer = setTimeout(() => {
						let styleAttr = $(mutation.target).attr('style');
						if (styleAttr === undefined || styleAttr === '' || !styleAttr.includes('background-color')) {
								$(mutation.target).addClass('srp-colopicker-no-background-color');
								$(mutation.target).css('opacity', '0.8');
						}else{
							$(mutation.target).removeClass('srp-colopicker-no-background-color');
							$(mutation.target)[0].style.removeProperty('opacity');
						}

						//if its from cmb2-id-preview-bg-color, change the background color of the builder only else refresh shortcode
						if (mutation.target.closest('.cmb2-id-preview-bg-color')) {
							setBuilderBackgroundColor();
						}else{
							isSaving = false;
							sendAjaxRequest_for_builder();
						}
					}, 250); // Debounce AJAX calls by 250ms
				}
			});
		});
	
		// Initially observe all existing .wp-color-result elements
		$('.wp-color-result').each(function() {
			styleObserver.observe(this, { attributes: true, attributeFilter: ['style'] });
		});
	}
	
	function debounce_shortcodeBuilder_UI(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	}
	function init_shortcodeBuilder_ResponsiveUI() {
		if ($(window).width() < 1280) {
			$('.nav-tab-wrapper').hide();
			$("body").addClass("folded");
		} if ($(window).width() < 1980) {
			$('.nav-tab-wrapper').hide();
		} else {
			$('.nav-tab-wrapper').show();
			$("body").removeClass("folded");
		}
	}
	
	function codeMirror_init(){
		if(typeof initAlready !== 'undefined'){
			return;
		}
		var textarea = document.getElementById('custom_css'); // Match the ID given in CMB2 setup
		if (textarea && textarea.nextSibling && textarea.nextSibling.classList.contains('CodeMirror')) {
			var cmInstance = textarea.nextSibling.CodeMirror;
			if (cmInstance) {
				// Now you can use cmInstance to manipulate CodeMirror
				// Example: Listen to keydown events
				initAlready = true;
				cmInstance.on("keydown", function(cm, event) {
					if (event.keyCode === 13) {
						textarea.value = cmInstance.getValue(); // Synchronize the CodeMirror content to the textarea
						$(cm.getTextArea()).trigger('change'); // Trigger change event on the original textarea
					}
				});

				// Handle blur events
				cmInstance.on("blur", function(cm, event) {
					textarea.value = cmInstance.getValue(); // Synchronize the CodeMirror content to the textarea
					$(cm.getTextArea()).trigger('change'); // Trigger change event on the original textarea when CodeMirror loses focus
				});
			} else {
				console.error('No CodeMirror instance found on this element');
			}
		} else {
			console.error('CodeMirror DOM structure not found. Check if initialization is complete.');
		}
	}
	function init_shortcodeBuilder_Accordeons(){
		// Initially hide all content under each title except for the title itself
		$('.cmb-type-title').nextUntil('.cmb-type-title').addClass('hidden');
	
		// Add click event to each title
		$('.cmb-type-title').click(function() {
			// Toggle visibility of all elements under this title until the next title
			var elements = $(this).nextUntil('.cmb-type-title');
			if (elements.hasClass('hidden')) {
				elements.removeClass('hidden').addClass('visible');
				$(this).addClass('active'); // Add active class to rotate the arrow
				//check if it has class cmb2-id-shortcode-advanced-title
				if($(this).hasClass('cmb2-id-shortcode-advanced-title')){
					codeMirror_init();
				}

				
			} else {
				elements.removeClass('visible').addClass('hidden');
				$(this).removeClass('active'); // Remove active class to reset the arrow
			}
	
			// Optionally, to keep only one section open at a time
			$('.cmb-type-title').not(this).nextUntil('.cmb-type-title').removeClass('visible').addClass('hidden');
			$('.cmb-type-title').not(this).removeClass('active'); // Reset other arrows
		});
	}
	
	


	function sendAjaxRequest_for_builder() {
		var data = {
			action: 'update_shortcode',
			nonce: sonaar_admin_ajax.ajax.ajax_nonce
		};
		
		$('#cmb2-metabox-srmp3_settings_shortcodebuilder input, #cmb2-metabox-srmp3_settings_shortcodebuilder select, #cmb2-metabox-srmp3_settings_shortcodebuilder textarea').each(function() {
			var $this = $(this);
			var nameAttr = $this.attr('name');
			if (!nameAttr) return;  // Skip if no name attribute
		
			var styleAttr = $this.closest('.cmb-row').attr('style');
			if (styleAttr && styleAttr.includes('display: none') && !styleAttr.includes('/*')) {
				return;  // Skip if the parent .cmb-row is hidden
			}
		
			var name = nameAttr.replace('[]', '');
			if ($this.closest('.cmb-repeatable-grouping').length) {
				var iterator = $this.closest('.cmb-repeatable-grouping').data('iterator');
				name = name.replace(/(\[\d+\])/g, '') + '[' + iterator + ']';
			}
		
			var targetSelector = $this.attr('data-target-selector');
			if (targetSelector) {
				var currentValue = $this.val();
				targetSelector = targetSelector.replace(/{{VALUE}}/g, currentValue);
				if(targetSelector.includes('#') && currentValue.length < 3){
					return; // Skip invalid CSS
				}
			}
		
			if ($this.is(':checkbox')) {
				if ($this.closest('.cmb2-id-category').length) {  // Replace with the specific container/class identifying the old fields
					data[name] = data[name] || { values: [], selectors: [] };
					if ($this.is(':checked')) {
						data[name].values.push($this.val());
						if (targetSelector) {
							data[name].selectors.push(targetSelector);
						}
					}
				} else {
					data[name] = data[name] || [];
					if ($this.is(':checked')) {
						data[name] = { value: $this.val(), selector: targetSelector };
					}
				}
			} else if ($this.is(':radio')) {
				if ($this.is(':checked')) {
					data[name] = { value: $this.val(), selector: targetSelector };
				}
			} else {
				var value = $this.val();
				if (!value || value === '#') return; // Skip if value is empty
				if ($this.data('iris')) {  // Handle color picker
					value = $this.iris('color') || value;  // Use color picker value if available
				}
				data[name] = { value: value, selector: targetSelector };
			}
		});

		// Convert checkbox values to comma-separated strings and normalize data
		for (var key in data) {
			if (data[key] && data[key].values && Array.isArray(data[key].values)) {
				data[key] = data[key].values.join(',');
			} else if (typeof data[key] === 'object' && data[key].hasOwnProperty('value')) {
				if (data[key].selector) {
					// Append the CSS property name as the style property if specified in the key
					data[key] = `${data[key].value}|||${data[key].selector}`;
				} else {
					// If no selector, just pass the value
					data[key] = data[key].value;
				}
			}
		}
	
				
		 // Show the preloader
		 $('#shortcode_preloader').show();
		// Send AJAX request
		$.ajax({
			url: sonaar_admin_ajax.ajax.ajax_url,
			type: 'POST',
			data: data,
			success: function(response) {
				$('#srmp3-admin-shortcode').text(response.data.shortcode); // Display the shortcode
				$('#srmp3-player-preview').html(response.data.html); // Replace the content with the rendered HTML
				$('#srmp3-admin-shortcode-container').show();
				$('#shortcode_preloader').hide();

				$('.iron-audioplayer').each(function(){
					//Set sticky player according to the player data attribute. we need to do this here because this check has already been done on setStickyPlayer in document.ready and is not called again
					if (jQuery(this).data("sticky-player")) {
						IRON.audioPlayer.stickyEnable = true;
					  }else{
						//check if IRON.audioPlayer.stickyEnable is defined before setting it to false
						IRON.audioPlayer.stickyEnable = false;
						if (IRON.sonaar && IRON.sonaar.player) {
							IRON.sonaar.player.pause();
							jQuery('#sonaar-player').css("bottom", "-96px"); // make sure sticky is hidden if it was present.
						}
					  }
				})
				if (IRON.audioPlayer && typeof IRON.audioPlayer.setIronAudioplayers === 'function') {
					IRON.audioPlayer.setIronAudioplayers();
				}
				setShortcodeColors();


			}
		});
	}










	function setShortcodeColors() {
		var preTag = document.getElementById("srmp3-admin-shortcode");
		var shortcode = preTag.textContent.trim();
	
		// Regular expression to match the specific shortcode opening and closing tags
		var regex = /(\[sonaar_audioplayer)|(\]\[\/sonaar_audioplayer\])|(\[|\/|\]|"|=)|(\w+)=["']([^"']+)["']/g;
	
		// Replace matched text with HTML span tags
		var highlightedShortcode = shortcode.replace(regex, function(match, open, close, special, arg, value) {
			if (open) {
				// Highlight the opening tag without the closing bracket
				return '<span class="shortcode-name">' + match + '</span>';
			} else if (close) {
				// Highlight the closing tag including both brackets
				return '<span class="shortcode-name">' + match + '</span>';
			} else if (special) {
				return '<span class="special">' + match + '</span>';
			} else if (arg) {
				// Handle arguments and values with the appropriate highlights
				return '<span class="argument">' + arg + '</span><span class="special">=</span><span class="special">"</span><span class="value">' + value + '</span><span class="special">"</span>';
			}
		});
	
		// Update the content of the <pre> tag with the highlighted shortcode
		preTag.innerHTML = highlightedShortcode;
	}
	
	
	

	function init_lightDarkmodeSwitch() {
		// Append the toggle switch container to .srmp3-settings-topbar
		let toggleContainer = document.createElement("div");
		toggleContainer.id = "toggle-container";
		toggleContainer.className = "srmp3darkmode-toggle-container";
		//topBar.appendChild(toggleContainer);
	  
		// Function to create a toggle switch
		function createToggle(dashiconClass, positionClass) {
			let toggle = document.createElement("div");
			toggle.className = "srmp3darkmode-toggle-switch";
		  
			let knob = document.createElement("div");
			knob.className = "srmp3darkmode-toggle-knob";
		  
			let iconElem = document.createElement("span");
			iconElem.className = "dashicons " + dashiconClass + " srmp3darkmode-toggle-icon " + positionClass;

			toggle.appendChild(iconElem);
			toggle.appendChild(knob);

			toggle.addEventListener("click", () => {
				const isDarkMode = document.body.classList.contains("dark-mode");
		
				if (isDarkMode) {
					iconElem.className = "fas fa-moon srmp3darkmode-toggle-icon srmp3darkmode-toggle-icon-right"; // Sun icon
					knob.style.transform = "translateX(0)";
					document.body.classList.remove("dark-mode");
				} else {
					iconElem.className = "fas fa-sun srmp3darkmode-toggle-icon srmp3darkmode-toggle-icon-left"; // Moon icon
					knob.style.transform = "translateX(20px)";
					document.body.classList.add("dark-mode");
				}
		
				// AJAX request to update user meta
				fetch(sonaar_admin_ajax.ajax.ajax_url, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
					},
					body: new URLSearchParams({
						action: 'srmp3_toggle_dark_mode',
						nonce: sonaar_admin_ajax.ajax.ajax_nonce,
						dark_mode: isDarkMode ? 'false' : 'true'
					})
				});
			});
			
			
		  
			return toggle;
		}
		
		// Create and append toggles
		let darkModeToggle = createToggle("dashicons-visibility", "srmp3darkmode-toggle-icon-left");
		
		if (document.body.classList.contains("dark-mode")) {
			darkModeToggle.firstChild.className = "fas fa-sun srmp3darkmode-toggle-icon srmp3darkmode-toggle-icon-left";
			darkModeToggle.lastChild.style.transform = "translateX(20px)";
		} else {
			darkModeToggle.firstChild.className = "fas fa-moon srmp3darkmode-toggle-icon srmp3darkmode-toggle-icon-right";
			darkModeToggle.lastChild.style.transform = "translateX(0)";
		}
		
		toggleContainer.appendChild(darkModeToggle);
		// Insert the toggleContainer before the button
		let topBar = document.querySelector(".srmp3-settings-topbar");
		let saveButton = document.getElementById("srmp3-settings-save-bt");
		topBar.insertBefore(toggleContainer, saveButton);
	}
	  
	  
	function init_srmp3_fixed_ui(){
		//if option-srmp3_settings_ * wildcard is present
		
		var nav = document.querySelector('.cmb2-options-page .nav-tab-wrapper'); // Get the nav
		// make sure nav exists length is not 0
		if (!nav){
			return;
		}
		if (!$("[class*='option-srmp3_settings_']").length) {
			return;
		}

		init_lightDarkmodeSwitch();
 
	 
		
		/**
		 * Add a new div with 100% width to prevent notices from third parties to break the layout
		 */
		 var navBreaker = document.createElement('div');
		 navBreaker.className = 'nav-breaker';
		 nav.parentNode.insertBefore(navBreaker, nav);

		/**
		 * Add a version number to the menu
		 */
		const versionWrapper = document.createElement('div');
		versionWrapper.classList.add('srmp3-settings-version');

		var freeVer = 'MP3 Audio Player Free <span class="srmp3-settings-pro-not-available">' + (sonaar_music && sonaar_music.plugin_version_free ? sonaar_music.plugin_version_free : 'Not available') + '</span>';
		var proVer = 'MP3 Audio Player Pro <span class="srmp3-settings-pro-not-available">' + (sonaar_music && sonaar_music.plugin_version_pro.includes('Not Installed') ? '' + sonaar_music.plugin_version_pro : sonaar_music.plugin_version_pro) + '</span>';
		
		$('.nav-tab-wrapper').append(versionWrapper);
		
		versionWrapper.innerHTML = freeVer + '<br>' + proVer;

		// When the new button is clicked, trigger the real save button's click event
		$('#srmp3-settings-save-bt').on('click', function(e) {
			e.preventDefault(); // Prevent default form submission by button
			$('input[name="submit-cmb"]').click(); // Trigger click on the real CMB2 submit button
		});

		/**
		 * Add scroll behavior on each menu linksand highlight them when clicked
		 */
		const submenuLinks = document.querySelectorAll('.sr-option-submenus a');

		submenuLinks.forEach(link => {
			link.addEventListener('click', function(event) {
				event.preventDefault();
				const anchor = this.getAttribute('data-anchor');
				
				// Try to find an element by ID first
				let targetElement = document.getElementById(anchor);
	
				// If no element with the ID is found, try to find an element by class name
				if (!targetElement) {
					
					const elements = document.getElementsByClassName(anchor);
					if (elements.length > 0) {
						// Use the first element with the class name
						targetElement = elements[0];
					}
				}
	
				if (targetElement) {
					// Calculate the position to scroll to, taking into account the 30px offset
					const positionToScroll = targetElement.getBoundingClientRect().top + window.scrollY - 130;
					
					//add anchor to the url
					//remove cm2-id from anchor
					var realAnchor = anchor.replace('cmb2-id-', '');
					window.location.hash = realAnchor;
					

		
					// Scroll to the calculated position
					window.scrollTo({ top: positionToScroll, behavior: 'smooth' });
		
					// Add the highlighted class to the target element
					targetElement.classList.add('sr-highlighted');
					// Remove the highlighted class after 3 seconds
					setTimeout(() => {
						targetElement.classList.remove('sr-highlighted');
					}, 2500);
				}
			});
		});


		
		
		
	}
	function init_srmp3_importTemplates(){
		//This function is used for the Elementor AND Shortcode template importer.
		// check if $('.srmp3_import_overlay') is not present, then we dont need to init the function
		if(!$('.srmp3_import_overlay').length) return;

		var options = {
			valueNames: [ 'srp-tmpl-title' ],
			listClass: 'template-list',
			searchClass: 'srp_search',
		};
		
		var action = ($("body[class*='srmp3-import-shortcode-templates']").length) ? 'import_srmp3_shortcode_template' : 'import_srmp3_elementor_template';
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
				action: action,
				nonce: sonaar_admin_ajax.ajax.ajax_nonce,
				filename: json_file
			};
			$.post(
				sonaar_admin_ajax.ajax.ajax_url,
				data, 
				function(response) {
					var obj;
					if(action === 'import_srmp3_shortcode_template'){ // we dont deal with the same kind of response
						obj = response;
						if(obj.success === true){
							//add the template name and replace space with - at the end of the href attr
							var template_name = obj.data.template_name;
							$('.srmp3_import_success a').attr('href', $('.srmp3_import_success a').attr('href') + '&tmpl=' + template_name);
							$('.srmp3_import_success .srmp3-template-name').text('"' + response.data.template_name + '"');
						}else{
							obj.message = response.data.message;
						}
						
					}else{
						obj = $.parseJSON(response);
					}
					elt.show();
					please_wait.hide();
					$("html, body").animate({ scrollTop: 0 }, "slow");
					if(obj.success === true) { 
						$('.srmp3_import_success').show();
					} else {
						$('.srmp3_import_error_message').remove();
						$('.srmp3_import_failed').append('<div class="srmp3_import_error_message">' + obj.message + '</div>');
						$('.srmp3_import_failed').show();
					}
				});

		});
	}
	function init_srmp3_generate_bt(){
		//Used for Audio Previews AND Tracks Indexation
		if($('.srmp3-generate-bt').length){
			const url = new URL(window.location.href);
			var posts_in = url.searchParams.get("posts_in");
			//seperate the post ids with commas
			if(posts_in){
				//count how many posts_in are set
				var posts_in_count = posts_in.split(',').length;
				posts_in = posts_in.replace(/,/g, ', ');
				$('#audiopreview-settings-title').after(
					'<div style="width: fit-content;" class="notice notice-warning is-dismissible audiopreview_posts_in_notice">' +
					'<h2>' +
					'<strong>Action required!</strong> ' + posts_in_count + ' posts are ready to have their audio previews generated.' +
					'</h2>' +
					'<p>Review the settings below and click <strong>Generate</strong> Button.</p>' +
					'<p style="font-size:10px;">Posts: ' + posts_in + '</p>' +
					'</div>'
				);
				$('#srmp3_indexTracks_status').text('We will proceed with ' + posts_in_count + ' posts.');
			}
			setTimeout(function() {
				// Delegate event handling for inputs, checkboxes, and datepicker
				$(document).on('input', '.cmb-row input[type="text"], .cmb-row select', handleInputChange);
				$(document).on('change', '.cmb-row input, .cmb-row select', handleInputChange);
				$(document).on('click', '#ui-datepicker-div', handleInputChange);
	
				// Delegate event handling for file upload and remove buttons
				$(document).on('click', '.file-status, .cmb2-upload-button, .cmb2-remove-file-button', handleInputChange);
			}, 2000);
			function handleInputChange(event) {
				hasChanges = true;

				if($('#alb_tracklist_repeat, .option-srmp3_settings_audiopreview').length) return;

				const excludedIds = ['peaks_overwrite1', 'peaks_overwrite2']; // Add more IDs as needed

				// Check if the target element has an ID and if that ID is in the exclusion list
				if (event.target.id && excludedIds.includes(event.target.id)) {
					return; // Exclude this element
				}
				$('.srmp3-generate-bt').css('opacity', '0.5').css('pointer-events', 'none');
				
				// Check if the message is already present; if not, add it
				if ($('#saveChangeMessage').length === 0) {
					$('#srmp3_indexTracks').after('<span id="saveChangeMessage" style="margin-left:10px; color:red;">Save changes and refresh this page before rebuiling index.</span>');
					$('.srmp3-audiopreview-bt').after('<span id="saveChangeMessage" style="margin-left:10px; color:red;">Save changes and refresh this page before generate previews.</span>');
				}
			}
		}
	}

	function init_srmp3_tools(){
		if ($('.option-srmp3_settings_tools').length) {
		}else{
			return;
		}
		//console.log("INIT SRMP3 AUDIOREVIEW");
		// For audio preview generation

		var continueIndexing = true;
		// For lazyload search indexation
		function isFirefox() {
			return navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
		}
		if (!isFirefox()) {
			$('#srmp3-settings-generatepeaks-bt-container').after('<div style="margin-top:10px;color:red;"><strong>Warning:</strong> Peaks Generation is resource-intensive. For audio tracks longer than 30 minutes, consider using Firefox for better memory management. If your browser crashes during peak generation, try Firefox.</div><br>');
        }

		$('#srmp3_convertcpt').click(function(e) {
			var userConfirmation = confirm("Are you sure you want to export your old stuff into MP3 Audio Player? Please confirm");
			if (userConfirmation) {
				continueIndexing = true;
				e.preventDefault();
				$(this).css('opacity', '0.5').css('pointer-events', 'none');
				$(this).siblings('#indexationProgress').css('display', 'inline-block').val(0);
				originalText = $(this).text();
				$(this).text("Exporting & Recreating Posts...");
				$(this).addClass('spinningIcon showSpinner').removeClass('showCheckmark');
				if (!$(this).siblings('#stopButton').length) {
					$(this).after('<button id="stopButton" class="srmp3-stopgenerate-bt">Stop</button>');
				}
				$(this).siblings('#srmp3_indexTracks_status').text('Processing...');
				$(document).on('click', '#stopButton', function() {
					continueIndexing = false;
					var btn = $(this).siblings('#srmp3_convertcpt');
					btn.text(originalText)
						.removeClass('showSpinner spinningIcon')
						.addClass('showCheckmark')
						.css('opacity', '1')
						.css('pointer-events', 'initial');
		
					btn.siblings('#srmp3_indexTracks_status').text('Stopped by user. ');
					
					$(this).siblings('#indexationProgress').css('display', 'none');
					$(this).remove();
				});
				srmp3_convertCPT(0, originalText, $(this));
				function srmp3_convertCPT(index, originalText, $clickedButton){
					if (!continueIndexing) {
						return;
					}
					$.ajax({
						url: sonaar_admin_ajax.ajax.ajax_url,
						type: 'post',
						dataType: 'json',
						data: {
							action: 'copy_SR_theme_playlist_to_MP3AudioPlayer_playlist',
							nonce: sonaar_admin_ajax.ajax.ajax_nonce,
							offset: index
						},
						success: function(response) {
							if(response.error){
								$clickedButton.siblings('#srmp3_indexTracks_status').text(response.error);
								return;
							}
							if (response.totalPosts && response.processedPosts) {
								$clickedButton.siblings('#progressText').text(response.processedPosts + " / " + response.totalPosts + " posts");
							}
		
							if (response.progress) {
								$clickedButton.siblings('#indexationProgress').val(Math.round(Number(response.progress)));
							}
				
							if (response.message) {
								if(indexPos != trackLength && !completed){
									$clickedButton.siblings('#srmp3_indexTracks_status').text(response.message);
								}
							}

							if (response.completed) {
								$clickedButton.siblings('#stopButton').remove();
								$clickedButton.siblings('#indexationProgress').css('display', 'none');
								//$clickedButton.siblings('#stopGeneratePeaksButton').css('display', 'none');
								$clickedButton.siblings('#srmp3_indexTracks_status').text('Completed 🎉 ' + response.message);
								$clickedButton
									.text(originalText)
									.removeClass('showSpinner spinningIcon')
									.addClass('showCheckmark')
									.css('opacity', '1')
									.css('pointer-events', 'initial');

							}else{
								index +=10;
								srmp3_convertCPT(index, originalText, $clickedButton);
							}
							
						},
						error: function(jqXHR, textStatus, errorThrown) {
							console.error("Error: ", textStatus, errorThrown);
						},
					});
					
					if (!continueIndexing) {
						return;
					}
				}
			}
			

		});
		$('.srmp3-generatepeaks-bt').click(function(e) {
			// GENERATE BT SPECIFIC TRACK
			e.preventDefault();
			$(this).css('opacity', '0.5').css('pointer-events', 'none');
			$(this).siblings('#indexationProgress').css('display', 'inline-block').val(0);
			originalText = $(this).text();
			$(this).text("Generating the file(s)...");
			$(this).addClass('spinningIcon showSpinner').removeClass('showCheckmark');

			if (!$(this).siblings('#stopGeneratePeaksButton').length) {
				$(this).after('<button id="stopGeneratePeaksButton" class="srmp3-stopgenerate-bt">Stop</button>');
			}
			$(this).siblings('#srmp3_indexTracks_status').text('Processing...');
			
			const audioContext = new (window.AudioContext || window.webkitAudioContext)();

			get_audio_files(0, originalText, $(this), audioContext);


			$(document).on('click', '#stopGeneratePeaksButton', function() {
				continueIndexing = false;
				var btn = $(this).siblings('.srmp3-generatepeaks-bt');
				btn.text(originalText)
					.removeClass('showSpinner spinningIcon')
					.addClass('showCheckmark')
					.css('opacity', '1')
					.css('pointer-events', 'initial');
	
				btn.siblings('#srmp3_indexTracks_status').text('Stopped by user. ');
				
				$(this).siblings('#indexationProgress').css('display', 'none');
				$(this).remove();
			});

			function get_audio_files(index, originalText, $clickedButton, audioContext){
				if (!continueIndexing) {
					return;
				}

				var overwrite = document.querySelector('.cmb2-id-peaks-overwrite .cmb2-enable.selected') ? 'true' : 'false';

				$.ajax({
					url: sonaar_admin_ajax.ajax.ajax_url,
					type: 'post',
					dataType: 'json',
					data: {
						action: 'get_audio_files',
						nonce: sonaar_admin_ajax.ajax.ajax_nonce,
						offset: index,
						overwrite: overwrite,
					},
					success: function(response) {
						if(response.error){
							$clickedButton.siblings('#srmp3_indexTracks_status').text(response.error);
							return;
						}
				
						this.audioContext;
						
						generatePeaks(response.files, 0, audioContext);
						
						if (response.totalPosts && response.processedPosts) {
							$clickedButton.siblings('#progressText').text(response.processedPosts + " / " + response.totalPosts + " posts");
						}
	
						if (response.progress) {
							$clickedButton.siblings('#indexationProgress').val(Math.round(Number(response.progress)));
						}
			
						if (response.message) {
							if(indexPos != trackLength && !completed){
								$clickedButton.siblings('#srmp3_indexTracks_status').text(response.message);
							}
						}

						if (response.completed) {
							$clickedButton.siblings('#stopIndexingButton').remove();
							$clickedButton.siblings('#indexationProgress').css('display', 'none');
							$clickedButton.siblings('#stopGeneratePeaksButton').css('display', 'none');
							$clickedButton.siblings('#srmp3_indexTracks_status').text('Completed 🎉 ' + response.message);
							$clickedButton
								.text(originalText)
								.removeClass('showSpinner spinningIcon')
								.addClass('showCheckmark')
								.css('opacity', '1')
								.css('pointer-events', 'initial');

						}else{
							index +=1;
							get_audio_files(index, originalText, $clickedButton, audioContext);
						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.error("Error: ", textStatus, errorThrown);
					},
				});

			}
			async function generatePeaks(files, currentIndex, audioContext) {
				// Base condition to stop recursion
				if (currentIndex >= files.length) {
					return;
				}
				const file = files[currentIndex];
				try {
					const response 		= await fetch(file.file);
					const arrayBuffer 	= await response.arrayBuffer();
					try {
						console.log('Try to decode audio for:' , file.post_id , ' index: ', file.index, ' file: ', file.file);
						var audioBuffer = await audioContext.decodeAudioData(arrayBuffer);
						let peaks 		= IRON.extractPeaks(audioBuffer);
						IRON.updatePeaksOnServer(file.post_id, file.media_id, file.index, peaks, file.file, file.is_temp, file.is_preview);
						 // Attempt to release the audioBuffer memory
						 audioBuffer = null;
						// Process the next file after the current one is done
						await generatePeaks(files, currentIndex + 1, audioContext);
					} catch (decodeError) {
						deleteTempFile(file.file, file.is_temp);
						
						console.error('Error decoding file:', file.file, decodeError);
						// Even if there is an error, proceed with the next file
						await generatePeaks(files, currentIndex + 1, audioContext);
					}
				} catch (fetchError) {
					console.error('Error fetching file:', file.file, fetchError);
					// Proceed with the next file in case of fetch error
					await generatePeaks(files, currentIndex + 1, audioContext);
				}
			}

			function deleteTempFile(file, is_temp){
				if(!is_temp) return;

				console.log('try to delete the file');
				$.ajax({
					url: 		sonaar_admin_ajax.ajax.ajax_url,
					type: 		'post',
					dataType: 	'json',
					data: {
						action: 'removeTempFiles',
						nonce: sonaar_admin_ajax.ajax.ajax_nonce,
						file: file,
						is_temp: true,
					},
					success: function(response) {
						console.log('File deleted');
					},
					error: function(textStatus, errorThrown) {
						console.error("Error deleting file", textStatus, errorThrown);
					}
				});
			}
		});
		var delete_bt_originalText = $('#srmp3-bulkRemove-bt').html();

		function countPeakFiles_AJAX(){			
			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'count_peak_files',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce,
				},
				success: function(response) {
					var fileCount = response.count;
					// Append fileCount to the original text
					$('#srmp3-bulkRemove-bt').html(delete_bt_originalText + ' (' + fileCount + ')');
				},
				error: function(xhr, status, error) {
					//return false;
				}
			});
		}
		countPeakFiles_AJAX();
		$('#srmp3-bulkRemove-bt').click(function(e) {
			e.preventDefault();

			// Disable the button to avoid multiple clicks
			$(this).prop('disabled', true);

			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'count_peak_files',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce
				},
				success: function(response) {
					var fileCount = response.count;
					var userConfirmation = confirm("Time to cleanup !?\n\nAre you sure you want to remove the " + fileCount + " peak files on your server? Do not worry, this will NOT delete or remove any of your audio files.");
					if (userConfirmation) {
						// Start the removal process
						$.ajax({
							url: sonaar_admin_ajax.ajax.ajax_url,
							type: 'post',
							dataType: 'json',
							data: {
								action: 'remove_peak_files_and_update_posts',
								nonce: sonaar_admin_ajax.ajax.ajax_nonce
							},
							success: function(response) {
								if(response.error){
									alert('There was an error: ' + response.error);
									return;
								};
								if(response.success) {
									countPeakFiles_AJAX();
									alert('All temporary peak files removed.');
								} else {
									alert('There was an error: ' + response.message);
								}
								// Enable the button again
								$('#srmp3-bulkRemove-bt').prop('disabled', false);
							},
							error: function(xhr, status, error) {
								alert('An error occurred: ' + error);
								// Enable the button again
								$('#srmp3-bulkRemove-bt').prop('disabled', false);
							}
						});
					}else{
						$('#srmp3-bulkRemove-bt').prop('disabled', false);
					}
				},
				error: function(xhr, status, error) {
					alert('An error occurred while counting the files: ' + error);
				}
			});
		});

		$('#srmp3_indexTracks').click(function(e) {
			e.preventDefault();
			$('#srmp3_indexTracks').siblings('#indexationProgress').css('display', 'inline-block').val(0);
			var originalText = $(this).text();
			$(this).text("Indexing Tracks...");
			$(this).addClass('spinningIcon showSpinner').removeClass('showCheckmark');
			indexPosts_AJAX(0, originalText);
		});

		function indexPosts_AJAX(offset, originalText) {
			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'index_alb_tracklist_for_lazyload',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce,
					offset: offset
				},
				success: function(response) {
					console.log(response);
		
					if (response.totalPosts && response.processedPosts) {
						$('#srmp3_indexTracks').siblings('#progressText').text(response.processedPosts + " / " + response.totalPosts + " posts");
					}
		
					if (response.progress) {
						$('#srmp3_indexTracks').siblings('#indexationProgress').val(Math.round(Number(response.progress)));
					}
		
					if (response.message) {
						$('#srmp3_indexTracks').siblings('#srmp3_indexTracks_status').text(response.message);
					}
		
					if (response.completed) {
						$('#srmp3_indexTracks').siblings('#indexationProgress').css('display', 'none');
						$('#srmp3_indexTracks').siblings('#srmp3_indexTracks_status').text('Completed 🎉 ' + response.message);
						$('#srmp3_indexTracks')
							.text(originalText)
							.removeClass('showSpinner spinningIcon')
							.addClass('showCheckmark');
					} else {
						indexPosts_AJAX(offset + 250, originalText);
					}
				},
				error: function() {
					$('#srmp3_indexTracks').text(originalText);
					$('#srmp3_indexTracks').siblings('#srmp3_indexTracks_status').text('An error occurred.');
				}
			});
		}

	}


	function init_srmp3_audioPreview(){
		if ($('.option-srmp3_settings_audiopreview').length || $('#acf_albums_infos').length) {
		}else{
			return;
		}

		//console.log("INIT SRMP3 AUDIOREVIEW");
		// For audio preview generation
		var continueIndexing = true;
		var completed;
		var originalText;
		let timerInterval;
        let startTime;

		if ($('.option-srmp3_settings_audiopreview .audiopreview-denied').length) {
			$('.option-srmp3_settings_audiopreview .cmb-row:not(:first-child):not(:nth-child(2))').css('opacity', '0.5').css('pointer-events', 'none');
			$('.option-srmp3_settings_audiopreview .submit').css('opacity', '0.5').css('pointer-events', 'none');
		}

		$('.srmp3-cmb2-preview-file .file-status.cmb2-media-item').each(function() {
			let content = $(this).html();
			content = content.replace(/&nbsp;&nbsp;/g, '');
			$(this).html(content);
		});

		$('.srmp3-post-all-audiopreview-bt').click(function(e) {
			// GENERATE BT POST ALL
			startTimer($(this));
			trackLength = $('.srmp3-audiopreview-bt').length;
			e.preventDefault(); // Prevent any default behavior of the button
			var userConfirmation = confirm("Are you sure you want to proceed?\n\nWe will generate " + trackLength + " preview files.");
			if (userConfirmation) {
				$('.srmp3-audiopreview-bt').trigger('click');
				$(this).css('opacity', '0.5').css('pointer-events', 'none');
				$(this).siblings('#srmp3_indexTracks_status').text('Processing...');
				$(this).addClass('spinningIcon showSpinner').removeClass('showCheckmark');
				completed = false;
			} // Trigger click event on all elements with the class srmp3-audiopreview-bt
		});

		$(document).on('click', '.srmp3-audiopreview-bt', function(e) {
			
			// GENERATE BT SPECIFIC TRACK
			e.preventDefault();
			const $this = $(this);

			$this.css('opacity', '0.5').css('pointer-events', 'none');
			var parentRow = $this.closest('.cmb-row');
		
			$this.siblings('#indexationProgress').css('display', 'inline-block').val(0);
			originalText = $this.text();
			$this.text("Generating the file(s)...");
			$this.addClass('spinningIcon showSpinner').removeClass('showCheckmark');

			if (!$this.siblings('#stopIndexingButton').length) {
				$this.after('<button id="stopIndexingButton" class="srmp3-stopgenerate-bt">Stop</button>');
			}
			$this.siblings('#srmp3_indexTracks_status').text('Processing...');
			var posts_in;
			var postID = $('#post_ID').val();
			var index = null;


			if (postID) {
				//console.log('we are in a POST !!');
				var selectElem = parentRow.prevAll('.cmb-row').find('select.cmb2_select[name^="alb_tracklist["]').first();

				if (selectElem.length > 0) {
					var selectName = selectElem.attr('name');
					var indexMatch = selectName.match(/\[(\d+)\]/);
					index = indexMatch ? indexMatch[1] : null;
				} else {
					console.error("Select element not found!");
				}

				const hasCMB2Changes = hasChanges;
				
				if (isGutenbergActive()) {
					startTimer($this);

					const isSaving = wp.data.select('core/editor').isSavingPost();
					const hasContentChanges = wp.data.select('core/editor').hasChangedContent();
				
					if (!isSaving && (hasContentChanges || hasCMB2Changes)) {
						wp.data.dispatch('core/editor').savePost().then(() => {
							hasChanges = false;
							indexAudioPreview_AJAX(0, originalText, postID, index, $(this), posts_in);
						}).catch(error => {
							stopTimer($this);
							console.error("Failed to save the post:", error);
						});
					} else {
						// console.log("No changes detected or post is currently being saved.");
						indexAudioPreview_AJAX(0, originalText, postID, index, $(this), posts_in);
					}
				} else {
					if (hasCMB2Changes) {
						const publishButton = document.getElementById('publish');
						if (publishButton) {
							if (confirm("We must save the post first. Please click the Generate Preview again once the save is complete.")) {
								publishButton.click();
							} else {
								console.log("User canceled the save operation.");
								// Handle the cancel action if necessary
							}
						}
					} else {
						startTimer($this);
						indexAudioPreview_AJAX(0, originalText, postID, index, $(this), posts_in);
					}
				}

			}else{
				//check if we have post_id in the URL query
				const url = new URL(window.location.href);
				var posts_in = url.searchParams.get("posts_in");
				startTimer($this);
				indexAudioPreview_AJAX(0, originalText, postID, index, $(this), posts_in);

			}

			
		});

		function isGutenbergActive() {
			return document.body.classList.contains('block-editor-page');
		}

		$(document).on('click', '#stopIndexingButton', function() {
			continueIndexing = false;
			countFiles_AJAX();
			var btn = $(this).siblings('.srmp3-audiopreview-bt');
			btn.text(originalText)
				.removeClass('showSpinner spinningIcon')
				.addClass('showCheckmark')
				.css('opacity', '1')
				.css('pointer-events', 'initial');

			btn.siblings('#srmp3_indexTracks_status').text('Stopped by user. ');
			stopTimer($(btn));
			
			$('#indexationProgress').css('display', 'none');
			$(this).remove();
		});

		var delete_bt_originalText = $('#srmp3-bulkRemove-bt').html();

		function countFiles_AJAX(){
			if($('.audiopreview-denied').length) return;

			//need a better check here should move it up
			if(!$('.option-srmp3_settings_audiopreview').length) return; 
			
			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'count_audio_files',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce,
				},
				success: function(response) {
					var fileCount = response.count-1;
					// Append fileCount to the original text
					$('#srmp3-bulkRemove-bt').html(delete_bt_originalText + ' (' + fileCount + ')');
				},
				error: function(xhr, status, error) {
					//return false;
				}
			});
		}
		countFiles_AJAX();

		$('#srmp3-bulkRemove-bt').click(function(e) {
			e.preventDefault();

			// Disable the button to avoid multiple clicks
			$(this).prop('disabled', true);

			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'count_audio_files',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce
				},
				success: function(response) {
					var fileCount = response.count;
					var userConfirmation = confirm("Time to cleanup !?\n\nAre you sure you want to remove the " + fileCount + " audio preview files on your server? You will need to re-generate them again.");
					if (userConfirmation) {
						// Start the removal process
						$.ajax({
							url: sonaar_admin_ajax.ajax.ajax_url,
							type: 'post',
							dataType: 'json',
							data: {
								action: 'remove_audio_files_and_update_posts',
								nonce: sonaar_admin_ajax.ajax.ajax_nonce
							},
							success: function(response) {
								if(response.success) {
									countFiles_AJAX();
									alert('All files removed and posts updated successfully!');
								} else {
									alert('There was an error: ' + response.message);
								}
								// Enable the button again
								$('#srmp3-bulkRemove-bt').prop('disabled', false);
							},
							error: function(xhr, status, error) {
								alert('An error occurred: ' + error);
								// Enable the button again
								$('#srmp3-bulkRemove-bt').prop('disabled', false);
							}
						});
					}else{
						$('#srmp3-bulkRemove-bt').prop('disabled', false);
					}
				},
				error: function(xhr, status, error) {
					alert('An error occurred while counting the files: ' + error);
				}
			});
		});

		function indexAudioPreview_AJAX(offset, originalText, postID, index, clickedButton, posts_in) {
			if (!continueIndexing) {
				return;
			}

			//* start of Post EDIT */ 
			let customData = {};
			let audiourl = clickedButton.closest('.cmb-repeatable-grouping').find('.srmp3-admin-track-player-container audio source').attr('src');
			let audioTitle = clickedButton.closest('.cmb-repeatable-grouping').find('#alb_tracklist_' + index + '_stream_title').val();
			
			var $clickedButton = clickedButton.closest('.cmb-repeatable-grouping');
			var $targetElement = $clickedButton.find('#alb_tracklist_' + index + '_post_trimstart').closest('.cmb-row');

			if ($targetElement.length && $targetElement.css('display') !== 'none') {
				customData = {
					trimstart: clickedButton.closest('.cmb-repeatable-grouping').find('#alb_tracklist_' + index + '_post_trimstart').val(),
					previewLength: clickedButton.closest('.cmb-repeatable-grouping').find('#alb_tracklist_' + index + '_post_audiopreview_duration').val(),
					fadein: clickedButton.closest('.cmb-repeatable-grouping').find('#alb_tracklist_' + index + '_post_fadein_duration').val(),
					fadeout: clickedButton.closest('.cmb-repeatable-grouping').find('#alb_tracklist_' + index + '_post_fadeout_duration').val(),
					watermarkFile: clickedButton.closest('.cmb-repeatable-grouping').find('#alb_tracklist_' + index + '_post_audio_watermark').val(),
					watermarkGap: clickedButton.closest('.cmb-repeatable-grouping').find('#alb_tracklist_' + index + '_post_audio_watermark_gap').val(),
					prerollFile: clickedButton.closest('.cmb-repeatable-grouping').find('#alb_tracklist_' + index + '_post_ad_preroll').val(),
					postrollFile: clickedButton.closest('.cmb-repeatable-grouping').find('#alb_tracklist_' + index + '_post_ad_postroll').val()
				};
			}
			//* end of Post EDIT */ 

			//* start of Bulk Option Page */ 
			let optionData = {};

			$optionElement = clickedButton.closest('.option-srmp3_settings_audiopreview');
			if ($optionElement.length) {
				optionData = {
					trimstart: $optionElement.find('#trimstart').val(),
					previewLength: $optionElement.find('#audiopreview_duration').val(),
					fadein: $optionElement.find('#fadein_duration').val(),
					fadeout: $optionElement.find('#fadeout_duration').val(),
					watermarkFile: $optionElement.find('#audio_watermark').val(),
					watermarkGap: $optionElement.find('#watermark_spacegap').val(),
					prerollFile: $optionElement.find('#ad_preroll').val(),
					postrollFile: $optionElement.find('#ad_postroll').val(),
				};
			}
			//* end of Bulk Option Page */

			$.ajax({
				url: sonaar_admin_ajax.ajax.ajax_url,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'index_audio_preview',
					nonce: sonaar_admin_ajax.ajax.ajax_nonce,
					offset: offset,
					post_id: postID,
					posts_in: posts_in,
					index: index,
					audioUrl: audiourl,
					audioTitle: audioTitle,
					customData: customData,
					optionData: optionData,
				},
				success: function(response) {
					countFiles_AJAX();
					//console.log(response);
					indexPos = parseInt(index) + 1;
					trackLength = $('.srmp3-audiopreview-bt').length;

					if (response.totalPosts && response.processedPosts) {
						clickedButton.siblings('#progressText').text(response.processedPosts + " / " + response.totalPosts + " posts");
					}

					if (response.progress) {
						clickedButton.siblings('#indexationProgress').val(Math.round(Number(response.progress)));
					}
		
					if (response.message) {
						if(indexPos != trackLength && !completed){
							$('.srmp3-post-all-audiopreview-bt').siblings('#srmp3_indexTracks_status').text(indexPos + ' / ' + trackLength + ' ' + response.message );
							clickedButton.siblings('#srmp3_indexTracks_status').text(response.message);
						}
					}
					if (response.completed) {
						var clickableLink = '';

						file_path = response.file_output;

						$('#alb_tracklist_'+index+'_audio_preview').val(response.file_output);

						if(!response.error){
							//console.log(indexPos,trackLength );
							if(indexPos == trackLength){
								completed = true;
								$('.srmp3-post-all-audiopreview-bt').siblings('#srmp3_indexTracks_status').html('Showtime! 🎉 ( ' + indexPos + ' / ' + indexPos + ' ) <em>Don\'t forget to <strong>save</strong> this post.</em>');
								stopTimer($('.srmp3-post-all-audiopreview-bt'));

							}
							
							if (response.file_output != null && response.file_output != ''){
								var timestamp = new Date().getTime();
								clickableLink = '<a href="' + file_path + '?_=' + timestamp + '" target="_blank">Listen Preview</a>';
								clickedButton.siblings('#progressText').text('');
								clickedButton.siblings('#srmp3_indexTracks_status').html('Success! 🎉 (' + clickableLink + ') <em>Don\'t forget to <strong>save</strong> this post.</em>').css('margin-right', '10px');
								stopTimer(clickedButton);

							}else{
								clickedButton.siblings('#srmp3_indexTracks_status').html('Showtime! 🎉').css('margin-right', '10px');
								stopTimer(clickedButton);
							}
						}else{
							stopTimer(clickedButton);
							// There is an error!
							clickedButton.siblings('#srmp3_indexTracks_status').html('<span style=color:red;>' + response.message + '</span>').css('margin-right', '10px');
							
						}
						
						clickedButton.siblings('#stopIndexingButton').remove();

						clickedButton.siblings('#indexationProgress').css('display', 'none');
						

						$('.srmp3-post-all-audiopreview-bt')
							.removeClass('showSpinner spinningIcon')
							.addClass('showCheckmark')
							.css('opacity', '1')
							.css('pointer-events', 'initial');

						clickedButton
							.text(originalText)
							.removeClass('showSpinner spinningIcon')
							.addClass('showCheckmark')
							.css('opacity', '1')
							.css('pointer-events', 'initial');
					} else {
						indexAudioPreview_AJAX(offset + parseInt(sonaar_music_pro.option.preview_batch_size), originalText, postID, index, clickedButton, posts_in);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					clickedButton.siblings('#stopIndexingButton').remove();
					clickedButton.siblings('#srmp3_index_audio_preview').text(originalText);
					
					if (jqXHR.status == 500) {
						// Server-side error
						clickedButton.siblings('#srmp3_indexTracks_status').text('An error occurred. It might be due to the server max execution time limit reached.');
					} else {
						// Some other error
						clickedButton.siblings('#srmp3_indexTracks_status').text('An error occurred. ' + jqXHR.responseText);
					}
				
					clickedButton
						.text(originalText)
						.removeClass('showSpinner spinningIcon')
						.addClass('showCheckmark')
						.css('opacity', '1')
						.css('pointer-events', 'initial');
				}
			}).always(function() {
				// Code to execute after the AJAX request completes
				//console.log('AJAX request completed');
			});
		}

		function createTimerDiv(parentElement) {
            const timerDiv = document.createElement('div');
            timerDiv.className = 'timer';
            timerDiv.innerText = '0.00';
            parentElement.before(timerDiv);
			timerDiv.style.display = 'inline-block';
			//set font family monospace
			timerDiv.style.fontFamily = 'monospace';
			//font size 11px
			timerDiv.style.fontSize = '11px';

            return timerDiv;
        }

        function removeTimerDiv(parentElement) {
            const timerDiv = parentElement.previousElementSibling;
            if (timerDiv && timerDiv.className === 'timer') {
                timerDiv.remove();
            }
        }

		function updateTimer(timerDiv, startTime) {
			return function() {
				const currentTime = Date.now();
				const timeElapsed = (currentTime - startTime);
				const seconds = (timeElapsed / 1000).toFixed(2);
				timerDiv.innerText = seconds;
			}
		}
		
		function startTimer(button) {
			const parentRow = button.closest('.cmb-row')[0];
			const indexationProgress = parentRow.querySelector('#srmp3_indexTracks_status');
			removeTimerDiv(indexationProgress); // Ensure previous timer is removed
			const timerDiv = createTimerDiv(indexationProgress);
			const startTime = Date.now();
			const timerInterval = setInterval(updateTimer(timerDiv, startTime), 1); // Update every 10ms for higher precision
			button.data('timerInterval', timerInterval);
		}

        function stopTimer(button) {
            const parentRow = button.closest('.cmb-row')[0];
            const indexationProgress = parentRow.querySelector('#srmp3_indexTracks_status');
            if (button.data('timerInterval')) {
                clearInterval(button.data('timerInterval'));
                button.removeData('timerInterval');
            }
            removeTimerDiv(indexationProgress);
        }

	}


	function init_toggleTracklistBox(){

		const button = document.createElement('div');

		button.textContent = 'Expand/Collapse All';

		button.classList.add('button', 'button-secondary' , 'srmp3-expand-collapse');

		const targetDiv = document.querySelector('div[data-groupid="alb_tracklist"] .cmb-row');
		if (targetDiv) {
			targetDiv.appendChild(button, targetDiv.firstChild);
		}

		button.addEventListener('click', toggleClosedClass);
		function toggleClosedClass() {
			const divs = document.querySelectorAll('div.postbox .cmb-row .cmb-repeatable-grouping');
			divs.forEach(div => {
				if (sonaar_music.option.collapse_tracklist_backend === 'true') {
					div.classList.remove('closed');
				} else {
					div.classList.add('closed');
				}
			});
			sonaar_music.option.collapse_tracklist_backend = (sonaar_music.option.collapse_tracklist_backend === 'true') ? 'false' : 'true';
		}
	}

	function hideShowTracklistStorelist() {
		// hide or show the tracklist and store list fields if the player type is set to "csv or rss" in the admin area
		var selectElement = document.getElementById("post_playlist_source");
		if (selectElement === null) return;
		var albTracklist = document.querySelector(".cmb2-id-alb-tracklist");
		var albStoreList = document.querySelector(".cmb2-id-alb-store-list.cmb-repeat");

		if (selectElement.value === "csv" || selectElement.value === "rss") {
		albTracklist.style.display = "none";
		albStoreList.style.display = "none";
		}

		selectElement.addEventListener("change", function() {
		if (selectElement.value === "csv"  || selectElement.value === "rss") {
			albTracklist.style.display = "none";
			albStoreList.style.display = "none";
		} else {
			albTracklist.style.display = "";
			albStoreList.style.display = "";
		}
		});
	}
	// When a new group row is added, clear selection and initialise Select2

	var observer;
	var addNewTrackButtonLabel;

	function init_CheckMaxInputVars(){
		var increaseMaxInputVars = sonaar_admin_ajax?.translations?.increaseMaxInputVars;

		if (increaseMaxInputVars) {
			increaseMaxInputVars = JSON.parse(increaseMaxInputVars); // Only parse if it's defined
			alert(increaseMaxInputVars);
		}

	}
	function init_TrackTitleOnRepeater(newRow){
		
		 // PREVENT MULTIPLE PLAY AT THE SAME TIME
		jQuery('audio').on('play', function(event) {
			var playingAudio = event.currentTarget; // Get the current target for the event
			// Pause other tracks
			jQuery('audio').each(function() {
				if (this !== playingAudio) {
				this.pause();
				}
			});
		});

		// RESET THE SOME FIELDS WHEN WE CLICK ADD NEW ROW
		if (typeof newRow !== 'undefined') {
			newRow.find('.srmp3-admin-track-player-container').css('display', 'none');
			newRow.find('.srmp3-admin-track-player-container audio source').attr('src', '');
			newRow.find('.cmb2-upload-button:first').val(addNewTrackButtonLabel);
		}
		
		// Set a timeout variable to be used for debouncing
		var timeoutId;
		
		// --------------------------------------------
		// Make the following input fields REACTIVE
		// --------------------------------------------
		var inputFields = document.querySelectorAll('.srmp3-cmb2-file input,  .sr-stream-url-field input, .srmp3-fileorstream select');
		inputFields.forEach(function(inputField) {
			inputField.addEventListener('input', function() {
				clearTimeout(timeoutId);
				var $myElement = inputField.closest('.cmb-repeatable-grouping');
				var myElementArray = $myElement ? [$myElement] : [];
				// Set a new timeout to call the function after 1500 milliseconds since we type in the field.
				timeoutId = setTimeout(addTrackTitletoTrackRepeater(myElementArray), 1000);
			});
		});
		
		// --------------------------------------------
		// Update Titles and Players in our admin custom fields
		// --------------------------------------------

		// If there is a previous observer, disconnect it
		if (observer) {
			observer.disconnect();
		}

		function onMutation(mutationsList, observer) {
			// Check if there are any childList mutations
			var hasChildListMutation = mutationsList.some(mutation => mutation.type === 'childList');

			if (hasChildListMutation) {

				// Clear any existing timeouts
				clearTimeout(timeoutId);

				  // Check if the mutation occurred inside .sr-tts-player-container
				  var isInsidePlayerContainer = mutationsList.some(mutation => {
					var target = mutation.target;
					while (target !== null) {
						if (target.classList.contains('srmp3-admin-track-player-container')) { // we dont want to create an infinite loop
							return true;
						}
						target = target.parentElement;
					}
					return false;
				});
		
				// If the mutation occurred inside .sr-tts-player-container, do nothing
				if (isInsidePlayerContainer) {
					return;
				}

				
				var $myElement = mutationsList[0].target.closest('.cmb-repeatable-grouping');

				// Clear the value of the peak field when we change track file
				var peakField = $myElement.querySelector('[name^="alb_tracklist"][name$="[track_peaks]"]'); 
				if (peakField) {
					peakField.value = '';
				}

				var myElementArray = $myElement ? [$myElement] : [];
				// Set a new timeout to call the function after a seconds
				timeoutId = setTimeout(addTrackTitletoTrackRepeater(myElementArray), 1000);
			}
		}

		// Create a new observer instance
		observer = new MutationObserver(onMutation);

		var fileStatusElements = document.querySelectorAll('.srmp3-cmb2-file');
		fileStatusElements.forEach(function(element) {
			observer.observe(element, { childList: true, subtree: true });
		});

	}


	function addTrackTitletoTrackRepeater(el = null) {
		// Get all the elements containing both the track title and filename
		if(el){
			var trackElements = el;
		}else{
			var trackElements = document.querySelectorAll('#alb_tracklist_repeat .cmb-repeatable-grouping');

		}
		if(!addNewTrackButtonLabel){
			addNewTrackButtonLabel = trackElements[0].querySelector('.cmb2-upload-button').value;
		}
		// Loop through each track element
		trackElements.forEach(function(trackElement) {
			// Find the track title span within this track element
			var trackTitle = trackElement.querySelector('.cmb-group-title.cmbhandle-title');
		
			var selectElement = trackElement.querySelector('select[name$="[FileOrStream]"]');
			var selectedOptionValue = selectElement.value;

			var $track;
			var audioURL = '';
			
			switch (selectedOptionValue) {
				case 'mp3':
					let mp3Element = trackElement.querySelector('.srmp3-cmb2-file .cmb2-media-status strong');
					audioURL = trackElement.querySelector('.srmp3-cmb2-file .cmb2-media-status a');
					if(audioURL && audioURL.attributes){
						audioURL = audioURL.attributes.href.value;
					}else{
						audioURL = '';
					}
					var targetLocation = trackElement.querySelector('.cmb2-upload-file-id').parentNode;

					$track = mp3Element ? mp3Element.innerText : '';
					break;
			
				case 'stream':
					let streamElement = trackElement.querySelector('.srmp3-cmb2-file input[name$="[stream_title]"]');
					audioURL = trackElement.querySelector('.sr-stream-url-field input[name$="[stream_link]"]');
					audioURL = audioURL ? audioURL.value : '';
					var targetLocation = trackElement.querySelector('.cmb2-text-url').parentNode;
					$track = streamElement ? streamElement.value : '';
					break;
			
				case 'icecast':
					let icecastElement = trackElement.querySelector('.srmp3-cmb2-file input[name$="[icecast_link]"]');
					audioURL = icecastElement ? icecastElement.value : '';
					var targetLocation = icecastElement ? icecastElement.closest('.cmb-td') : null;
					$track = icecastElement ? icecastElement.value : '';
					break;
			
				default:
					$track = '';
			}
			
			var playerContainer = trackElement.querySelector('.srmp3-admin-track-player-container');
			// Now check if the playerContainer is already at the targetLocation
			if (playerContainer.parentNode !== targetLocation) {
			  // Move the playerContainer to the target location only if necessary
			  targetLocation.appendChild(playerContainer);
			}
		  
			// Display the audio player
			if(audioURL){

				trackElement.querySelector('.cmb2-upload-button').value = 'Edit Track Details';

				audioElement = trackElement.querySelector('.srmp3-admin-track-player-container audio');
				var audioSourceElement = trackElement.querySelector('.srmp3-admin-track-player-container audio source');
				if (audioSourceElement) {
					audioSourceElement.setAttribute('src', audioURL);
				}

				audioElement.load();

				trackElement.querySelector('.srmp3-admin-track-player-container').style.display = 'block';

			}else{
				trackElement.querySelector('.cmb2-upload-button').value = addNewTrackButtonLabel;
				trackElement.querySelector('.srmp3-admin-track-player-container').style.display = 'none';
			}

			// Set Track Title and Number
			if (trackTitle && $track) {
				// Extract the track number
				var trackNumber = trackTitle.innerText.split(' : ')[0];

				// Create a new filename span element
				var fileNameSpan = document.createElement('span');
				fileNameSpan.className = 'srp-cmb2-filename';
				fileNameSpan.innerText = $track;

				// Remove any existing filename span element
				var existingFileNameSpan = trackTitle.querySelector('.srp-cmb2-filename');
				if (existingFileNameSpan) {
					existingFileNameSpan.remove();
				}

				// Set the track title text content and append the filename span element
				trackTitle.innerText = trackNumber + ' : ';
				trackTitle.appendChild(fileNameSpan);
			}
		});
	}

	//Load Music player Content (For Gutenberg ?!)
	function setIronAudioplayers(){
		if($('#cmb2-metabox-srmp3_settings_shortcodebuilder').length) return; // we dont want this interfer with the shortcode builder.
		if (typeof IRON === 'undefined') return;

		setTimeout(function(){ 
			IRON.players = []
			$('.iron-audioplayer').each(function(){
				var player = Object.create(  IRON.audioPlayer )
				player.init($(this))

				IRON.players.push(player)
			})
		}, 4000);
	
	}
	setIronAudioplayers();
})(jQuery);