<?php

class Esmart_Customer_Block_Adminhtml_Customer_Attribute_Edit_Js extends Mage_Adminhtml_Block_Template
{
    /**
     * Retrieve allowed Input Validate Filters in JSON format
     *
     * @return string
     */
    public function getValidateFiltersJson()
    {
        return Mage::helper('core')->jsonEncode(Mage::helper('esmart_customer')->getAttributeValidateFilters());
    }

    /**
     * Retrieve allowed Input Filter Types in JSON format
     *
     * @return string
     */
    public function getFilteTypesJson()
    {
        return Mage::helper('core')->jsonEncode(Mage::helper('esmart_customer')->getAttributeFilterTypes());
    }

    /**
     * Returns array of input types with type properties
     *
     * @return array
     */
    public function getAttributeInputTypes()
    {
        return Mage::helper('esmart_customer')->getAttributeInputTypes();
    }
}
