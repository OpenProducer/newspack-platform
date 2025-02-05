document.addEventListener('DOMContentLoaded', function() {
    document.documentElement.classList.remove('wp-toolbar');

    var radios = document.querySelectorAll('.sr-wizard-radio-option input[type="radio"]');
    var rssFeedSection = document.querySelector('.sr-wizard-item.has_rss_feed');

    // Function to update the active class based on the checked status
    function updateActiveClasses() {
        radios.forEach(function(item) {
            if (item.checked) {
                item.closest('.sr-wizard-radio-option').classList.add('active');
            } else {
                item.closest('.sr-wizard-radio-option').classList.remove('active');
            }
        });
    }
    
    // Function to toggle the RSS feed section
    function toggleRssFeedSection() {
        if (rssFeedSection === null) return;

        var selectedValue = document.querySelector('.sr-wizard-radio-option input[type="radio"][name="player_type"]:checked').value;
        if (selectedValue === 'podcast') {
            rssFeedSection.style.display = 'block';
        } else {
            rssFeedSection.style.display = 'none';
        }
    }

    // Initially update active classes on page load
    updateActiveClasses();
    toggleRssFeedSection();

    // Add event listeners to radio buttons
    radios.forEach(function(item) {
        item.addEventListener('change', function() {
            updateActiveClasses();
            toggleRssFeedSection();
        });
    });


    document.querySelectorAll('.sr-wizard-item').forEach(item => {
        const checkboxes = item.querySelectorAll('input[type="checkbox"]');
        const radios = item.querySelectorAll('input[type="radio"]');
    
        // Handle checkboxes
        checkboxes.forEach(checkbox => {
            item.addEventListener('click', function(e) {
                if (e.target.tagName.toLowerCase() !== 'input' && e.target.tagName.toLowerCase() !== 'span') {
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });
    
            if (checkbox.checked) {
                item.style.backgroundImage = 'linear-gradient(135deg, #7501e0, #9962d1)';
                item.style.color = '#FFFFFF';
            } else {
                item.style.backgroundImage = '';
                item.style.color = '';
            }
    
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    item.style.backgroundImage = 'linear-gradient(135deg, #7501e0, #9962d1)';
                    item.style.color = '#FFFFFF';
                } else {
                    item.style.backgroundImage = '';
                    item.style.color = '';
                }
            });
        });
    
        // Handle radio buttons
        radios.forEach(radio => {
            const radioOption = radio.closest('.sr-wizard-radio-option');
    
            radio.addEventListener('change', function() {
                // Clear styles for all radio options
                radios.forEach(r => {
                    const option = r.closest('.sr-wizard-radio-option');
                    option.style.backgroundImage = '';
                    option.style.color = '';
                });
    
                if (radio.checked) {
                    radioOption.style.backgroundImage = 'linear-gradient(135deg, #7501e0, #9962d1)';
                    radioOption.style.color = '#FFFFFF';
                }
    
                // Additional logic for displaying sections
                if (radio.name === 'player_type') {
                    const rssFeedSection = document.querySelector('.sr-wizard-item.has_rss_feed');
                    if (radio.value === 'podcast') {
                        rssFeedSection.style.display = 'block';
                    } else {
                        rssFeedSection.style.display = 'none';
                    }
                }
            });
    
            // Set initial styles for radio buttons
            if (radio.checked) {
                radioOption.style.backgroundImage = 'linear-gradient(135deg, #7501e0, #9962d1)';
                radioOption.style.color = '#FFFFFF';
            }
        });
    });
    
    
});
