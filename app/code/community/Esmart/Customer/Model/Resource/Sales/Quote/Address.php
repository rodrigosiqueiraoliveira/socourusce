<?php

class Esmart_Customer_Model_Resource_Sales_Quote_Address extends Esmart_Customer_Model_Resource_Sales_Address_Abstract
{
    /**
     * Main entity resource model name
     *
     * @var string
     */
    protected $_parentResourceModelName = 'sales/quote_address';

    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->_init('esmart_customer/sales_quote_address', 'entity_id');
    }
}
