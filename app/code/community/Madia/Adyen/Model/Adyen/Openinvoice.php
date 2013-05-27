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
class Madia_Adyen_Model_Adyen_Openinvoice extends Madia_Adyen_Model_Adyen_Hpp {

    protected $_code = 'adyen_openinvoice';
    protected $_formBlockType = 'adyen/form_openinvoice';
    protected $_infoBlockType = 'adyen/info_openinvoice';
    protected $_paymentMethod = 'openinvoice';

    public function assignData($data) {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $info->setCcType('openinvoice');
        return $this;
    }

    /**
     * @desc Get url of Adyen payment
     * @return string 
     * @todo add brandCode here
     */
    public function getAdyenSharedUrl() {
        $paymentRoutine = $this->_getConfigData('payment_routines', 'adyen_hpp');
        switch ($this->getConfigDataDemoMode()) {
            case true:
                if ($paymentRoutine == 'single') {
                    $url = 'https://test.adyen.com/hpp/pay.shtml';
                } else {
                    $url = "https://test.adyen.com/hpp/details.shtml?brandCode=openinvoice";
                }
                break;
            default:
                if ($paymentRoutine == 'single') {
                    $url = 'https://live.adyen.com/hpp/pay.shtml';
                } else {
                    $url = "https://live.adyen.com/hpp/details.shtml?brandCode=openinvoice";
                }
                break;
        }
        return $url;
    }

    /**
     * @desc Openinvoice Optional Fields.
     * @desc Notice these are used to prepopulate the fields, but client can edit them at Adyen.
     * @return type array
     */
    public function getFormFields() {
        $adyFields = parent::getFormFields();
        $adyFields = $this->getOptionalFormFields($adyFields,$this->_order);
        return $adyFields;
    }
    
    public function getOptionalFormFields($adyFields,$order) {
        if (empty($order)) return $adyFields;
        $billingAddress = $order->getBillingAddress();
        $adyFields['shopper.firstName'] = $billingAddress->getFirstname();
        $adyFields['shopper.lastName'] = $billingAddress->getLastname();
        $adyFields['billingAddress.street'] = $this->getStreet($billingAddress)->getName();
        $adyFields['billingAddress.houseNumberOrName'] = $this->getStreet($billingAddress)->getHouseNumber();
        $adyFields['billingAddress.city'] = $billingAddress->getCity();
        $adyFields['billingAddress.postalCode'] = $billingAddress->getPostcode();
        $adyFields['billingAddress.stateOrProvince'] = $billingAddress->getRegion();
        $adyFields['billingAddress.country'] = $billingAddress->getCountryId();
        $sign = $adyFields['billingAddress.street'] .
                $adyFields['billingAddress.houseNumberOrName'] .
                $adyFields['billingAddress.city'] .
                $adyFields['billingAddress.postalCode'] .
                $adyFields['billingAddress.stateOrProvince'] .
                $adyFields['billingAddress.country']
        ;
        //Generate HMAC encrypted merchant signature
        $secretWord = $this->_getSecretWord();
        $signMac = Zend_Crypt_Hmac::compute($secretWord, 'sha1', $sign);
        $adyFields['billingAddressSig'] = base64_encode(pack('H*', $signMac));

        
        $deliveryAddress = $order->getShippingAddress();
        $adyFields['deliveryAddress.street'] = $this->getStreet($deliveryAddress)->getName();
        $adyFields['deliveryAddress.houseNumberOrName'] = $this->getStreet($deliveryAddress)->getHouseNumber();
        $adyFields['deliveryAddress.city'] = $deliveryAddress->getCity();
        $adyFields['deliveryAddress.postalCode'] = $deliveryAddress->getPostcode();
        $adyFields['deliveryAddress.stateOrProvince'] = $deliveryAddress->getRegion();
        $adyFields['deliveryAddress.country'] = $deliveryAddress->getCountryId();
        $sign = $adyFields['deliveryAddress.street'] .
	        $adyFields['deliveryAddress.houseNumberOrName'] .
	        $adyFields['deliveryAddress.city'] .
	        $adyFields['deliveryAddress.postalCode'] .
	        $adyFields['deliveryAddress.stateOrProvince'] .
	        $adyFields['deliveryAddress.country']
        ;
        //Generate HMAC encrypted merchant signature
        $secretWord = $this->_getSecretWord();
        $signMac = Zend_Crypt_Hmac::compute($secretWord, 'sha1', $sign);
        $adyFields['deliveryAddressSig'] = base64_encode(pack('H*', $signMac));
        
        
        if ($adyFields['shopperReference'] != self::GUEST_ID) {
            $customer = Mage::getModel('customer/customer')->load($adyFields['shopperReference']);
            $adyFields['shopper.gender'] = strtoupper($this->getCustomerAttributeText($customer, 'gender'));
            $adyFields['shopper.infix'] = $customer->getPrefix();
            $dob = $customer->getDob();
            if (!empty($dob)) {
                $adyFields['shopper.dateOfBirthDayOfMonth'] = $this->getDate($dob, 'd');
                $adyFields['shopper.dateOfBirthMonth'] = $this->getDate($dob, 'm');
                $adyFields['shopper.dateOfBirthYear'] = $this->getDate($dob, 'Y');
            }
        }
        $adyFields['shopper.telephoneNumber'] = $billingAddress->getTelephone();
        if (parent::TEST_ENV) {
            Mage::log($adyFields, self::DEBUG_LEVEL, 'http-request.log');
        }
        return $adyFields;
    }    

    /**
     * Get Attribute label
     * @param type $customer
     * @param type $code
     * @return type 
     */
    public function getCustomerAttributeText($customer, $code='gender') {
        $helper = Mage::helper('adyen');
        return $helper->htmlEscape($customer->getResource()->getAttribute($code)->getSource()->getOptionText($customer->getGender()));
    }

    /**
     * Date Manipulation
     * @param type $date
     * @param type $format
     * @return type date
     */
    public function getDate($date = null, $format = 'Y-m-d H:i:s') {
        if (strlen($date) < 0) {
            $date = date('d-m-Y H:i:s');
        }
        $timeStamp = new DateTime($date);
        return $timeStamp->format($format);
    }
    
    /** 
     * Street format
     * @param type $address
     * @return Varien_Object 
     */
    public function getStreet($address) {
        if (empty($address)) return false;
        $street = self::formatStreet($address->getStreet());
        $streetName = $street['0'];
        unset($street['0']);
        $streetNr = implode('',$street);
        return new Varien_Object(array('name' => $streetName, 'house_number' => $streetNr));
    }
    
    /**
     * Fix this one string street + number
     * @example street + number
     * @param type $street
     * @return type $street
     */
    static public function formatStreet($street) {
        if (count($street) != 1) {
            return $street;
        }        
        preg_match('/((\s\d{0,10})|(\s\d{0,10}\w{1,3}))$/i', $street['0'], $houseNumber, PREG_OFFSET_CAPTURE);
        if(!empty($houseNumber['0'])) {
           $_houseNumber = trim($houseNumber['0']['0']);
           $position = $houseNumber['0']['1'];
           $streeName = trim(substr($street['0'], 0, $position));
           $street = array($streeName,$_houseNumber);
        }
        return $street;
    }
}