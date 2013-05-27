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
class Madia_Adyen_Model_Adyen_Data_IdealPaymentRequest extends Madia_Adyen_Model_Adyen_Data_Abstract {
    public $entranceCode;
    public $issuerId;
    public $language;
    public $merchantReturnUrl;
    
    public function __construct() {
        $this->amount = new Madia_Adyen_Model_Adyen_Data_Amount();
    }    
    
    public function create(Varien_Object $payment, $amount, $order, $paymentMethod = null, $merchantAccount = null) {
        $incrementId = $order->getIncrementId();
        $orderCurrencyCode = $order->getOrderCurrencyCode();
        $customerId = $order->getCustomerId();

        $this->reference = $incrementId;
        $this->merchantAccount = $merchantAccount;
        $this->amount->currency = $orderCurrencyCode;
        $this->amount->value = $this->_formatAmount($amount);

        //shopper data
        $customerEmail = $order->getCustomerEmail();
        $this->shopperEmail = $customerEmail;
        $this->shopperIP = $order->getRemoteIp();
        $this->shopperReference = $customerId;
        
        //IDEAL
        $this->entranceCode = $order->getQuoteId();
        $id = explode(DS, $payment->getInfoInstance()->getPoNumber());
        $this->issuerId = $id[0];
        $this->language = 'nl';
        $this->merchantReturnUrl = Mage::app()->getStore()->getBaseUrl().'checkout/onepage/success';

        return $this;
    }    
}