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
class Esmart_SmartCheckout_Block_Adminhtml_Sales_Order_View_Comment extends Mage_Adminhtml_Block_Sales_Order_View_Items
{
    public function _toHtml(){
        $html = parent::_toHtml();
        $comment = $this->getCommentHtml();
        return $html.$comment;
    }

    /**
     * get comment from order and return as html formatted string
     *
     *@return string
     */
    public function getCommentHtml(){
        /**
         * @todo Change this methods below
         * 
         */
        $comment = $this->getOrder()->getOnestepcheckoutCustomercomment();
        $feedback = $this->getOrder()->getOnestepcheckoutCustomerfeedback();
        $html = '';

        if ($this->isShowCustomerCommentEnabled() && $comment){
            $html .= '<div id="customer_comment" class="giftmessage-whole-order-container"><div class="entry-edit">';
            $html .= '<div class="entry-edit-head"><h4>'.$this->helper('onestepcheckout')->__('Customer Comment').'</h4></div>';
            $html .= '<fieldset>'.nl2br(Mage::helper('core')->escapeHtml($comment)).'</fieldset>';
            $html .= '</div></div>';
        }

        if($this->isShowCustomerFeedbackEnabled()){
            $html .= '<div id="customer_feedback" class="giftmessage-whole-order-container"><div class="entry-edit">';
            $html .= '<div class="entry-edit-head"><h4>'.$this->helper('onestepcheckout')->__('Customer Feedback').'</h4></div>';
            $html .= '<fieldset>'.nl2br(Mage::helper('core')->escapeHtml($feedback)).'</fieldset>';
            $html .= '</div></div>';
        }

        return $html;
    }

    public function isShowCustomerCommentEnabled(){
        return Mage::getStoreConfig('onestepcheckout/exclude_fields/enable_comments', $this->getOrder()->getStore());
    }

    public function isShowCustomerFeedbackEnabled(){
        return Mage::getStoreConfig('onestepcheckout/feedback/enable_feedback', $this->getOrder()->getStore());
    }
}
