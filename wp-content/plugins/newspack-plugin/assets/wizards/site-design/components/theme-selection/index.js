/**
 * Internal dependencies
 */
import { Grid, StyleCard } from '../../../../components/src';
import NewspackImg from './images/newspack.png';
import ScottImg from './images/scott.png';
import NelsonImg from './images/nelson.png';
import KatharineImg from './images/katharine.png';
import SachaImg from './images/sacha.png';
import JosephImg from './images/joseph.png';

const ThemeSelection = ( { theme, updateTheme } ) => (
	<Grid columns={ 3 } gutter={ 32 }>
		<StyleCard
			cardTitle="Newspack"
			image={ NewspackImg }
			url="//newspack.newspackstaging.com"
			isActive={ theme === 'newspack-theme' }
			onClick={ () => updateTheme( 'newspack-theme' ) }
			id={ `card--newspack-theme` }
		/>
		<StyleCard
			cardTitle="Scott"
			image={ ScottImg }
			url="//scott.newspackstaging.com"
			isActive={ theme === 'newspack-scott' }
			onClick={ () => updateTheme( 'newspack-scott' ) }
			id={ `card--newspack-scott` }
		/>
		<StyleCard
			cardTitle="Nelson"
			image={ NelsonImg }
			url="//nelson.newspackstaging.com"
			isActive={ theme === 'newspack-nelson' }
			onClick={ () => updateTheme( 'newspack-nelson' ) }
			id={ `card--newspack-nelson` }
		/>
		<StyleCard
			cardTitle="Katharine"
			image={ KatharineImg }
			url="//katharine.newspackstaging.com"
			isActive={ theme === 'newspack-katharine' }
			onClick={ () => updateTheme( 'newspack-katharine' ) }
			id={ `card--newspack-katharine` }
		/>
		<StyleCard
			cardTitle="Sacha"
			image={ SachaImg }
			url="//sacha.newspackstaging.com"
			isActive={ theme === 'newspack-sacha' }
			onClick={ () => updateTheme( 'newspack-sacha' ) }
			id={ `card--newspack-sacha` }
		/>
		<StyleCard
			cardTitle="Joseph"
			image={ JosephImg }
			url="//joseph.newspackstaging.com"
			isActive={ theme === 'newspack-joseph' }
			onClick={ () => updateTheme( 'newspack-joseph' ) }
			id={ `card--newspack-joseph` }
		/>
	</Grid>
);

export default ThemeSelection;
