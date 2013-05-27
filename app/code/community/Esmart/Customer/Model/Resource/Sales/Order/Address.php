<?php

class Esmart_Customer_Model_Resource_Sales_Order_Address extends Esmart_Customer_Model_Resource_Sales_Address_Abstract
{
    /**
     * Main entity resource model name
     *
     * @var string
     */
    protected $_parentResourceModelName = 'sales/order_address';

    /**
     * Initializes resource
     */
    protected function _construct()
    {
        $this->_init('esmart_customer/sales_order_address', 'entity_id');
    }
}
