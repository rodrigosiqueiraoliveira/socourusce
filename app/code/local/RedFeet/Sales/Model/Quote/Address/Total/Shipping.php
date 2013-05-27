<?php
class RedFeet_Sales_Model_Quote_Address_Total_Shipping extends Mage_Sales_Model_Quote_Address_Total_Shipping
{
    /**
     * Add shipping totals information to address object
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Mage_Sales_Model_Quote_Address_Total_Shipping
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $amount = $address->getShippingAmount();
        $title = Mage::helper('sales')->__('Shipping');
        
        $address->addTotal(array(
            'code' => $this->getCode(),
            'title' => $title,
            'value' => $address->getShippingAmount()
        ));
        return $this;
    }
}
