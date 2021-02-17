/**
 * WordPress dependencies.
 */
import { parse } from '@wordpress/blocks';
import { PanelBody, Spinner } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';
import { Component, Fragment } from '@wordpress/element';
import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';
import { ENTER, SPACE } from '@wordpress/keycodes';

/**
 * Internal dependencies.
 */
import './style.scss';
import Icon from '../shared/js/newspack-icon';

class PatternsSidebar extends Component {
	state = {
		patternGroups: window && window.newspack_blocks_data && window.newspack_blocks_data.patterns,
	};
	insert = content => {
		const {
			getBlock,
			getBlockSelectionEnd,
			getBlockIndex,
			insertBlocks,
			replaceBlocks,
		} = this.props;
		const currentBlock = getBlockSelectionEnd();
		const block = currentBlock ? getBlock( currentBlock ) : null;
		if ( block && 'core/paragraph' === block.name && '' === block.attributes.content ) {
			replaceBlocks( currentBlock, parse( content ) );
		} else {
			insertBlocks( parse( content ), currentBlock ? getBlockIndex( currentBlock ) + 1 : 0 );
		}
	};
	render() {
		const { patternGroups } = this.state;
		const sidebarTitle = __( 'Newspack Patterns', 'newspack-blocks' );
		const sidebarId = 'newspack-blocks-sidebar';
		return (
			<Fragment>
				<PluginSidebar name={ sidebarId } icon={ <Icon /> } title={ sidebarTitle }>
					{ patternGroups &&
						patternGroups
							.filter(
								patternGroup =>
									patternGroup.title && patternGroup.items && patternGroup.items.length
							)
							.map( ( patternGroup, patternGroupIndex ) => (
								<PanelBody
									title={ patternGroup.title }
									initialOpen={ true }
									key={ patternGroupIndex }
								>
									<div className="newspack-block-patterns">
										{ patternGroup.items &&
											patternGroup.items.map(
												( { image: image, content, title }, patternIndex ) => (
													<div
														key={ patternIndex }
														className="newspack-block-patterns__item"
														onClick={ () => this.insert( content ) }
														onKeyDown={ event => {
															if ( ENTER === event.keyCode || SPACE === event.keyCode ) {
																event.preventDefault();
																this.insert( content );
															}
														} }
														role="button"
														tabIndex="0"
														aria-label={ title }
													>
														<div className="newspack-block-patterns__item-preview">
															<img src={ image } alt={ __( 'Preview', 'newspack-block' ) } />
														</div>
													</div>
												)
											) }
									</div>
								</PanelBody>
							) ) }
					{ ! patternGroups && (
						<PanelBody>
							<Spinner />
							{ __( 'Loading patterns', 'newspack-blocks' ) }
						</PanelBody>
					) }
				</PluginSidebar>
				<PluginSidebarMoreMenuItem target={ sidebarId } icon={ <Icon /> }>
					{ sidebarTitle }
				</PluginSidebarMoreMenuItem>
			</Fragment>
		);
	}
}

const PatternsSidebarWithDispatch = compose( [
	withSelect( select => {
		const { getBlock, getBlockSelectionEnd, getBlockIndex } = select( 'core/block-editor' );
		return { getBlock, getBlockSelectionEnd, getBlockIndex };
	} ),
	withDispatch( dispatch => {
		const { insertBlocks, replaceBlocks } = dispatch( 'core/block-editor' );
		return { insertBlocks, replaceBlocks };
	} ),
] )( PatternsSidebar );

const newspackBlocksData = window && window.newspack_blocks_data ? window.newspack_blocks_data : {};
const { patterns } = newspackBlocksData;
if ( patterns && patterns.length ) {
	registerPlugin( 'newspack-blocks-sidebar', {
		render: PatternsSidebarWithDispatch,
	} );
}
