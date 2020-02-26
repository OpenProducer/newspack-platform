import React from 'react';
import { render } from '@testing-library/react';

import ImageUpload from './';

describe( 'ImageUpload', () => {
	it( 'should render an add image button', () => {
		const { getByText } = render( <ImageUpload /> );
		expect( getByText( 'Add image' ) ).toBeInTheDocument();
	} );

	it( 'should render an image and remove button', () => {
		const image = {
			id: 1234,
			url: 'https://upload.wikimedia.org/wikipedia/en/a/a9/Example.jpg',
		};
		const { getByAltText, getByText } = render( <ImageUpload image={ image } /> );
		expect( getByAltText( 'Upload preview' ) ).toBeInTheDocument();
		expect( getByText( 'Remove image' ) ).toBeInTheDocument();
	} );
} );
