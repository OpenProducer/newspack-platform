<?php
// Enqueue parent and child theme styles
function newspack_child_enqueue_styles() {
    $parent_handle = 'newspack-style';
    $version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style( $parent_handle, get_template_directory_uri() . '/style.css', [], $version );
    wp_enqueue_style( 'newspack-child-style', get_stylesheet_directory_uri() . '/style.css', [ $parent_handle ], $version );
}
add_action( 'wp_enqueue_scripts', 'newspack_child_enqueue_styles' );

// Inject categories, event date, and venue into the carousel block
function inject_event_categories_date_and_venue_into_carousel($block_content, $block) {
    // Check if the block is a newspack-blocks/carousel
    if (isset($block['blockName']) && $block['blockName'] === 'newspack-blocks/carousel') {
        error_log('Carousel block detected. Processing articles...');

        // Load the block content into a DOMDocument
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();

        // Handle potential encoding issues
        $block_content_utf8 = mb_convert_encoding($block_content, 'HTML-ENTITIES', 'UTF-8');
        $dom->loadHTML('<!DOCTYPE html><html><body>' . $block_content_utf8 . '</body></html>');

        $xpath = new DOMXPath($dom);
        $articles = $xpath->query('//article');

        $current_time = current_time('timestamp');
        $articles_data = [];

        foreach ($articles as $article) {
            $post_id = $article->getAttribute('data-post-id');
            error_log("Processing article for post ID: $post_id");

            // Get the event start date
            $event_start_date = get_post_meta($post_id, '_EventStartDate', true);
            if (!$event_start_date || strtotime($event_start_date) < $current_time) {
                error_log("Skipping past event for post ID: $post_id");
                continue;
            }

            $articles_data[] = [
                'node' => $article,
                'start_date' => strtotime($event_start_date)
            ];
        }

        // Sort articles by event start date
        usort($articles_data, function ($a, $b) {
            return $a['start_date'] <=> $b['start_date'];
        });

        // Clear existing articles and append sorted ones
        $container = $xpath->query('//div[contains(@class, "swiper-wrapper")]')->item(0);
        if ($container) {
            foreach ($container->childNodes as $child) {
                $container->removeChild($child);
            }

            foreach ($articles_data as $article_data) {
                $container->appendChild($article_data['node']);

                // Update category links and event date
                $post_id = $article_data['node']->getAttribute('data-post-id');
                $primary_category_id = get_post_meta($post_id, '_yoast_wpseo_primary_tribe_events_cat', true);
                $categories = get_the_terms($post_id, 'tribe_events_cat');
                $category_links = '';

                if ($primary_category_id) {
                    $primary_category = get_term($primary_category_id);
                    if ($primary_category && !is_wp_error($primary_category)) {
                        $category_url = get_term_link($primary_category);
                        $category_links .= '<a href="' . esc_url($category_url) . '">' . esc_html($primary_category->name) . '</a>';
                    }
                } elseif ($categories && !is_wp_error($categories)) {
                    foreach ($categories as $category) {
                        $category_url = get_term_link($category);
                        $category_links .= '<a href="' . esc_url($category_url) . '">' . esc_html($category->name) . '</a>, ';
                    }
                    // Remove trailing comma and space if no primary category
                    $category_links = rtrim($category_links, ', ');
                }

                $cat_links_div = $xpath->query('.//div[contains(@class, "cat-links")]', $article_data['node'])->item(0);
                if ($cat_links_div) {
                    $cat_links_div->nodeValue = '';
                    $fragment = $dom->createDocumentFragment();
                    $fragment->appendXML($category_links);
                    $cat_links_div->appendChild($fragment);
                }

                $event_date = get_post_meta($post_id, '_EventStartDate', true);
                $date_element = $xpath->query('.//time[contains(@class, "entry-date")]', $article_data['node'])->item(0);
                if ($event_date && $date_element) {
                    $formatted_date = date_i18n(get_option('date_format') . ' @ ' . get_option('time_format'), strtotime($event_date));
                    $date_element->nodeValue = $formatted_date;
                    $date_element->setAttribute('datetime', date('c', strtotime($event_date)));
                }

                // Add venue underneath the date
                $venue = '';

                // Prefer TEC venue ID; fallback to the old meta key if it exists.
                $venue_id = get_post_meta($post_id, '_EventVenueID', true);
                if ($venue_id) {
                    if (function_exists('tribe_get_venue')) {
                        $venue = tribe_get_venue($venue_id);
                    } else {
                        $venue = get_the_title($venue_id);
                    }
                }

                if (!$venue) {
                    $venue = get_post_meta($post_id, '_EventVenue', true);
                }

                $entry_meta = $xpath->query('.//div[contains(@class, "entry-meta")]', $article_data['node'])->item(0);
                if ($entry_meta) {
                    // Group date and venue into a single column to force stacking.
                    $when_where = $dom->createElement('div');
                    $when_where->setAttribute('class', 'event-when-where');

                    if ($date_element) {
                        if ($date_element->parentNode) {
                            $date_element->parentNode->removeChild($date_element);
                        }
                        $date_wrapper = $dom->createElement('div');
                        $date_wrapper->setAttribute('class', 'event-date');
                        $date_wrapper->appendChild($date_element);
                        $when_where->appendChild($date_wrapper);
                    }

                    if ($venue) {
                        $venue_wrapper = $dom->createElement('div');
                        $venue_wrapper->setAttribute('class', 'event-venue');
                        $venue_wrapper->appendChild($dom->createTextNode($venue));
                        $when_where->appendChild($venue_wrapper);
                    }

                    // Place the group inside entry-meta.
                    $entry_meta->appendChild($when_where);
                }
            }
        }

        // Return the modified block content
        $body = $dom->getElementsByTagName('body')->item(0);
        $block_content = $dom->saveHTML($body->firstChild);
    }

    return $block_content;
}
add_filter('render_block', 'inject_event_categories_date_and_venue_into_carousel', 10, 2);
