export type DonationFrequencySlug = 'once' | 'month' | 'year';

export type DonationFormInputName = `donation_value_${ DonationFrequencySlug }`;
type DonationFormInputOtherName = `donation_value_${ DonationFrequencySlug }_other`;
type DonationFormInputUntieredName = `donation_value_${ DonationFrequencySlug }_untiered`;

export type DonationFormValues = {
	[ key in DonationFormInputName ]: string;
} & {
	[ key in DonationFormInputOtherName ]: string;
} & {
	[ key in DonationFormInputUntieredName ]: string;
} & {
	amount: string;
	donation_frequency: DonationFrequencySlug;
	email: string;
	full_name: string;
	cid?: string;
	agree_to_pay_fees?: string;
	newsletter_opt_in?: string;
};
