/**
 * Types for the Prequisite component.
 */

declare global {
	interface Window {
		// Localized data on engagement wizard script.
		newspack_engagement_wizard: {
			has_reader_activation: boolean;
			has_memberships: boolean;
			new_subscription_lists_url: string;
			reader_activation_url: string;
			preview_query_keys: {
				background_color: string;
				display_title: string;
				hide_border: string;
				large_border: string;
				frequency: string;
				frequency_max: string;
				frequency_start: string;
				frequency_between: string;
				frequency_reset: string;
				overlay_color: string;
				overlay_opacity: string;
				overlay_size: string;
				no_overlay_background: string;
				placement: string;
				trigger_type: string;
				trigger_delay: string;
				trigger_scroll_progress: string;
				trigger_blocks_count: string;
				archive_insertion_posts_count: string;
				archive_insertion_is_repeating: string;
				utm_suppression: string;
			};
			preview_post: string;
			preview_archive: string;
		};
	}
}

// Available transactional email slugs.
type EmailSlugs =
	| 'reader-activation-verification'
	| 'reader-activation-magic-link'
	| 'reader-activation-otp-authentication'
	| 'reader-activation-reset-password'
	| 'reader-activation-delete-account';

// RAS config inherited from RAS wizard view.
type Config = {
	enabled?: boolean;
	enabled_account_link?: boolean;
	account_link_menu_locations?: [ 'tertiary-menu' ];
	newsletters_label?: string;
	terms_text?: string;
	terms_url?: string;
	sync_esp?: boolean;
	metadata_prefix?: string;
	sync_esp_delete?: boolean;
	active_campaign_master_list?: number;
	mailchimp_audience_id?: string;
	emails?: {
		[ key in EmailSlugs ]: {
			label: string;
			description: string;
			post_id: number;
			edit_link: string;
			subject: string;
			from_name: string;
			from_email: string;
			reply_to_email: string;
			status: string;
		};
	};
	sender_name?: string;
	sender_email_address?: string;
	contact_email_address?: string;
};

// Props for the Prequisite component.
export type PrequisiteProps = {
	config: Config;
	getSharedProps: (
		configKey: string,
		type: string
	) => {
		onChange: ( value: string | boolean ) => void;
		disabled?: boolean;
		checked?: boolean;
		value?: string;
	};
	inFlight: boolean;
	saveConfig: ( config: Config ) => void;

	// Schema for prequisite object is defined in PHP class Reader_Activation::get_prerequisites_status().
	prerequisite: {
		active: boolean;
		plugins?: {
			[ pluginName: string ]: boolean; // Are the required plugins active?
		};
		label: string;
		description: string;
		warning?: string;
		instructions?: string;
		help_url: string;
		fields?: {
			[ fieldName: string ]: {
				label: string;
				description: string;
			};
		};
		href?: string;
		action_text?: string;
		action_enabled?: boolean;
		disabled_text?: string;
	};
};

export type InputField = {
	name: string;
	type: string;
	label: string;
	description: string;
	required?: boolean;
	max_length?: number;
	default: string | number | boolean;
	value?: string | number | boolean;
	options?: {
		label: string;
		value: string | number;
	};
};

// Schema is defined in Newspack Campaigns: https://github.com/Automattic/newspack-popups/blob/master/includes/schemas/class-prompts.php
export type PromptType = {
	status: string;
	slug: string;
	title: string;
	content: string;
	featured_image_id?: number;
	segments: [
		{
			id: number;
			name: string;
		}
	];
	options: PromptOptions;
	user_input_fields: [ InputField ];
	help_info?: {
		screenshot?: string;
		recommendations?: Array< string >;
		description?: string;
		url?: string;
	};
	ready?: boolean;
};

export type PromptOptions = {
	background_color: string;
	display_title: boolean;
	hide_border: boolean;
	large_border: boolean;
	frequency: string;
	frequency_max: number;
	frequency_start: number;
	frequency_between: number;
	frequency_reset: string;
	overlay_color: string;
	overlay_opacity: number;
	overlay_size: string;
	no_overlay_background: boolean;
	placement: string;
	trigger_type: string;
	trigger_delay: number;
	trigger_scroll_progress: number;
	trigger_blocks_count: number;
	archive_insertion_posts_count: number;
	archive_insertion_is_repeating: false;
	post_types: Array< string >;
	archive_page_types: Array< string >;
	additional_classes: string;
	excluded_categories: [
		{
			id: number;
			name: string;
		}
	];
	excluded_tags: [
		{
			id: number;
			name: string;
		}
	];
	categories: [
		{
			id: number;
			name: string;
		}
	];
	tags: [
		{
			id: number;
			name: string;
		}
	];
	campaign_groups: [
		{
			id: number;
			name: string;
		}
	];
};

export type Attachment = {
	id?: number;
	source_url?: string;
	url: string;
};

export type InputValues = {
	[ fieldName: string ]: string | number | Array< string > | Array< number > | boolean;
};

// Props for the Prompt component.
export type PromptProps = {
	inFlight: boolean;
	setInFlight: ( inFlight: boolean ) => void;
	prompt: PromptType;
	setPrompts: ( prompts: boolean | Array< PromptType > ) => void;
	unblock: () => void;
};
