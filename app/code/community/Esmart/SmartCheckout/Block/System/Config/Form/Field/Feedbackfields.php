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
 * @package     Esmart_SmartCheckout
 * @copyright   Copyright (c) 2012 Smart Commerce do Brasil. (http://www.e-smart.com.br)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
 
 /**
 * 
 *
 * @category    Esmart
 * @package     Esmart_SmartCheckout
 * @author      Tiago Sampaio <tiago.sampaio@e-smart.com.br>
 */
class Esmart_SmartCheckout_Block_System_Config_Form_Field_Feedbackfields extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    public function __construct()
    {
        $this->addColumn('value', array(
            'label' => Mage::helper('smartcheckout')->__('Label'),
            'style' => 'width:250px',
        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('smartcheckout')->__('Add label');
        parent::__construct();
    }
    
}