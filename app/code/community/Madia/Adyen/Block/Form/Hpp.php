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
class Madia_Adyen_Block_Form_Hpp extends Mage_Payment_Block_Form {

    protected function _construct() {
        $this->setTemplate('adyen/form/hpp.phtml');
        parent::_construct();
    }

    /**
     * Retrieve availables credit card types
     *
     * @return array
     */
    public function getHppAvailableTypes() {
        return $this->getMethod()->getAvailableHPPTypes();
    }

    /**
     * @since 0.1.0.4
     * @return type 
     */
    public function hppOptionsDisabled() {
        return Mage::getStoreConfig("payment/adyen_hpp/disable_hpptypes");
    }

    /**
     * Retrieve availables credit card types
     *
     * @return array
     */
    public function getIdealAvailableTypes() {
        $isConfigDemoMode = $this->getMethod()->getConfigDataDemoMode();
        $list = ($isConfigDemoMode === true) ? 
                explode(',', (string) $this->getMethod()->getConfigData('ideal_test_issuer_list')) :
                explode(',', (string) $this->getMethod()->getConfigData('ideal_issuer_list'));
        return $list;
    }

}