import React from 'react';
import { shallow } from 'enzyme';
import ImageUpload from './';

describe( 'ImageUpload', () => {
	describe( 'basic rendering', () => {
		it( 'should render an image uploader ready for upload', () => {
			const uploader = shallow( <ImageUpload /> );
			expect( uploader.hasClass( 'newspack-image-upload' ) ).toBe( true );
			expect( uploader.find( '.newspack-image-upload__add-image' ) ).toHaveLength( 1 );
		} );

		it( 'should render an image uploader prepopulated with an upload', () => {
			const image = {
				id: 1234,
				url: 'https://upload.wikimedia.org/wikipedia/en/a/a9/Example.jpg',
			};
			const uploader = shallow( <ImageUpload image={ image } /> );
			expect( uploader.hasClass( 'newspack-image-upload' ) ).toBe( true );
			expect( uploader.find( '.newspack-image-upload__remove-image' ) ).toHaveLength( 1 );
		} );
	} );
} );
