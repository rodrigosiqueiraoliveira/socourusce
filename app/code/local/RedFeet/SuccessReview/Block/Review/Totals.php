<?php
class RedFeet_SuccessReview_Block_Review_Totals  extends Mage_Sales_Block_Items_Abstract
{
	public function getOrder(){
		return Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
	}
	
	public function getDiscount(){
		return $this->helper('checkout')->formatPrice($this->getOrder()->getBaseDiscountAmount());
	}
	
	public function getShippingTax(){
		return $this->helper('checkout')->formatPrice($this->getOrder()->getBaseShippingAmount());
	}
	
	public function getGrandTotal(){
		return $this->helper('checkout')->formatPrice($this->getOrder()->getBaseGrandTotal());
	}
}