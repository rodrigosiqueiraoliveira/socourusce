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
class Esmart_Logging_Block_Adminhtml_Log extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Header text getter
     *
     * @return string
     */
    public function getHeaderText()
    {
        return Mage::helper('esmart_logging')->__('Admin Actions Log');
    }

    /**
     * Grid contents getter
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml();
    }
}