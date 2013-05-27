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
class Madia_Adyen_ProcessController extends Mage_Core_Controller_Front_Action {
    /**
     * @var Madia_Adyen_Model_Adyen_Data_Server
     */
    const SOAP_SERVER = 'Madia_Adyen_Model_Adyen_Data_Server_Notification';

    const OPENINVOICE_SOAP_SERVER = 'Madia_Adyen_Model_Adyen_Data_Server_Openinvoice';

    /**
     * Redirect Block
     * need to be redeclared
     */
    protected $_redirectBlockType = 'adyen/redirect';

    /**
     * @desc Soap Interface/Webservice
     * @since 0.0.9.9r2
     */
    public function soapAction() {
        $classmap = Mage::getModel('adyen/adyen_data_notificationClassmap');
        $server = new SoapServer($this->_getWsdl(), array('classmap' => $classmap));
        $server->setClass(self::SOAP_SERVER);
        $server->addFunction(SOAP_FUNCTIONS_ALL);
        $server->handle();

        //get soap request
        $request = Mage::registry('soap-request');

        if (empty($request)) {
            return false;
        }

        if (is_array($request->notification->notificationItems->NotificationRequestItem)) {
            foreach ($request->notification->notificationItems->NotificationRequestItem as $item) {
                $this->processResponse($item);
            }
        } else {
            $item = $request->notification->notificationItems->NotificationRequestItem;
            $this->processResponse($item);
        }
        exit();
    }

    public function openinvoiceAction() {
        $_mode = $this->_getConfigData('demoMode');
        $wsdl = ($_mode == 'Y') ?
                'https://ca-test.adyen.com/ca/services/OpenInvoiceDetail?wsdl' :
                'https://ca-live.adyen.com/ca/services/OpenInvoiceDetail?wsdl';
        $server = new SoapServer($wsdl);
        $server->setClass(self::OPENINVOICE_SOAP_SERVER);
        $server->addFunction(SOAP_FUNCTIONS_ALL);
        $server->handle();
        exit();
    }

    /**
     * @desc Get Wsdl
     * @since 0.0.9.9r2
     * @return string
     */
    protected function _getWsdl() {
        return Mage::getModuleDir('etc', 'Madia_Adyen') . DS . 'Notification.wsdl';
    }

    protected function _expireAjax() {
        if (!$this->getCheckout()->getQuote()->hasItems()) {
            $this->getResponse()->setHeader('HTTP/1.1', '403 Session Expired');
            exit;
        }
    }

    public function redirectAction() {
        try {
            $session = $this->_getCheckout();
            $order = $this->_getOrder();
            $session->setAdyenQuoteId($session->getQuoteId());
            $session->setAdyenRealOrderId($session->getLastRealOrderId());
            $order->loadByIncrementId($session->getLastRealOrderId());

            //redirect only if this order is never recorded
            $hasEvent = Mage::getResourceModel('adyen/adyen_event')
                                    ->getLatestStatus($session->getLastRealOrderId());
            if (!empty($hasEvent) || !$order->getId()) {
                $this->_redirect('/');
                return $this;
            }
            
            //redirect to adyen
            if (strcmp($order->getState(), Mage_Sales_Model_Order::STATE_PENDING_PAYMENT) === 0 ||
                    (strcmp($order->getState(), Mage_Sales_Model_Order::STATE_NEW) === 0)) {
                $_status = $this->_getConfigData('order_status');
                $order->addStatusHistoryComment(Mage::helper('adyen')->__('Customer was redirected to Adyen.'), $_status);
                $this->getResponse()->setBody(
                        $this->getLayout()
                                ->createBlock($this->_redirectBlockType)
                                ->setOrder($order)
                                ->toHtml()
                );
                $session->unsQuoteId();
            }
            //continue shopping
            else {
                $this->_redirect('/');
                return $this;
            }
        } catch (Exception $e) {
            $session->addException($e, Mage::helper('adyen')->__($e->getMessage()));
            $this->cancel();
        }
    }

    /**
     * Adyen returns POST variables to this action
     */
    public function successAction() {
        $status = $this->processResponse();
        $session = $this->_getCheckout();
        $session->unsAdyenRealOrderId();
        $session->setQuoteId($session->getAdyenQuoteId(true));
        $session->getQuote()->setIsActive(false)->save();
        if ($status) {            
            $this->_redirect('checkout/onepage/success');
        } else {
            $this->cancel();
        }
    }

    /**
     * @desc reloads the items in the cart && cancel the order
     * @since v009
     */
    public function cancel() {
        $cart = Mage::getSingleton('checkout/cart');
        $session = $this->_getCheckout();
        $order = Mage::getModel('sales/order');
        $incrementId = $session->getLastRealOrderId();
        $session->getQuote()->setIsActive(false)->save();
        $session->clear();
        if (empty($incrementId)) {
            $session->addError($this->__('Your payment failed, Please try again later'));
            $this->_redirect('checkout/cart');
            return;
        }
        
        $order->loadByIncrementId($incrementId);
        
        //handle the old order here
        try {
            $order->setActionFlag(Mage_Sales_Model_Order::ACTION_FLAG_CANCEL, true);
            $order->cancel()->save();
        } catch (Mage_Core_Exception $e) {
            Mage::logException($e);
        }
        $items = $order->getItemsCollection();
        foreach ($items as $item) {
            try {
                $cart->addOrderItem($item);
            } catch (Mage_Core_Exception $e) {
                $session->addError($this->__($e->getMessage()));
                Mage::logException($e);
                continue;
            }
        }
        $cart->save();
        $session->addError($this->__('Your payment failed. Please try again later'));
        $this->_redirect('checkout/cart');
    }

    public function insAction() {
        try {
            echo '[accepted]';
            $status = $this->processResponse();
        } catch (Exception $e) {
            Mage::logException($e);
        }
        exit();
    }

    /**
     * @since v008
     * @desc Update order status accordingly
     * @throws Exception
     */
    public function processResponse($soapItem = null) {
        return Mage::getModel('adyen/process')->processResponse($soapItem);
    }

    protected function _getCheckout() {
        return Mage::getSingleton('checkout/session');
    }

    protected function _getOrder() {
        return Mage::getModel('sales/order');
    }
    
    /**
     * @desc Give Default settings
     * @example $this->_getConfigData('demoMode','adyen_abstract')
     * @since 0.0.2
     * @param string $code
     */
    protected function _getConfigData($code, $paymentMethodCode = null, $storeId = null) {
        return Mage::getModel('adyen/process')->getConfigData($code, $paymentMethodCode, $storeId);
    }
}