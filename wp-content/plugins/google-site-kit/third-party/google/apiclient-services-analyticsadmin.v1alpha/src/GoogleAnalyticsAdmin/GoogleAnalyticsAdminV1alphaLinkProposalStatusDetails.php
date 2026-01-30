<?php

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
namespace Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha;

class GoogleAnalyticsAdminV1alphaLinkProposalStatusDetails extends \Google\Site_Kit_Dependencies\Google\Model
{
    /**
     * Unspecified product.
     */
    public const LINK_PROPOSAL_INITIATING_PRODUCT_LINK_PROPOSAL_INITIATING_PRODUCT_UNSPECIFIED = 'LINK_PROPOSAL_INITIATING_PRODUCT_UNSPECIFIED';
    /**
     * This proposal was created by a user from Google Analytics.
     */
    public const LINK_PROPOSAL_INITIATING_PRODUCT_GOOGLE_ANALYTICS = 'GOOGLE_ANALYTICS';
    /**
     * This proposal was created by a user from a linked product (not Google
     * Analytics).
     */
    public const LINK_PROPOSAL_INITIATING_PRODUCT_LINKED_PRODUCT = 'LINKED_PRODUCT';
    /**
     * Unspecified state
     */
    public const LINK_PROPOSAL_STATE_LINK_PROPOSAL_STATE_UNSPECIFIED = 'LINK_PROPOSAL_STATE_UNSPECIFIED';
    /**
     * This proposal is awaiting review from a Google Analytics user. This
     * proposal will automatically expire after some time.
     */
    public const LINK_PROPOSAL_STATE_AWAITING_REVIEW_FROM_GOOGLE_ANALYTICS = 'AWAITING_REVIEW_FROM_GOOGLE_ANALYTICS';
    /**
     * This proposal is awaiting review from a user of a linked product. This
     * proposal will automatically expire after some time.
     */
    public const LINK_PROPOSAL_STATE_AWAITING_REVIEW_FROM_LINKED_PRODUCT = 'AWAITING_REVIEW_FROM_LINKED_PRODUCT';
    /**
     * This proposal has been withdrawn by an admin on the initiating product.
     * This proposal will be automatically deleted after some time.
     */
    public const LINK_PROPOSAL_STATE_WITHDRAWN = 'WITHDRAWN';
    /**
     * This proposal has been declined by an admin on the receiving product. This
     * proposal will be automatically deleted after some time.
     */
    public const LINK_PROPOSAL_STATE_DECLINED = 'DECLINED';
    /**
     * This proposal expired due to lack of response from an admin on the
     * receiving product. This proposal will be automatically deleted after some
     * time.
     */
    public const LINK_PROPOSAL_STATE_EXPIRED = 'EXPIRED';
    /**
     * This proposal has become obsolete because a link was directly created to
     * the same external product resource that this proposal specifies. This
     * proposal will be automatically deleted after some time.
     */
    public const LINK_PROPOSAL_STATE_OBSOLETE = 'OBSOLETE';
    /**
     * Output only. The source of this proposal.
     *
     * @var string
     */
    public $linkProposalInitiatingProduct;
    /**
     * Output only. The state of this proposal.
     *
     * @var string
     */
    public $linkProposalState;
    /**
     * Output only. The email address of the user that proposed this linkage.
     *
     * @var string
     */
    public $requestorEmail;
    /**
     * Output only. The source of this proposal.
     *
     * Accepted values: LINK_PROPOSAL_INITIATING_PRODUCT_UNSPECIFIED,
     * GOOGLE_ANALYTICS, LINKED_PRODUCT
     *
     * @param self::LINK_PROPOSAL_INITIATING_PRODUCT_* $linkProposalInitiatingProduct
     */
    public function setLinkProposalInitiatingProduct($linkProposalInitiatingProduct)
    {
        $this->linkProposalInitiatingProduct = $linkProposalInitiatingProduct;
    }
    /**
     * @return self::LINK_PROPOSAL_INITIATING_PRODUCT_*
     */
    public function getLinkProposalInitiatingProduct()
    {
        return $this->linkProposalInitiatingProduct;
    }
    /**
     * Output only. The state of this proposal.
     *
     * Accepted values: LINK_PROPOSAL_STATE_UNSPECIFIED,
     * AWAITING_REVIEW_FROM_GOOGLE_ANALYTICS, AWAITING_REVIEW_FROM_LINKED_PRODUCT,
     * WITHDRAWN, DECLINED, EXPIRED, OBSOLETE
     *
     * @param self::LINK_PROPOSAL_STATE_* $linkProposalState
     */
    public function setLinkProposalState($linkProposalState)
    {
        $this->linkProposalState = $linkProposalState;
    }
    /**
     * @return self::LINK_PROPOSAL_STATE_*
     */
    public function getLinkProposalState()
    {
        return $this->linkProposalState;
    }
    /**
     * Output only. The email address of the user that proposed this linkage.
     *
     * @param string $requestorEmail
     */
    public function setRequestorEmail($requestorEmail)
    {
        $this->requestorEmail = $requestorEmail;
    }
    /**
     * @return string
     */
    public function getRequestorEmail()
    {
        return $this->requestorEmail;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\GoogleAnalyticsAdminV1alpha\GoogleAnalyticsAdminV1alphaLinkProposalStatusDetails::class, 'Google\\Site_Kit_Dependencies\\Google_Service_GoogleAnalyticsAdminV1alpha_GoogleAnalyticsAdminV1alphaLinkProposalStatusDetails');
