(()=>{var e;e=jQuery,wp.customize("header_display_tagline",(function(i){i.bind((function(i){!1===i?e("body").addClass("hide-site-tagline").removeClass("show-site-tagline"):e("body").removeClass("hide-site-tagline").addClass("show-site-tagline")}))})),wp.customize("hide_front_page_title",(function(i){i.bind((function(i){!0===i?e("body").addClass("hide-homepage-title"):e("body").removeClass("hide-homepage-title")}))})),wp.customize("show_author_bio",(function(i){i.bind((function(i){!1===i?e("body").addClass("hide-author-bio"):e("body").removeClass("hide-author-bio")}))})),wp.customize("show_author_email",(function(i){i.bind((function(i){!1===i?e("body").addClass("hide-author-email"):e("body").removeClass("hide-author-email")}))}))})();