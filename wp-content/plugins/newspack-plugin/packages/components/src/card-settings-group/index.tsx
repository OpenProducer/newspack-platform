/**
 * Content Gates edit - settings group component.
 */

/**
 * Internal dependencies
 */
import { Card } from '../';
import './style.scss';

const CardSettingsGroup = ( {
	actionType = 'chevron',
	children,
	icon = null,
	title = '',
	description = '',
	isActive = false,
	onEnable = () => {},
}: {
	actionType?: 'chevron' | 'toggle' | 'button' | 'link' | 'none';
	children?: React.ReactNode;
	icon?: React.ReactNode;
	title: string;
	description?: string;
	isActive?: boolean;
	onEnable?: () => void;
} ) => {
	return (
		<Card
			className="newspack-card--core--settings-group"
			actionType={ actionType }
			isSmall
			__experimentalCoreCard
			__experimentalCoreProps={ {
				header: (
					<>
						<h3>{ title }</h3>
						{ description && <p>{ description }</p> }
					</>
				),
				onHeaderClick: onEnable,
				icon,
				iconBackgroundColor: true,
				isActive,
				title,
			} }
		>
			{ isActive && children }
		</Card>
	);
};

export default CardSettingsGroup;
