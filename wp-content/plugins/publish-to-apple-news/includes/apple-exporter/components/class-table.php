<?php
/**
 * Contains a component class representing a table.
 *
 * @package Apple_News
 * @since 1.4.0
 */

namespace Apple_Exporter\Components;

/**
 * A component class representing a table.
 *
 * @since 1.4.0
 */
class Table extends Component {

	/**
	 * Look for node matches for this component.
	 *
	 * @param \DOMElement $node The node to examine for matches.
	 * @access public
	 * @return \DOMElement|null The node on success, or null on no match.
	 */
	public static function node_matches( $node ) {

		// In order to match, HTML support needs to be turned on globally.
		$settings = get_option( \Admin_Apple_Settings::$option_name );
		if ( ! empty( $settings['html_support'] ) && 'no' === $settings['html_support'] ) {
			return null;
		}

		// Check if node is a table, or a figure with a table class.
		if (
			(
				self::node_has_class( $node, 'wp-block-table' ) &&
				$node->hasChildNodes() &&
				'table' === $node->firstChild->nodeName
			) ||
			'table' === $node->nodeName ) {
			return $node;
		}

		return null;
	}

	/**
	 * Register all specs for the component.
	 *
	 * @access public
	 */
	public function register_specs() {
		// Get information about the currently loaded theme.
		$theme = \Apple_Exporter\Theme::get_used();

		// Register the JSON for the table itself.
		$this->register_spec(
			'json',
			__( 'JSON', 'apple-news' ),
			array(
				'role'   => 'htmltable',
				'html'   => '#html#',
				'layout' => 'table-layout',
				'style'  => 'default-table',
			)
		);
		$this->register_spec(
			'json-with-caption-text',
			__( 'JSON With Caption Text', 'apple-news' ),
			array(
				'role'       => 'container',
				// Table Component.
				'components' => array(
					array(
						'role'   => 'htmltable',
						'html'   => '#html#',
						'layout' => 'table-layout',
						'style'  => 'default-table',
					),
					// Caption Component.
					array(
						'role'   => 'caption',
						'text'   => '#caption_text#',
						'format' => 'html',
					),
				),
			)
		);

		// Register the JSON for the table layout.
		$this->register_spec(
			'table-layout',
			__( 'Table Layout', 'apple-news' ),
			array(
				'margin' => array(
					'bottom' => '#table_body_line_height#',
				),
			)
		);

		// Register the JSON for the table style.
		$table_cell_base_conditional    = array();
		$table_row_col_base_conditional = array();
		// Get Dark Table Colors.
		$table_border_color_dark            = $theme->get_value( 'table_border_color_dark' );
		$table_body_background_color_dark   = $theme->get_value( 'table_body_background_color_dark' );
		$table_body_color_dark              = $theme->get_value( 'table_body_color_dark' );
		$table_header_background_color_dark = $theme->get_value( 'table_header_background_color_dark' );
		$table_header_color_dark            = $theme->get_value( 'table_header_color_dark' );

		// If all are empty, do not add conditional styles.
		$dark_table_colors_exist =
			! empty( $table_border_color_dark ) ||
			! empty( $table_body_background_color_dark ) ||
			! empty( $table_body_color_dark ) ||
			! empty( $table_header_background_color_dark ) ||
			! empty( $table_header_color_dark );
		if ( $dark_table_colors_exist ) {
			$table_cell_base_conditional    = array(
				array(
					'selectors'  => array(
						array( 'evenRows' => true ),
						array( 'oddRows' => true ),
					),
					'conditions' => array(
						'minSpecVersion'       => '1.14',
						'preferredColorScheme' => 'dark',
					),
				),
			);
			$table_row_col_base_conditional = array(
				array(
					'selectors'  => array(
						array( 'even' => true ),
						array( 'odd' => true ),
					),
					'conditions' => array(
						'minSpecVersion'       => '1.14',
						'preferredColorScheme' => 'dark',
					),
				),
			);
		}

		// The following block sets:
		// Dark Background Color of Cells
		// Dark Text Color of Cells.
		$dark_bg_text_conditional = array();
		if (
			! empty( $table_body_background_color_dark ) ||
			! empty( $table_body_color_dark )
		) {
			$dark_bg_text_conditional = array(
				'conditional' => array( $table_cell_base_conditional[0] ),
			);
		}

		if ( ! empty( $table_body_background_color_dark ) ) {
			$dark_bg_text_conditional['conditional'][0]['backgroundColor'] = '#table_body_background_color_dark#';
		}

		if ( ! empty( $table_body_color_dark ) ) {
			$dark_bg_text_conditional['conditional'][0]['textStyle'] = array(
				'textColor' => '#table_body_color_dark#',
			);
		}

		// The following block sets:
		// Dark Header Background Color of Cells
		// Dark Header Text Color of Cells.
		$dark_header_bg_text_conditional = array();
		if (
			! empty( $table_body_background_color_dark ) ||
			! empty( $table_body_color_dark )
		) {
			$dark_header_bg_text_conditional = array(
				'conditional' => array( $table_cell_base_conditional[0] ),
			);
		}

		if ( ! empty( $table_header_background_color_dark ) ) {
			$dark_header_bg_text_conditional['conditional'][0]['backgroundColor'] = '#table_header_background_color_dark#';
		}

		if ( ! empty( $table_header_color_dark ) ) {
			$dark_header_bg_text_conditional['conditional'][0]['textStyle'] = array(
				'textColor' => '#table_header_color_dark#',
			);
		}

		// Set Dark Border for Columns.
		$dark_inner_border_conditional = array();
		if ( ! empty( $table_border_color_dark ) ) {
			$dark_inner_border_conditional = array(
				'conditional' => array(
					$table_row_col_base_conditional[0] + array(
						'divider' => array(
							'color' => '#table_border_color_dark#',
							'style' => '#table_border_style#',
							'width' => '#table_border_width#',
						),
					),
				),
			);
		}

		// Set Dark Outer Border for Table.
		$dark_outer_border_conditional = array();
		if ( ! empty( $table_border_color_dark ) ) {
			$dark_outer_border_conditional = array(
				'conditional' => array(
					'border'     => array(
						'all' => array(
							'color' => '#table_border_color_dark#',
							'style' => '#table_border_style#',
							'width' => '#table_border_width#',
						),
					),
					'conditions' => array(
						'minSpecVersion'       => '1.14',
						'preferredColorScheme' => 'dark',
					),
				),
			);
		}

		$this->register_spec(
			'default-table',
			__( 'Table Style', 'apple-news' ),
			array_merge(
				array(
					'border'     => array(
						'all' => array(
							'color' => '#table_border_color#',
							'style' => '#table_border_style#',
							'width' => '#table_border_width#',
						),
					),
					'tableStyle' => array(
						'cells'       => array_merge(
							array(
								'backgroundColor'     => '#table_body_background_color#',
								'horizontalAlignment' => '#table_body_horizontal_alignment#',
								'padding'             => '#table_body_padding#',
								'textStyle'           => array(
									'fontName'   => '#table_body_font#',
									'fontSize'   => '#table_body_size#',
									'lineHeight' => '#table_body_line_height#',
									'textColor'  => '#table_body_color#',
									'tracking'   => '#table_body_tracking#',
								),
								'verticalAlignment'   => '#table_body_vertical_alignment#',
							),
							$dark_bg_text_conditional
						),
						'columns'     => array_merge(
							array(
								'divider' => array(
									'color' => '#table_border_color#',
									'style' => '#table_border_style#',
									'width' => '#table_border_width#',
								),
							),
							$dark_inner_border_conditional
						),
						'headerCells' => array_merge(
							array(
								'backgroundColor'     => '#table_header_background_color#',
								'horizontalAlignment' => '#table_header_horizontal_alignment#',
								'padding'             => '#table_header_padding#',
								'textStyle'           => array(
									'fontName'   => '#table_header_font#',
									'fontSize'   => '#table_header_size#',
									'lineHeight' => '#table_header_line_height#',
									'textColor'  => '#table_header_color#',
									'tracking'   => '#table_header_tracking#',
								),
								'verticalAlignment'   => '#table_header_vertical_alignment#',
							),
							$dark_header_bg_text_conditional
						),
						'headerRows'  => array_merge(
							array(
								'divider' => array(
									'color' => '#table_border_color#',
									'style' => '#table_border_style#',
									'width' => '#table_border_width#',
								),
							),
							$dark_inner_border_conditional
						),
						'rows'        => array_merge(
							array(
								'divider' => array(
									'color' => '#table_border_color#',
									'style' => '#table_border_style#',
									'width' => '#table_border_width#',
								),
							),
							$dark_inner_border_conditional
						),
					),
				),
				$dark_outer_border_conditional
			)
		);
	}

	/**
	 * Build the component.
	 *
	 * @param string $html The HTML to parse into text for processing.
	 * @access protected
	 */
	protected function build( $html ) {

		// If HTML is not enabled for this component, bail.
		if ( ! $this->html_enabled() ) {
			return;
		}

		/**
		 * Allows for table HTML to be filtered before being applied.
		 *
		 * @param string $html The raw HTML for the table.
		 *
		 * @since 1.4.0
		 */
		$table_html = apply_filters(
			'apple_news_build_table_html',
			$this->parser->parse( $html )
		);

		// If we don't have any table HTML at this point, bail.
		if ( empty( $table_html ) ) {
			return;
		}

		$table_spec    = 'json';
		$table_caption = '';
		if ( preg_match( '/<figcaption>(.+?)<\/figcaption>/', $html, $caption_match ) ) {
			$table_caption = $caption_match[1];
			$table_spec    = 'json-with-caption-text';
		}
		$values = array(
			'#html#'         => preg_replace( '/<\/table>.*/', '</table>', $table_html ),
			'#caption_text#' => $table_caption,
		);

		// Add the JSON for this component.
		$this->register_json( $table_spec, $values );

		// Register the layout for the table.
		$this->register_layout( 'table-layout', 'table-layout' );

		// Register the style for the table.
		$this->register_component_style(
			'default-table',
			'default-table'
		);
	}

	/**
	 * Whether HTML format is enabled for this component type.
	 *
	 * @param bool $enabled Optional. Whether to enable HTML support for this component. Defaults to true.
	 *
	 * @access protected
	 * @return bool Whether HTML format is enabled for this component type.
	 */
	protected function html_enabled( $enabled = true ) { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		return parent::html_enabled( $enabled );
	}
}
