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
 * Logging event model
 */
class Esmart_Logging_Model_Event extends Mage_Core_Model_Abstract
{
    const RESULT_SUCCESS = 'success';
    const RESULT_FAILURE = 'failure';


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_init('esmart_logging/event');
    }

    /**
     * Set some data automatically before saving model
     *
     * @return Esmart_Logging_Model_Event
     */
    protected function _beforeSave()
    {
        if (!$this->getId()) {
            $this->setStatus($this->getIsSuccess() ? self::RESULT_SUCCESS : self::RESULT_FAILURE);
            if (!$this->getUser() && $id = $this->getUserId()) {
                $this->setUser(Mage::getModel('admin/user')->load($id)->getUserName());
            }
            if (!$this->hasTime()) {
                $this->setTime(time());
            }
        }
        /**
         * Prepare short details data
         */
        $info = array();
        $info['general'] = $this->getInfo();
        if ($this->getAdditionalInfo()) {
            $info['additional'] = $this->getAdditionalInfo();
        }
        $this->setInfo(serialize($info));
        return parent::_beforeSave();
    }

    /**
     * Define if current event has event changes
     *
     * @return bool
     */
    public function hasChanges()
    {
        if ($this->getId()) {
            return (bool)$this->getResource()->getEventChangeIds($this->getId());
        }
        return false;
    }
}
