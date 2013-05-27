<?php
require_once('Mage/Checkout/controllers/CartController.php');

/**

 * Shopping cart controller

 */

class RedFeet_Sessioncart_Checkout_CartController extends Mage_Checkout_CartController
{

    /**

     * Add product to shopping cart action

     */

    public function addAction()

    {
        if(Mage::helper('customer')->isLoggedIn() == true) {

            $customer = Mage::helper('customer')->getCustomer()->getData();

            

            if ($customer['group_id'] == 4) {

                $jump = false;

                

                $cart   = $this->_getCart();

                $params = $this->getRequest()->getParams();


                $helper = Mage::helper('sessioncart/data');

                //$sessao_produto = $helper->setSessionCart($this->getRequest()->getParams(), 'PRODUTO');



                //echo "<pre>".print_r($params,  true)."</pre>";

                

                $dados = Mage::getSingleton('core/session')->getProdutosSession();


                if($dados == "") {

                    $this->_getSession()->addError("Selecione uma quantidade para algum produto!");

                    echo "<script>javascript:window.history.back(-1);</script>";

                    exit;

                }

                

                $i = 0;

                foreach($dados as $item) {

                    if($item['id_produto'] == $params['product']){

                        foreach($item as $indice => $valor) {

                            $array_params[$i]["product"] = $params["product"]; 

                            $k = 0;

                            foreach($params["super_attribute"] as $indice => $valor) {

                                if($k == 0) {

                                    $array_params[$i]["super_attribute"][$indice] = $item["cor"];

                                    $k++;

                                }

                                else

                                    $array_params[$i]["super_attribute"][$indice] = $item["tam"];

                            }

                            $array_params[$i]["qty"] = $item["qty"];

                        }

                        $i++;

                    }

                }

                //exit("<pre>".print_r($array_params, true)."</pre>");

                

                foreach($array_params as $params) {

                    try {

                        if (isset($params['qty'])) {

                            $filter = new Zend_Filter_LocalizedToNormalized(

                                array('locale' => Mage::app()->getLocale()->getLocaleCode())

                            );

                            $params['qty'] = $filter->filter($params['qty']);

                        }

                        

                        $product = $this->_initProduct();

                        $related = $this->getRequest()->getParam('related_product');

            

                        /**

                         * Check product availability

                         */

                        if (!$product) {

                            $this->_goBack();

                            return;

                        }

            

                        $cart->addProduct($product, $params);

                        if (!empty($related)) {

                            $cart->addProductsByIds(explode(',', $related));

                        }

            

                        /**

                         * @todo remove wishlist observer processAddToCart

                         */

                        Mage::dispatchEvent('checkout_cart_add_product_complete',

                            array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())

                        );

            

                        if (!$this->_getSession()->getNoCartRedirect(true)) {

                            if (!$cart->getQuote()->getHasError()){

                                if(empty($message)) {

                                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));

                                    $this->_getSession()->addSuccess($message);

                                }

                            }

                            $this->_goBack();

                        }

                        

                    } catch (Mage_Core_Exception $e) {

                        /*

                        if ($this->_getSession()->getUseNotice(true)) {

                            $this->_getSession()->addNotice($e->getMessage());

                        } else {

                            $messages = array_unique(explode("\n", $e->getMessage()));

                            foreach ($messages as $message) {

                                $this->_getSession()->addError($message);

                            }

                        }

                        */

                        $error = 1;

                        

                        $url = $this->_getSession()->getRedirectUrl(true);

                        

                        if ($url) {

                            $this->getResponse()->setRedirect($url);

                        } else {

                            $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());

                        }

                        

                        $jump = true;

                    } catch (Exception $e) {

                        $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));

                        Mage::logException($e);

                        $this->_goBack();

                        

                        $jump = true;

                    }

                }

                

                if($jump == false) {

                    $cart->save();

                    $this->_getSession()->setCartWasUpdated(true);

                }

                

                Mage::getSingleton('core/session')->clear();

                

                if($error != "")

                    Mage::getSingleton('core/session')->setMinhaVariavel('Quantidade do produto indisponível');

                

                //$helper->getInfoTeste();

            }

        }

        if(Mage::helper('customer')->isLoggedIn() == false || $customer['group_id'] != 4) {

            $cart   = $this->_getCart();

            $params = $this->getRequest()->getParams();

            try {

                if (isset($params['qty'])) {

                    $filter = new Zend_Filter_LocalizedToNormalized(

                        array('locale' => Mage::app()->getLocale()->getLocaleCode())

                    );

                    $params['qty'] = $filter->filter($params['qty']);

                }

    

                $product = $this->_initProduct();

                $related = $this->getRequest()->getParam('related_product');

    

                /**

                 * Check product availability

                 */

                if (!$product) {

                    $this->_goBack();

                    return;

                }

    

                $cart->addProduct($product, $params);

                if (!empty($related)) {

                    $cart->addProductsByIds(explode(',', $related));

                }

    

                $cart->save();

    

                $this->_getSession()->setCartWasUpdated(true);

    

                /**

                 * @todo remove wishlist observer processAddToCart

                 */

                Mage::dispatchEvent('checkout_cart_add_product_complete',

                    array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())

                );

    

                if (!$this->_getSession()->getNoCartRedirect(true)) {

                    if (!$cart->getQuote()->getHasError()){

                        $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->htmlEscape($product->getName()));

                        $this->_getSession()->addSuccess($message);

                    }

                    $this->_goBack();

                }

            } catch (Mage_Core_Exception $e) {

                if ($this->_getSession()->getUseNotice(true)) {

                    $this->_getSession()->addNotice($e->getMessage());

                } else {

                    $messages = array_unique(explode("\n", $e->getMessage()));

                    foreach ($messages as $message) {

                        $this->_getSession()->addError($message);

                    }

                }

    

                $url = $this->_getSession()->getRedirectUrl(true);

                if ($url) {

                    $this->getResponse()->setRedirect($url);

                } else {

                    $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());

                }

            } catch (Exception $e) {

                $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));

                Mage::logException($e);

                $this->_goBack();

            }

        }
    }
    
    /**
     * Initialize shipping information
     */
    public function estimatePostAjaxAction()
    {
        $country    = (string) $this->getRequest()->getParam('country_id');
        $postcode   = (string) $this->getRequest()->getParam('estimate_postcode');
        $city       = (string) $this->getRequest()->getParam('estimate_city');
        $regionId   = (string) $this->getRequest()->getParam('region_id');
        $region     = (string) $this->getRequest()->getParam('region');

        $this->_getQuote()->getShippingAddress()
            ->setCountryId($country)
            ->setCity($city)
            ->setPostcode($postcode)
            ->setRegionId($regionId)
            ->setRegion($region)
            ->setCollectShippingRates(true);
        $this->_getQuote()->save();
        
        $response = array();
        $message = $this->__('C&aacute;lculo de frete');
        $response['status'] = 'SUCCESS';
        $response['message'] = $message;
        
        $this->_getQuote()->getShippingAddress()->collectTotals()->setCollectShippingRates(true)->collectShippingRates();
        
        $this->loadLayout(array('checkout_cart_index', 'Checkout_checkout_cart_index'));
        $layout = $this->getLayout();
        $blockName = 'checkout.cart.shipping.estimate';

        $finalBlock = $layout->createBlock('checkout/cart_shipping_estimate', $blockName)->setTemplate("checkout/cart/shipping_estimate_post.phtml")->toHtml();
        $response['shippingRates'] = $finalBlock;
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

}