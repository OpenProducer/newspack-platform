import React from 'react';
import { shallow } from 'enzyme';
import ProgressBar from './';

describe( 'ProgressBar', () => {
	describe( 'basic rendering', () => {
		it( 'should render a ProgressBar element with no labels', () => {
			const bar = shallow( <ProgressBar completed="1" total="2" /> );
			expect( bar.hasClass( 'newspack-progress-bar' ) ).toBe( true );
			expect( bar.find( 'h2' ) ).toHaveLength( 0 );
			expect( bar.find( 'p' ) ).toHaveLength( 0 );
		} );

		it( 'should render a ProgressBar element with a label', () => {
			const bar = shallow( <ProgressBar completed="1" total="2" label="test label" /> );
			expect( bar.hasClass( 'newspack-progress-bar' ) ).toBe( true );
			expect( bar.find( 'h2' ) ).toHaveLength( 1 );
			expect( bar.find( 'p' ) ).toHaveLength( 0 );
		} );

		it( 'should render a ProgressBar element with a fraction', () => {
			const bar = shallow( <ProgressBar completed="1" total="2" displayFraction /> );
			expect( bar.hasClass( 'newspack-progress-bar' ) ).toBe( true );
			expect( bar.find( 'h2' ) ).toHaveLength( 0 );
			expect( bar.find( 'p' ) ).toHaveLength( 1 );
		} );

		it( 'should render a ProgressBar element with both label and fraction', () => {
			const bar = shallow( <ProgressBar completed="1" total="2" label="test label" displayFraction /> );
			expect( bar.hasClass( 'newspack-progress-bar' ) ).toBe( true );
			expect( bar.find( 'h2' ) ).toHaveLength( 1 );
			expect( bar.find( 'p' ) ).toHaveLength( 1 );
		} );

		it( 'should calculate progress correctly', () => {
			const bar = shallow( <ProgressBar completed="1" total="2" /> );
			expect( bar.instance().getCompletionPercentage( 1, 2 ) ).toBe( 50 );
			expect( bar.instance().getCompletionPercentage( 2, 3 ) ).toBe( 67 );
			expect( bar.instance().getCompletionPercentage( 6, 3 ) ).toBe( 100 );
		} );

		it( 'should handle non-numeric values in ProgressBar element', () => {
			const bar = shallow( <ProgressBar completed="cats" total="dogs" displayFraction /> );
			expect( bar.find( 'p' ).text() ).toBe( '0/0' );
			expect( bar.find( '.newspack-progress-bar__bar' ).render().css( 'width' ) ).toBe( '100%' );
		} );

		it( 'should handle non-logical values in ProgressBar element', () => {
			const bar = shallow( <ProgressBar completed="3" total="-1" displayFraction /> );
			expect( bar.find( 'p' ).text() ).toBe( '0/0' );
			expect( bar.find( '.newspack-progress-bar__bar' ).render().css( 'width' ) ).toBe( '100%' );
		} );
	} );
} );
