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
 * Custom handlers for models logging
 *
 */
class Esmart_Logging_Model_Handler_Models
{
    /**
     * SaveAfter handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return object Esmart_Logging_Event_Changes or false if model wasn't modified
     */
    public function modelSaveAfter($model, $processor)
    {
        $processor->collectId($model);
        $changes = Mage::getModel('esmart_logging/event_changes')
            ->setOriginalData($model->getOrigData())
            ->setResultData($model->getData());
        return $changes;
    }

    /**
     * Delete after handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return object Esmart_Logging_Event_Changes
     */
    public function modelDeleteAfter($model, $processor)
    {
        $processor->collectId($model);
        $changes = Mage::getModel('esmart_logging/event_changes')
            ->setOriginalData($model->getOrigData())
            ->setResultData(null);
        return $changes;
    }

    /**
     * MassUpdate after handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return object Esmart_Logging_Event_Changes
     */
    public function modelMassUpdateAfter($model, $processor)
    {
        return $this->modelSaveAfter($model, $processor);
    }

    /**
     * MassDelete after handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return object Esmart_Logging_Event_Changes
     */
    public function modelMassDeleteAfter($model, $processor)
    {
        return $this->modelSaveAfter($model, $processor);
    }

    /**
     * Load after handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return Esmart_Logging_Model_Event_Changes
     */
    public function modelViewAfter($model, $processor)
    {
        $processor->collectId($model);
        return true;
    }
}
