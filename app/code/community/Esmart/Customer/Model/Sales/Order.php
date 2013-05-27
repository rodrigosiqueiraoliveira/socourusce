<?php

class Esmart_Customer_Model_Sales_Order extends Esmart_Customer_Model_Sales_Abstract
{
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('esmart_customer/sales_order');
    }
}
