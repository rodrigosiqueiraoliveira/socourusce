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
 * User column filter for Event Log grid
 */
class Esmart_Logging_Block_Adminhtml_Grid_Filter_User extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Select
{
    /**
     * Build filter options list
     *
     * @return array
     */
    public function _getOptions()
    {
        $options = array(array('value' => '', 'label' => Mage::helper('esmart_logging')->__('All Users')));
        foreach (Mage::getResourceModel('esmart_logging/event')->getUserNames() as $username) {
            $options[] = array('value' => $username, 'label' => $username);
        }
        return $options;
    }

    /**
     * Filter condition getter
     *
     * @string
     */
    public function getCondition()
    {
        return $this->getValue();
    }
}
