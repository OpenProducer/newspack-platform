import example from './example';
import mailchimp from './mailchimp';

const SERVICE_PROVIDERS = {
	example,
	mailchimp,
};

export const getServiceProvider = () => {
	const serviceProvider =
		window && window.newspack_newsletters_data && window.newspack_newsletters_data.service_provider;
	return SERVICE_PROVIDERS[ serviceProvider ];
};
