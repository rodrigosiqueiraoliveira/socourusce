<?php

abstract class Esmart_Customer_Model_Sales_Address_Abstract extends Esmart_Customer_Model_Sales_Abstract
{
    /**
     * Attach data to collection
     *
     * @param Varien_Data_Collection_Db $collection
     * @return Esmart_Customer_Model_Sales_Address_Abstract
     */
    public function attachDataToCollection(Varien_Data_Collection_Db $collection)
    {
        $this->_getResource()->attachDataToCollection($collection);
        return $this;
    }
}
