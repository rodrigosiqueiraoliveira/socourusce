<?php

class Esmart_Customer_Helper_Customer extends Esmart_Eav_Helper_Data
{
    /**
     * Default attribute entity type code
     *
     * @return string
     */
    protected function _getEntityTypeCode()
    {
        return 'customer';
    }

    /**
     * Return available customer attribute form as select options
     *
     * @return array
     */
    public function getAttributeFormOptions()
    {
        return array(
            array(
                'label' => Mage::helper('esmart_customer')->__('Customer Checkout Register'),
                'value' => 'checkout_register'
            ),
            array(
                'label' => Mage::helper('esmart_customer')->__('Customer Registration'),
                'value' => 'customer_account_create'
            ),
            array(
                'label' => Mage::helper('esmart_customer')->__('Customer Account Edit'),
                'value' => 'customer_account_edit'
            ),
            array(
                'label' => Mage::helper('esmart_customer')->__('Admin Checkout'),
                'value' => 'adminhtml_checkout'
            ),
        );
    }
}
