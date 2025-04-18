/**
 * Constants for the settings page.
 */

/**
 * Settings page namespace.
 */
export const PAGE_NAMESPACE = 'newspack-settings';

/**
 * Theme and Brand.
 */
export const THEME_BRAND_DEFAULTS = {
	// Colors.
	header_color: 'custom',
	theme_colors: 'default',
	primary_color_hex: '#003da5',
	secondary_color_hex: '#666666',
	// Typography.
	font_header: '',
	font_body: '',
	accent_allcaps: true,
	custom_font_import_code: undefined,
	custom_font_import_code_alternate: undefined,
	// Header.
	header_center_logo: false,
	header_simplified: false,
	header_solid_background: false,
	header_color_hex: '#003da5',
	custom_logo: '',
	logo_size: 0,
	header_text: false, // No custom_logo set.
	header_display_tagline: false, // No custom_logo set.
	// Footer.
	footer_copyright: '',
	footer_color: 'default',
	footer_color_hex: '',
	newspack_footer_logo: '',
	footer_logo_size: 'medium',
	// Homepage pattern.
	homepage_pattern_index: 0,
};

export const DISPLAY_SETTINGS_DEFAULTS = {
	// Author Bio.
	show_author_bio: true,
	show_author_email: false,
	author_bio_length: 200,
	// Default Featured Image and Post Template.
	featured_image_default: 'large',
	post_template_default: 'default',
	// Featured Image and Post Template for All Posts.
	featured_image_all_posts: 'none',
	post_template_all_posts: 'none',
	// Media Credits.
	newspack_image_credits_placeholder_url: undefined,
	newspack_image_credits_class_name: 'image-credit',
	newspack_image_credits_prefix_label: 'Credit:',
	newspack_image_credits_placeholder: null,
	newspack_image_credits_auto_populate: false,
};

export const DEFAULT_THEME_MODS: ThemeMods = {
	...THEME_BRAND_DEFAULTS,
	...DISPLAY_SETTINGS_DEFAULTS,

	/**
	 * Misc.
	 */
	custom_css_post_id: -1,
};

export default {
	PAGE_NAMESPACE,
};
