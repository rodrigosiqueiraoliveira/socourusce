<?php
class RedFeet_Sales_Model_Quote_Address_Total_Subtotal extends Mage_Sales_Model_Quote_Address_Total_Subtotal
{
    /**
     * Assign subtotal amount and label to address object
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Mage_Sales_Model_Quote_Address_Total_Subtotal
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        $address->addTotal(array(
            'code'  => $this->getCode(),
            'title' => Mage::helper('sales')->__('Subtotal'),
            'value' => $address->getSubtotal()
        ));
        return $this;
    }
}
