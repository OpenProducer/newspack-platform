<?php
/**
 * Newspack Ads GAM management
 *
 * @package Newspack
 */

namespace Newspack_Ads\Providers;

use Newspack_Ads\Providers\GAM_Model;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\AdsApi\Common\Configuration;
use Google\AdsApi\AdManager\AdManagerSessionBuilder;
use Google\AdsApi\AdManager\Util\v202205\StatementBuilder;
use Google\AdsApi\AdManager\AdManagerSession;
use Google\AdsApi\AdManager\v202205\AdUnitTargeting;
use Google\AdsApi\AdManager\v202205\LineItemCreativeAssociation;
use Google\AdsApi\AdManager\v202205\Statement;
use Google\AdsApi\AdManager\v202205\String_ValueMapEntry;
use Google\AdsApi\AdManager\v202205\TextValue;
use Google\AdsApi\AdManager\v202205\SetValue;
use Google\AdsApi\AdManager\v202205\CustomTargetingKey;
use Google\AdsApi\AdManager\v202205\CustomTargetingValue;
use Google\AdsApi\AdManager\v202205\ServiceFactory;
use Google\AdsApi\AdManager\v202205\ArchiveAdUnits as ArchiveAdUnitsAction;
use Google\AdsApi\AdManager\v202205\ActivateAdUnits as ActivateAdUnitsAction;
use Google\AdsApi\AdManager\v202205\DeactivateAdUnits as DeactivateAdUnitsAction;
use Google\AdsApi\AdManager\v202205\Network;
use Google\AdsApi\AdManager\v202205\User;
use Google\AdsApi\AdManager\v202205\AdUnit;
use Google\AdsApi\AdManager\v202205\AdUnitSize;
use Google\AdsApi\AdManager\v202205\AdUnitTargetWindow;
use Google\AdsApi\AdManager\v202205\Order;
use Google\AdsApi\AdManager\v202205\ArchiveOrders;
use Google\AdsApi\AdManager\v202205\UpdateResult;
use Google\AdsApi\AdManager\v202205\Creative;
use Google\AdsApi\AdManager\v202205\LineItem;
use Google\AdsApi\AdManager\v202205\EnvironmentType;
use Google\AdsApi\AdManager\v202205\Size;
use Google\AdsApi\AdManager\v202205\Company;
use Google\AdsApi\AdManager\v202205\CompanyType;

use Google\AdsApi\AdManager\v202205\Goal;
use Google\AdsApi\AdManager\v202205\CreativePlaceholder;
use Google\AdsApi\AdManager\v202205\Money;
use Google\AdsApi\AdManager\v202205\Targeting;
use Google\AdsApi\AdManager\v202205\CustomCriteriaSet;
use Google\AdsApi\AdManager\v202205\CustomCriteria;
use Google\AdsApi\AdManager\v202205\InventoryTargeting;

use Google\AdsApi\AdManager\v202205\ApiException;

require_once NEWSPACK_ADS_COMPOSER_ABSPATH . 'autoload.php';

/**
 * Newspack Ads GAM Management
 */
final class GAM_API {
	// https://developers.google.com/ad-manager/api/soap_xml: An arbitrary string name identifying your application. This will be shown in Google's log files.
	const GAM_APP_NAME_FOR_LOGS = 'Newspack';

	const SERVICE_ACCOUNT_CREDENTIALS_OPTION_NAME = '_newspack_ads_gam_credentials';

	const GAM_API_VERSION = 'v202205';

	/**
	 * Codes of networks that the user has access to.
	 *
	 * @var Network[]
	 */
	private static $networks = [];

	/**
	 * Reusable GAM session.
	 *
	 * @var AdManagerSession
	 */
	private static $session = null;

	/**
	 * GAM Network Code in use.
	 *
	 * @var string
	 */
	private static $network_code = null;

	/**
	 * Custom targeting keys.
	 *
	 * @var string[]
	 */
	public static $custom_targeting_keys = [
		'id',
		'slug',
		'category',
		'post_type',
		'template',
	];

	/**
	 * Cached credentials.
	 *
	 * @var object|false OAuth2 credentials or false, if none cached.
	 */
	private static $oauth2_credentials = false;

	/**
	 * Get a WP_Error object from an optional ApiException or message.
	 *
	 * @param ApiException $exception       Optional Google Ads API exception.
	 * @param string       $default_message Optional default message to use.
	 *
	 * @return WP_Error Error.
	 */
	private static function get_api_error( ApiException $exception = null, $default_message = null ) {
		$error_message = $default_message;
		$errors        = [];
		if ( ! is_null( $exception ) ) {
			$error_message = $error_message ?? $exception->getMessage();
			foreach ( $exception->getErrors() as $error ) {
				$errors[] = $error->getErrorString();
			}
		}
		if ( in_array( 'UniqueError.NOT_UNIQUE', $errors ) ) {
			$error_message = __( 'Name must be unique.', 'newspack-ads' );
		}
		if ( in_array( 'CommonError.CONCURRENT_MODIFICATION', $errors ) ) {
			$error_message = __( 'Unexpected API error, please try again in 30 seconds.', 'newspack-ads' );
		}
		return new \WP_Error(
			'newspack_ads_gam_error',
			$error_message ?? __( 'An unexpected error occurred', 'newspack-ads' ),
			array(
				'status' => '400',
				'level'  => 'warning',
			)
		);
	}

	/**
	 * Set the network code to be used.
	 *
	 * @param string $network_code Network code.
	 */
	public static function set_network_code( $network_code ) {
		self::$network_code = $network_code;
	}

	/**
	 * Get credentials for connecting to GAM.
	 *
	 * @throws \Exception If credentials are not found.
	 */
	private static function get_credentials() {
		$mode = self::get_connection_details();
		if ( $mode['credentials'] ) {
			return $mode['credentials'];
		}
		throw new \Exception( __( 'Credentials not found.', 'newspack-ads' ) );
	}

	/**
	 * Get OAuth2 credentials.
	 *
	 * @return object OAuth2 credentials.
	 */
	private static function get_google_oauth2_credentials() {
		if ( self::$oauth2_credentials ) {
			return self::$oauth2_credentials;
		}
		if ( class_exists( 'Newspack\Google_Services_Connection' ) ) {
			$fresh_oauth2_credentials = \Newspack\Google_Services_Connection::get_oauth2_credentials();
			if ( false !== $fresh_oauth2_credentials ) {
				self::$oauth2_credentials = $fresh_oauth2_credentials;
				return self::$oauth2_credentials;
			}
		}
		return false;
	}

	/**
	 * Get Service Account credentials.
	 *
	 * @param array $service_account_credentials_config Service Account Credentials.
	 *
	 * @return ServiceAccountCredentials|false OAuth2 credentials or false otherwise.
	 */
	private static function get_service_account_credentials( $service_account_credentials_config = false ) {
		if ( false === $service_account_credentials_config ) {
			$service_account_credentials_config = self::service_account_credentials_config();
		}
		if ( ! $service_account_credentials_config ) {
			return false;
		}
		try {
			return new ServiceAccountCredentials( 'https://www.googleapis.com/auth/dfp', $service_account_credentials_config );
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Get GAM service account user.
	 *
	 * @return User Current user.
	 */
	private static function get_current_user() {
		$service_factory = new ServiceFactory();
		$session         = self::get_gam_session();
		$service         = $service_factory->createUserService( $session );
		return $service->getCurrentUser();
	}

	/**
	 * Get GAM networks the authenticated user has access to.
	 *
	 * @return Network[] Array of networks.
	 * @throws \Exception If not able to fetch networks.
	 */
	private static function get_gam_networks() {
		if ( empty( self::$networks ) ) {
			// Create a configuration and session to get the network codes.
			$config = new Configuration(
				[
					'AD_MANAGER' => [
						'networkCode'     => '-', // Provide non-empty network code to pass validation.
						'applicationName' => self::GAM_APP_NAME_FOR_LOGS,
					],
				]
			);

			$oauth2_credentials = self::get_credentials();
			$session            = ( new AdManagerSessionBuilder() )->from( $config )->withOAuth2Credential( $oauth2_credentials )->build();
			$service_factory    = new ServiceFactory();
			self::$networks     = $service_factory->createNetworkService( $session )->getAllNetworks();
		}
		return self::$networks;
	}

	/**
	 * Get serialized GAM networks the authenticated user has access to.
	 *
	 * @return array[] Array of serialized networks.
	 * @throws \Exception If not able to fetch networks.
	 */
	public static function get_serialized_gam_networks() {
		$networks = self::get_gam_networks();
		return array_map(
			function( $network ) {
				return [
					'id'   => $network->getId(),
					'name' => $network->getDisplayName(),
					'code' => $network->getNetworkCode(),
				];
			},
			$networks
		);
	}

	/**
	 * Get user's GAM network. Defaults to the first found network if not found or empty.
	 *
	 * @return Network GAM network.
	 * @throws \Exception If there is no GAM network to use.
	 */
	private static function get_gam_network() {
		$networks     = self::get_gam_networks();
		$network_code = self::$network_code;
		if ( empty( $networks ) ) {
			throw new \Exception( __( 'Missing GAM Ad network.', 'newspack-ads' ) );
		}
		if ( $network_code ) {
			foreach ( $networks as $network ) {
				if ( $network_code === $network->getNetworkCode() ) {
					return $network;
				}
			}
		}
		return $networks[0];
	}

	/**
	 * Get user's GAM network code.
	 *
	 * @return int GAM network code.
	 */
	private static function get_gam_network_code() {
		return self::get_gam_network()->getNetworkCode();
	}

	/**
	 * Get GAM session for making API requests.
	 *
	 * @return AdManagerSession GAM Session.
	 */
	private static function get_gam_session() {
		if ( self::$session ) {
			return self::$session;
		}
		$oauth2_credentials = self::get_credentials();
		$service_factory    = new ServiceFactory();

		// Create a new configuration and session, with a network code.
		$config        = new Configuration(
			[
				'AD_MANAGER' => [
					'networkCode'     => self::get_gam_network_code(),
					'applicationName' => self::GAM_APP_NAME_FOR_LOGS,
				],
			]
		);
		self::$session = ( new AdManagerSessionBuilder() )->from( $config )->withOAuth2Credential( $oauth2_credentials )->build();

		return self::$session;
	}

	/**
	 * Create inventory service.
	 *
	 * @return InventoryService Inventory service.
	 */
	private static function get_gam_inventory_service() {
		$service_factory = new ServiceFactory();
		$session         = self::get_gam_session();
		return $service_factory->createInventoryService( $session );
	}

	/**
	 * Create order service.
	 *
	 * @return OrderService Order service.
	 */
	private static function get_order_service() {
		$service_factory = new ServiceFactory();
		$session         = self::get_gam_session();
		return $service_factory->createOrderService( $session );
	}

	/**
	 * Create creative service.
	 *
	 * @return CreativeService Creative service.
	 */
	private static function get_creative_service() {
		$service_factory = new ServiceFactory();
		$session         = self::get_gam_session();
		return $service_factory->createCreativeService( $session );
	}

	/**
	 * Create line item service.
	 *
	 * @return LineItemService Line Item service.
	 */
	private static function get_line_item_service() {
		$service_factory = new ServiceFactory();
		$session         = self::get_gam_session();
		return $service_factory->createLineItemService( $session );
	}

	/**
	 * Create company service.
	 *
	 * @return CompanyService Company service.
	 */
	private static function get_company_service() {
		$service_factory = new ServiceFactory();
		$session         = self::get_gam_session();
		return $service_factory->createCompanyService( $session );
	}

	/**
	 * Create a statement builder for ad unit retrieval.
	 *
	 * @param int[] $ids Optional array of ad unit ids.
	 * @return StatementBuilder Statement builder.
	 */
	private static function get_serialised_gam_ad_units_statement_builder( $ids = [] ) {
		$inventory_service = self::get_gam_inventory_service();

		// Get all non-archived ad units, unless ids are specified.
		$statement_builder = new StatementBuilder();
		if ( empty( $ids ) ) {
			$statement_builder = $statement_builder->where( "Status IN('ACTIVE')" );
		} else {
			$statement_builder = $statement_builder->where( 'ID IN(' . implode( ', ', $ids ) . ')' );
		}
		$statement_builder->orderBy( 'name ASC' )->limit( StatementBuilder::SUGGESTED_PAGE_LIMIT );
		return $statement_builder;
	}


	/**
	 * Get all GAM Advertisers in the user's network.
	 *
	 * @return Company[] Array of Companies of typer Advertiser.
	 */
	private static function get_advertisers() {
		$line_items            = [];
		$service               = self::get_company_service();
		$page_size             = StatementBuilder::SUGGESTED_PAGE_LIMIT;
		$statement_builder     = ( new StatementBuilder() )->orderBy( 'id ASC' )->limit( $page_size )->withBindVariableValue( 'type', CompanyType::ADVERTISER );
		$total_result_set_size = 0;
		do {
			$page = $service->getCompaniesByStatement( $statement_builder->toStatement() );
			if ( $page->getResults() !== null ) {
				$total_result_set_size = $page->getTotalResultSetSize();
				foreach ( $page->getResults() as $company ) {
					$line_items[] = $company;
				}
			}
			$statement_builder->increaseOffsetBy( $page_size );
		} while ( $statement_builder->getOffset() < $total_result_set_size );
		return $line_items;
	}

	/**
	 * Get all Advertisers in the user's network, serialised.
	 *
	 * @param Company[] $companies Optional array of companies to serialise. If empty, return all advertisers.
	 *
	 * @return array[] Array of serialised companies.
	 */
	public static function get_serialised_advertisers( $companies = null ) {
		return array_map(
			function( $item ) {
				return [
					'id'   => $item->getId(),
					'name' => $item->getName(),
				];
			},
			null !== $companies ? $companies : self::get_advertisers()
		);
	}

	/**
	 * Get details of the authorised GAM user.
	 *
	 * @return object Details of the user.
	 */
	public static function get_gam_settings() {
		try {
			$service_factory = new ServiceFactory();
			$session         = self::get_gam_session();
			return [
				'user_email'   => $service_factory->createUserService( $session )->getCurrentUser()->getEmail(),
				'network_code' => self::get_gam_network_code(),
			];
		} catch ( \Exception $e ) {
			return [];
		}
	}

	/**
	 * Get all GAM orders in the user's network.
	 *
	 * @param StatementBuilder $statement_builder (optional) Statement builder.
	 *
	 * @return Order[] Array of Orders.
	 */
	private static function get_orders( StatementBuilder $statement_builder = null ) {
		$orders                = [];
		$order_service         = self::get_order_service();
		$page_size             = StatementBuilder::SUGGESTED_PAGE_LIMIT;
		$total_result_set_size = 0;
		$statement_builder     = $statement_builder ?? new StatementBuilder();
		$statement_builder->orderBy( 'name ASC' )->limit( $page_size );
		if ( ! empty( $ids ) ) {
			$statement_builder = $statement_builder->where( 'ID IN(' . implode( ', ', $ids ) . ')' );
		}
		do {
			$page = $order_service->getOrdersByStatement( $statement_builder->toStatement() );
			if ( $page->getResults() !== null ) {
				$total_result_set_size = $page->getTotalResultSetSize();
				foreach ( $page->getResults() as $order ) {
					$orders[] = $order;
				}
			}
			$statement_builder->increaseOffsetBy( $page_size );
		} while ( $statement_builder->getOffset() < $total_result_set_size );
		return $orders;
	}

	/**
	 * Get orders by a list of IDs.
	 *
	 * @param int[] $ids Array of order IDs.
	 *
	 * @return Order[] Array of Orders.
	 */
	public static function get_orders_by_id( $ids = [] ) {
		if ( ! is_array( $ids ) ) {
			$ids = [ $ids ];
		}
		$statement_builder = ( new StatementBuilder() )->where( 'ID IN(' . implode( ', ', $ids ) . ')' );
		return self::get_orders( $statement_builder );
	}

	/**
	 * Get orders by advertiser ID.
	 *
	 * @param int $advertiser_id Advertiser ID.
	 *
	 * @return Order[] Array of Orders.
	 */
	public static function get_orders_by_advertiser( $advertiser_id ) {
		$statement_builder = ( new StatementBuilder() )->where( sprintf( 'advertiserId = %d', $advertiser_id ) );
		return self::get_orders( $statement_builder );
	}

	/**
	 * Get all orders in the user's network, serialised.
	 *
	 * @param Order[] $orders (optional) Array of Orders.
	 *
	 * @return object[] Array of serialised orders.
	 */
	public static function get_serialised_orders( $orders = null ) {
		return array_map(
			function( $order ) {
				return [
					'id'            => $order->getId(),
					'name'          => $order->getName(),
					'status'        => $order->getStatus(),
					'is_archived'   => $order->getIsArchived(),
					'advertiser_id' => $order->getAdvertiserId(),
				];
			},
			null !== $orders ? $orders : self::get_orders()
		);
	}

	/**
	 * Get creatives from an optional initialized statement builder.
	 *
	 * @param StatementBuilder $statement_builder (optional) Statement builder.
	 *
	 * @return Creative[] Array of creatives.
	 */
	private static function get_creatives( StatementBuilder $statement_builder = null ) {
		$creatives             = [];
		$creative_service      = self::get_creative_service();
		$page_size             = StatementBuilder::SUGGESTED_PAGE_LIMIT;
		$total_result_set_size = 0;
		$statement_builder     = $statement_builder ?? new StatementBuilder();
		$statement_builder->orderBy( 'id ASC' )->limit( $page_size );
		do {
			$page = $creative_service->getCreativesByStatement( $statement_builder->toStatement() );
			if ( $page->getResults() !== null ) {
				$total_result_set_size = $page->getTotalResultSetSize();
				foreach ( $page->getResults() as $creative ) {
					$creatives[] = $creative;
				}
			}
			$statement_builder->increaseOffsetBy( $page_size );
		} while ( $statement_builder->getOffset() < $total_result_set_size );
		return $creatives;
	}

	/**
	 * Get creatives from an advertiser.
	 *
	 * @param int $advertiser_id Advertiser ID.
	 *
	 * @return Creative[] Array of creatives.
	 */
	private static function get_creatives_by_advertiser( $advertiser_id ) {
		$statement_builder = ( new StatementBuilder() )->where( sprintf( 'advertiserId = %d', $advertiser_id ) );
		return self::get_creatives( $statement_builder );
	}

	/**
	 * Get all creatives in the user's network, serialised.
	 *
	 * @param Creative[] $creatives (optional) Array of Creatives.
	 *
	 * @return array[] Array of serialised creatives.
	 */
	public static function get_serialised_creatives( $creatives = null ) {
		return array_map(
			function( $creatives ) {
				return [
					'id'           => $creatives->getId(),
					'name'         => $creatives->getName(),
					'advertiserId' => $creatives->getAdvertiserId(),
				];
			},
			null !== $creatives ? $creatives : self::get_creatives()
		);
	}

	/**
	 * Get creatives from an advertiser, serialised.
	 *
	 * @param int $advertiser_id Advertiser ID.
	 *
	 * @return array[] Array of serialised creatives.
	 */
	public static function get_serialised_creatives_by_advertiser( $advertiser_id ) {
		return self::get_serialised_creatives( self::get_creatives_by_advertiser( $advertiser_id ) );
	}

	/**
	 * Get all GAM Line Items in the user's network.
	 *
	 * @param StatementBuilder $statement_builder (optional) Statement builder.
	 *
	 * @return LineItem[] Array of Orders.
	 */
	private static function get_line_items( StatementBuilder $statement_builder = null ) {
		$line_items        = [];
		$service           = self::get_line_item_service();
		$page_size         = StatementBuilder::SUGGESTED_PAGE_LIMIT;
		$statement_builder = $statement_builder ?? new StatementBuilder();
		$statement_builder->orderBy( 'id ASC' )->limit( $page_size );
		$total_result_set_size = 0;
		do {
			$page = $service->getLineItemsByStatement( $statement_builder->toStatement() );
			if ( $page->getResults() !== null ) {
				$total_result_set_size = $page->getTotalResultSetSize();
				foreach ( $page->getResults() as $line_item ) {
					$line_items[] = $line_item;
				}
			}
			$statement_builder->increaseOffsetBy( $page_size );
		} while ( $statement_builder->getOffset() < $total_result_set_size );
		return $line_items;
	}

	/**
	 * Get all line items given an order ID.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return LineItems[] Array of LineItems.
	 */
	public static function get_line_items_by_order_id( $order_id ) {
		$statement_builder = ( new StatementBuilder() )->where( sprintf( 'orderId = %d', $order_id ) );
		return self::get_line_items( $statement_builder );
	}

	/**
	 * Get all Line Items in the user's network, serialised.
	 *
	 * @param LineItem[] $line_items (optional) Array of line items.
	 *
	 * @return object[] Array of serialised orders.
	 */
	public static function get_serialised_line_items( $line_items = null ) {
		return array_map(
			function( $item ) {
				return [
					'id'          => $item->getId(),
					'orderId'     => $item->getOrderId(),
					'name'        => $item->getName(),
					'status'      => $item->getStatus(),
					'is_archived' => $item->getIsArchived(),
					'type'        => $item->getLineItemType(),
				];
			},
			null !== $line_items ? $line_items : self::get_line_items()
		);
	}

	/**
	 * Get all GAM Ad Units in the user's network.
	 * If $ids parameter is not specified, will return all ad units found.
	 *
	 * @param int[] $ids Optional array of ad unit ids.
	 * @return AdUnit[] Array of AdUnits.
	 */
	private static function get_gam_ad_units( $ids = [] ) {
		$gam_ad_units      = [];
		$statement_builder = self::get_serialised_gam_ad_units_statement_builder( $ids );
		$inventory_service = self::get_gam_inventory_service();

		// Retrieve a small amount of items at a time, paging through until all items have been retrieved.
		$total_result_set_size = 0;
		do {
			$page = $inventory_service->getAdUnitsByStatement(
				$statement_builder->toStatement()
			);

			if ( $page->getResults() !== null ) {
				$total_result_set_size = $page->getTotalResultSetSize();
				foreach ( $page->getResults() as $item ) {
					$ad_unit_name = $item->getName();
					if ( 0 === strpos( $ad_unit_name, 'ca-pub-' ) ) {
						// There are these phantom ad units with 'ca-pub-<int>' names.
						continue;
					}
					$gam_ad_units[] = $item;
				}
			}
			$statement_builder->increaseOffsetBy( StatementBuilder::SUGGESTED_PAGE_LIMIT );
		} while ( $statement_builder->getOffset() < $total_result_set_size );

		return $gam_ad_units;
	}

	/**
	 * Get all GAM Ad Units in the user's network, serialised.
	 *
	 * @param int[] $ids Optional array of ad unit ids.
	 * @return object[] Array of serialised ad units.
	 */
	public static function get_serialised_gam_ad_units( $ids = [] ) {
		try {
			$ad_units            = self::get_gam_ad_units( $ids );
			$ad_units_serialised = [];
			foreach ( $ad_units as $ad_unit ) {
				$ad_units_serialised[] = self::serialize_ad_unit( $ad_unit );
			}
			return $ad_units_serialised;
		} catch ( \Exception $e ) {
			$error_message = $e->getMessage();
			if ( 0 <= strpos( $error_message, 'NETWORK_API_ACCESS_DISABLED' ) ) {
				$network_code  = self::get_gam_network_code();
				$settings_link = "https://admanager.google.com/${network_code}#admin/settings/network";
				$error_message = __( 'API access for this GAM account is disabled.', 'newspack-ads' ) .
				" <a href=\"${settings_link}\">" . __( 'Enable API access in your GAM settings.', 'newspack' ) . '</a>';
			}
			return new \WP_Error(
				'newspack_ads_gam_get_ad_units',
				$error_message,
				array(
					'status' => '400',
					'level'  => 'warning',
				)
			);
		}
	}

	/**
	 * Serialize Ad Unit.
	 *
	 * @param AdUnit $gam_ad_unit An AdUnit.
	 * @return object Ad Unit configuration.
	 */
	private static function serialize_ad_unit( $gam_ad_unit ) {
		$ad_unit = [
			'id'     => $gam_ad_unit->getId(),
			'code'   => $gam_ad_unit->getAdUnitCode(),
			'status' => $gam_ad_unit->getStatus(),
			'name'   => $gam_ad_unit->getName(),
			'fluid'  => $gam_ad_unit->getIsFluid(),
			'sizes'  => [],
		];
		$sizes   = $gam_ad_unit->getAdUnitSizes();
		if ( $sizes ) {
			foreach ( $sizes as $size ) {
				$size               = $size->getSize();
				$ad_unit['sizes'][] = [ $size->getWidth(), $size->getHeight() ];
			}
		}
		return $ad_unit;
	}

	/**
	 * Create an advertiser in GAM.
	 *
	 * @param string $name The advertiser name.
	 *
	 * @return array The created serialized advertiser.
	 *
	 * @throws \Exception In case of error in googleads lib.
	 */
	public static function create_advertiser( $name ) {
		$advertiser = new Company();
		$advertiser->setName( $name );
		$advertiser->setType( CompanyType::ADVERTISER );
		$service = self::get_company_service();
		$results = $service->createCompanies( [ $advertiser ] );
		return self::get_serialised_advertisers( $results )[0];
	}

	/**
	 * Create a GAM Order.
	 *
	 * @param string $name          Order Name.
	 * @param string $advertiser_id Order Advertiser ID.
	 *
	 * @return array|WP_Error Serialised created order or error if it fails.
	 */
	public static function create_order( $name, $advertiser_id ) {
		$order = new Order();
		$order->setName( $name );
		$order->setAdvertiserId( $advertiser_id );
		$order->setTraffickerId( self::get_current_user()->getId() );
		try {
			$service        = self::get_order_service();
			$created_orders = $service->createOrders( [ $order ] );
		} catch ( ApiException $e ) {
			return self::get_api_error( $e, __( 'Order was not created due to an unexpected error.', 'newspack-ads' ) );
		}
		return self::get_serialised_orders( $created_orders )[0];
	}

	/**
	 * Create a GAM Creative.
	 *
	 * @param array[] $creatives_config Array of creative configurations.
	 *
	 * @return object Created creative.
	 *
	 * @throws \Exception If unable to create creatives.
	 */
	public static function create_creatives( $creatives_config = [] ) {
		$creatives = [];
		$xsi_types = [
			'BaseDynamicAllocationCreative',
			'BaseRichMediaStudioCreative',
			'ClickTrackingCreative',
			'HasDestinationUrlCreative',
			'Html5Creative',
			'InternalRedirectCreative',
			'LegacyDfpCreative',
			'ProgrammaticCreative',
			'TemplateCreative',
			'ThirdPartyCreative',
			'UnsupportedCreative',
			'VastRedirectCreative',
		];
		foreach ( $creatives_config as $creative_config ) {
			$creative_config = wp_parse_args(
				$creative_config,
				[
					'xsi_type' => 'Creative',
				]
			);
			if ( ! in_array( $creative_config['xsi_type'], $xsi_types, true ) ) {
				throw new \Exception( 'Invalid xsi type' );
			}
			$fully_qualified_creative_class = 'Google\\AdsApi\\AdManager\\' . self::GAM_API_VERSION . '\\' . $creative_config['xsi_type'];
			$creative                       = new $fully_qualified_creative_class();
			$creative->setName( $creative_config['name'] );
			$creative->setAdvertiserId( $creative_config['advertiser_id'] );
			$creative->setSize( new Size( $creative_config['width'], $creative_config['height'] ) );
			switch ( $creative_config['xsi_type'] ) {
				case 'ThirdPartyCreative':
					$creative_config = wp_parse_args(
						$creative_config,
						[
							'snippet'                  => '',
							'is_safe_frame_compatible' => true,
						]
					);
					$creative->setSnippet( $creative_config['snippet'] );
					$creative->setIsSafeFrameCompatible( $creative_config['is_safe_frame_compatible'] );
					break;
			}
			$creatives[] = $creative;
		}
		$service           = self::get_creative_service();
		$created_creatives = $service->createCreatives( $creatives );
		return self::get_serialised_creatives( $created_creatives );
	}

	/**
	 * Create or update line items.
	 *
	 * If the line item config contains the `id` property, it will attempt to
	 * update. Otherwise it will create.
	 *
	 * @param array[] $line_item_configs List of line item configurations.
	 *
	 * @return LineItem[] Created and/or updated line items.
	 *
	 * @throws \Exception If unsupported configuration or unable to create line items.
	 */
	public static function create_or_update_line_items( $line_item_configs = [] ) {
		$network              = self::get_gam_network();
		$line_items_to_create = [];
		$line_items_to_update = [];
		foreach ( $line_item_configs as $config ) {
			$config    = wp_parse_args(
				$config,
				[
					'start_date_time_type'   => 'IMMEDIATELY',
					'line_item_type'         => 'PRICE_PRIORITY',
					'cost_type'              => 'CPM',
					'creative_rotation_type' => 'EVEN',
					'primary_goal'           => [
						'goal_type' => 'NONE',
					],
				]
			);
			$line_item = new LineItem();
			$line_item->setOrderId( $config['order_id'] );
			$line_item->setName( $config['name'] );
			$line_item->setLineItemType( $config['line_item_type'] );
			$line_item->setCreativeRotationType( $config['creative_rotation_type'] );
			$line_item->setPrimaryGoal( new Goal( $config['primary_goal']['goal_type'] ) );

			// Creative placeholders (or expected creatives).
			if ( isset( $config['creative_placeholders'] ) && ! empty( $config['creative_placeholders'] ) ) {
				$creative_placeholders = array_map(
					function ( $size ) {
						return new CreativePlaceholder( new Size( $size[0], $size[1] ) );
					},
					$config['creative_placeholders']
				);
				$line_item->setCreativePlaceholders( $creative_placeholders );
			}

			// Date and time options.
			$line_item->setStartDateTimeType( $config['start_date_time_type'] );
			if ( isset( $config['unlimited_end_date_time'] ) ) {
				$line_item->setUnlimitedEndDateTime( (bool) $config['unlimited_end_date_time'] );
			}

			// Cost options.
			$line_item->setCostType( $config['cost_type'] );
			if ( isset( $config['cost_per_unit'] ) ) {
				if ( ! isset( $config['cost_per_unit']['currency_code'] ) ) {
					$config['cost_per_unit']['currency_code'] = $network->getCurrencyCode();
				}
				$cost_per_unit = new Money( $config['cost_per_unit']['currency_code'], $config['cost_per_unit']['micro_amount'] );
				$line_item->setCostPerUnit( $cost_per_unit );
				if ( isset( $config['cost_per_unit']['micro_amount_value'] ) ) {
					$value_cost_per_unit = new Money( $config['cost_per_unit']['currency_code'], $config['cost_per_unit']['micro_amount_value'] );
					$line_item->setValueCostPerUnit( $value_cost_per_unit );
				}
			}

			// Targeting options.
			if ( isset( $config['targeting'] ) ) {
				$targeting = new Targeting();

				// Obligatory inventory targeting.
				// Default is "Run of network", which is targeted to the network's root ad unit including descendants.
				$inventory_targeting = new InventoryTargeting();
				if ( ! isset( $config['targeting']['inventory_targeting'] ) ) {
					$inventory_targeting->setTargetedAdUnits( [ new AdUnitTargeting( $network->getEffectiveRootAdUnitId(), true ) ] );
				} else {
					throw new \Exception( 'Inventory targeting is not supported yet' );
				}
				$targeting->setInventoryTargeting( $inventory_targeting );

				// Custom targeting.
				if ( isset( $config['targeting']['custom_targeting'] ) ) {
					$criteria_set = new CustomCriteriaSet( 'AND' );
					$children     = [];
					foreach ( $config['targeting']['custom_targeting'] as $key_id => $value_ids ) {
						$children[] = new CustomCriteria( $key_id, $value_ids, 'IS' );
					}
					$criteria_set->setChildren( $children );
					$targeting->setCustomTargeting( $criteria_set );
				}

				// Apply configured targeting to line item.
				$line_item->setTargeting( $targeting );
			}

			if ( isset( $config['id'] ) ) {
				$line_item->setId( $config['id'] );
				$line_items_to_update[] = $line_item;
			} else {
				$line_items_to_create[] = $line_item;
			}
		}
		$service            = self::get_line_item_service();
		$created_line_items = ! empty( $line_items_to_create ) ? $service->createLineItems( $line_items_to_create ) : [];
		$updated_line_items = ! empty( $line_items_to_update ) ? $service->updateLineItems( $line_items_to_update ) : [];
		return array_merge( $created_line_items, $updated_line_items );
	}

	/**
	 * Create line item to creative associations (LICAs).
	 *
	 * @param array[] $lica_configs LICA configurations.
	 *
	 * @return LineItemCreativeAssociation[] Created LICAs.
	 */
	public static function associate_creatives_to_line_items( $lica_configs ) {
		$licas = [];
		foreach ( $lica_configs as $lica_config ) {
			$lica = new LineItemCreativeAssociation();
			$lica->setLineItemId( (int) $lica_config['line_item_id'] );
			$lica->setCreativeId( (int) $lica_config['creative_id'] );
			if ( isset( $lica_config['sizes'] ) && ! empty( $lica_config['sizes'] ) ) {
				$lica->setSizes(
					array_map(
						function ( $size ) {
							return new Size( $size[0], $size[1] );
						},
						$lica_config['sizes']
					)
				);
			}
			$licas[] = $lica;
		}
		$session        = self::get_gam_session();
		$service        = ( new ServiceFactory() )->createLineItemCreativeAssociationService( $session );
		$attempt_create = true;
		while ( $attempt_create ) {
			try {
				$result         = $service->createLineItemCreativeAssociations( array_values( $licas ) );
				$attempt_create = false;
			} catch ( ApiException $e ) {
				foreach ( $e->getErrors() as $error ) {
					if ( 'CommonError.ALREADY_EXISTS' === $error->getErrorString() ) {
						// Attempt to create again without duplicate associations.
						$index = $error->getFieldPathElements()[0]->getIndex();
						unset( $licas[ $index ] );
					} else {
						// Bail if there are other errors.
						return self::get_api_error( $e, __( 'Unexpected error while creating creative associations.', 'newspack-ads' ) );
					}
				}
				// Leave without errors if entire batch exists.
				if ( 0 === count( $licas ) ) {
					$attempt_create = false;
					return [];
				}
			}
		}
		return $result;
	}

	/**
	 * Archive a GAM Order.
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return UpdateResult
	 */
	public static function archive_order( $order_id ) {
		return self::get_order_service()->performOrderAction(
			new ArchiveOrders(),
			( new StatementBuilder() )->where( 'id = :id' )
				->orderBy( 'id ASC' )
				->limit( StatementBuilder::SUGGESTED_PAGE_LIMIT )
				->withBindVariableValue( 'id', $order_id )
				->toStatement()
		);
	}

	/**
	 * Modify a GAM Ad Unit.
	 * Given a configuration object and an AdUnit instance, return modified AdUnit.
	 * If the AdUnit is not provided, create a new one.
	 *
	 * @param object $ad_unit_config Configuration for an Ad Unit.
	 * @param AdUnit $ad_unit Ad Unit.
	 * @return AdUnit Ad Unit.
	 */
	private static function modify_ad_unit( $ad_unit_config, $ad_unit = null ) {
		$name     = $ad_unit_config['name'];
		$sizes    = $ad_unit_config['sizes'];
		$is_fluid = isset( $ad_unit_config['fluid'] ) && $ad_unit_config['fluid'];
		$slug     = substr( sanitize_title( $name ), 0, 80 ); // Ad unit code can have 100 characters at most.

		if ( null === $ad_unit ) {
			$ad_unit = new AdUnit();
			$ad_unit->setAdUnitCode( uniqid( $slug . '-' ) );
			$network = self::get_gam_network();
			$ad_unit->setParentId( $network->getEffectiveRootAdUnitId() );
			$ad_unit->setTargetWindow( AdUnitTargetWindow::BLANK );
		}

		$ad_unit->setName( $name );
		$ad_unit->setIsFluid( $is_fluid );

		$ad_unit_sizes = [];
		foreach ( $sizes as $size_spec ) {
			$size = new Size();
			$size->setWidth( $size_spec[0] );
			$size->setHeight( $size_spec[1] );
			$size->setIsAspectRatio( false );
			$ad_unit_size = new AdUnitSize();
			$ad_unit_size->setSize( $size );
			$ad_unit_size->setEnvironmentType( EnvironmentType::BROWSER );
			$ad_unit_sizes[] = $ad_unit_size;
		}
		$ad_unit->setAdUnitSizes( $ad_unit_sizes );

		if ( isset( $ad_unit_config['status'] ) ) {
			$status          = $ad_unit_config['status'];
			$existing_status = $ad_unit->getStatus();
			if ( $existing_status !== $status ) {
				self::change_ad_unit_status( $ad_unit->getId(), $status );
			}
		}

		return $ad_unit;
	}

	/**
	 * Update Ad Unit.
	 *
	 * @param object $ad_unit_config Ad Unit configuration.
	 * @return AdUnit|WP_Error Updated AdUnit or error.
	 */
	public static function update_ad_unit( $ad_unit_config ) {
		try {
			$inventory_service = self::get_gam_inventory_service();
			$found_ad_units    = self::get_gam_ad_units( [ $ad_unit_config['id'] ] );
			if ( empty( $found_ad_units ) ) {
				return self::get_api_error( null, __( 'Ad Unit was not found.', 'newspack-ads' ) );
			}
			$result = $inventory_service->updateAdUnits(
				[ self::modify_ad_unit( $ad_unit_config, $found_ad_units[0] ) ]
			);
			if ( empty( $result ) ) {
				return self::get_api_error( null, __( 'Ad Unit was not updated.', 'newspack-ads' ) );
			}
			return $result[0];
		} catch ( ApiException $e ) {
			return self::get_api_error( $e, __( 'Ad Unit was not updated.', 'newspack-ads' ) );
		}
	}

	/**
	 * Create a GAM Ad Unit.
	 *
	 * @param object $ad_unit_config Configuration of the ad unit.
	 * @return AdUnit|WP_Error Created AdUnit or error.
	 */
	public static function create_ad_unit( $ad_unit_config ) {
		try {
			$network           = self::get_gam_network();
			$inventory_service = self::get_gam_inventory_service();
			$ad_unit           = self::modify_ad_unit( $ad_unit_config );
			$created_ad_units  = $inventory_service->createAdUnits( [ $ad_unit ] );
			if ( empty( $created_ad_units ) ) {
				return self::get_api_error( null, __( 'Ad Unit was not created.', 'newspack-ads' ) );
			}
			return self::serialize_ad_unit( $created_ad_units[0] );
		} catch ( ApiException $e ) {
			return self::get_api_error( $e, __( 'Ad Unit was not created.', 'newspack-ads' ) );
		}
	}

	/**
	 * Change status of a single GAM Ad Unit.
	 *
	 * @param int    $id Id of the ad unit to archive.
	 * @param string $status Desired status of the ad unit.
	 */
	public static function change_ad_unit_status( $id, $status ) {
		try {
			switch ( $status ) {
				case 'ACTIVE':
					$action = new ActivateAdUnitsAction();
					break;
				case 'INACTIVE':
					$action = new DeactivateAdUnitsAction();
					break;
				case 'ARCHIVE':
					$action = new ArchiveAdUnitsAction();
					break;
				default:
					return false;
			}
			$inventory_service = self::get_gam_inventory_service();

			$statement_builder = self::get_serialised_gam_ad_units_statement_builder( [ $id ] );
			$result            = $inventory_service->performAdUnitAction(
				$action,
				$statement_builder->toStatement()
			);
			if ( null !== $result && $result->getNumChanges() > 0 ) {
				return true;
			} else {
				return false;
			}
		} catch ( \Exception $e ) {
			return false;
		}
	}

	/**
	 * Create a custom targeting key-val segmentation with optional sample values.
	 *
	 * @param string   $name   The name of the key.
	 * @param string[] $values Optional sample values.
	 *
	 * @return array[
	 *  'targeting_key'  => CustomTargetingKey,
	 *  'found_values'   => CustomTargetingValue[],
	 *  'created_values' => CustomTargetingValue[]
	 * ]
	 *
	 * @throws \Exception If there is an error while communicating with the API.
	 */
	public static function create_targeting_key( $name, $values = [] ) {
		$session = self::get_gam_session();
		$service = ( new ServiceFactory() )->createCustomTargetingService( $session );

		$statement = new Statement(
			"WHERE name = :name AND status = 'ACTIVE'",
			[
				new String_ValueMapEntry(
					'name',
					new SetValue(
						[
							new TextValue( $name ),
						]
					)
				),
			]
		);

		$targeting_key = null;
		$found_keys    = $service->getCustomTargetingKeysByStatement( $statement )->getResults();
		if ( empty( $found_keys ) ) {
			$targeting_key = $service->createCustomTargetingKeys(
				[
					( new CustomTargetingKey() )->setName( $name )->setType( 'FREEFORM' )->setStatus( 'ACTIVE' ),
				]
			)[0];
		} else {
			$targeting_key = $found_keys[0];
		}

		$found_values   = [];
		$created_values = [];
		if ( $targeting_key && count( $values ) ) {
			$key_id           = $targeting_key->getId();
			$values_statement = new Statement(
				"WHERE customTargetingKeyId = :key_id AND name = :name AND status = 'ACTIVE'",
				[
					new String_ValueMapEntry(
						'key_id',
						new SetValue(
							[
								new TextValue( $key_id ),
							]
						)
					),
					new String_ValueMapEntry(
						'name',
						new SetValue(
							array_map(
								function ( $key ) {
									return new TextValue( $key );
								},
								$values
							)
						)
					),
				]
			);
			$found_values     = (array) $service->getCustomTargetingValuesByStatement( $values_statement )->getResults();
			$values_to_create = array_values(
				array_diff(
					$values,
					array_map(
						function ( $value ) {
							return $value->getName();
						},
						$found_values
					)
				)
			);
			$created_values   = $service->createCustomTargetingValues(
				array_map(
					function ( $value ) use ( $key_id ) {
						return ( new CustomTargetingValue() )->setCustomTargetingKeyId( $key_id )->setName( $value );
					},
					$values_to_create
				)
			);
		}
		return [
			'targeting_key'  => $targeting_key,
			'found_values'   => is_array( $found_values ) && count( $found_values ) ? $found_values : [],
			'created_values' => is_array( $created_values ) && count( $created_values ) ? $created_values : [],
		];
	}

	/**
	 * Update custom targeting keys with predefined values if necessary.
	 *
	 * @return string[] Created custom targeting keys names or empty array if none was created.
	 *
	 * @throws \Exception If there is an error while communicating with the API.
	 */
	public static function update_custom_targeting_keys() {
		$session = self::get_gam_session();
		$service = ( new ServiceFactory() )->createCustomTargetingService( $session );

		// Find existing keys.
		$or_clauses = implode(
			' OR ',
			array_map(
				function( $key ) {
					return sprintf( "name LIKE '%s'", strtolower( $key ) );
				},
				self::$custom_targeting_keys
			)
		);
		$statement  = new Statement( sprintf( "WHERE ( %s ) AND status = 'ACTIVE'", $or_clauses ) );
		try {
			$keys = $service->getCustomTargetingKeysByStatement( $statement );
		} catch ( \Exception $e ) {
			$error_message = $e->getMessage();
			if ( false !== strpos( $error_message, 'NETWORK_API_ACCESS_DISABLED' ) ) {
				throw new \Exception( __( 'API access for this GAM account is disabled.', 'newspack-ads' ) );
			} else {
				throw new \Exception( __( 'Unable to find existing targeting keys.', 'newspack-ads' ) );
			}
		}

		$keys_to_create = array_values(
			array_diff(
				self::$custom_targeting_keys,
				array_map(
					function ( $key ) {
						return strtolower( $key->getName() );
					},
					(array) $keys->getResults()
				)
			)
		);

		// Create custom targeting keys.
		if ( ! empty( $keys_to_create ) ) {
			try {
				$created_keys = $service->createCustomTargetingKeys(
					array_map(
						function ( $key ) {
								return ( new CustomTargetingKey() )->setName( $key )->setType( 'FREEFORM' );
						},
						$keys_to_create
					)
				);
			} catch ( \Exception $e ) {
				throw new \Exception( __( 'Unable to create custom targeting keys', 'newspack-ads' ) );
			}
			return array_map(
				function( $key ) {
					return $key->getName();
				},
				$created_keys
			);
		}
		return [];
	}

	/**
	 * Verify WP environment to make sure it's safe to use GAM.
	 *
	 * @return bool Whether it's safe to use GAM.
	 */
	private static function is_environment_compatible() {
		// Constant Contact Form plugin loads an old version of Guzzle that breaks the SDK.
		if ( class_exists( 'Constant_Contact' ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Get saved Service Account credentials config.
	 */
	private static function service_account_credentials_config() {
		return get_option( self::SERVICE_ACCOUNT_CREDENTIALS_OPTION_NAME, false );
	}

	/**
	 * How does this instance connect to GAM?
	 */
	private static function get_connection_details() {
		$credentials = self::get_service_account_credentials();
		if ( false !== $credentials ) {
			return [
				'credentials' => $credentials,
				'mode'        => 'service_account',
			];
		}
		$credentials = self::get_google_oauth2_credentials();
		if ( false !== $credentials ) {
			return [
				'credentials' => $credentials,
				'mode'        => 'oauth',
			];
		}
		return [
			'credentials' => null,
			'mode'        => 'legacy', // Manual connection.
		];
	}

	/**
	 * Get GAM connection status.
	 *
	 * @return object Object with status information.
	 */
	public static function connection_status() {
		$connection_details = self::get_connection_details();
		$response           = [
			'connected'       => false,
			'connection_mode' => $connection_details['mode'],
		];
		if ( false === self::is_environment_compatible() ) {
			$response['incompatible'] = true;
			$response['error']        = __( 'Cannot connect to Google Ad Manager. This WordPress instance is not compatible with this feature.', 'newspack-ads' );
			return $response;
		}
		try {
			$response['network_code'] = self::get_gam_network_code();
			$response['connected']    = true;
		} catch ( \Exception $e ) {
			$response['error'] = $e->getMessage();
		}
		return $response;
	}

	/**
	 * Update GAM credentials.
	 *
	 * @param array $credentials_config Credentials to update.
	 *
	 * @return object Object with status information.
	 */
	public static function update_gam_credentials( $credentials_config ) {
		try {
			self::get_service_account_credentials( $credentials_config );
		} catch ( \Exception $e ) {
			return new \WP_Error( 'newspack_ads_gam_credentials', $e->getMessage() );
		}
		$update_result = update_option( self::SERVICE_ACCOUNT_CREDENTIALS_OPTION_NAME, $credentials_config );
		if ( ! $update_result ) {
			return new \WP_Error( 'newspack_ads_gam_credentials', __( 'Unable to update GAM credentials', 'newspack-ads' ) );
		}
		return GAM_Model::get_gam_connection_status();
	}

	/**
	 * Clear existing GAM credentials.
	 *
	 * @return object Object with status information.
	 */
	public static function remove_gam_credentials() {
		$deleted_credentials_result  = delete_option( self::SERVICE_ACCOUNT_CREDENTIALS_OPTION_NAME );
		$deleted_network_code_result = delete_option( GAM_Model::OPTION_NAME_GAM_NETWORK_CODE );
		if ( ! $deleted_credentials_result || ! $deleted_network_code_result ) {
			return new \WP_Error( 'newspack_ads_gam_credentials', __( 'Unable to remove GAM credentials', 'newspack-ads' ) );
		}
		return GAM_Model::get_gam_connection_status();
	}
}
