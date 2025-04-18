/**
 * Theme names without `newspack` prefix.
 */
type ThemeNames =
	| 'theme'
	| 'scott'
	| 'nelson'
	| 'katharine'
	| 'sacha'
	| 'joseph';

/**
 * Theme names with `newspack` prefix.
 */
type NewspackThemes = `newspack-${ ThemeNames }`;

/**
 * Property on theme mods endpoint.
 */
interface Etc {
	post_count: string;
}

/**
 * Theme and brand schema.
 */
interface ThemeData< T = {} > {
	etc: Etc;
	theme: '' | NewspackThemes;
	theme_mods: ThemeMods< T >;
	homepage_patterns: HomepagePattern[];
}

/**
 * Homepage pattern schema.
 */
type HomepagePattern = {
	content: string;
	image: string;
};

/**
 * Theme and Brand.
 */
interface ThemeAndBrand {
	// Colors.
	header_color: string; // Possible values from context
	theme_colors: string;
	primary_color_hex: string;
	secondary_color_hex: string;

	// Typography.
	font_body: string;
	font_header: string;
	font_body_stack?: string;
	font_header_stack?: string;
	accent_allcaps: boolean;
	custom_font_import_code?: string;
	custom_font_import_code_alternate?: string;

	// Header.
	header_center_logo: boolean;
	header_simplified: boolean;
	header_solid_background: boolean;
	header_color_hex: string;
	custom_logo: string;
	logo_size: number;
	header_text: boolean;
	header_display_tagline: boolean;

	// Footer.
	footer_copyright: string;
	footer_color: string;
	footer_color_hex: string;
	newspack_footer_logo: string;
	footer_logo_size: string;

	// Homepage pattern.
	homepage_pattern_index: number;
}

/**
 * Theme mods component.
 */
type ThemeModComponentProps< T = ThemeMods > = {
	update: ( a: Partial< T > ) => void;
	isFetching?: boolean;
	data: T;
};

/**
 * Recirculation schema.
 */
interface Recirculation {
	relatedPostsEnabled: boolean;
	relatedPostsError: WizardApiErrorType | null;
	relatedPostsMaxAge: number;
	relatedPostsUpdated: boolean;
}

/**
 * Display settings.
 */
interface DisplaySettings {
	// Author Bio.
	show_author_bio: boolean;
	show_author_email: boolean;
	author_bio_length: number;

	// Default Featured Image and Post Template.
	featured_image_default: string;
	post_template_default: string;

	// Featured Image and Post Template for All Posts.
	featured_image_all_posts: string;
	post_template_all_posts: string;

	// Media Credits.
	newspack_image_credits_placeholder_url?: string;
	newspack_image_credits_class_name: string;
	newspack_image_credits_prefix_label: string;
	newspack_image_credits_placeholder: number | null;
	newspack_image_credits_auto_populate: boolean;
}

interface MiscSettings {
	/**
	 * Misc.
	 */
	custom_css_post_id: number;
}

interface ThemeMods extends ThemeAndBrand, DisplaySettings, MiscSettings {}
