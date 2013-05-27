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
 * Log grid container
 */
class Esmart_Logging_Block_Adminhtml_Details extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Store curent event
     *
     * @var Esmart_Logging_Model_Event
     */
    protected $_currentEevent = null;

    /**
     * Store current event user
     *
     * @var Mage_Admin_Model_User
     */
    protected $_eventUser = null;

    /**
     * Add back button
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_addButton('back', array(
            'label'   => Mage::helper('esmart_logging')->__('Back'),
            'onclick' => "setLocation('" . Mage::getSingleton('adminhtml/url')->getUrl('*/*/'). "')",
            'class'   => 'back'
        ));
    }

    /**
     * Header text getter
     *
     * @return string
     */
    public function getHeaderText()
    {
        if ($this->getCurrentEvent()) {
            return Mage::helper('esmart_logging')->__('Log Entry #%d', $this->getCurrentEvent()->getId());
        }
        return Mage::helper('esmart_logging')->__('Log Entry Details');
    }

    /**
     * Get current event
     *
     * @return Esmart_Logging_Model_Event|null
     */
    public function getCurrentEvent()
    {
        if (null === $this->_currentEevent) {
            $this->_currentEevent = Mage::registry('current_event');
        }
        return $this->_currentEevent;
    }

    /**
     * Convert x_forwarded_ip to string
     *
     * @return string|bool
     */
    public function getEventXForwardedIp()
    {
        if ($this->getCurrentEvent()) {
            $xForwarderFor = long2ip($this->getCurrentEvent()->getXForwardedIp());
            if ($xForwarderFor && $xForwarderFor != '0.0.0.0') {
                return $xForwarderFor;
            }
        }
        return false;
    }

    /**
     * Convert ip to string
     *
     * @return string|bool
     */
    public function getEventIp()
    {
        if ($this->getCurrentEvent()) {
            return long2ip($this->getCurrentEvent()->getIp());
        }
        return false;
    }

    /**
     * Replace /n => <br /> in event error_message
     *
     * @return string|bool
     */
    public function getEventError()
    {
        if ($this->getCurrentEvent()) {
            return nl2br($this->getCurrentEvent()->getErrorMessage());
        }
        return false;
    }

    /**
     * Get current event user
     *
     * @return Mage_Admin_Model_User|null
     */
    public function getEventUser()
    {
        if (null === $this->_eventUser) {
            $this->_eventUser = Mage::getModel('admin/user')->load($this->getUserId());
        }
        return $this->_eventUser;
    }

    /**
     * Unserialize and retrive event info
     *
     * @return string
     */
    public function getEventInfo()
    {
        $info = null;
        $data = $this->getCurrentEvent()->getInfo();
        try {
            $info = unserialize($data);
        } catch (Exception $e) {
            $info = $data;
        }
        return $info;
    }
}
