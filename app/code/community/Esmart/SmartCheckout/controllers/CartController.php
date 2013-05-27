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
require_once  Mage::getModuleDir('controllers', 'Mage_Checkout').DS.'CartController.php';
class Esmart_SmartCheckout_CartController extends Mage_Checkout_CartController
{
    /**
     * Set back redirect url to response
     *
     * @return Mage_Checkout_CartController
     */
    protected function _goBack()
    {
        if ($returnUrl = $this->getRequest()->getParam('return_url')) {
            // clear layout messages in case of external url redirect
            if ($this->_isUrlInternal($returnUrl)) {
                $this->_getSession()->getMessages(true);
            }
            $this->getResponse()->setRedirect($returnUrl);
        } elseif (!Mage::getStoreConfig('checkout/cart/redirect_to_cart') && !$this->getRequest()->getParam('in_cart') && $backUrl = $this->_getRefererUrl()) {
            $this->getResponse()->setRedirect($backUrl);
        } else {
            if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
                $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
            }
            //if config enabled, clear messages and redirect to checkout
            if(Mage::getStoreConfig('smartcheckout/direct_checkout/redirect_to_cart')){

                $customerGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
                $allowedGroups = Mage::getStoreConfig('smartcheckout/direct_checkout/group_ids');

                if(!empty($allowedGroups)){
                    $allowedGroups = explode(',',$allowedGroups);
                } else {
                    $allowedGroups = array();
                }

                if(!in_array($customerGroupId, $allowedGroups)){

                    $this->_getSession()->getMessages(true);
                    $this->_redirect('smartcheckout', array('_secure'=>true));
                } else {
                    $this->_redirect('checkout/cart');
                }
            } else {
                $this->_redirect('checkout/cart');
            }
        }
        return $this;
    }

}
