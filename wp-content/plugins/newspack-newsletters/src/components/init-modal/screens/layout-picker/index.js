/**
 * External dependencies
 */
import classnames from 'classnames';
import { find } from 'lodash';

/**
 * WordPress dependencies
 */
import { parse } from '@wordpress/blocks';
import { Fragment, useMemo, useState, useEffect } from '@wordpress/element';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { Button, Spinner } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { BLANK_LAYOUT_ID } from '../../../../utils/consts';
import { isUserDefinedLayout } from '../../../../utils';
import { useLayoutsState } from '../../../../utils/hooks';
import SingleLayoutPreview from './SingleLayoutPreview';
import NewsletterPreview from '../../../newsletter-preview';

const LAYOUTS_TABS = [
	{
		title: __( 'Default layouts', 'newspack-newsletters' ),
		filter: layout => layout.post_author === undefined,
	},
	{
		title: __( 'My layouts', 'newspack-newsletters' ),
		filter: isUserDefinedLayout,
		isEditable: true,
	},
];

const LayoutPicker = ( {
	getBlocks,
	insertBlocks,
	replaceBlocks,
	savePost,
	setNewsletterMeta,
} ) => {
	const { layouts, isFetchingLayouts, deleteLayoutPost } = useLayoutsState();

	const insertLayout = layoutId => {
		const { post_content: content, meta = {} } = find( layouts, { ID: layoutId } ) || {};
		const blocksToInsert = content ? parse( content ) : [];
		const existingBlocksIds = getBlocks().map( ( { clientId } ) => clientId );
		if ( existingBlocksIds.length ) {
			replaceBlocks( existingBlocksIds, blocksToInsert );
		} else {
			insertBlocks( blocksToInsert );
		}
		const metaPayload = {
			template_id: layoutId,
			...meta,
		};
		setNewsletterMeta( metaPayload );
		setTimeout( savePost, 1 );
	};

	const [ selectedLayoutId, setSelectedLayoutId ] = useState( null );
	const layoutPreviewProps = useMemo(() => {
		const layout = selectedLayoutId && find( layouts, { ID: selectedLayoutId } );
		return layout ? { blocks: parse( layout.post_content ), meta: layout.meta } : null;
	}, [ selectedLayoutId, layouts.length ]);

	const canRenderPreview = layoutPreviewProps && layoutPreviewProps.blocks.length > 0;

	const renderPreview = () =>
		canRenderPreview ? (
			<NewsletterPreview { ...layoutPreviewProps } viewportWidth={ 600 } />
		) : (
			<p>{ __( 'Select a layout to preview.', 'newspack-newsletters' ) }</p>
		);

	const [ activeTabIndex, setActiveTabIndex ] = useState( 0 );
	const activeTab = LAYOUTS_TABS[ activeTabIndex ];
	const displayedLayouts = layouts.filter( activeTab.filter );

	// Switch tab to user layouts if there are any.
	useEffect(() => {
		if ( layouts.filter( isUserDefinedLayout ).length ) {
			setActiveTabIndex( 1 );
		}
	}, [ layouts.length ]);

	return (
		<Fragment>
			<div className="newspack-newsletters-modal__content">
				<div className="newspack-newsletters-tabs newspack-newsletters-buttons-group">
					{ LAYOUTS_TABS.map( ( { title }, i ) => (
						<Button
							key={ i }
							disabled={ isFetchingLayouts }
							className={ classnames( 'newspack-newsletters-tabs__button', {
								'newspack-newsletters-tabs__button--is-active':
									! isFetchingLayouts && i === activeTabIndex,
							} ) }
							onClick={ () => setActiveTabIndex( i ) }
						>
							{ title }
						</Button>
					) ) }
				</div>
				<div
					className={ classnames( 'newspack-newsletters-modal__layouts', {
						'newspack-newsletters-modal__layouts--loading': isFetchingLayouts,
					} ) }
				>
					{ isFetchingLayouts ? (
						<Spinner />
					) : (
						<div
							className={ classnames( {
								'newspack-newsletters-layouts': displayedLayouts.length > 0,
							} ) }
						>
							{ displayedLayouts.length ? (
								displayedLayouts.map( layout => (
									<SingleLayoutPreview
										key={ layout.ID }
										selectedLayoutId={ selectedLayoutId }
										setSelectedLayoutId={ setSelectedLayoutId }
										deleteHandler={ deleteLayoutPost }
										isEditable={ activeTab.isEditable }
										{ ...layout }
									/>
								) )
							) : (
								<span>
									{ __(
										'Turn any newsletter to a layout via the "Layout" sidebar menu in the editor.',
										'newspack-newsletters'
									) }
								</span>
							) }
						</div>
					) }
				</div>

				<div className="newspack-newsletters-modal__preview">
					{ ! isFetchingLayouts && renderPreview() }
				</div>
			</div>
			<div className="newspack-newsletters-modal__action-buttons">
				<Button isSecondary onClick={ () => insertLayout( BLANK_LAYOUT_ID ) }>
					{ __( 'Start From Scratch', 'newspack-newsletters' ) }
				</Button>
				<span className="separator">{ __( 'or', 'newspack-newsletters' ) }</span>
				<Button
					isPrimary
					disabled={ isFetchingLayouts || ! canRenderPreview }
					onClick={ () => insertLayout( selectedLayoutId ) }
				>
					{ __( 'Use Selected Layout', 'newspack-newsletters' ) }
				</Button>
			</div>
		</Fragment>
	);
};

export default compose( [
	withSelect( select => {
		const { getBlocks } = select( 'core/block-editor' );
		return {
			getBlocks,
		};
	} ),
	withDispatch( dispatch => {
		const { savePost, editPost } = dispatch( 'core/editor' );
		const { insertBlocks, replaceBlocks } = dispatch( 'core/block-editor' );
		return {
			savePost,
			insertBlocks,
			replaceBlocks,
			setNewsletterMeta: meta => editPost( { meta } ),
		};
	} ),
] )( LayoutPicker );
