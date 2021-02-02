<?php
/**
 * Publish to Apple News: \Apple_Exporter\Components\Cover class
 *
 * @package Apple_News
 * @subpackage Apple_Exporter\Components
 */

namespace Apple_Exporter\Components;

use Apple_Exporter\Theme;

/**
 * A cover is optional and displayed at the very top of the article. It's
 * loaded from the Exporter_Content's cover attribute, if present.
 * This component does not need a node so no need to implement match_node.
 *
 * In a WordPress context, the Exporter_Content's cover attribute is a post's
 * thumbnail, a.k.a featured image.
 *
 * @since 0.2.0
 */
class Cover extends Component {

	/**
	 * Register all specs for the component.
	 *
	 * @access public
	 */
	public function register_specs() {
		$theme = \Apple_Exporter\Theme::get_used();
		$this->register_spec(
			'json',
			__( 'JSON', 'apple-news' ),
			array(
				'role'       => 'header',
				'layout'     => 'headerPhotoLayout',
				'components' => array(
					array(
						'role'   => 'photo',
						'layout' => 'headerPhotoLayout',
						'URL'    => '#url#',
					),
				),
				'behavior'   => array(
					'type'   => 'parallax',
					'factor' => 0.8,
				),
			)
		);

		$conditional = array();
		if ( ! empty( $theme->get_value( 'caption_color_dark' ) ) ) {
			$conditional = array(
				'conditional' => array(
					'textColor'  => '#caption_color_dark#',
					'conditions' => array(
						'minSpecVersion'       => '1.14',
						'preferredColorScheme' => 'dark',
					),
				),
			);
		}

		$this->register_spec(
			'jsonWithCaption',
			__( 'JSON with Caption', 'apple-news' ),
			array(
				'role'       => 'header',
				'layout'     => 'headerPhotoLayout',
				'components' => array(
					array(
						'role'    => 'photo',
						'layout'  => 'headerPhotoLayoutWithCaption',
						'URL'     => '#url#',
						'caption' => array(
							'format'    => 'html',
							'text'      => '#caption#',
							'textStyle' => array(
								'fontName' => '#caption_font#',
							),
						),
					),
					array(
						'role'      => 'caption',
						'text'      => '#caption#',
						'format'    => 'html',
						'textStyle' => array_merge(
							array(
								'textAlignment' => '#text_alignment#',
								'fontName'      => '#caption_font#',
								'fontSize'      => '#caption_size#',
								'tracking'      => '#caption_tracking#',
								'lineHeight'    => '#caption_line_height#',
								'textColor'     => '#caption_color#',
							),
							$conditional
						),
					),
				),
				'behavior'   => array(
					'type'   => 'parallax',
					'factor' => 0.8,
				),
			)
		);

		$this->register_spec(
			'headerPhotoLayout',
			__( 'Layout', 'apple-news' ),
			array(
				'ignoreDocumentMargin' => true,
				'columnStart'          => 0,
				'columnSpan'           => '#layout_columns#',
			)
		);

		$this->register_spec(
			'headerPhotoLayoutWithCaption',
			__( 'Layout with Caption', 'apple-news' ),
			array(
				'ignoreDocumentMargin' => true,
				'columnStart'          => 0,
				'columnSpan'           => '#layout_columns#',
				'margin'               => array(
					'bottom' => '#caption_line_height#',
				),
			)
		);

		$this->register_spec(
			'headerBelowTextPhotoLayout',
			__( 'Below Text Layout', 'apple-news' ),
			array(
				'ignoreDocumentMargin' => true,
				'columnStart'          => 0,
				'columnSpan'           => '#layout_columns#',
				'margin'               => array(
					'top'    => 30,
					'bottom' => 0,
				),
			)
		);
	}

	/**
	 * Build the component.
	 *
	 * @param array|string $options {
	 *    The options for the component. If a string is provided, assume it is a URL.
	 *
	 *    @type string $caption The caption for the image.
	 *    @type string $url     The URL to the featured image.
	 * }
	 * @access protected
	 */
	protected function build( $options ) {

		$theme = Theme::get_used();

		// Handle case where options is a URL.
		if ( ! is_array( $options ) ) {
			$options = [
				'url' => $options,
			];
		}

		// If we can't get a valid URL, bail.
		$url   = $this->maybe_bundle_source( $options['url'] );
		$check = trim( $url );
		if ( empty( $check ) ) {
			return;
		}

		// Fork for caption vs. not.
		if ( ! empty( $options['caption'] )
			&& true === $theme->get_value( 'cover_caption' )
		) {
			$this->register_json(
				'jsonWithCaption',
				array(
					'#caption#'          => $options['caption'],
					'#url#'              => $url,
					'#caption_tracking#' => intval( $theme->get_value( 'caption_tracking' ) ) / 100,
				)
			);
		} else {
			$this->register_json(
				'json',
				array(
					'#url#' => $url,
				)
			);
		}

		$this->set_default_layout();
	}

	/**
	 * Set the default layout for the component.
	 *
	 * @access private
	 */
	private function set_default_layout() {

		// Get information about the currently loaded theme.
		$theme = \Apple_Exporter\Theme::get_used();

		$this->register_layout(
			'headerPhotoLayout',
			'headerPhotoLayout',
			array(
				'#layout_columns#' => $theme->get_layout_columns(),
			)
		);

		$this->register_layout(
			'headerPhotoLayoutWithCaption',
			'headerPhotoLayoutWithCaption',
			array(
				'#caption_line_height#' => $theme->get_value( 'caption_line_height' ),
				'#layout_columns#'      => $theme->get_layout_columns(),
			)
		);

		$this->register_layout(
			'headerBelowTextPhotoLayout',
			'headerBelowTextPhotoLayout',
			array(
				'#layout_columns#' => $theme->get_layout_columns(),
			)
		);
	}
}
