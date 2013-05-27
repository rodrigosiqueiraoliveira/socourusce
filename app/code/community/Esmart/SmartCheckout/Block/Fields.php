<?php
/**
 * Smart Commerce do Brasil
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@e-smart.com.br so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.e-smart.com.br for more information.
 *
 * @category    Esmart
 * @package     Esmart_SmartCheckout
 * @copyright   Copyright (c) 2012 Smart Commerce do Brasil. (http://www.e-smart.com.br)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
 
 /**
 * 
 *
 * @category    Esmart
 * @package     Esmart_SmartCheckout
 * @author      Tiago Sampaio <tiago.sampaio@e-smart.com.br>
 */
class Esmart_SmartCheckout_Block_Fields extends Esmart_SmartCheckout_Block_Checkout
{

	/**
     * Sales Qoute Billing Address instance
     *
     * @var Mage_Sales_Model_Quote_Address
     */
    protected $_billing_address;

    /**
     * Sales Qoute Shipping Address instance
     *
     * @var Mage_Sales_Model_Quote_Address
     */
    protected $_shipping_address;

    public function _construct()
    {
        $this->setSubTemplate(true);
        parent::_construct();
    }

    /**
     * Return Sales Quote Address model
     *
     * @return Mage_Sales_Model_Quote_Address
     */
    public function getBillingAddress()
    {
    	if($this->getQuote()->getBillingAddress()) {
        	$this->_billing_address = $this->getQuote()->getBillingAddress();

        	if ($this->isCustomerLoggedIn()) {
                if(!$this->_billing_address->getFirstname()) {
                    $this->_billing_address->setFirstname($this->getQuote()->getCustomer()->getFirstname());
                }
                if(!$this->_billing_address->getLastname()) {
                    $this->_billing_address->setLastname($this->getQuote()->getCustomer()->getLastname());
                }
            }
    	} else {
    		$this->_billing_address = Mage::getModel('sales/quote_address');
    	}

        return $this->_billing_address;
    }

    /**
     * Return Sales Quote Address model
     *
     * @return Mage_Sales_Model_Quote_Address
     */
    public function getShippingAddress()
    {

    	if($this->getQuote()->getShippingAddress()) {
    		$this->_shipping_address = $this->getQuote()->getShippingAddress();

    		if ($this->isCustomerLoggedIn()) {
                if(!$this->_shipping_address->getFirstname()) {
                    $this->_shipping_address->setFirstname($this->getQuote()->getCustomer()->getFirstname());
                }
                if(!$this->_shipping_address->getLastname()) {
                    $this->_shipping_address->setLastname($this->getQuote()->getCustomer()->getLastname());
                }
            }
    	} else {
            $this->_shipping_address = Mage::getModel('sales/quote_address');
        }

        return $this->_shipping_address;
    }
    
}