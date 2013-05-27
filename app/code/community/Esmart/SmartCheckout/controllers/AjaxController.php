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
class Esmart_SmartCheckout_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function add_extra_productAction()
    {
        $helper = Mage::helper('smartcheckout/extraproducts');
        $product_id = $this->getRequest()->getPost('product_id');
        $remove = $this->getRequest()->getPost('remove', false);

        if($helper->isValidExtraProduct($product_id)) {

            if(!$remove)    {
                /* Add product to cart if it doesn't exist */
                $product = Mage::getModel('catalog/product')->load($product_id);
                $cart = Mage::getSingleton('checkout/cart');
                $cart->addProduct($product);
                $cart->save();
            }
            else    {
                $items = Mage::helper('checkout/cart')->getCart()->getItems();
                foreach($items as $item)    {
                    if($item->getProduct()->getId() == $product_id) {
                        Mage::helper('checkout/cart')->getCart()->removeItem($item->getId())->save();
                    }
                }

            }
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function indexAction()
    {
        $resource = Mage::getResourceModel('sales/order_collection');

        if(method_exists($resource, 'getEntity'))   {
            echo 'Is using EAV';
        }
        else {
            echo 'Not using EAV';
        }

        die();

        var_dump($resource->getEntity());
        var_dump(get_class_methods($resource->getEntity()));
        var_dump($resource);

        die();

        var_dump(get_class_methods($resource));




        echo get_class($collection);
        echo '<br>';
        echo get_class($sales);

        var_dump(get_class_methods($collection));

        var_dump(get_class_methods($sales));
        var_dump($sales);

        die('<br><br>ajaxcontroller!');

        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _isEmailRegistered($email)
    {
        $model = Mage::getModel('customer/customer');
        $model->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);

        if($model->getId() == NULL)    {
            return false;
        }

        return true;
    }

    public function add_couponAction()
    {
        $quote = $this->_getOnepage()->getQuote();
        $couponCode = (string)$this->getRequest()->getParam('code');

        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }

        $response = array(
            'success' => false,
            'error'=> false,
            'message' => false,
        );



        try {

            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->setCouponCode(strlen($couponCode) ? $couponCode : '')
            ->collectTotals()
            ->save();

            if ($couponCode) {
                if ($couponCode == $quote->getCouponCode()) {
                    $response['success'] = true;
                    $response['message'] = $this->__('Coupon code "%s" was applied successfully.', Mage::helper('core')->escapeHtml($couponCode));
                }
                else {
                    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode));
                }
            } else {
                $response['success'] = true;
                $response['message'] = $this->__('Coupon code was canceled successfully.');
            }


        }
        catch (Mage_Core_Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $e->getMessage();
        }
        catch (Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $this->__('Can not apply coupon code.');
        }




        $html = $this->getLayout()
                    ->createBlock('checkout/onepage_shipping_method_available')
                    ->setTemplate('esmart/smartcheckout/shipping_method.phtml')
                    ->toHtml();

        $response['shipping_method'] = $html;


        $html = $this->getLayout()
        ->createBlock('checkout/onepage_payment_methods','choose-payment-method')
        ->setTemplate('esmart/smartcheckout/payment_method.phtml');

        if(Mage::helper('esmart/smartcheckout')->isEnterprise() && Mage::helper('customer')->isLoggedIn()){

            $customerBalanceBlock = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance', array('template'=>'esmart/smartcheckout/customerbalance/payment/additional.phtml'));
            $customerBalanceBlockScripts = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance_scripts', array('template'=>'esmart/smartcheckout/customerbalance/payment/scripts.phtml'));

            $rewardPointsBlock = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.points', array('template'=>'esmart/smartcheckout/reward/payment/additional.phtml', 'before' => '-'));
            $rewardPointsBlockScripts = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.scripts', array('template'=>'esmart/smartcheckout/reward/payment/scripts.phtml', 'after' => '-'));

            $this->getLayout()->getBlock('choose-payment-method')
            ->append($customerBalanceBlock)
            ->append($customerBalanceBlockScripts)
            ->append($rewardPointsBlock)
            ->append($rewardPointsBlockScripts)
            ;
        }

        if(Mage::helper('smartcheckout')->isEnterprise()){
            $giftcardScripts = $this->getLayout()->createBlock('enterprise_giftcardaccount/checkout_onepage_payment_additional', 'giftcardaccount_scripts', array('template'=>'esmart/smartcheckout/giftcardaccount/onepage/payment/scripts.phtml'));
            $html->append($giftcardScripts);
        }

        $response['payment_method'] = $html->toHtml();

          // Add updated totals HTML to the output
        $html = $this->getLayout()
        ->createBlock('smartcheckout/summary')
        ->setTemplate('esmart/smartcheckout/summary.phtml')
        ->toHtml();

        $response['summary'] = $html;

        $this->getResponse()->setBody(Zend_Json::encode($response));
    }

    public function add_giftcardAction(){

        $response = array(
            'success' => false,
            'error'=> true,
            'message' => $this->__('Cannot apply Gift Card, please try again later.'),
        );

        $code = $this->getRequest()->getParam('code', false);
        $remove = $this->getRequest()->getParam('remove', false);

        if (!empty($code) && empty($remove)) {
            try {
                Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                    ->loadByCode($code)
                    ->addToCart();

                $response['success'] = true;
                $response['error'] = false;
                $response['message'] = $this->__('Gift Card "%s" was added successfully.', Mage::helper('core')->escapeHtml($code));

            } catch (Mage_Core_Exception $e) {
                Mage::dispatchEvent('enterprise_giftcardaccount_add', array('status' => 'fail', 'code' => $code));

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $e->getMessage();

            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addException(
                    $e,
                    $this->__('Cannot apply Gift Card, please try again later.')
                );

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $this->__('Cannot apply Gift Card, please try again later.');

            }
        }

        if(!empty($remove)){
            $codes = $this->_getOnepage()->getQuote()->getGiftCards();
            if(!empty($codes)){
                $codes = unserialize($codes);
            } else {
                $codes = array();
            }
            $response['message'] = $this->__('Cannot remove Gift Card, please try again later.');
            $messageCodes = array();
            foreach($codes as $value){
                try {
                    Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                        ->loadByCode($value['c'])
                        ->removeFromCart();
                    $messageCodes[] = $value['c'];
                    $response['success'] = true;
                    $response['error'] = false;
                    $response['message'] = $this->__('Gift Card "%s" was removed successfully.', Mage::helper('core')->escapeHtml(implode(', ',$messageCodes)));

                } catch (Mage_Core_Exception $e) {

                    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $e->getMessage();

                } catch (Exception $e) {
                    Mage::getSingleton('checkout/session')->addException(
                        $e,
                        $this->__('Cannot remove Gift Card, please try again later.')
                    );

                    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $this->__('Cannot remove Gift Card, please try again later.');

                }
            }
        }



        // Add updated totals HTML to the output
        $html = $this->getLayout()
        ->createBlock('smartcheckout/summary')
        ->setTemplate('esmart/smartcheckout/summary.phtml')
        ->toHtml();

        $response['summary'] = $html;

        $html = $this->getLayout()
        ->createBlock('checkout/onepage_shipping_method_available')
        ->setTemplate('esmart/smartcheckout/shipping_method.phtml')
        ->toHtml();

        $response['shipping_method'] = $html;

        $html = $this->getLayout()
        ->createBlock('checkout/onepage_payment_methods')
        ->setTemplate('esmart/smartcheckout/payment_method.phtml');

        if(Mage::helper('smartcheckout')->isEnterprise()){
            $giftcardScripts = $this->getLayout()->createBlock('enterprise_giftcardaccount/checkout_onepage_payment_additional', 'giftcardaccount_scripts', array('template'=>'esmart/smartcheckout/giftcardaccount/onepage/payment/scripts.phtml'));
            $html->append($giftcardScripts);
        }

        $response['payment_method'] = $html->toHtml();

        $this->getResponse()->setBody(Zend_Json::encode($response));
    }

    public function check_emailAction()
    {
        $validator = new Zend_Validate_EmailAddress();
        $email = $this->getRequest()->getPost('email', false);

        $data = array('result'=>'invalid');


        if($email && $email != '')  {
            if(!$validator->isValid($email))    {

            }
            else    {

                // Valid email, check for existance
                if($this->_isEmailRegistered($email))   {
                    $data['result'] = 'exists';
                }
                else    {
                    $data['result'] = 'clean';
                }
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($data));
    }

    public function forgotPasswordAction()
    {
        $email = $this->getRequest()->getPost('email', false);

        if (!Zend_Validate::is($email, 'EmailAddress')) {
            $result = array('success'=>false);
        }
        else    {

            $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($email);

            if ($customer->getId()) {
                try {
                    $newPassword = $customer->generatePassword();
                    $customer->changePassword($newPassword, false);
                    $customer->sendPasswordReminderEmail();
                    $result = array('success'=>true);
                }
                catch (Exception $e){
                    $result = array('success'=>false, 'error'=>$e->getMessage());
                }
            }
            else {
                $result = array('success'=>false, 'error'=>'notfound');
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    public function loginAction()
    {
        //$sessionId = session_id();
        $username = $this->getRequest()->getPost('smartcheckout_username', false);
        $password = $this->getRequest()->getPost('smartcheckout_password',  false);
        $session = Mage::getSingleton('customer/session');

        $result = array('success' => false);

        if ($username && $password) {
            try {
                $session->login($username, $password);
            } catch (Exception $e) {
                $result['error'] = $e->getMessage();
            }
            if (! isset($result['error'])) {
                $result['success'] = true;
            }
        } else {
            $result['error'] = $this->__(
            'Please enter a username and password.');
        }

        //session_id($sessionId);
        $this->getResponse()->setBody(Zend_Json::encode($result));

    }

    public function save_billingAction()
    {
        $helper = Mage::helper('smartcheckout/checkout');

        $billing_data = $this->getRequest()->getPost('billing', array());
        $shipping_data = $this->getRequest()->getPost('shipping', array());
        $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
        $shippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);

        $billing_data = $helper->load_exclude_data($billing_data);
        $shipping_data = $helper->load_exclude_data($shipping_data);

        if(Mage::helper('customer')->isLoggedIn() && $helper->differentShippingAvailable()){
            if(!empty($customerAddressId)){
                $billingAddress = Mage::getModel('customer/address')->load($customerAddressId);
                if(is_object($billingAddress) && $billingAddress->getCustomerId() ==  Mage::helper('customer')->getCustomer()->getId()){
                    $billing_data = array_merge($billing_data, $billingAddress->getData());
                }
            }
            if(!empty($shippingAddressId)){
                $shippingAddress = Mage::getModel('customer/address')->load($shippingAddressId);
                if(is_object($shippingAddress) && $shippingAddress->getCustomerId() ==  Mage::helper('customer')->getCustomer()->getId()){
                    $shipping_data = array_merge($shipping_data, $shippingAddress->getData());
                }
            }
        }

        if(!empty($billing_data['use_for_shipping'])) {
           $shipping_data = $billing_data;
        }

        // set customer tax/vat number for further usage
        $taxid = '';
        if(!empty($billing_data['taxvat'])){
            $taxid = $billing_data['taxvat'];
        } else if(!empty($billing_data['vat_id'])){
            $taxid = $billing_data['vat_id'];
        }
        if (!empty($taxid)) {
            $this->_getOnepage()->getQuote()->setCustomerTaxvat($taxid);
            $this->_getOnepage()->getQuote()->setTaxvat($taxid);
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxvat($taxid);
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxId($taxid);
            $this->_getOnepage()->getQuote()->getBillingAddress()->setVatId($taxid);
        } else {
            $this->_getOnepage()->getQuote()->setCustomerTaxvat('');
            $this->_getOnepage()->getQuote()->setTaxvat('');
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxvat('');
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxId('');
            $this->_getOnepage()->getQuote()->getBillingAddress()->setVatId('');
        }

        $this->_getOnepage()->getQuote()->getBillingAddress()->addData($billing_data)->implodeStreetAddress()->setCollectShippingRates(true);
        if(!$this->_getOnepage()->getQuote()->isVirtual() && !Mage::helper('customer')->isLoggedIn()){
            $this->_getOnepage()->getQuote()->getShippingAddress()->addData($shipping_data)->implodeStreetAddress()->setCollectShippingRates(true);
        }

        $paymentMethod = $this->getRequest()->getPost('payment_method', false);
        $selectedMethod = $this->_getOnepage()->getQuote()->getPayment()->getMethod();

        $store = $this->_getOnepage()->getQuote() ? $this->_getOnepage()->getQuote()->getStoreId() : null;
        $methods = $helper->getActiveStoreMethods($store, $this->_getOnepage()->getQuote());

        if($paymentMethod && !empty($methods) && !in_array($paymentMethod, $methods)){
            $paymentMethod = false;
        }

        if(!$paymentMethod && $selectedMethod && in_array($selectedMethod, $methods)){
             $paymentMethod = $selectedMethod;
        }

        if($this->_getOnepage()->getQuote()->isVirtual()) {
            $this->_getOnepage()->getQuote()->getBillingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
        } else {
            $this->_getOnepage()->getQuote()->getShippingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
        }

        try {
            if($paymentMethod){
                $this->_getOnepage()->getQuote()->getPayment()->getMethodInstance();
            }
        } catch (Exception $e) {
        }

        $result = $this->_getOnepage()->saveBilling($billing_data, $customerAddressId);

        if(Mage::helper('customer')->isLoggedIn()){
            $this->_getOnepage()->getQuote()->getBillingAddress()->setSaveInAddressBook(empty($billing_data['save_in_address_book']) ? 0 : 1);
            $this->_getOnepage()->getQuote()->getShippingAddress()->setSaveInAddressBook(empty($shipping_data['save_in_address_book']) ? 0 : 1);
        }

        if($helper->differentShippingAvailable()) {
            if(empty($billing_data['use_for_shipping'])) {
                $shipping_result = $helper->saveShipping($shipping_data, $shippingAddressId);
            }else {
                $shipping_result = $helper->saveShipping($billing_data, $customerAddressId);
            }
        }

        $shipping_method = $this->getRequest()->getPost('shipping_method', false);

        if(!empty($shipping_method)) {
           $helper->saveShippingMethod($shipping_method);
        }

        if(!Mage::helper('customer')->isLoggedIn()){
            $this->_getOnepage()->getQuote()->setTotalsCollectedFlag(false)->collectTotals();
        }

        $this->loadLayout(false);

        if(Mage::helper('smartcheckout')->isEnterprise() && Mage::helper('customer')->isLoggedIn()){

            $customerBalanceBlock = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance', array('template'=>'esmart/smartcheckout/customerbalance/payment/additional.phtml'));
            $customerBalanceBlockScripts = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance_scripts', array('template'=>'esmart/smartcheckout/customerbalance/payment/scripts.phtml'));

            $rewardPointsBlock = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.points', array('template'=>'esmart/smartcheckout/reward/payment/additional.phtml', 'before' => '-'));
            $rewardPointsBlockScripts = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.scripts', array('template'=>'esmart/smartcheckout/reward/payment/scripts.phtml', 'after' => '-'));

            $this->getLayout()->getBlock('choose-payment-method')
            ->append($customerBalanceBlock)
            ->append($customerBalanceBlockScripts)
            ->append($rewardPointsBlock)
            ->append($rewardPointsBlockScripts)
            ;

        }

        if(Mage::helper('smartcheckout')->isEnterprise()){
            $giftcardScripts = $this->getLayout()->createBlock('enterprise_giftcardaccount/checkout_onepage_payment_additional', 'giftcardaccount_scripts', array('template'=>'esmart/smartcheckout/giftcardaccount/onepage/payment/scripts.phtml'));
            $this->getLayout()->getBlock('choose-payment-method')
            ->append($giftcardScripts);
        }

        $this->renderLayout();

    }

    public function paymentrefreshAction()
    {
        $payment_method = $this->getRequest()->getPost('payment_method');
        $helper = Mage::helper('smartcheckout/checkout');
        if($payment_method != '')   {
            try {
                $payment = $this->getRequest()->getPost('payment', array());
                $payment['method'] = $payment_method;
                $this->_getOnepage()->getQuote()->getPayment()->setMethod($payment['method'])->getMethodInstance();
                //$payment_result = $this->_getOnepage()->savePayment($payment);
                $helper->savePayment($payment);
            }
            catch(Exception $e) {
                //die('Error: ' . $e->getMessage());
                // Silently fail for now
            }
        }

        $this->loadLayout(false);

        if(Mage::helper('smartcheckout')->isEnterprise() && Mage::helper('customer')->isLoggedIn()){

            $customerBalanceBlock = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance', array('template'=>'esmart/smartcheckout/customerbalance/payment/additional.phtml'));
            $customerBalanceBlockScripts = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance_scripts', array('template'=>'esmart/smartcheckout/customerbalance/payment/scripts.phtml'));

            $rewardPointsBlock = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.points', array('template'=>'esmart/smartcheckout/reward/payment/additional.phtml', 'before' => '-'));
            $rewardPointsBlockScripts = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.scripts', array('template'=>'esmart/smartcheckout/reward/payment/scripts.phtml', 'after' => '-'));

            $this->getLayout()->getBlock('choose-payment-method')
            ->append($customerBalanceBlock)
            ->append($customerBalanceBlockScripts)
            ->append($rewardPointsBlock)
            ->append($rewardPointsBlockScripts)
            ;
        }

        $this->renderLayout();
    }

    public function set_methods_separateAction()
    {
        $helper = Mage::helper('smartcheckout/checkout');

        $shipping_method = $this->getRequest()->getPost('shipping_method');
        $old_shipping_method = $this->_getOnepage()->getQuote()->getShippingAddress()->getShippingMethod();

        if($shipping_method != '' && $shipping_method != $old_shipping_method)  {
            //$result = $this->_getOnepage()->saveShippingMethod($shipping_method);
            // Use our helper instead
            $helper->saveShippingMethod($shipping_method);
        }
        //$this->_getOnepage()->getQuote()->getShippingAddress()->collectTotals();

        $paymentMethod = $this->getRequest()->getPost('payment_method', false);
        $selectedMethod = $this->_getOnepage()->getQuote()->getPayment()->getMethod();

        $store = $this->_getOnepage()->getQuote() ? $this->_getOnepage()->getQuote()->getStoreId() : null;
        $methods = $helper->getActiveStoreMethods($store, $this->_getOnepage()->getQuote());

        if($paymentMethod && !empty($methods) && !in_array($paymentMethod, $methods)){
            $paymentMethod = false;
        }

        if(!$paymentMethod && $selectedMethod && in_array($selectedMethod, $methods)){
             $paymentMethod = $selectedMethod;
        }

        try {
            $payment = $this->getRequest()->getPost('payment', array());
            //$payment = array();
            if(!empty($paymentMethod)){
                $payment['method'] = $paymentMethod;
            }
            //$payment_result = $this->_getOnepage()->savePayment($payment);
            $helper->savePayment($payment);
        }
        catch(Exception $e) {
            //die('Error: ' . $e->getMessage());
            // Silently fail for now
        }
        $this->_getOnepage()->getQuote()->collectTotals()->save();
        $this->loadLayout(false);

        if(Mage::helper('smartcheckout')->isEnterprise() && Mage::helper('customer')->isLoggedIn()){

            $customerBalanceBlock = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance', array('template'=>'esmart/smartcheckout/customerbalance/payment/additional.phtml'));
            $customerBalanceBlockScripts = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance_scripts', array('template'=>'esmart/smartcheckout/customerbalance/payment/scripts.phtml'));

            $rewardPointsBlock = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.points', array('template'=>'esmart/smartcheckout/reward/payment/additional.phtml', 'before' => '-'));
            $rewardPointsBlockScripts = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.scripts', array('template'=>'esmart/smartcheckout/reward/payment/scripts.phtml', 'after' => '-'));

            $this->getLayout()->getBlock('choose-payment-method')
            ->append($customerBalanceBlock)
            ->append($customerBalanceBlockScripts)
            ->append($rewardPointsBlock)
            ->append($rewardPointsBlockScripts)
            ;
        }

        if(Mage::helper('smartcheckout')->isEnterprise()){
            $giftcardScripts = $this->getLayout()->createBlock('enterprise_giftcardaccount/checkout_onepage_payment_additional', 'giftcardaccount_scripts', array('template'=>'esmart/smartcheckout/giftcardaccount/onepage/payment/scripts.phtml'));
            $this->getLayout()->getBlock('choose-payment-method')
            ->append($giftcardScripts);
        }

        $this->renderLayout();
    }

    public function set_methodsAction()
    {
        $helper = Mage::helper('smartcheckout/checkout');
        $shipping_method = $this->getRequest()->getPost('shipping_method');

        if($shipping_method != '')  {
            //$result = $this->_getOnepage()->saveShippingMethod($shipping_method);
            $helper->saveShippingMethod($shipping_method);
        }

        $payment_method = $this->getRequest()->getPost('payment_method');

        if($payment_method != '')   {
            try {
                $payment = $this->getRequest()->getPost('payment', array());
                $payment['method'] = $payment_method;
                //$payment_result = $this->_getOnepage()->savePayment($payment);
                $helper->savePayment($payment);
            }
            catch(Exception $e) {
                //die('Error: ' . $e->getMessage());
                // Silently fail for now
            }
        }

        //$this->_getOnepage()->getQuote()->collectTotals()->save();

        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function set_payment_methodAction()
    {
        $payment_method = $this->getRequest()->getPost('payment_method');
        $payment = array('method' => $payment_method);
        $result = $this->_getOnepage()->savePayment($payment);

        $this->loadLayout(false);
        $this->renderLayout();
    }

    public function set_shipping_methodAction()
    {
        $shipping_method = $this->getRequest()->getPost('shipping_method');
        $result = $this->_getOnepage()->saveShippingMethod($shipping_method);

        $this->loadLayout(false);
        $this->renderLayout();
    }

    protected function _getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
    public function registerAction() {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }

        if ($this->getRequest()->isPost()) {
            $errors = array();

            if (! $customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }

            $lastOrderId = $this->_getOnepage()->getCheckout()->getLastOrderId();
            $order = Mage::getModel('sales/order')->load($lastOrderId);
            $billing = $order->getBillingAddress();
            $shipping = $order->getShippingAddress();

            $customer->setData('firstname', $billing->getFirstname());
            $customer->setData('lastname', $billing->getLastname());
            $customer->setData('email', $order->getCustomerEmail());

            foreach (Mage::getConfig()->getFieldset('customer_account') as $code => $node) {
                if ($node->is('create') && ($value = $this->getRequest()->getParam($code)) !== null) {
                    $customer->setData($code, $value);
                }
            }

            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }

            /**
             * Initialize customer group id
             */
            $customer->getGroupId();

            $baddress = Mage::getModel('customer/address')->setData($billing->getData())->setIsDefaultBilling(1)->setId(null);
            $customer->addAddress($baddress);
            $saddress = Mage::getModel('customer/address')->setData($shipping->getData())->setIsDefaultShipping(1)->setId(null);
            $customer->addAddress($saddress);

            $result = array('success' => false, 'message' => false, 'error' => false );

            try {
                $validationCustomer = $customer->validate();
                if (is_array($validationCustomer)) {
                    $errors = array_merge($validationCustomer, $errors);
                }
                $validationResult = count($errors) == 0;
                if (true === $validationResult) {

                    $customer->save();

                    $result['success'] = true;

                    if ($customer->isConfirmationRequired()) {

                        $customer->sendNewAccountEmail('confirmation', $this->_getSession()->getBeforeAuthUrl());
                        $this->_getSession()->addSuccess($this->__('Account confirmation is required. Please, check your e-mail for confirmation link. To resend confirmation email please <a href="%s">click here</a>.', Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())));

                        $result['message'] = 'email_confirmation';

                    } else {
                        $this->_getSession()->setCustomerAsLoggedIn($customer);
                        $url = $this->_welcomeCustomer($customer);

                        $result['message'] = 'customer_logged_in';
                    }

                    // Add the last order to this account
                    $order->setCustomerId($customer->getId());
                    $order->setCustomerIsGuest(false);
                    $order->setCustomerGroupId($customer->getGroupId());
                    $order->save();

                } else {
                    $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                        }

                        $result['error'] = 'validation_failed';
                        $result['errors'] = $errors;
                    } else {
                        $result['error'] = 'invalid_customer_data';
                    }
                }
            } catch ( Mage_Core_Exception $e ) {

                $result['error'] = $e->getMessage();

            } catch ( Exception $e ) {

                $result['error'] = $e->getMessage();

            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }

    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess($this->__('Thank you for registering with %s', Mage::app()->getStore()->getName()));

        $customer->sendNewAccountEmail($isJustConfirmed ? 'confirmed' : 'registered');

        $successUrl = Mage::getUrl('*/*/index', array('_secure'=>true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }
}
