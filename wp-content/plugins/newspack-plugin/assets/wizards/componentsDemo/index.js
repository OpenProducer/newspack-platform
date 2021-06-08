import '../../shared/js/public-path';

/* eslint-disable jsx-a11y/anchor-is-valid */

/**
 * Components Demo
 */

/**
 * WordPress dependencies.
 */
import { Component, Fragment, render } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { audio, plus, reusableBlock, typography } from '@wordpress/icons';

/**
 * Internal dependencies.
 */
import {
	ActionCard,
	ColorPicker,
	ImageUpload,
	CheckboxControl,
	Card,
	Button,
	ButtonCard,
	Handoff,
	Notice,
	Footer,
	TextControl,
	PluginInstaller,
	PluginToggle,
	ProgressBar,
	SelectControl,
	Modal,
	ToggleGroup,
	WebPreview,
	AutocompleteWithSuggestions,
} from '../../components/src';

class ComponentsDemo extends Component {
	/**
	 * constructor. Demo of how the parent interacts with the components, and controls their values.
	 */
	constructor() {
		super( ...arguments );
		this.state = {
			selectedPostForAutocompleteWithSuggestions: [],
			selectedPostsForAutocompleteWithSuggestionsMultiSelect: [],
			inputTextValue1: 'Input value',
			inputTextValue2: '',
			inputNumValue: 0,
			image: null,
			selectValue1: '2nd',
			selectValue2: '',
			modalShown: false,
			toggleGroupChecked: false,
			color1: '#3366ff',
		};
	}

	/**
	 * Render the example stub.
	 */
	render() {
		const {
			selectedPostForAutocompleteWithSuggestions,
			selectedPostsForAutocompleteWithSuggestionsMultiSelect,
			inputTextValue1,
			inputTextValue2,
			inputNumValue,
			selectValue1,
			selectValue2,
			modalShown,
			actionCardToggleChecked,
			toggleGroupChecked,
			color1,
		} = this.state;

		return (
			<Fragment>
				<div className="newspack-wizard__header">
					<div className="newspack-wizard__header__inner">
						<h1>{ __( 'Components' ) }</h1>
						<p>{ __( 'Demo of all the Newspack components' ) }</p>
					</div>
				</div>
				<div className="newspack-wizard newspack-wizard__content">
					<Card>
						<h2>{ __( 'Autocomplete with Suggestions (single-select)', 'newspack' ) }</h2>
						<AutocompleteWithSuggestions
							label={ __( 'Search for a post', 'newspack' ) }
							help={ __(
								'Begin typing post title, click autocomplete result to select.',
								'newspack'
							) }
							onChange={ items =>
								this.setState( { selectedPostForAutocompleteWithSuggestions: items } )
							}
							selectedItems={ selectedPostForAutocompleteWithSuggestions }
						/>

						<hr />

						<h2>{ __( 'Autocomplete with Suggestions (multi-select)', 'newspack' ) }</h2>
						<AutocompleteWithSuggestions
							hideHelp
							multiSelect
							label={ __( 'Search widgets', 'newspack' ) }
							help={ __(
								'Begin typing post title, click autocomplete result to select.',
								'newspack'
							) }
							onChange={ items =>
								this.setState( { selectedPostsForAutocompleteWithSuggestionsMultiSelect: items } )
							}
							postTypes={ [ { slug: 'page', label: 'Pages' }, { slug: 'post', label: 'Posts' } ] }
							postTypeLabel={ 'widget' }
							postTypeLabelPlural={ 'widgets' }
							selectedItems={ selectedPostsForAutocompleteWithSuggestionsMultiSelect }
						/>
					</Card>
					<Card>
						<h2>{ __( 'Plugin toggles' ) }</h2>
						<PluginToggle
							plugins={ {
								woocommerce: {
									shouldRefreshAfterUpdate: true,
								},
								'fb-instant-articles': {
									actionText: __( 'Configure Instant Articles' ),
									href: '/wp-admin/admin.php?page=newspack',
								},
							} }
						/>
					</Card>
					<Card>
						<h2>{ __( 'Web Previews' ) }</h2>
						<Card buttonsCard noBorder>
							<WebPreview
								url="//newspack.pub/"
								label={ __( 'Preview Newspack Blog', 'newspack' ) }
								isPrimary
							/>
							<WebPreview
								url="//newspack.pub/"
								renderButton={ ( { showPreview } ) => (
									<a href="#" onClick={ showPreview }>
										{ __( 'Preview Newspack Blog', 'newspack' ) }
									</a>
								) }
							/>
						</Card>
					</Card>
					<Card>
						<h2>{ __( 'Color picker' ) }</h2>
						<ColorPicker
							label={ __( 'Color Picker' ) }
							color={ color1 }
							onChange={ color => this.setState( { color1: color } ) }
						/>
					</Card>
					<Card>
						<ToggleGroup
							title={ __( 'Example Toggle Group' ) }
							description={ __( 'This is the description of a toggle group.' ) }
							checked={ toggleGroupChecked }
							onChange={ checked => this.setState( { toggleGroupChecked: checked } ) }
						>
							<p>{ __( 'This is the content of the toggle group' ) }</p>
						</ToggleGroup>
					</Card>
					<Card>
						<h2>{ __( 'Handoff Buttons' ) }</h2>
						<Card buttonsCard noBorder>
							<Handoff
								modalTitle="Manage AMP"
								modalBody="Click to go to the AMP dashboard. There will be a notification bar at the top with a link to return to Newspack."
								plugin="amp"
								isTertiary
							/>
							<Handoff plugin="jetpack" />
							<Handoff plugin="google-site-kit" />
							<Handoff plugin="woocommerce" />
							<Handoff
								plugin="wordpress-seo"
								isPrimary
								editLink="/wp-admin/admin.php?page=wpseo_dashboard#top#features"
							>
								{ __( 'Specific Yoast Page' ) }
							</Handoff>
						</Card>
					</Card>
					<Card>
						<h2>{ __( 'Modal' ) }</h2>
						<Card buttonsCard noBorder>
							<Button isPrimary onClick={ () => this.setState( { modalShown: true } ) }>
								{ __( 'Open modal' ) }
							</Button>
						</Card>
						{ modalShown && (
							<Modal
								title="This is the modal title"
								onRequestClose={ () => this.setState( { modalShown: false } ) }
							>
								<p>
									{ __(
										'Based on industry research, we advise to test the modal component, and continuing this sentence so we can see how the text wraps is one good way of doing that.'
									) }
								</p>
								<Card buttonsCard noBorder>
									<Button isPrimary onClick={ () => this.setState( { modalShown: false } ) }>
										{ __( 'Dismiss' ) }
									</Button>
									<Button isSecondary onClick={ () => this.setState( { modalShown: false } ) }>
										{ __( 'Also dismiss' ) }
									</Button>
								</Card>
							</Modal>
						) }
					</Card>
					<Card>
						<h2>{ __( 'Notice' ) }</h2>
						<Notice noticeText={ __( 'This is an info notice.' ) } />
						<Notice noticeText={ __( 'This is an error notice.' ) } isError />
						<Notice noticeText={ __( 'This is a help notice.' ) } isHelp />
						<Notice noticeText={ __( 'This is a success notice.' ) } isSuccess />
						<Notice noticeText={ __( 'This is a warning notice.' ) } isWarning />
					</Card>
					<Card>
						<h2>{ __( 'Plugin installer' ) }</h2>
						<PluginInstaller
							plugins={ [ 'woocommerce', 'amp', 'wordpress-seo' ] }
							canUninstall
							onStatus={ ( { complete, pluginInfo } ) => {
								console.log(
									complete
										? 'All plugins installed successfully'
										: 'Plugin installation incomplete',
									pluginInfo
								);
							} }
						/>
					</Card>
					<Card>
						<h2>{ __( 'Plugin installer (small)' ) }</h2>
						<PluginInstaller
							plugins={ [ 'woocommerce', 'amp', 'wordpress-seo' ] }
							isSmall
							canUninstall
							onStatus={ ( { complete, pluginInfo } ) => {
								console.log(
									complete
										? 'All plugins installed successfully'
										: 'Plugin installation incomplete',
									pluginInfo
								);
							} }
						/>
					</Card>
					<PluginInstaller
						plugins={ [ 'woocommerce', 'amp', 'wordpress-seo' ] }
						onStatus={ ( { complete, pluginInfo } ) => {
							console.log(
								complete ? 'All plugins installed successfully' : 'Plugin installation incomplete',
								pluginInfo
							);
						} }
					/>
					<PluginInstaller
						plugins={ [ 'woocommerce', 'amp', 'wordpress-seo' ] }
						isSmall
						onStatus={ ( { complete, pluginInfo } ) => {
							console.log(
								complete ? 'All plugins installed successfully' : 'Plugin installation incomplete',
								pluginInfo
							);
						} }
					/>
					<ActionCard
						title="Example One"
						description="Has an action button."
						actionText="Install"
						onClick={ () => {
							console.log( 'Install clicked' );
						} }
					/>
					<ActionCard
						title="Example Two"
						description="Has action button and secondary button (visible on hover)."
						actionText={ __( 'Edit' ) }
						secondaryActionText={ __( 'Delete' ) }
						onClick={ () => {
							console.log( 'Edit clicked' );
						} }
						onSecondaryActionClick={ () => {
							console.log( 'Delete clicked' );
						} }
					/>
					<ActionCard
						title="Example Three"
						description="Waiting/in-progress state, no action button."
						actionText="Installing..."
						isWaiting
					/>
					<ActionCard
						title="Example Four"
						description="Error notification"
						actionText="Install"
						onClick={ () => {
							console.log( 'Install clicked' );
						} }
						notification={
							<Fragment>
								Plugin cannot be installed <a href="#">Retry</a> | <a href="#">Documentation</a>
							</Fragment>
						}
						notificationLevel="error"
					/>
					<ActionCard
						title="Example Five"
						description="Warning notification, action button"
						notification={
							<Fragment>
								There is a new version available. <a href="#">View details</a> or{' '}
								<a href="#">update now</a>
							</Fragment>
						}
						notificationLevel="warning"
					/>
					<ActionCard
						title="Example Six"
						description="Static text, no button"
						actionText="Active"
					/>
					<ActionCard
						title="Example Seven"
						description="Static text, secondary action button."
						actionText="Active"
						secondaryActionText={ __( 'Delete' ) }
						onSecondaryActionClick={ () => {
							console.log( 'Delete clicked' );
						} }
					/>
					<ActionCard
						title="Example Eight"
						description="Image with link and action button."
						actionText="Set Up"
						onClick={ () => {
							console.log( 'Set Up' );
						} }
						image="//s1.wp.com/wp-content/themes/h4/landing/marketing/pages/hp-jan-2019/media/man-with-shadow.jpg"
						imageLink="https://wordpress.com"
					/>
					<ActionCard
						title="Example Nine"
						description="Action Card with Toggle Control."
						actionText={ actionCardToggleChecked && 'Set Up' }
						onClick={ () => {
							console.log( 'Set Up' );
						} }
						toggleOnChange={ checked => this.setState( { actionCardToggleChecked: checked } ) }
						toggleChecked={ actionCardToggleChecked }
					/>
					<ActionCard
						badge="Premium"
						title="Example Ten"
						description="An example of an action card with a badge."
						actionText="Install"
						onClick={ () => {
							console.log( 'Install clicked' );
						} }
					/>
					<ActionCard
						isSmall
						title="Example Eleven (small)"
						description="An example of a small action card."
						actionText="Install"
						onClick={ () => {
							console.log( 'Install clicked' );
						} }
					/>
					<ActionCard
						title="Handoff"
						description="An example of an action card with Handoff."
						actionText="Configure"
						handoff="jetpack"
					/>
					<ActionCard
						title="Handoff"
						description="An example of an action card with Handoff and EditLink."
						actionText="Configure"
						handoff="jetpack"
						editLink="admin.php?page=jetpack#/settings"
					/>
					<Card>
						<h2>{ __( 'Checkboxes' ) }</h2>
						<CheckboxControl
							label={ __( 'Checkbox is tested?' ) }
							onChange={ function() {
								console.log( "Yep, it's tested" );
							} }
						/>
						<CheckboxControl
							label={ __( 'Checkbox w/Tooltip' ) }
							onChange={ function() {
								console.log( "Yep, it's tested" );
							} }
							tooltip="This is tooltip text"
						/>
						<CheckboxControl
							label={ __( 'Checkbox w/Help' ) }
							onChange={ function() {
								console.log( "Yep, it's tested" );
							} }
							help="This is help text"
						/>
					</Card>
					<Card>
						<h2>{ __( 'Image Uploader' ) }</h2>
						<ImageUpload
							image={ this.state.image }
							onChange={ image => {
								this.setState( { image } );
								console.log( 'Image:' );
								console.log( image );
							} }
						/>
					</Card>
					<Card>
						<h2>{ __( 'Text Inputs' ) }</h2>
						<TextControl
							label={ __( 'Text Input with value' ) }
							value={ inputTextValue1 }
							onChange={ value => this.setState( { inputTextValue1: value } ) }
						/>
						<TextControl
							label={ __( 'Text Input empty' ) }
							value={ inputTextValue2 }
							onChange={ value => this.setState( { inputTextValue2: value } ) }
						/>
						<TextControl
							type="number"
							label={ __( 'Number Input' ) }
							value={ inputNumValue }
							onChange={ value => this.setState( { inputNumValue: value } ) }
						/>
						<TextControl label={ __( 'Text Input disabled' ) } disabled />
					</Card>
					<Card>
						<h2>{ __( 'Progress bar' ) }</h2>
						<ProgressBar completed="2" total="3" />
						<ProgressBar completed="2" total="5" label={ __( 'Progress made' ) } />
						<ProgressBar completed="0" total="5" displayFraction />
						<ProgressBar completed="3" total="8" label={ __( 'Progress made' ) } displayFraction />
					</Card>
					<Card>
						<h2>{ __( 'Select dropdowns' ) }</h2>
						<SelectControl
							label={ __( 'Label for Select with a preselection' ) }
							value={ selectValue1 }
							options={ [
								{ value: '', label: __( '- Select -' ), disabled: true },
								{ value: '1st', label: __( 'First' ) },
								{ value: '2nd', label: __( 'Second' ) },
								{ value: '3rd', label: __( 'Third' ) },
							] }
							onChange={ value => this.setState( { selectValue1: value } ) }
						/>
						<SelectControl
							label={ __( 'Label for Select with no preselection' ) }
							value={ selectValue2 }
							options={ [
								{ value: '', label: __( '- Select -' ), disabled: true },
								{ value: '1st', label: __( 'First' ) },
								{ value: '2nd', label: __( 'Second' ) },
								{ value: '3rd', label: __( 'Third' ) },
							] }
							onChange={ value => this.setState( { selectValue2: value } ) }
						/>
						<SelectControl
							label={ __( 'Label for disabled Select' ) }
							disabled
							options={ [
								{ value: '', label: __( '- Select -' ), disabled: true },
								{ value: '1st', label: __( 'First' ) },
								{ value: '2nd', label: __( 'Second' ) },
								{ value: '3rd', label: __( 'Third' ) },
							] }
						/>
					</Card>
					<Card>
						<h2>{ __( 'Buttons' ) }</h2>
						<h3>{ __( 'Default' ) }</h3>
						<Card buttonsCard noBorder>
							<Button isPrimary>isPrimary</Button>
							<Button isSecondary>isSecondary</Button>
							<Button isTertiary>isTertiary</Button>
							<Button isQuaternary>isQuaternary</Button>
							<Button isLink>isLink</Button>
						</Card>
						<h3>{ __( 'Disabled' ) }</h3>
						<Card buttonsCard noBorder>
							<Button isPrimary disabled>
								isPrimary
							</Button>
							<Button isSecondary disabled>
								isSecondary
							</Button>
							<Button isTertiary disabled>
								isTertiary
							</Button>
							<Button isQuaternary disabled>
								isQuaternary
							</Button>
							<Button isLink disabled>
								isLink
							</Button>
						</Card>
						<h3>isSmall</h3>
						<Card buttonsCard noBorder>
							<Button isPrimary isSmall>
								isPrimary
							</Button>
							<Button isSecondary isSmall>
								isSecondary
							</Button>
							<Button isTertiary isSmall>
								isTertiary
							</Button>
							<Button isQuaternary isSmall>
								isQuaternary
							</Button>
						</Card>
					</Card>
					<Card>
						<h2>{ __( 'ButtonCard' ) }</h2>
						<ButtonCard
							href="admin.php?page=newspack-site-design-wizard"
							title={ __( 'Site Design', 'newspack' ) }
							desc={ __( 'Branding, color, typography, layouts', 'newspack' ) }
							icon={ typography }
							chevron
						/>
						<ButtonCard
							href="#"
							title={ __( 'Start a new site', 'newspack' ) }
							desc={ __( "You don't have content to import", 'newspack' ) }
							icon={ plus }
							className="br--top"
							grouped
						/>
						<ButtonCard
							href="#"
							title={ __( 'Migrate an existing site', 'newspack' ) }
							desc={ __( 'You have content to import', 'newspack' ) }
							icon={ reusableBlock }
							className="br--bottom"
							grouped
						/>
						<ButtonCard
							href="#"
							title={ __( 'Add a new Podcast', 'newspack' ) }
							desc="isSmall"
							icon={ audio }
							className="br--top"
							isSmall
							grouped
						/>
						<ButtonCard
							href="#"
							title={ __( 'Add a new Font', 'newspack' ) }
							desc="isSmall + chevron"
							icon={ typography }
							className="br--bottom"
							chevron
							isSmall
							grouped
						/>
					</Card>
				</div>
				<Footer />
			</Fragment>
		);
	}
}

render( <ComponentsDemo />, document.getElementById( 'newspack-components-demo' ) );
