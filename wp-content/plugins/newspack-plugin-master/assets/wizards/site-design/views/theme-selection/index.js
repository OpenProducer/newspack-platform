/**
 * WordPress dependencies
 */
import { Component } from '@wordpress/element';

/**
 * Internal dependencies
 */
import { StyleCard, StyleCardGroup, withWizardScreen } from '../../../../components/src';
import NewspackImg from './images/newspack.png';
import ScottImg from './images/scott.png';
import NelsonImg from './images/nelson.png';
import KatharineImg from './images/katharine.png';
import SachaImg from './images/sacha.png';
import JosephImg from './images/joseph.png';

/**
 * Theme Selection Screen.
 */
class ThemeSelection extends Component {
	/**
	 * Render.
	 */
	render() {
		const { updateTheme, theme } = this.props;
		return (
			<StyleCardGroup>
				<StyleCard
					cardTitle="Newspack"
					image={ NewspackImg }
					url="//newspack.newspackstaging.com"
					isActive={ theme === 'newspack-theme' }
					onClick={ () => updateTheme( 'newspack-theme' ) }
				/>
				<StyleCard
					cardTitle="Scott"
					image={ ScottImg }
					url="//scott.newspackstaging.com"
					isActive={ theme === 'newspack-scott' }
					onClick={ () => updateTheme( 'newspack-scott' ) }
				/>
				<StyleCard
					cardTitle="Nelson"
					image={ NelsonImg }
					url="//nelson.newspackstaging.com"
					isActive={ theme === 'newspack-nelson' }
					onClick={ () => updateTheme( 'newspack-nelson' ) }
				/>
				<StyleCard
					cardTitle="Katharine"
					image={ KatharineImg }
					url="//katharine.newspackstaging.com"
					isActive={ theme === 'newspack-katharine' }
					onClick={ () => updateTheme( 'newspack-katharine' ) }
				/>
				<StyleCard
					cardTitle="Sacha"
					image={ SachaImg }
					url="//sacha.newspackstaging.com"
					isActive={ theme === 'newspack-sacha' }
					onClick={ () => updateTheme( 'newspack-sacha' ) }
				/>
				<StyleCard
					cardTitle="Joseph"
					image={ JosephImg }
					url="//joseph.newspackstaging.com"
					isActive={ theme === 'newspack-joseph' }
					onClick={ () => updateTheme( 'newspack-joseph' ) }
				/>
			</StyleCardGroup>
		);
	}
}

export default withWizardScreen( ThemeSelection );
