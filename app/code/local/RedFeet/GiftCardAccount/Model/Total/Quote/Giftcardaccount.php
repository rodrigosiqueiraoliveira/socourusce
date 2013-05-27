<?php
class RedFeet_GiftCardAccount_Model_Total_Quote_GiftCardAccount extends Enterprise_GiftCardAccount_Model_Total_Quote_GiftCardAccount
{
    /**
     * Return shopping cart total row items
     *
     * @param Mage_Sales_Model_Quote_Address $address
     * @return Enterprise_GiftCardAccount_Model_Total_Quote_GiftCardAccount
     */
    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {
        return $this;
    }
}
