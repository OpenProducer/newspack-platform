<?php

namespace Google\AdsApi\AdManager\v202008;


/**
 * This file was generated from WSDL. DO NOT EDIT.
 */
class SiteService extends \Google\AdsApi\Common\AdsSoapClient
{

    /**
     * @var array $classmap The defined classes
     */
    private static $classmap = array (
      'ObjectValue' => 'Google\\AdsApi\\AdManager\\v202008\\ObjectValue',
      'ApiError' => 'Google\\AdsApi\\AdManager\\v202008\\ApiError',
      'ApiException' => 'Google\\AdsApi\\AdManager\\v202008\\ApiException',
      'ApiVersionError' => 'Google\\AdsApi\\AdManager\\v202008\\ApiVersionError',
      'ApplicationException' => 'Google\\AdsApi\\AdManager\\v202008\\ApplicationException',
      'AuthenticationError' => 'Google\\AdsApi\\AdManager\\v202008\\AuthenticationError',
      'BooleanValue' => 'Google\\AdsApi\\AdManager\\v202008\\BooleanValue',
      'CollectionSizeError' => 'Google\\AdsApi\\AdManager\\v202008\\CollectionSizeError',
      'CommonError' => 'Google\\AdsApi\\AdManager\\v202008\\CommonError',
      'Date' => 'Google\\AdsApi\\AdManager\\v202008\\Date',
      'DateTime' => 'Google\\AdsApi\\AdManager\\v202008\\DateTime',
      'DateTimeValue' => 'Google\\AdsApi\\AdManager\\v202008\\DateTimeValue',
      'DateValue' => 'Google\\AdsApi\\AdManager\\v202008\\DateValue',
      'DeactivateSite' => 'Google\\AdsApi\\AdManager\\v202008\\DeactivateSite',
      'FeatureError' => 'Google\\AdsApi\\AdManager\\v202008\\FeatureError',
      'FieldPathElement' => 'Google\\AdsApi\\AdManager\\v202008\\FieldPathElement',
      'InternalApiError' => 'Google\\AdsApi\\AdManager\\v202008\\InternalApiError',
      'NotNullError' => 'Google\\AdsApi\\AdManager\\v202008\\NotNullError',
      'NumberValue' => 'Google\\AdsApi\\AdManager\\v202008\\NumberValue',
      'ParseError' => 'Google\\AdsApi\\AdManager\\v202008\\ParseError',
      'PermissionError' => 'Google\\AdsApi\\AdManager\\v202008\\PermissionError',
      'PublisherQueryLanguageContextError' => 'Google\\AdsApi\\AdManager\\v202008\\PublisherQueryLanguageContextError',
      'PublisherQueryLanguageSyntaxError' => 'Google\\AdsApi\\AdManager\\v202008\\PublisherQueryLanguageSyntaxError',
      'QuotaError' => 'Google\\AdsApi\\AdManager\\v202008\\QuotaError',
      'RequiredCollectionError' => 'Google\\AdsApi\\AdManager\\v202008\\RequiredCollectionError',
      'RequiredError' => 'Google\\AdsApi\\AdManager\\v202008\\RequiredError',
      'ServerError' => 'Google\\AdsApi\\AdManager\\v202008\\ServerError',
      'SetValue' => 'Google\\AdsApi\\AdManager\\v202008\\SetValue',
      'SiteAction' => 'Google\\AdsApi\\AdManager\\v202008\\SiteAction',
      'Site' => 'Google\\AdsApi\\AdManager\\v202008\\Site',
      'SiteError' => 'Google\\AdsApi\\AdManager\\v202008\\SiteError',
      'SitePage' => 'Google\\AdsApi\\AdManager\\v202008\\SitePage',
      'SoapRequestHeader' => 'Google\\AdsApi\\AdManager\\v202008\\SoapRequestHeader',
      'SoapResponseHeader' => 'Google\\AdsApi\\AdManager\\v202008\\SoapResponseHeader',
      'Statement' => 'Google\\AdsApi\\AdManager\\v202008\\Statement',
      'StatementError' => 'Google\\AdsApi\\AdManager\\v202008\\StatementError',
      'StringFormatError' => 'Google\\AdsApi\\AdManager\\v202008\\StringFormatError',
      'StringLengthError' => 'Google\\AdsApi\\AdManager\\v202008\\StringLengthError',
      'String_ValueMapEntry' => 'Google\\AdsApi\\AdManager\\v202008\\String_ValueMapEntry',
      'SubmitSiteForApproval' => 'Google\\AdsApi\\AdManager\\v202008\\SubmitSiteForApproval',
      'TextValue' => 'Google\\AdsApi\\AdManager\\v202008\\TextValue',
      'UpdateResult' => 'Google\\AdsApi\\AdManager\\v202008\\UpdateResult',
      'UrlError' => 'Google\\AdsApi\\AdManager\\v202008\\UrlError',
      'Value' => 'Google\\AdsApi\\AdManager\\v202008\\Value',
      'createSitesResponse' => 'Google\\AdsApi\\AdManager\\v202008\\createSitesResponse',
      'getSitesByStatementResponse' => 'Google\\AdsApi\\AdManager\\v202008\\getSitesByStatementResponse',
      'performSiteActionResponse' => 'Google\\AdsApi\\AdManager\\v202008\\performSiteActionResponse',
    );

    /**
     * @param array $options A array of config values
     * @param string $wsdl The wsdl file to use
     */
    public function __construct(array $options = array(),
                $wsdl = 'https://ads.google.com/apis/ads/publisher/v202008/SiteService?wsdl')
    {
      foreach (self::$classmap as $key => $value) {
        if (!isset($options['classmap'][$key])) {
          $options['classmap'][$key] = $value;
        }
      }
      $options = array_merge(array (
      'features' => 1,
    ), $options);
      parent::__construct($wsdl, $options);
    }

    /**
     * Creates new {@link Site} objects.
     *
     * @param \Google\AdsApi\AdManager\v202008\Site[] $sites
     * @return \Google\AdsApi\AdManager\v202008\Site[]
     * @throws \Google\AdsApi\AdManager\v202008\ApiException
     */
    public function createSites(array $sites)
    {
      return $this->__soapCall('createSites', array(array('sites' => $sites)))->getRval();
    }

    /**
     * Gets a {@link SitePage} of {@link Site} objects that satisfy the given {@link Statement#query}.
     * The following fields are supported for filtering:
     *
     * <table>
     * <tr>
     * <th scope="col">PQL Property</th> <th scope="col">Object Property</th>
     * </tr>
     * <tr>
     * <td>{@code id}</td>
     * <td>{@link Site#id}</td>
     * </tr>
     * <tr>
     * <td>{@code url}</td>
     * <td>{@link Site#url}</td>
     * </tr>
     * <tr>
     * <td>{@code childNetworkCode}</td>
     * <td>{@link Site#childNetworkCode}</td>
     * </tr>
     * <tr>
     * <td>{@code approvalStatus}</td>
     * <td>{@link Site#approvalStatus}</td>
     * </tr>
     * <tr>
     * <td>{@code active}</td>
     * <td>{@link Site#active}</td>
     * </tr>
     * </table>
     *
     * @param \Google\AdsApi\AdManager\v202008\Statement $filterStatement
     * @return \Google\AdsApi\AdManager\v202008\SitePage
     * @throws \Google\AdsApi\AdManager\v202008\ApiException
     */
    public function getSitesByStatement(\Google\AdsApi\AdManager\v202008\Statement $filterStatement)
    {
      return $this->__soapCall('getSitesByStatement', array(array('filterStatement' => $filterStatement)))->getRval();
    }

    /**
     * Performs actions on {@link Site} objects that match the given {@link Statement#query}.
     *
     * @param \Google\AdsApi\AdManager\v202008\SiteAction $siteAction
     * @param \Google\AdsApi\AdManager\v202008\Statement $filterStatement
     * @return \Google\AdsApi\AdManager\v202008\UpdateResult
     * @throws \Google\AdsApi\AdManager\v202008\ApiException
     */
    public function performSiteAction(\Google\AdsApi\AdManager\v202008\SiteAction $siteAction, \Google\AdsApi\AdManager\v202008\Statement $filterStatement)
    {
      return $this->__soapCall('performSiteAction', array(array('siteAction' => $siteAction, 'filterStatement' => $filterStatement)))->getRval();
    }

}
