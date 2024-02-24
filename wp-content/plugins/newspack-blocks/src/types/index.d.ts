declare global {
	interface Window {
		wpcomGutenberg: {
			blogPublic: string;
		};
		newspack_blocks_data: {
			assets_path: string;
			supports_recaptcha: boolean;
			has_recaptcha: boolean;
			recaptcha_url: string;
			can_use_name_your_price: boolean;
			tier_amounts_template: string;
		};
		grecaptcha: any;
		newspackReaderActivation: {
			on: function;
			off: function;
			setReaderEmail: function;
			setAuthenticated: function;
			refreshAuthentication: function;
			getReader: function;
			hasAuthLink: function;
			setAuthStrategy: function;
			getAuthStrategy: function;
		};
	}

	type PostId = number;
	type CategoryId = number;
	type TagId = number;
	type Taxonomy = { slug: string; terms: number[] }[];
	type AuthorId = number;

	type PostType = { name: string; slug: string; supports: { newspack_blocks: boolean } };

	// As used by Newspack_Blocks_API::posts_endpoint.
	type PostsQuery = {
		include?: PostId[];
		excerptLength?: number;
		showExcerpt?: boolean;
	};

	type Block = {
		name: string;
		clientId: string;
		attributes: {
			// eslint-disable-next-line @typescript-eslint/no-explicit-any
			[key: string]: any;
		};
		innerBlocks: Block[];
	};

	type Post = {
		id: number;
	};
}

export { };
