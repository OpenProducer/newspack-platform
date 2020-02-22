/**
 * New/Edit Ad Unit Screen
 */

/**
 * WordPress dependencies.
 */
import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
 * Material UI dependencies.
 */
import DeleteIcon from '@material-ui/icons/Delete';
import LibraryAddIcon from '@material-ui/icons/LibraryAdd';

/**
 * Internal dependencies.
 */
import { Button, Card, TextControl, withWizardScreen } from '../../../../components/src';
import './style.scss';

/**
 * New/Edit Ad Unit Screen.
 */
class AdUnit extends Component {
	/**
	 * Handle an update to an ad unit field.
	 *
	 * @param string key Ad Unit field
	 * @param mixed  value New value for field
	 *
	 */
	handleOnChange( key, value ) {
		const { adUnit, onChange, service } = this.props;
		onChange( { ...adUnit, ad_service: service, [ key ]: value } );
	}

	/**
	 * Render.
	 */
	render() {
		const { adUnit, onSave, service } = this.props;
		const { id, code, name } = adUnit;
		const sizes = adUnit.sizes && Array.isArray( adUnit.sizes ) ? adUnit.sizes : [ [ 120, 120 ] ];
		return (
			<Fragment>
				<TextControl
					label={ __( 'Ad unit name' ) }
					value={ name || '' }
					onChange={ value => this.handleOnChange( 'name', value ) }
				/>
				<TextControl
					label={ __( 'Ad unit code' ) }
					value={ code || '' }
					onChange={ value => this.handleOnChange( 'code', value ) }
				/>
				{ sizes.map( ( size, index ) => (
					<div className="newspack_ad_unit__sizes">
						<TextControl
							label={ __( 'Width' ) }
							value={ size[ 0 ] }
							type="number"
							onChange={ value => {
								sizes[ index ][ 0 ] = value;
								this.handleOnChange( 'sizes', sizes );
							} }
						/>
						<TextControl
							label={ __( 'Height' ) }
							value={ size[ 1 ] }
							type="number"
							onChange={ value => {
								sizes[ index ][ 1 ] = value;
								this.handleOnChange( 'sizes', sizes );
							} }
						/>
						{ sizes.length > 1 && (
							<Button
								isTertiary
								onClick={ () => {
									sizes.splice( index, 1 );
									this.handleOnChange( 'sizes', sizes );
								} }
							>
								<DeleteIcon />
							</Button>
						) }
					</div>
				) ) }
				<Button
					isTertiary
					className="newspack-button__add-size"
					onClick={ () => this.handleOnChange( 'sizes', [ ...sizes, [ 120, 120 ] ] ) }
				>
					<LibraryAddIcon />
					{ __( 'Add Size', 'newspack' ) }
				</Button>
				<div className="clear" />
				<div className="newspack-buttons-card">
					<Button isPrimary onClick={ () => onSave( id ) }>
						{ __( 'Save' ) }
					</Button>
					<Button isDefault href={ `#/${ service }` }>
						{ __( 'Cancel' ) }
					</Button>
				</div>
			</Fragment>
		);
	}
}

export default withWizardScreen( AdUnit );
