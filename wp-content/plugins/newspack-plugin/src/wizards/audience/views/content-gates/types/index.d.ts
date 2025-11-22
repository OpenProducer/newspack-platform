declare module '@wordpress/block-editor';

type AccessRule = {
	name: string;
	description: string;
	options?: { value: string; label: string }[];
	conflicts?: string[];
	is_boolean: boolean;
	default: string | string[] | boolean;
};

type Metering = {
	enabled: boolean;
	anonymous_count: number;
	registered_count: number;
	period: string;
};

type AccessRules = {
	[ key: string ]: AccessRule;
};

type GateRule = {
	slug: string;
	value: string | string[] | boolean;
};

type Gate = {
	id: number;
	title: string;
	description: string;
	metering: Metering;
	access_rules: GateRule[];
	content_rules: [];
	priority: number;
};
