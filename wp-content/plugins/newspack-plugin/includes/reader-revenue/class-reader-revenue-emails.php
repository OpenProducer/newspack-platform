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
	 * Get the from email address for reader revenue emails.
	 *
	 * @return string
	 */
	public static function get_from_email() {
		// Get the site domain and get rid of www.
		$sitename   = wp_parse_url( network_home_url(), PHP_URL_HOST );
		$from_email = 'receipts@';

		if ( null !== $sitename ) {
			if ( 'www.' === substr( $sitename, 0, 4 ) ) {
				$sitename = substr( $sitename, 4 );
			}

			$from_email .= $sitename;
		}

		return apply_filters( 'newspack_reader_revenue_from_email', $from_email );
	}

	/**
	 * Register email type.
	 *
	 * @param array $configs Email types.
	 */
	public static function add_email_configs( $configs ) {
		$configs[ self::EMAIL_TYPES['RECEIPT'] ] = [
			'name'                   => self::EMAIL_TYPES['RECEIPT'],
			'label'                  => __( 'Receipt', 'newspack-plugin' ),
			'description'            => __( "Email sent to the donor after they've donated.", 'newspack-plugin' ),
			'template'               => dirname( NEWSPACK_PLUGIN_FILE ) . '/includes/templates/reader-revenue-emails/receipt.php',
			'editor_notice'          => __( 'This email will be sent to a reader after they contribute to your site.', 'newspack-plugin' ),
			'from_email'             => self::get_from_email(),
			'available_placeholders' => [
				[
					'label'    => __( 'the customer billing name', 'newspack-plugin' ),
					'template' => '*BILLING_NAME*',
				],
				[
					'label'    => __( 'the customer billing first name', 'newspack-plugin' ),
					'template' => '*BILLING_FIRST_NAME*',
				],
				[
					'label'    => __( 'the customer billing last name', 'newspack-plugin' ),
					'template' => '*BILLING_LAST_NAME*',
				],
				[
					'label'    => __( 'the billing frequency (one-time, monthly or annual)', 'newspack-plugin' ),
					'template' => '*BILLING_FREQUENCY*',
				],
				[
					'label'    => __( 'the product name', 'newspack-plugin' ),
					'template' => '*PRODUCT_NAME*',
				],
				[
					'label'    => __( 'the payment amount', 'newspack-plugin' ),
					'template' => '*AMOUNT*',
				],
				[
					'label'    => __( 'payment date', 'newspack-plugin' ),
					'template' => '*DATE*',
				],
				[
					'label'    => __( 'payment method (last four digits of the card used)', 'newspack-plugin' ),
					'template' => '*PAYMENT_METHOD*',
				],
				[
					'label'    => __(
						'the contact email to your site (same as the "From" email address)',
						'newspack-plugin'
					),
					'template' => '*CONTACT_EMAIL*',
				],
				[
					'label'    => __( 'automatically-generated receipt link', 'newspack-plugin' ),
					'template' => '*RECEIPT_URL*',
				],
			],
		];

		return $configs;
	}
}
Reader_Revenue_Emails::init();
