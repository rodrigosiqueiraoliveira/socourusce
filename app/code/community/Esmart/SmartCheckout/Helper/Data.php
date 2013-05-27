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
class Esmart_SmartCheckout_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function checkValid($observer)
    {
        $layout = Mage::app()->getLayout();
        $content = $layout->getBlock('content');
        $block = $layout->createBlock('smartcheckout/valid');
        $content->insert($block);
    }

    public function setCustomerComment($observer)
    {
        $enableComments = Mage::getStoreConfig('smartcheckout/exclude_fields/enable_comments');
        $enableCommentsDefault = Mage::getStoreConfig('smartcheckout/exclude_fields/enable_comments_default');
        $enableFeedback = Mage::getStoreConfig('smartcheckout/feedback/enable_feedback');
        $orderComment = $this->_getRequest()->getPost('smartcheckout_comments');
        $orderComment = trim($orderComment);

        if($enableComments && !$enableCommentsDefault) {
            if ($orderComment != ""){
                $observer->getEvent()->getOrder()->setOnestepcheckoutCustomercomment(Mage::helper('core')->escapeHtml($orderComment));
            }
        }

        if($enableComments && $enableCommentsDefault) {
            if ($orderComment != ""){
                $observer->getEvent()->getOrder()->setState($observer->getEvent()->getOrder()->getStatus(), true, Mage::helper('core')->escapeHtml($orderComment), false );
            }
        }

        if($enableFeedback){

            $feedbackValues = unserialize(Mage::getStoreConfig('smartcheckout/feedback/feedback_values'));
            $feedbackValue = $this->_getRequest()->getPost('smartcheckout-feedback');
            $feedbackValueFreetext = $this->_getRequest()->getPost('smartcheckout-feedback-freetext');

            if(!empty($feedbackValue)){
                if($feedbackValue!='freetext'){
                    $feedbackValue = $feedbackValues[$feedbackValue]['value'];
                } else {
                    $feedbackValue = $feedbackValueFreetext;
                }

                $observer->getEvent()->getOrder()->setOnestepcheckoutCustomerfeedback(Mage::helper('core')->escapeHtml($feedbackValue));
            }

        }
    }

    public function isRewriteCheckoutLinksEnabled()
    {
        return Mage::getStoreConfig('smartcheckout/general/rewrite_checkout_links');
    }

    /**
     * If we are using enterprise wersion or not
     * @return int
     */
    public function isEnterPrise(){
        return (int)is_object(Mage::getConfig()->getNode('global/models/enterprise_enterprise'));
    }

    /**
     * Encode the mixed $valueToEncode into the JSON format
     *
     * @param mixed $valueToEncode
     * @param  boolean $cycleCheck Optional; whether or not to check for object recursion; off by default
     * @param  array $options Additional options used during encoding
     * @return string
     */
    public function jsonEncode($valueToEncode, $cycleCheck = false, $options = array())
    {
        $json = Zend_Json::encode($valueToEncode, $cycleCheck, $options);
        /* @var $inline Mage_Core_Model_Translate_Inline */
        $inline = Mage::getSingleton('core/translate_inline');
        if ($inline->isAllowed()) {
            $inline->setIsJson(true);
            $inline->processResponseBody($json);
            $inline->setIsJson(false);
        }

        return $json;
    }

    /**
     * Check if value is only -
     * @param mixed $value
     */
    public function clearDash($value = null){
        if($value == '-'){
            return '';
        }
        //backwards compatibility with < 1.4.1.*
        return Mage::helper('core')->htmlEscape($value);
    }
}
