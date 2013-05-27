<?php

/**
 * Madia Adyen Payment Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category	Madia
 * @package	Madia_Adyen
 * @copyright	Copyright (c) 2012 Madia (http://www.madia.nl)
 * @license	http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * @category   Payment Gateway
 * @package    Madia_Adyen
 * @author     Omar,Muhsin <info@madia.nl>
 * @property   Madia B.V
 * @copyright  Copyright (c) 2012 Madia BV (http://www.madia.nl)
 */
class Madia_Adyen_Model_Adyen_Data_InvoiceRow extends Madia_Adyen_Model_Adyen_Data_Abstract {

    public $currency;
    public $description;
    public $itemPrice;
    public $itemVAT;
    public $lineReference;
    public $numberOfItems;
    public $vatCategory;

    public function create($item, $count, $order) {
        $this->currency = $order->getOrderCurrencyCode();
        $this->description = $item->getName();
        $this->itemPrice = $this->_formatAmount($item->getPrice());
        $this->itemVAT = $this->_formatAmount($item->getTaxAmount());
        $this->lineReference = $count;
        $this->numberOfItems = (int) $item->getQtyOrdered();
        $this->vatCategory = "None";
        return $this;
    }

}