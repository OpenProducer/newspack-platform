import type { DonateBlockAttributes, ComponentProps, DonationFrequencySlug } from '../types';

export const AmountValueInput = ( {
	frequencySlug,
	tierIndex,
	id,
	label,
	attributes,
	settings,
	amounts,
	setAttributes,
	setSettings,
}: ComponentProps & {
	frequencySlug: DonationFrequencySlug;
	tierIndex: number;
	id: string;
	label?: string;
} ) => {
	const onChange = ( {
		value,
		frequency,
	}: {
		value: string;
		frequency: DonationFrequencySlug;
	} ) => {
		const subject = attributes.manual ? attributes : settings;
		subject.amounts[ frequency ][ tierIndex ] = parseFloat( value );
		const update: Partial< DonateBlockAttributes > = {
			amounts: {
				[ frequency ]: subject.amounts[ frequency ],
				...subject.amounts,
			},
		};

		if ( attributes.manual ) {
			setAttributes( update );
		} else {
			setSettings( update );
		}
	};

	return (
		<span key={ `${ frequencySlug }-${ tierIndex }` }>
			{ label && <label htmlFor={ id }>{ label }</label> }
			<input
				type="number"
				min={ attributes.minimumDonation }
				onChange={ evt =>
					onChange( {
						value: evt.target.value,
						frequency: frequencySlug,
					} )
				}
				value={ amounts[ frequencySlug ][ tierIndex ] }
				id={ id }
			/>
		</span>
	);
};
