<?php
 /**
 * GoMage Advanced Navigation Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2011 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 3.0
 * @since        Class available since Release 1.0
 */

class RedFeet_Navigation_Block_Adminhtml_Catalog_Category_Tab_Attributes extends GoMage_Navigation_Block_Adminhtml_Catalog_Category_Tab_Attributes
{
    protected function _prepareForm() {
        
    	$group = $this->getGroup();    	
    	if ($group->getAttributeGroupName() == 'Advanced Navigation'){
    			        	        	        
	        $form = new Varien_Data_Form();
	        $form->setHtmlIdPrefix('group_' . $group->getId());
	        $form->setDataObject($this->getCategory());
		        	        
	        $attributes_lr = $this->left_right_column;
	        $attributes_pw_s = array();
	        $attributes_pw_m = array();
	        $attributes_pw_fl = array();
	        $attributes_pw_sl = array();
	        $attributes_pw_ob = array();
	        	        
	        if ($category = $this->getCategory()){
	        	$level = intval($category->getLevel())-2;
	        	 switch ($level){
	           		case 2:
						$attributes_pw_s = $this->plain_window_settings;
						$attributes_pw_s = array_diff($attributes_pw_s, array('navigation_pw_s_column', 'navigation_pw_s_img'));
						
						$attributes_pw_m = $this->menu_bar_settings;
						$attributes_pw_fl = $this->first_level_subcategory;
						$attributes_pw_sl = $this->second_level_subcategory;
						$attributes_pw_ob = $this->offer_block_settings;
	           			break;
	           		case 3: //first_level_subcategory
						$attributes_pw_s[] = 'navigation_pw_s_column';
						$attributes_pw_s[] = 'navigation_pw_s_img';
	           			break;
	           		case 4: //second_level_subcategory						
						$attributes_pw_s[] = 'navigation_pw_s_img';
	           			break;
	        	 }		
	        }
	        
	        if (count($attributes_pw_s)){
	        	$fieldset = $form->addFieldset('fieldset_group_' . $group->getId() . '_pw_s', array(
		            'legend'    => Mage::helper('gomage_navigation')->__('Plain Window Settings'),
		            'class'     => 'fieldset-wide',
		        ));
		        $attributes = $this->getNeededAttributes($attributes_pw_s);
		        $this->_setFieldset($attributes, $fieldset);
	        }
	        
    		if (count($attributes_pw_m)){
	        	$fieldset = $form->addFieldset('fieldset_group_' . $group->getId() . '_pw_m', array(
		            'legend'    => Mage::helper('gomage_navigation')->__('Menu Bar Settings'),
		            'class'     => 'fieldset-wide',
		        ));
		        $attributes = $this->getNeededAttributes($attributes_pw_m);
		        $this->_setFieldset($attributes, $fieldset);
	        }
	        
    		if (count($attributes_pw_fl)){
	        	$fieldset = $form->addFieldset('fieldset_group_' . $group->getId() . '_pw_fl', array(
		            'legend'    => Mage::helper('gomage_navigation')->__('First Level Subcategory Settings'),
		            'class'     => 'fieldset-wide',
		        ));
		        $attributes = $this->getNeededAttributes($attributes_pw_fl);
		        $this->_setFieldset($attributes, $fieldset);
	        }
	        
    		if (count($attributes_pw_sl)){
	        	$fieldset = $form->addFieldset('fieldset_group_' . $group->getId() . '_pw_sl', array(
		            'legend'    => Mage::helper('gomage_navigation')->__('Second Level Subcategory Settings'),
		            'class'     => 'fieldset-wide',
		        ));
		        $attributes = $this->getNeededAttributes($attributes_pw_sl);
		        $this->_setFieldset($attributes, $fieldset);
	        }
	        
    		if (count($attributes_pw_ob)){
	        	$fieldset = $form->addFieldset('fieldset_group_' . $group->getId() . '_pw_ob', array(
		            'legend'    => Mage::helper('gomage_navigation')->__('Offer Block Settings'),
		            'class'     => 'fieldset-wide',
		        ));
		        $attributes = $this->getNeededAttributes($attributes_pw_ob);
		        $this->_setFieldset($attributes, $fieldset);
	        }
	        
	        $fieldset = $form->addFieldset('fieldset_group_' . $group->getId() . '_lr', array(
	            'legend'    => Mage::helper('gomage_navigation')->__('Left / Right Column Navigation'),
	            'class'     => 'fieldset-wide',
	        ));
	        $attributes = $this->getNeededAttributes($attributes_lr);
	        $this->_setFieldset($attributes, $fieldset);
	        
	        
	        $form->addValues($this->getCategory()->getData());
        	Mage::dispatchEvent('adminhtml_catalog_category_edit_prepare_form', array('form'=>$form));
        	$form->setFieldNameSuffix('general');
        	$this->setForm($form);    		
        	
        	return $this;
    	
    	}else{                        
        	return parent::_prepareForm();
    	}
    }
}