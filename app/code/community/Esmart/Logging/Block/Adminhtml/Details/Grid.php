<?php
/**
 * Smart Commerce do Brasil
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@e-smart.com.br so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.e-smart.com.br for more information.
 *
 * @category    Esmart
 * @package     Esmart_Logging
 * @author      Tiago Sampaio <tiago.sampaio@e-smart.com.br>
 * @copyright   Copyright (c) 2012 Smart Commerce do Brasil. (http://www.e-smart.com.br)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */


/**
 * Admin Actions Log Archive grid
 *
 */
class Esmart_Logging_Block_Adminhtml_Details_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize default sorting and html ID
     */
    protected function _construct()
    {
        $this->setId('loggingDetailsGrid');
        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);
    }

    /**
     * Prepare grid collection
     *
     * @return Esmart_Logging_Block_Events_Archive_Grid
     */
    protected function _prepareCollection()
    {
        $event = Mage::registry('current_event');
        $collection = Mage::getResourceModel('esmart_logging/event_changes_collection')
            ->addFieldToFilter('event_id', $event->getId());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return Esmart_Logging_Block_Events_Archive_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('source_name', array(
            'header'    => Mage::helper('esmart_logging')->__('Source Data'),
            'sortable'  => false,
            'renderer'  => 'esmart_logging/adminhtml_details_renderer_sourcename',
            'index'     => 'source_name',
            'width'     => 1
        ));

        $this->addColumn('original_data', array(
            'header'    => Mage::helper('esmart_logging')->__('Value Before Change'),
            'sortable'  => false,
            'renderer'  => 'esmart_logging/adminhtml_details_renderer_diff',
            'index'     => 'original_data'
        ));

        $this->addColumn('result_data', array(
            'header'    => Mage::helper('esmart_logging')->__('Value After Change'),
            'sortable'  => false,
            'renderer'  => 'esmart_logging/adminhtml_details_renderer_diff',
            'index'     => 'result_data'
        ));

        return parent::_prepareColumns();
    }

    /**
     * Grid URL
     *
     * @param Mage_Catalog_Model_Product|Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return;
    }
}
