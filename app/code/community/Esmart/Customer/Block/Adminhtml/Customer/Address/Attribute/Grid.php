<?php

class Esmart_Customer_Block_Adminhtml_Customer_Address_Attribute_Grid extends Mage_Eav_Block_Adminhtml_Attribute_Grid_Abstract
{
    /**
     * Initialize grid, set grid Id
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setDefaultSort('sort_order');
        $this->setId('customerAddressAttributeGrid');
    }

    /**
     * Prepare customer address attributes grid collection object
     *
     * @return Esmart_Customer_Block_Adminhtml_Customer_Address_Attribute_Grid
     */
    protected function _prepareCollection()
    {
        /* @var $collection Mage_Customer_Model_Entity_Address_Attribute_Collection */
        $collection = Mage::getResourceModel('customer/address_attribute_collection')
            ->addSystemHiddenFilter()
            ->addExcludeHiddenFrontendFilter();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare customer address attributes grid columns
     *
     * @return Esmart_Customer_Block_Adminhtml_Customer_Address_Attribute_Grid
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumn('is_visible', array(
            'header'    => Mage::helper('esmart_customer')->__('Visible on Frontend'),
            'sortable'  => true,
            'index'     => 'is_visible',
            'type'      => 'options',
            'options'   => array(
                '0' => Mage::helper('esmart_customer')->__('No'),
                '1' => Mage::helper('esmart_customer')->__('Yes'),
            ),
            'align'     => 'center',
        ));

        $this->addColumn('sort_order', array(
            'header'    => Mage::helper('esmart_customer')->__('Sort Order'),
            'sortable'  => true,
            'align'     => 'center',
            'index'     => 'sort_order'
        ));

        return $this;
    }
}
