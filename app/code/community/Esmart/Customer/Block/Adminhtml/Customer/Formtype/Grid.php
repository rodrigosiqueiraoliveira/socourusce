<?php

class Esmart_Customer_Block_Adminhtml_Customer_Formtype_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize Grid Block
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setSaveParametersInSession(true);
        $this->setDefaultSort('code');
        $this->setDefaultDir('asc');
    }

    /**
     * Prepare grid collection object
     *
     * @return Enterprice_Customer_Block_Adminhtml_Customer_Formtype_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('eav/form_type')
            ->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare Grid columns
     *
     * @return Enterprice_Customer_Block_Adminhtml_Customer_Formtype_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('code', array(
            'header'    => Mage::helper('esmart_customer')->__('Form Type Code'),
            'index'     => 'code',
        ));

        $this->addColumn('label', array(
            'header'    => Mage::helper('esmart_customer')->__('Label'),
            'index'     => 'label',
        ));

        $this->addColumn('store_id', array(
            'header'    => Mage::helper('esmart_customer')->__('Store View'),
            'index'     => 'store_id',
            'type'      => 'store'
        ));

        $design = Mage::getModel('core/design_source_design')
            ->setIsFullLabel(true)->getAllOptions(false);
        array_unshift($design, array(
            'value' => 'all',
            'label' => Mage::helper('esmart_customer')->__('All Themes')
        ));
        $this->addColumn('theme', array(
            'header'     => Mage::helper('esmart_customer')->__('For Theme'),
            'type'       => 'theme',
            'index'      => 'theme',
            'options'    => $design,
            'with_empty' => true,
            'default'    => Mage::helper('esmart_customer')->__('All Themes')
        ));

        $this->addColumn('is_system', array(
            'header'    => Mage::helper('esmart_customer')->__('System'),
            'index'     => 'is_system',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('esmart_customer')->__('No'),
                1 => Mage::helper('esmart_customer')->__('Yes'),
            )
        ));

        return parent::_prepareColumns();
    }

    /**
     * Retrieve row click URL
     *
     * @param Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('type_id' => $row->getId()));
    }
}
