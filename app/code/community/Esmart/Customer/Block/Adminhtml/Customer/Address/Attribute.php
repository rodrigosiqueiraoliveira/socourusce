<?php

class Esmart_Customer_Block_Adminhtml_Customer_Address_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Define controller, block and labels
     *
     */
    public function __construct()
    {
        $this->_blockGroup = 'esmart_customer';
        $this->_controller = 'adminhtml_customer_address_attribute';
        $this->_headerText = Mage::helper('esmart_customer')->__('Manage Customer Address Attributes');
        $this->_addButtonLabel = Mage::helper('esmart_customer')->__('Add New Attribute');
        parent::__construct();
    }
}
