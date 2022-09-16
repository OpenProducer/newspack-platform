<?php
/**
 * Reader-revenue related emails.
 *
 * @package Newspack
 */

namespace Newspack;

defined( 'ABSPATH' ) || exit;

/**
 * Reader-revenue related emails.
 */
class Reader_Revenue_Emails {
	const EMAIL_TYPES = [
		'RECEIPT' => 'receipt',
	];

	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public static function init() {
		add_filter( 'newspack_email_configs', [ __CLASS__, 'add_email_configs' ] );
	}

	/**
	 * Register email type.
	 *
	 * @param array $configs Email types.
	 */
	public static function add_email_configs( $configs ) {
		$configs[ self::EMAIL_TYPES['RECEIPT'] ] = [
			'name'                   => self::EMAIL_TYPES['RECEIPT'],
			'label'                  => __( 'Receipt', 'newspack' ),
			'description'            => __( "Email sent to the donor after they've donated.", 'newspack' ),
			'template'               => dirname( NEWSPACK_PLUGIN_FILE ) . '/includes/templates/reader-revenue-emails/receipt.php',
			'editor_notice'          => __( 'This email will be sent to a reader after they contribute to your site.', 'newspack' ),
			'available_placeholders' => [
				[
					'label'    => __( 'the payment amount', 'newspack' ),
					'template' => '*AMOUNT*',
				],
				[
					'label'    => __( 'payment date', 'newspack' ),
					'template' => '*DATE*',
				],
				[
					'label'    => __( 'payment method (last four digits of the card used)', 'newspack' ),
					'template' => '*PAYMENT_METHOD*',
				],
				[
					'label'    => __(
						'the contact email to your site (same as the "From" email address)',
						'newspack'
					),
					'template' => '*CONTACT_EMAIL*',
				],
				[
					'label'    => __( 'automatically-generated receipt link', 'newspack' ),
					'template' => '*RECEIPT_URL*',
				],
			],
		];
		return $configs;
	}
}
Reader_Revenue_Emails::init();
