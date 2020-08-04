import example from './example';
import mailchimp from './mailchimp';
import constant_contact from './constant_contact';

const SERVICE_PROVIDERS = {
	example,
	mailchimp,
	constant_contact,
};

export const getServiceProvider = () => {
	const serviceProvider =
		window && window.newspack_newsletters_data && window.newspack_newsletters_data.service_provider;
	return SERVICE_PROVIDERS[ serviceProvider ];
};
