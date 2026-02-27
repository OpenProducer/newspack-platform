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
namespace Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle;

class AmountDetails extends \Google\Site_Kit_Dependencies\Google\Model
{
    protected $canceledAmountType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails::class;
    protected $canceledAmountDataType = '';
    protected $chargeableAmountType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails::class;
    protected $chargeableAmountDataType = '';
    protected $chargedAmountType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails::class;
    protected $chargedAmountDataType = '';
    protected $declinedAmountType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails::class;
    protected $declinedAmountDataType = '';
    protected $refundedAmountType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails::class;
    protected $refundedAmountDataType = '';
    /**
     * The canceled amount of this transaction.
     *
     * @param PriceDetails $canceledAmount
     */
    public function setCanceledAmount(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails $canceledAmount)
    {
        $this->canceledAmount = $canceledAmount;
    }
    /**
     * @return PriceDetails
     */
    public function getCanceledAmount()
    {
        return $this->canceledAmount;
    }
    /**
     * The chargeable amount of this transaction. This scenario should be rare and
     * would only occur if a publisher happens to call the API while the order is
     * still in a processing state.
     *
     * @param PriceDetails $chargeableAmount
     */
    public function setChargeableAmount(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails $chargeableAmount)
    {
        $this->chargeableAmount = $chargeableAmount;
    }
    /**
     * @return PriceDetails
     */
    public function getChargeableAmount()
    {
        return $this->chargeableAmount;
    }
    /**
     * The charged amount of this transaction.
     *
     * @param PriceDetails $chargedAmount
     */
    public function setChargedAmount(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails $chargedAmount)
    {
        $this->chargedAmount = $chargedAmount;
    }
    /**
     * @return PriceDetails
     */
    public function getChargedAmount()
    {
        return $this->chargedAmount;
    }
    /**
     * The declined amount of this transaction.
     *
     * @param PriceDetails $declinedAmount
     */
    public function setDeclinedAmount(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails $declinedAmount)
    {
        $this->declinedAmount = $declinedAmount;
    }
    /**
     * @return PriceDetails
     */
    public function getDeclinedAmount()
    {
        return $this->declinedAmount;
    }
    /**
     * The refunded amount of this transaction.
     *
     * @param PriceDetails $refundedAmount
     */
    public function setRefundedAmount(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails $refundedAmount)
    {
        $this->refundedAmount = $refundedAmount;
    }
    /**
     * @return PriceDetails
     */
    public function getRefundedAmount()
    {
        return $this->refundedAmount;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\AmountDetails::class, 'Google\\Site_Kit_Dependencies\\Google_Service_SubscribewithGoogle_AmountDetails');
