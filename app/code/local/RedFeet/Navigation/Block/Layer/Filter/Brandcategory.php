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
	
class RedFeet_Navigation_Block_Layer_Filter_Brandcategory extends GoMage_Navigation_Block_Layer_Filter_Category
{
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'catalog/layer_filter_brandcategory';
        
        if(Mage::helper('gomage_navigation')->isGomageNavigation() &&
           Mage::getStoreConfigFlag('gomage_navigation/category/active')){
        	
        	$type = Mage::getStoreConfig('gomage_navigation/category/filter_type');
        	
        	switch($type): 
        	
	        	default:
	        	
	        		$this->_template = ('gomage/navigation/layer/filter/category/brand_categories.phtml');
	        	
	        	break;
	        	
	        	case(GoMage_Navigation_Model_Layer::FILTER_TYPE_IMAGE):
	        	
	        		$this->_template = ('gomage/navigation/layer/filter/image.phtml');
	        	
	        	break;
	        	
	        	case(GoMage_Navigation_Model_Layer::FILTER_TYPE_DROPDOWN):
	        	
	        		$this->_template = ('gomage/navigation/layer/filter/dropdown.phtml');
	        	
	        	break;
        	
        	endswitch;
        	
        }
    }
}
