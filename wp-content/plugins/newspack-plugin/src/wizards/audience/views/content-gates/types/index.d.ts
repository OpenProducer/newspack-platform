declare module '@wordpress/block-editor';

type HeaderAction = {
	type: 'primary' | 'secondary' | 'more';
	label: string;
	icon: React.ReactNode;
	action: () => void;
	disabled?: boolean;
	destructive?: boolean;
};

type GateAccessRuleValue = string | string[] | boolean;
type AccessRule = {
	name: string;
	default: GateAccessRuleValue;
	description?: string;
	id?: string;
	is_boolean?: boolean;
	options?: { value: string; label: string }[];
	placeholder?: string;
	value: GateAccessRuleValue;
};

type GateContentRuleValue = string[];
type ContentRule = {
	name: string;
	default: GateContentRuleValue;
	description?: string;
	options?: { value: string; label: string }[];
	value: GateContentRuleValue;
};

type Metering = {
	enabled: boolean;
	count: number;
	period: 'week' | 'month';
};

type GateRuleProps = {
	config: AccessRule | ContentRule;
	rule?: GateAccessRule | GateContentRule;
	enabled?: boolean;
	onToggle?: (slug: string) => void;
	slug: string;
	exclusion?: boolean;
	onChange: (value: GateRuleValue) => void;
	onChangeExclusion?: (value: boolean) => void;
};

type GateRuleControlProps = {
	slug: string;
	value: GateRuleValue;
	exclusion?: boolean;
	onChange: (value: GateRuleValue) => void;
	onChangeExclusion?: (value: boolean) => void;
};

type AccessRules = {
	[key: string]: AccessRule;
};

type ContentRules = {
	[key: string]: ContentRule;
};

type GateAccessRule = {
	slug: string;
	value: GateAccessRuleValue;
};

type GateContentRule = {
	slug: string;
	value: GateContentRuleValue;
	exclusion?: boolean;
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

type GateAccessRuleGroup = GateAccessRule[];

type CustomAccess = {
	active: boolean;
	metering: Metering;
	gate_layout_id: number;
	access_rules: GateAccessRuleGroup[];
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
