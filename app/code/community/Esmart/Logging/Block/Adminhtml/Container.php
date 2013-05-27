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
 * General Logging container
 */
class Esmart_Logging_Block_Adminhtml_Container extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Curent event data storage
     *
     * @deprecated after 1.6.0.0
     * @var object
     */
    protected $_eventData = null;

    /**
     * Remove add button
     * Set block group and controller
     *
     */
    public function __construct()
    {
        $action = Mage::app()->getRequest()->getActionName();
        $this->_blockGroup = 'esmart_logging';
        $this->_controller = 'adminhtml_' . $action;

        parent::__construct();
        $this->_removeButton('add');
    }

    /**
     * Header text getter
     *
     * @return string
     */
    public function getHeaderText()
    {
        return Mage::helper('esmart_logging')->__($this->getData('header_text'));
    }

    /**
     * Get current event data
     *
     * @deprecated after 1.6.0.0
     * @return object Esmart_Logging_Model_Event
     */
    public function getEventData()
    {
        if (!$this->_eventData) {
            $this->_eventData = Mage::registry('current_event');
        }
        return $this->_eventData;
    }

    /**
     * Convert x_forwarded_ip to string
     *
     * @deprecated after 1.6.0.0
     * @return string
     */
    public function getEventXForwardedIp()
    {
        return long2ip($this->getEventData()->getXForwardedIp());
    }

    /**
     * Convert ip to string
     *
     * @deprecated after 1.6.0.0
     * @return string
     */
    public function getEventIp()
    {
        return long2ip($this->getEventData()->getIp());
    }

    /**
     * Replace /n => <br /> in event error_message
     *
     * @deprecated after 1.6.0.0
     * @return string
     */
    public function getEventError()
    {
        return nl2br($this->getEventData()->getErrorMessage());
    }
}
