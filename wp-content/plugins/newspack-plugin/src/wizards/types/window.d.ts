declare global {
	interface Window {
		newspackWizardsAdminHeader: {
			tabs: Array< {
				textContent: string;
				href: string;
				forceSelected: boolean;
			} >;
			title: string;
		};
		newspackAudience: {
			has_reader_activation: boolean;
			has_memberships: boolean;
			new_subscription_lists_url: string;
			reader_activation_url: string;
			preview_query_keys: {
				[ K in PromptOptionsBaseKey ]: string;
			};
			preview_post: string;
			preview_archive: string;
		};
		newspackAudienceCampaigns: {
			api: string;
			preview_post: string;
			preview_archive: string;
			frontend_url: string;
			custom_placements: {
				[ key: string ]: string;
			};
			overlay_placements: string[];
			overlay_sizes: Array< {
				value: string;
				label: string;
			} >;
			preview_query_keys: {
				[ K in PromptOptionsBaseKey ]: string;
			};
			experimental: boolean;
			criteria: Array< {
				category: string;
				description: string;
				id: string;
				matching_attribute: string;
				matching_function: string;
				name: string;
			} >;
		};
		newspackAudienceDonations: {
			can_use_name_your_price: boolean;
		};
		newspackAudienceSubscriptions: {
			tabs: Array< {
				title: string;
				path: string;
				header: string;
				description: string;
				href: string;
				btn_text: string;
			} >;
		};
	}
}

export {};
