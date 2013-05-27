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
class Esmart_SmartCheckout_Helper_Extraproducts extends Mage_Core_Helper_Abstract
{
    function getProductIds()
    {
        $ids_raw = Mage::getStoreConfig('smartcheckout/extra_products/product_ids');
        
        if($ids_raw && $ids_raw != '') {
            return explode(',', $ids_raw);        
        }
        else {
            return array();
        }
    }
   
    function productInCart($product_id)
    {
        $cart = Mage::helper('checkout/cart')->getCart();
        foreach($cart->getItems() as $item) {
            if($item->getProduct()->getId() == $product_id) {
                return true;
            }
        }
        return false;
    }

    function isValidExtraProduct($product_id)
    {
        $ids = $this->getProductIds();
        if(in_array($product_id, $ids)) {
            return true;
        }

        return false;
    }

    function hasExtraProducts()
    {
        if(count($this->getProductIds()) > 0) {
            return true;
        }
        return false;
    }

    function getExtraProducts()
    {
        $items = array();
        foreach($this->getProductIds() as $id) {
            if($id != '') {
                try {
                    $item = Mage::getModel('catalog/product')->load($id);
                } catch(Exception $e) {
                    continue;
                }
                if($item->getId()) {
                    $items[] = $item;
                }
            }
        }
        return $items;
    }

}
