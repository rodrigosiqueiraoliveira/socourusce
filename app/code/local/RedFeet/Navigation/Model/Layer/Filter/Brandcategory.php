<?php
class RedFeet_Navigation_Model_Layer_Filter_Brandcategory extends RedFeet_Navigation_Model_Layer_Filter_Category
{
    
    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->_requestVar = 'brand_cat';
    }
    
    protected function _getItemsData()
    {
        $key = $this->getLayer()->getStateKey().'_SUBCATEGORIES';
        $data = $this->getLayer()->getAggregator()->getCacheData($key);
        if ($data === null) {
            $category   = $this->getLayer()->getBrandCategory();
            /** @var $categoty Mage_Catalog_Model_Categeory */
            //$categories = $categoty->getChildrenCategories();
            
            if (Mage::getStoreConfigFlag('gomage_navigation/category/show_allsubcats')){
                $cats_ids = array_diff($category->getAllChildren(true), array($category->getId()));
                if (count($cats_ids)) {
                    $cats_ids_str = implode(',', $cats_ids);
                } else {
                    $cats_ids_str = '0';   
                }
                
            }else {
                $cats_ids = array();                
                if ($category->getChildren())
                  $cats_ids = explode(',', $category->getChildren());
                                                                                                       
                $cats_ids_str = '0';
                
                foreach ($cats_ids as $_id)
                {                
                   $cats_ids_str .= ',' . $this->_addChildsCategory($_id,  explode(',',Mage::app()->getFrontController()->getRequest()->getParam($this->_requestVar)));
                }
                                                
                foreach(explode(',',Mage::app()->getFrontController()->getRequest()->getParam($this->_requestVar)) as $_id){                	 
                	$_cat = Mage::getModel('catalog/category')->load($_id);
                	$cats_ids_str .= ',' . $_cat->getId();
                	if ($_cat->getChildren()){
                		$cats_ids_str .= ',' . $_cat->getChildren();
                	}
                	$_parent_cat = Mage::getModel('catalog/category')->load($_cat->getParentId());                	                	
                	while($category->getLevel() < $_parent_cat->getLevel()){
                		$cats_ids_str .= ',' . $_parent_cat->getId(); 	
                		$_parent_cat = Mage::getModel('catalog/category')->load($_parent_cat->getParentId());
                	}                		
                }                                
            }
                                      
            $categories = Mage::getResourceModel('catalog/category_collection');
	        /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection */
	        $categories->addAttributeToSelect('url_key')
	            ->addAttributeToSelect('name')
	            ->addAttributeToSelect('all_children')
	            ->addAttributeToSelect('level')
	            ->addAttributeToSelect('is_anchor')
	            ->addAttributeToFilter('is_active', 1)
	            ->addAttributeToSelect('filter_image')
	            ->addIdFilter($cats_ids_str)	            
	            ->joinUrlRewrite()	            	            
	            ->load();
	            	            
	        $category_list_ids = array();
	        foreach ($categories as $category){
	        	$category_list_ids[$category->getId()] = $category;
	        }    
	        
         	$category = $this->getLayer()->getCurrentCategory();	        
	        foreach($category->getChildrenCategories() as $_category){
	        	$this->_renderCategoryList($_category, $category_list_ids);
	        }
	            	            
	            	                    
			$selected = array();
	        
	        if($value = Mage::app()->getFrontController()->getRequest()->getParam($this->_requestVar)){
	        
	        	$selected = array_merge($selected, explode(',', $value));
	        
	        }
			
            $data = array();
            
            $filter_mode = Mage::helper('gomage_navigation')->isGomageNavigation();
            
        	if(count($this->category_list) > 0){
        		
        		$category_count = $this->_getResource()->getCount($this, $this->category_list);
        		        		        		        		
	            foreach ($this->category_list as $category) {
	                
	                if ($category->getIsActive()) {
	                	
	                	if(in_array($category->getId(), $selected) && !$filter_mode){	                		
	                		continue;	                		
	                	}
	                	
	                	if (Mage::getStoreConfig('gomage_navigation/category/hide_empty') && !isset($category_count[$category->getId()])){	                	    
	                	    continue;	                	    
	                	} 
	                	
	                	if(in_array($category->getId(), $selected) && $filter_mode){
	                		
	                		$active = true;
	                		
	                		$value = $category->getId();
	                		
	                	}else{
	                		
	                		$active = false;
		                	
		                	if(!empty($selected)){
		                		
		                		$value = $this->_prepareRequestValue($selected, $category);		                		 		                	
		                		$value = implode(',', $value);
		                	
		                	}else{
		                		
		                		$value = $category->getId();
		                		
		                	}
		                	
	                	}
	                		                		                		                		                	
	                    $data[] = array(
	                        'label'     => Mage::helper('core')->htmlEscape($category->getName()),
	                        'value'     => $value,	                        
	                        'count'     => isset($category_count[$category->getId()]) ? $category_count[$category->getId()] : 0,
	                        'active'	=> $active,
	                        'image' 	=> $category->getFilterImage(),
	                        'level'     => $category->getLevel(),
	                        'haschild' 	=> $category->getChildren(),                          
	                    );
	                }
	            }
	            
	            
        	}
        	
        	$tags = $this->getLayer()->getStateTags();
        	
            $this->getLayer()->getAggregator()->saveCacheData($data, $key, $tags);
            
            
        }
        return $data;
    }
    
    /**
     * Get filter value for reset current filter state
     *
     * @return mixed
     */
    public function getUniqueValue($values = null)
    {
	$current_value = Mage::app()->getFrontController()->getRequest()->getParam($this->_requestVar);
        if($values && $current_value){
	    $current_value = explode(',', $current_value);
	    $new_values = array_diff(explode(',', $values), $current_value);
	    $retorno = array_shift($new_values);
	    if($retorno)
		return $retorno;
	    $new_values = array_intersect($current_value, explode(',', $values));
	    $retorno = array_shift($new_values);
	    if($retorno)
		return $retorno;
    	}
        return $values;
    }
    
    /**
     * Retrieve resource instance
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Attribute
     */
    protected function _getResource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = Mage::getResourceModel('catalog/layer_filter_brandcategory');
        }
        return $this->_resource;
    }
    
    /**
     * Get filter name
     *
     * @return string
     */
    public function getName()
    {
        return Mage::helper('catalog')->__('Brands');
    }
}
