(function ($) {
    $(document).ready(function () {
         //check if .sr-post-search-ajax is in the page otherwise return
         if ($('.sr-post-search-ajax').length === 0) {
            return; // Exit if no .sr-post-search-ajax fields are present
        }
        $('.sr-post-search-ajax').each(function () {
            var postType = $(this).data('post-type');
            var metaQuery = $(this).data('meta-query'); // Get the meta query from the field
            var taxonomy = $(this).data('taxonomy'); // Get the taxonomy if provided
            var searchType = $(this).data('search-type') || 'post'; // Default to 'post'
            var isMultiple = $(this).data('select-behavior') === 'add';
        
            var selectElement = $(this);
            console.log("yo", );
        
            selectElement.select2({
                multiple: isMultiple, // Enable multiple if 'add'
                ajax: {
                    url: SR_Select2_Ajax.ajax_url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            post_type: postType,
                            taxonomy: taxonomy, // Include taxonomy for taxonomy searches
                            search_type: searchType, // Specify the type of search
                            meta_query: JSON.stringify(metaQuery), // Pass meta_query as JSON
                            nonce: SR_Select2_Ajax.nonce,
                            action: 'sr_post_search',
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {
                                    id: item.id, // Use post ID or term ID as value
                                    text: item.text, // Use post title or taxonomy term as text
                                };
                            }),
                        };
                    },
                    cache: true,
                },
                allowClear: true, // Enable the clear button
                minimumInputLength: 1,
                language: {
                    inputTooShort: function () {
                        return 'Enter 1 or more characters...'; // Text to display as a "placeholder" in the search box
                    },
                },
            });
        
            // Handle clear event to prevent errors
            selectElement.on('select2:clear', function () {
                // Explicitly set the value to null to avoid undefined errors
                $(this).val(null).trigger('change');
            });
        });
        

       // Set or remove required attribute based on visibility, only if initially set by PHP
       $('.cmb-row').each(function () {
            var cmbRow = $(this);
            var selectField = cmbRow.find('.sr-post-search-ajax');

            // Check if the field has the required attribute initially
            if (!selectField.attr('required')) {
                return; // Skip if not initially required
            }

            // Initialize MutationObserver
            var observer = new MutationObserver(function () {
                if (cmbRow.is(':visible')) {
                    // Set required attribute if .cmb-row is visible
                    selectField.attr('required', 'required');
                } else {
                    // Remove required attribute if .cmb-row is hidden
                    selectField.removeAttr('required');
                }
            });

            // Observe style attribute changes for visibility
            observer.observe(cmbRow[0], { attributes: true, attributeFilter: ['style'] });

            // Initial visibility check
            if (cmbRow.is(':visible')) {
                selectField.attr('required', 'required');
            } else {
                selectField.removeAttr('required');
            }
        });
    });
})(jQuery);
