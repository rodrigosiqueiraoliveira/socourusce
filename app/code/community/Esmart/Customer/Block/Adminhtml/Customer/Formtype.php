<?php

class Esmart_Customer_Block_Adminhtml_Customer_Formtype extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     *
     */
    public function __construct()
    {
        $this->_blockGroup = 'esmart_customer';
        $this->_controller = 'adminhtml_customer_formtype';
        $this->_headerText = Mage::helper('esmart_customer')->__('Manage Form Types');

        parent::__construct();

        $this->_updateButton('add', 'label', Mage::helper('esmart_customer')->__('New Form Type'));
    }
}
