<?php
class RedFeet_SuccessReview_Block_Review_Info  extends Mage_Sales_Block_Items_Abstract
{
	public function getItems(){
		$order = $this->getOrder();
		return $order->getItemsCollection();
	}
	
	public function getOrder(){
		return Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
	}
}