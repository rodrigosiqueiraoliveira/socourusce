<?php

class Esmart_Customer_Helper_Address extends Esmart_Eav_Helper_Data
{
    /**
     * Default attribute entity type code
     *
     * @return string
     */
    protected function _getEntityTypeCode()
    {
        return 'customer_address';
    }

    /**
     * Return available customer address attribute form as select options
     *
     * @return array
     */
    public function getAttributeFormOptions()
    {
        return array(
            array(
                'label' => Mage::helper('esmart_customer')->__('Customer Address Registration'),
                'value' => 'customer_register_address'
            ),
            array(
                'label' => Mage::helper('esmart_customer')->__('Customer Account Address'),
                'value' => 'customer_address_edit'
            ),
        );
    }
}
