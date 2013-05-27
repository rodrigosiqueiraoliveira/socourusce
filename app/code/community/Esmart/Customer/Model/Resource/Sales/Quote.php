<?php

class Esmart_Customer_Model_Resource_Sales_Quote extends Esmart_Customer_Model_Resource_Sales_Abstract
{
    /**
     * Main entity resource model name
     *
     * @var string
     */
    protected $_parentResourceModelName = 'sales/quote';

    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->_init('esmart_customer/sales_quote', 'entity_id');
    }
}
