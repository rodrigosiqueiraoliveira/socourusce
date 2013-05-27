<?php
class RedFeet_SalesRule_Model_Quote_Discount extends Mage_SalesRule_Model_Quote_Discount
{
    /**
     * Add discount total information to address
     *
     * @param   Mage_Sales_Model_Quote_Address $address
     * @return  Mage_SalesRule_Model_Quote_Discount
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        if(Mage::helper('customer')->getCustomer()->getGroupId() != 4) {
            $amount = $address->getDiscountAmount();

            $description = $address->getDiscountDescription();
            $title = Mage::helper('sales')->__('Discount');
            
            $address->addTotal(array(
                'code'  => $this->getCode(),
                'title' => $title,
                'value' => $amount
            ));
        }
        return $this;
    }
}
