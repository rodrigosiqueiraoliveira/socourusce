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
 * Logging configuration model
 *
 * Merges logging.xml files and provides access to nodes and labels
 */
class Esmart_Logging_Model_Config
{
    /**
     * logging.xml merged config
     *
     * @var Varien_Simplexml_Config
     */
    protected $_xmlConfig;

    /**
     * Translated and sorted labels
     *
     * @var array
     */
    protected $_labels = array();

    protected $_systemConfigValues = null;

    /**
     * Load config from cache or merged from logging.xml files
     */
    public function __construct()
    {
        $configXml = Mage::app()->loadCache('esmart_logging_config');
        if ($configXml) {
            $this->_xmlConfig = new Varien_Simplexml_Config($configXml);
        } else {
            $config = new Varien_Simplexml_Config;
            $config->loadString('<?xml version="1.0"?><logging></logging>');
            Mage::getConfig()->loadModulesConfiguration('logging.xml', $config);
            $this->_xmlConfig = $config;
            if (Mage::app()->useCache('config')) {
                Mage::app()->saveCache($config->getXmlString(), 'esmart_logging_config',
                    array(Mage_Core_Model_Config::CACHE_TAG));
            }
        }
    }

    /**
     * Current system config values getter
     *
     * @return array
     */
    public function getSystemConfigValues()
    {
        if (null === $this->_systemConfigValues) {
            $this->_systemConfigValues = Mage::getStoreConfig('esmart_logging/esmart_logging/actions');
            if (null === $this->_systemConfigValues) {
                $this->_systemConfigValues = array();
                foreach ($this->getLabels() as $key => $label) {
                    $this->_systemConfigValues[$key] = 1;
                }
            }
            else {
                $this->_systemConfigValues = unserialize($this->_systemConfigValues);
            }
        }
        return $this->_systemConfigValues;
    }

    /**
     * Check whether specified full action name or event group should be logged
     *
     * @param string $reference
     * @param bool $isGroup
     */
    public function isActive($reference, $isGroup = false)
    {
        if (!$isGroup) {
            foreach ($this->_getNodesByFullActionName($reference) as $action) {
                $reference = $action->getParent()->getParent()->getName();
                $isGroup   = true;
                break;
            }
        }

        if ($isGroup) {
            $this->getSystemConfigValues();
            return isset($this->_systemConfigValues[$reference]);
        }

        return false;
    }

    /**
     * Get configuration node for specified full action name
     *
     * @param string $fullActionName
     * @return Varien_Simplexml_Element|false
     */
    public function getNode($fullActionName)
    {
        foreach ($this->_getNodesByFullActionName($fullActionName) as $actionConfig) {
            return $actionConfig;
        }
        return false;
    }

    /**
     * Get all labels translated and sorted ASC
     *
     * @return array
     */
    public function getLabels()
    {
        if (!$this->_labels) {
            foreach ($this->_xmlConfig->getXpath('/logging/*/label') as $labelNode) {
                $helperName = $labelNode->getParent()->getAttribute('module');
                if (!$helperName) {
                    $helperName = 'esmart_logging';
                }
                $this->_labels[$labelNode->getParent()->getName()] = Mage::helper($helperName)->__((string)$labelNode);
            }
            asort($this->_labels);
        }
        return $this->_labels;
    }

    /**
     * Get logging action translated label
     *
     * @param string $action
     * @return string
     */
    public function getActionLabel($action)
    {
        $xpath = 'actions/' . $action . '/label';
        $actionLabelNode = $this->_xmlConfig->getNode($xpath);

        if (!$actionLabelNode) {
            return $action;
        }

        $label = (string)$actionLabelNode;
        $module = $actionLabelNode->getParent()->getAttribute('module');
        $helper = $module ? Mage::helper($module) : Mage::helper('esmart_logging');

        return $helper->__($label);
    }

    /**
     * Lookup configuration nodes by full action name
     *
     * @param string $fullActionName
     * @return array
     */
    protected function _getNodesByFullActionName($fullActionName)
    {
        if (!$fullActionName) {
            return array();
        }
        $actionNodes = $this->_xmlConfig->getXpath("/logging/*/actions/{$fullActionName}[1]");
        if ($actionNodes) {
            return $actionNodes;
        }
        return array();
    }
}
