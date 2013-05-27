<?php
class RedFeet_Navigation_Block_Layer_View extends GoMage_Navigation_Block_Layer_View {
	
	/**
	* Category Block Name
	*
	* @var string
	*/
	protected $_brandCategoryBlockName;

	/**
	* Initialize blocks names
	*/
	protected function _initBlocks()
	{
	    $this->_brandCategoryBlockName = 'catalog/layer_filter_brandcategory';
	    parent::_initBlocks();
	}
	
	/**
	* Prepare child blocks
	*
	* @return Mage_Catalog_Block_Layer_View
	*/
	protected function _prepareLayout()
	{
		$brandCategoryBlock = $this->getLayout()->createBlock($this->_brandCategoryBlockName)
		    ->setLayer($this->getLayer())
		    ->init();
	
		$this->setChild('brand_category_filter', $brandCategoryBlock);
	
		return parent::_prepareLayout();
	}
	
	/**
	* Get all layer filters
	*
	* @return array
	*/
	public function getFilters()
	{
		$filters = array();
		if ($categoryFilter = $this->_getCategoryFilter()) {
		    $filters[] = $categoryFilter;
		}
		
		if ($brandCategoryFilter = $this->_getBrandCategoryFilter()) {
		    $filters[] = $brandCategoryFilter;
		}
	
		$filterableAttributes = $this->_getFilterableAttributes();
		foreach ($filterableAttributes as $attribute) {
		    $filters[] = $this->getChild($attribute->getAttributeCode() . '_filter');
		}
	
		return $filters;
	}
	
	/**
	* Get category filter block
	*
	* @return Mage_Catalog_Block_Layer_Filter_Category
	*/
	protected function _getBrandCategoryFilter()
	{
	    return $this->getChild('brand_category_filter');
	}
}