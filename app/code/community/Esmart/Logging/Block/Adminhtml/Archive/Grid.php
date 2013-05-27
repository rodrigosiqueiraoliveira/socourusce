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
class Esmart_Logging_Block_Adminhtml_Archive_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize default sorting and html ID
     */
    protected function _construct()
    {
        $this->setId('loggingArchiveGrid');
        $this->setDefaultSort('basename');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare grid collection
     *
     * @return Esmart_Logging_Block_Events_Archive_Grid
     */
    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getSingleton('esmart_logging/archive_collection'));
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return Esmart_Logging_Block_Events_Archive_Grid
     */
    protected function _prepareColumns()
    {
        $downloadUrl = $this->getUrl('*/*/download');

        $this->addColumn('download', array(
            'header'    => Mage::helper('esmart_logging')->__('Archive File'),
            'format'    => '<a href="' . $downloadUrl .'basename/$basename/">$basename</a>',
            'index'     => 'basename',
        ));

        $this->addColumn('date', array(
            'header'    => Mage::helper('esmart_logging')->__('Date'),
            'type'      => 'date',
            'index'     => 'time',
            'filter'    => 'esmart_logging/adminhtml_archive_grid_filter_date'
        ));

        return parent::_prepareColumns();
    }

    /**
     * Row click callback URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/archiveGrid', array('_current' => true));
    }
}
