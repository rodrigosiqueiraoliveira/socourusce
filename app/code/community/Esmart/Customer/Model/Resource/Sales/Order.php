<?php

class Esmart_Customer_Model_Resource_Sales_Order extends Esmart_Customer_Model_Resource_Sales_Abstract
{
    /**
     * Main entity resource model name
     *
     * @var string
     */
    protected $_parentResourceModelName = 'sales/order';

    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->_init('esmart_customer/sales_order', 'entity_id');
    }
}
