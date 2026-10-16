jQuery( document ).on('change', '#inspector-select-control-0, #inspector-toggle-control-0, #inspector-toggle-control-1, #inspector-toggle-control-2, #inspector-toggle-control-3, #inspector-toggle-control-4', function (e) {
    setTimeout(function(){ 
        // jQuery('#inspector-select-control-0').select2();
        
		IRON.players = []
		jQuery('.iron-audioplayer').each(function(){

			var player = Object.create(  IRON.audioPlayer )
			player.init(jQuery(this))

			IRON.players.push(player)
		})
	 }, 2500);
});
var observer;

jQuery(document).ready(function($) {
	var $myRepeatGroup = $('#alb_tracklist_repeat');
	if ($myRepeatGroup.length) {
		//only execute if we are in presence of album repeater group.
		init_TrackTitleOnRepeater();
		addTrackTitletoTrackRepeater();
		init_toggleTracklistBox();
		hideShowTracklistStorelist();
		
		$( document ).on('cmb2_add_row', function (event, newRow) {
			init_TrackTitleOnRepeater();
		});
		
	}
	if($('#srmp3_indexTracks').length){

		// Select all the input fields and checkboxes within your DOM structure
		const inputsAndCheckboxes = document.querySelectorAll('.cmb-row input');

		// Add an event listener to each input and checkbox
		inputsAndCheckboxes.forEach(function(input) {
			function handleInputChange() {
				$('#srmp3_indexTracks').css('opacity', '0.5').css('pointer-events', 'none');
				
				// Check if the message is already present; if not, add it
				if ($('#saveChangeMessage').length === 0) {
					$('#srmp3_indexTracks').after('<span id="saveChangeMessage" style="margin-left:10px; color:red;">Save Changes before Rebuiling Index.</span>');
				}
			}
			if (input.type === 'text') {
				input.addEventListener('input', handleInputChange);
			} else {
				input.addEventListener('change', handleInputChange);
			}
		});

		$('#srmp3_indexTracks').click(function(e) {
			e.preventDefault();
			$('#indexationProgress').css('display', 'inline-block').val(0);
			var originalText = $(this).text();
			$(this).text("Indexing Tracks...");
			$(this).addClass('spinningIcon showSpinner').removeClass('showCheckmark');
			indexPosts(0, originalText);
		});
	
		function indexPosts(offset, originalText) {
			$.ajax({
				url: sonaar_music_pro.ajaxurl,
				type: 'post',
				dataType: 'json',
				data: {
					action: 'index_alb_tracklist_for_lazyload',
					offset: offset
				},
				success: function(response) {
					console.log(response);

					if (response.totalPosts && response.processedPosts) {
						$('#progressText').text(response.processedPosts + " / " + response.totalPosts + " posts");
					}

					if (response.progress) {
						$('#indexationProgress').val(Math.round(Number(response.progress)));
					}
		
					if (response.message) {
						$('#srmp3_indexTracks_status').text(response.message);
					}
		
					if (response.completed) {
						$('#indexationProgress').css('display', 'none');
						$('#srmp3_indexTracks')
							.text(originalText)
							.removeClass('showSpinner spinningIcon')
							.addClass('showCheckmark');
					} else {
						indexPosts(offset + 250, originalText);
					}
				},
				error: function() {
					$('#srmp3_indexTracks').text(originalText);
					$('#srmp3_indexTracks_status').text('An error occurred.');
				}
			});
		}
	}
	
	
	

});
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

function init_TrackTitleOnRepeater(){
	// Set a timeout variable to be used for debouncing
	var timeoutId;
	
	// --------------------------------------------
	// Update Titles for External Audio Files for our admin custom fields
	// --------------------------------------------
	var inputFields = document.querySelectorAll('.srmp3-cmb2-file input');
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
	// Update Titles for Local MP3 for our admin custom fields
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
			var $myElement = mutationsList[0].target.closest('.cmb-repeatable-grouping');
			var myElementArray = $myElement ? [$myElement] : [];
            // Set a new timeout to call the function after 3 seconds
            timeoutId = setTimeout(addTrackTitletoTrackRepeater(myElementArray), 1000); // 3000 milliseconds = 3 seconds
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
  
	// Loop through each track element
	trackElements.forEach(function(trackElement) {
	 	// Find the track title span within this track element
	  	var trackTitle = trackElement.querySelector('.cmb-group-title.cmbhandle-title');
	
	  	var selectElement = trackElement.querySelector('select[name$="[FileOrStream]"]');
		var selectedOptionValue = selectElement.value;
		var $track;
		switch (selectedOptionValue) {
			case 'mp3':
				let mp3Element = trackElement.querySelector('.srmp3-cmb2-file .cmb2-media-status strong');
				$track = mp3Element ? mp3Element.innerText : '';
				break;
		
			case 'stream':
				let streamElement = trackElement.querySelector('.srmp3-cmb2-file input[name$="[stream_title]"]');
				$track = streamElement ? streamElement.value : '';
				break;
		
			case 'icecast':
				let icecastElement = trackElement.querySelector('.srmp3-cmb2-file input[name$="[icecast_link]"]');
				$track = icecastElement ? icecastElement.value : '';
				break;
		
			default:
				$track = '';
		}
  
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

//Load Music player Content
function setIronAudioplayers(){
	if (typeof IRON === 'undefined') return;

	setTimeout(function(){ 
		IRON.players = []
		jQuery('.iron-audioplayer').each(function(){

			var player = Object.create(  IRON.audioPlayer )
			player.init(jQuery(this))

			IRON.players.push(player)
		})
	 }, 4000);
  
}

setIronAudioplayers();
