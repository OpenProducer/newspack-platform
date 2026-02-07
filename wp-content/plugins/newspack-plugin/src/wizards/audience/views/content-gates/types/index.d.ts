declare module '@wordpress/block-editor';

type AccessRule = {
	name: string;
	description: string;
	options?: { value: string; label: string }[];
	is_boolean: boolean;
	default: string | string[] | boolean;
};

type ContentRule = {
	name: string;
	description?: string;
	options?: { value: string; label: string }[];
	value: string[];
	default: string[];
};

type Metering = {
	enabled: boolean;
	count: number;
	period: 'week' | 'month';
};

type AccessRules = {
	[key: string]: AccessRule;
};

type ContentRules = {
	[key: string]: ContentRule;
};

type GateAccessRule = {
	slug: string;
	value: string | string[] | boolean;
};

type GateAccessRuleValue = string | string[] | boolean;

type GateContentRuleValue = string[];

type GateAccessRuleControlProps = {
	slug: string;
	value: GateAccessRuleValue;
	onChange: (value: GateAccessRuleValue) => void;
};

type GateContentRule = {
	slug: string;
	value: string[];
	exclusion?: boolean;
};

type GateContentRuleControlProps = {
	slug: string;
	value: GateContentRuleValue;
	exclusion?: boolean;
	onChange: (value: GateContentRuleValue) => void;
	onChangeExclusion?: (value: boolean) => void;
};

type GateStatus = 'publish' | 'draft' | 'pending' | 'future' | 'private' | 'trash';

type Gate = {
	id: number;
	title: string;
	priority: number;
	status: GateStatus;
	isExpanded?: boolean;
	collapse?: boolean;
	content_rules: GateContentRule[];
	registration: Registration;
	custom_access: CustomAccess;
};

type Registration = {
	active: boolean;
	metering: Metering;
	require_verification: boolean;
	gate_layout_id: number;
};

type CustomAccess = {
	active: boolean;
	metering: Metering;
	gate_layout_id: number;
	access_rules: GateAccessRule[];
};

type ContentGiftingConfig = {
	enabled: boolean;
	limit: number;
	interval: string;
	expiration_time: number;
	expiration_time_unit: string;
	cta_label: string;
	button_label: string;
};

type MeteringCountdownConfig = {
	enabled: boolean;
	style: string;
	cta_label: string;
	button_label: string;
	cta_url: string;
};

type GateSettings = {
	content_gifting?: ContentGiftingConfig;
	countdown_banner?: MeteringCountdownConfig;
};

type GateConfig = {
	gates: Gate[];
	config: GateSettings
};
