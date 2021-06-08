/**
 * WordPress dependencies.
 */
import { useEffect, useRef, useState, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Draggable, MenuItem } from '@wordpress/components';
import { ESCAPE } from '@wordpress/keycodes';
import { Icon, chevronDown, chevronUp, dragHandle, moreVertical } from '@wordpress/icons';

/**
 * Internal dependencies.
 */
import { ActionCard, Button, Card, Notice, Popover, Router } from '../../../../components/src';
import { descriptionForSegment, getFavoriteCategoryNames } from '../../utils';

const { NavLink, useHistory } = Router;

const AddNewSegmentLink = () => (
	<NavLink to="segments/new">
		<Button isPrimary isSmall>
			{ __( 'Add New Segment', 'newspack' ) }
		</Button>
	</NavLink>
);

const SegmentActionCard = ( {
	inFlight,
	segment,
	segments,
	deleteSegment,
	dropTargetIndex,
	setDropTargetIndex,
	index,
	sortSegments,
	totalSegments,
	wrapperRef,
} ) => {
	const [ popoverVisibility, setPopoverVisibility ] = useState( false );
	const [ categories, setCategories ] = useState( [] );
	const [ isDragging, setIsDragging ] = useState( false );

	useEffect( () => {
		updateCategories();
	}, [ segment ] );

	const updateCategories = async () => {
		if ( 0 < segment.configuration?.favorite_categories?.length ) {
			setCategories( await getFavoriteCategoryNames( segment.configuration.favorite_categories ) );
		}
	};

	const onFocusOutside = () => setPopoverVisibility( false );
	const history = useHistory();
	const isFirstTarget = 0 === index;
	const isLastTarget = index + 1 === totalSegments;
	const isDropTarget = index === dropTargetIndex;
	const targetIsLast = isLastTarget && dropTargetIndex >= totalSegments;
	const resortSegments = targetIndex => {
		if ( inFlight ) {
			return;
		}

		const sortedSegments = [ ...segments ];

		// We need to account for the fact that the dragged segment is actually still in the list.
		const target = targetIndex > index ? targetIndex - 1 : targetIndex;

		// Remove the segment and drop it back into the array at the target index.
		sortedSegments.splice( index, 1 );
		sortedSegments.splice( target, 0, segment );

		// Reindex priorities to avoid gaps and dupes.
		sortedSegments.forEach( ( _segment, _index ) => ( _segment.priority = _index ) );

		// Only trigger the API request if the order has changed.
		if ( JSON.stringify( sortedSegments ) !== JSON.stringify( segments ) ) {
			sortSegments( sortedSegments );
		}
	};
	const onDragStart = () => {
		if ( isDragging || inFlight ) {
			return;
		}

		setIsDragging( true );
	};
	const onDragEnd = () => {
		if ( inFlight ) {
			return;
		}

		if ( null !== dropTargetIndex ) {
			resortSegments( dropTargetIndex );
		}

		setDropTargetIndex( null );
		setIsDragging( false );
	};
	const onDragOver = e => {
		if ( inFlight ) {
			return;
		}

		const wrapperRect = wrapperRef.current.getBoundingClientRect();
		const isDraggingToTop = e.pageY <= wrapperRect.top + window.scrollY;
		const isDraggingToBottom = e.pageY >= wrapperRect.bottom + window.scrollY;

		if (
			isDraggingToTop ||
			isDraggingToBottom ||
			e.target.classList.contains( 'newspack-action-card' )
		) {
			const segmentCards = Array.prototype.slice.call(
				wrapperRef.current.querySelectorAll( '.newspack-campaigns__draggable' )
			);

			let targetIndex = segmentCards.indexOf( e.target.parentElement );

			// If dragging the element over itself or over an invalid target, cancel the drop.
			if ( 0 > targetIndex || targetIndex === index + 1 ) {
				targetIndex = index;
			}

			// Handle dropping before the first item.
			if ( isDraggingToTop ) {
				targetIndex = 0;
			}

			// Handle dropping after the last item.
			if ( isDraggingToBottom ) {
				targetIndex = totalSegments;
			}

			setDropTargetIndex( targetIndex );
		}
	};
	const moveUp = () => {
		if ( inFlight ) {
			return;
		}

		let target = index - 1;

		if ( 0 > target ) {
			target = 0;
		}

		resortSegments( target );
	};
	const moveDown = () => {
		if ( inFlight ) {
			return;
		}

		let target = index + 2;

		if ( totalSegments < target ) {
			target = totalSegments;
		}

		resortSegments( target );
	};

	return (
		<div
			className={
				'newspack-campaigns__draggable' +
				( isDragging ? ' is-dragging' : '' ) +
				( isDropTarget ? ' is-drop-target' : '' ) +
				( targetIsLast ? ' drop-target-after' : '' )
			}
			id={ `segment-${ segment.id }` }
		>
			<Draggable
				elementId={ `segment-${ segment.id }` }
				transferData={ {} }
				onDragStart={ onDragStart }
				onDragEnd={ onDragEnd }
				onDragOver={ onDragOver }
			>
				{ ( { onDraggableStart, onDraggableEnd } ) => (
					<ActionCard
						isSmall
						title={ segment.name }
						titleLink={ `#/segments/${ segment.id }` }
						description={ descriptionForSegment( segment, categories ) }
						actionText={
							<>
								<Button
									isQuaternary
									onClick={ () => setPopoverVisibility( ! popoverVisibility ) }
									label={ __( 'More options', 'newspack' ) }
									icon={ moreVertical }
								/>
								{ popoverVisibility && (
									<Popover
										position="bottom left"
										onKeyDown={ event => ESCAPE === event.keyCode && onFocusOutside }
										onFocusOutside={ onFocusOutside }
									>
										<MenuItem onClick={ () => onFocusOutside() } className="screen-reader-text">
											{ __( 'Close Popover', 'newspack' ) }
										</MenuItem>
										<MenuItem
											onClick={ () => history.push( `/segments/${ segment.id }` ) }
											className="newspack-button"
										>
											{ __( 'Edit', 'newspack' ) }
										</MenuItem>
										<MenuItem
											onClick={ () => deleteSegment( segment ) }
											className="newspack-button"
										>
											{ __( 'Delete', 'newspack' ) }
										</MenuItem>
									</Popover>
								) }
							</>
						}
					>
						<div className="newspack-campaigns__segment-priority-controls">
							<div
								className="drag-handle"
								draggable
								onDragStart={ onDraggableStart }
								onDragEnd={ onDraggableEnd }
							>
								<Icon icon={ dragHandle } height={ 18 } width={ 18 } />
							</div>
							<div className="movers">
								<Button
									icon={ chevronUp }
									isLink
									onClick={ moveUp }
									disabled={ isFirstTarget }
									label={ __( 'Move segment position up', 'newspack' ) }
								/>
								<Button
									icon={ chevronDown }
									isLink
									onClick={ moveDown }
									disabled={ isLastTarget }
									label={ __( 'Move segment position down', 'newspack' ) }
								/>
							</div>
						</div>
					</ActionCard>
				) }
			</Draggable>
		</div>
	);
};

const SegmentsList = ( { wizardApiFetch, segments, setSegments, isLoading } ) => {
	const [ dropTargetIndex, setDropTargetIndex ] = useState( null );
	const [ sortedSegments, setSortedSegments ] = useState( null );
	const [ inFlight, setInFlight ] = useState( false );
	const [ error, setError ] = useState( null );
	const ref = useRef();
	const deleteSegment = segment => {
		setInFlight( true );
		setError( null );
		wizardApiFetch( {
			path: `/newspack/v1/wizard/newspack-popups-wizard/segmentation/${ segment.id }`,
			method: 'DELETE',
			quiet: true,
		} )
			.then( _segments => {
				setInFlight( false );
				setSegments( _segments );
			} )
			.catch( e => {
				console.error( e );
				setInFlight( false );
			} );
	};
	const sortSegments = segmentsToSort => {
		setError( null );
		setSortedSegments( segmentsToSort );
		setInFlight( true );
		wizardApiFetch( {
			path: `/newspack/v1/wizard/newspack-popups-wizard/segmentation-sort`,
			method: 'POST',
			data: { segmentIds: segmentsToSort.map( _segment => _segment.id ) },
			quiet: true,
		} )
			.then( _segments => {
				setInFlight( false );
				setSortedSegments( null );
				setSegments( _segments );
			} )
			.catch( e => {
				setInFlight( false );
				setError( e.message || __( 'There was an error sorting segments. Please try again.' ) );
				setSegments( segments );
			} );
	};

	if ( segments === null ) {
		return null;
	}

	// Optimistically update the order of the list while the sort request is pending.
	const segmentsToShow = sortedSegments || segments;

	return segments.length ? (
		<Fragment>
			{ error && <Notice noticeText={ error } isError /> }
			<Card headerActions noBorder>
				<h2>{ __( 'Audience segments', 'newspack' ) }</h2>
				<AddNewSegmentLink />
			</Card>
			<div
				className={ 'newspack-campaigns-wizard-segments__list' + ( inFlight ? ' is-loading' : '' ) }
				ref={ ref }
			>
				{ segmentsToShow.map( ( segment, index ) => (
					<SegmentActionCard
						deleteSegment={ deleteSegment }
						key={ segment.id }
						inFlight={ inFlight || isLoading > 0 }
						segment={ segment }
						segments={ segments }
						sortSegments={ sortSegments }
						index={ index }
						wrapperRef={ ref }
						dropTargetIndex={ dropTargetIndex }
						setDropTargetIndex={ setDropTargetIndex }
						totalSegments={ segments.length }
					/>
				) ) }
			</div>
		</Fragment>
	) : (
		<Fragment>
			<Card headerActions noBorder>
				<h2>{ __( 'You have no saved audience segments.', 'newspack' ) }</h2>
				<AddNewSegmentLink />
			</Card>
			<p>
				{ __(
					'Create audience segments to target visitors by engagement, activity, and more.',
					'newspack'
				) }
			</p>
		</Fragment>
	);
};

export default SegmentsList;
