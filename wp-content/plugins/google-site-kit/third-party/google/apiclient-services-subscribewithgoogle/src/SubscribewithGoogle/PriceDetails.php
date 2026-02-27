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

class PriceDetails extends \Google\Site_Kit_Dependencies\Google\Collection
{
    protected $collection_key = 'taxDetails';
    protected $pretaxAmountType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\Money::class;
    protected $pretaxAmountDataType = '';
    protected $taxAmountType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\Money::class;
    protected $taxAmountDataType = '';
    protected $taxDetailsType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\TaxDetails::class;
    protected $taxDetailsDataType = 'array';
    protected $totalAmountType = \Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\Money::class;
    protected $totalAmountDataType = '';
    /**
     * The tax-exclusive amount.
     *
     * @param Money $pretaxAmount
     */
    public function setPretaxAmount(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\Money $pretaxAmount)
    {
        $this->pretaxAmount = $pretaxAmount;
    }
    /**
     * @return Money
     */
    public function getPretaxAmount()
    {
        return $this->pretaxAmount;
    }
    /**
     * The amount of tax to collect.
     *
     * @param Money $taxAmount
     */
    public function setTaxAmount(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\Money $taxAmount)
    {
        $this->taxAmount = $taxAmount;
    }
    /**
     * @return Money
     */
    public function getTaxAmount()
    {
        return $this->taxAmount;
    }
    /**
     * The tax details of the state.
     *
     * @param TaxDetails[] $taxDetails
     */
    public function setTaxDetails($taxDetails)
    {
        $this->taxDetails = $taxDetails;
    }
    /**
     * @return TaxDetails[]
     */
    public function getTaxDetails()
    {
        return $this->taxDetails;
    }
    /**
     * The total cost, including tax.
     *
     * @param Money $totalAmount
     */
    public function setTotalAmount(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\Money $totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }
    /**
     * @return Money
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(\Google\Site_Kit_Dependencies\Google\Service\SubscribewithGoogle\PriceDetails::class, 'Google\\Site_Kit_Dependencies\\Google_Service_SubscribewithGoogle_PriceDetails');
