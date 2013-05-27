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
 * Log and archive grids controller
 */
class Esmart_Logging_Adminhtml_LoggingController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Log page
     */
    public function indexAction()
    {
        $this->_title($this->__('System'))
             ->_title($this->__('Admin Actions Logs'))
             ->_title($this->__('Report'));

        $this->loadLayout();
        $this->_setActiveMenu('system/esmart_logging');
        $this->renderLayout();
    }

    /**
     * Log grid ajax action
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * View logging details
     */
    public function detailsAction()
    {
        $this->_title($this->__('System'))
             ->_title($this->__('Admin Actions Logs'))
             ->_title($this->__('Report'))
             ->_title($this->__('View Entry'));

        $eventId = $this->getRequest()->getParam('event_id');
        $model   = Mage::getModel('esmart_logging/event')
            ->load($eventId);
        if (!$model->getId()) {
            $this->_redirect('*/*/');
            return;
        }
        Mage::register('current_event', $model);

        $this->loadLayout();
        $this->_setActiveMenu('system/esmart_logging');
        $this->renderLayout();
    }

    /**
     * Export log to CSV
     */
    public function exportCsvAction()
    {
        $this->_prepareDownloadResponse('log.csv',
            $this->getLayout()->createBlock('esmart_logging/adminhtml_index_grid')->getCsvFile()
        );
    }

    /**
     * Export log to MSXML
     */
    public function exportXmlAction()
    {
        $this->_prepareDownloadResponse('log.xml',
            $this->getLayout()->createBlock('esmart_logging/adminhtml_index_grid')->getExcelFile()
        );
    }

    /**
     * Archive page
     */
    public function archiveAction()
    {
        $this->_title($this->__('System'))
             ->_title($this->__('Admin Actions Logs'))
             ->_title($this->__('Archive'));

        $this->loadLayout();
        $this->_setActiveMenu('system/esmart_logging');
        $this->renderLayout();
    }

    /**
     * Archive grid ajax action
     */
    public function archiveGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Download archive file
     */
    public function downloadAction()
    {
        $archive = Mage::getModel('esmart_logging/archive')->loadByBaseName(
            $this->getRequest()->getParam('basename')
        );
        if ($archive->getFilename()) {
            $this->_prepareDownloadResponse($archive->getBaseName(), $archive->getContents(), $archive->getMimeType());
        }
    }

    /**
     * permissions checker
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'archive':
            case 'download':
            case 'archiveGrid':
                return Mage::getSingleton('admin/session')->isAllowed('admin/esmart_logging/esmart_logging/backups');
                break;
            case 'grid':
            case 'exportCsv':
            case 'exportXml':
            case 'details':
            case 'index':
                return Mage::getSingleton('admin/session')->isAllowed('admin/esmart_logging/esmart_logging/events');
                break;
        }

    }
}
