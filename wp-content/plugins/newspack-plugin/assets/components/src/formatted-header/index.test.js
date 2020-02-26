import React from 'react';
import { render } from '@testing-library/react';

import FormattedHeader from './';

describe( 'FormattedHeader', () => {
	it( 'should render a header and a subheader', () => {
		const headerText = 'header text';
		const subHeaderText = 'subheader text';
		const { getByText } = render(
			<FormattedHeader headerText={ headerText } subHeaderText={ subHeaderText } />
		);
		expect( getByText( headerText ) ).toBeInTheDocument();
		expect( getByText( subHeaderText ) ).toBeInTheDocument();
	} );
} );
