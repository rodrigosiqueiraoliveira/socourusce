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
class Esmart_SmartCheckout_Helper_Message extends Mage_GiftMessage_Helper_Message
{

    public function getInline ($type, Varien_Object $entity,
    $dontDisplayContainer = false)
    {

        $html = parent::getInline($type, $entity, $dontDisplayContainer);

        if (! empty($html)) {
            $block = Mage::getSingleton('core/layout')->createBlock('giftmessage/message_inline')
                                                        ->setId('giftmessage_form_' . $this->_nextId ++)
                                                        ->setDontDisplayContainer($dontDisplayContainer)
                                                        ->setEntity($entity)
                                                        ->setType($type)
                                                        ->setTemplate('esmart/smartcheckout/gift_message.phtml');

            $html = $block->toHtml();
        }

        return $html;
    }
}
