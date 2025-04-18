/* globals newspack_relative_time */
document.addEventListener('DOMContentLoaded', () => {

	/**
	 * Calculate the time difference and return the value and unit.
	 *
	 * Eg. 3 day, 1 year ago, etc.
	 * @param {number} time_diff - The time difference in seconds
	 * @return {{value: number, unit: string}} Object with value and unit
	 */
	const time_ago = (time_diff) => {
		const abs_time_diff = Math.abs(time_diff);
		let unit, value;

		if (abs_time_diff < 60) {
			unit = 'second';
			value = time_diff;
		} else if (abs_time_diff < 3600) {
			unit = 'minute';
			value = time_diff / 60;
		} else if (abs_time_diff < 86400) {
			unit = 'hour';
			value = time_diff / 3600;
		} else if (abs_time_diff < 2592000) {
			unit = 'day';
			value = time_diff / 86400;
		} else if (abs_time_diff < 31536000) {
			unit = 'month';
			value = time_diff / 2592000;
		} else {
			unit = 'year';
			value = time_diff / 31536000;
		}

		return {value: Math.floor(value), unit};
	};

	const entry_dates = document.querySelectorAll(
		'time.entry-date, .comment-meta time'
	);
	if (entry_dates.length < 1) {
		return;
	}
	const locale = newspack_relative_time.language_tag || 'en-US';
	const cutoff_in_days = parseInt(newspack_relative_time.cutoff, 10) || 14;
	const cutoff = cutoff_in_days * 60 * 60 * 24;

	const rtf = new Intl.RelativeTimeFormat(locale, {
		numeric: 'auto',
		style: 'long',
	});

	const now = new Date();

	entry_dates.forEach(time_element => {
		// Get the datetime attribute value
		const datetime = time_element.getAttribute('datetime');
		if (!datetime) {
			return;
		}

		const date = new Date(datetime);

		if (isNaN(date.getTime())) {
			return;
		}

		const time_diff = Math.floor((date - now) / 1000);
		if (Math.abs(time_diff) > cutoff) {
			// If the cutoff is reached, leave the date alone.
			return;
		}

		const {value, unit} = time_ago(time_diff);

		// Update the element's text content
		time_element.textContent = rtf.format(value, unit);

		// Add a title attribute with the original date on hover.
		if (!time_element.title) {
			time_element.title = date.toLocaleString(locale);
		}
	});
});
