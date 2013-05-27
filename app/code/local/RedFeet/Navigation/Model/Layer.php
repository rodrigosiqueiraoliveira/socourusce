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
	
class RedFeet_Navigation_Model_Layer extends GoMage_Navigation_Model_Layer {
	/**
	* Retrieve current category model
	* If no category found in registry, the root will be taken
	*
	* @return Mage_Catalog_Model_Category
	*/
	public function getCurrentCategory()
	{
		$category = $this->getData('current_category');
		if (is_null($category)) {
			if ($category = Mage::registry('current_category')) {
				$this->setData('current_category', $category);
			}
			else {
			    $category = Mage::getModel('catalog/category')->load($this->getCurrentStore()->getRootCategoryId());
			    $this->setData('current_category', $category);
			}
		}
		return $category;
	}
	
	public function getRootCategory()
	{
		// Id 37 referente à categoria que será carregada no menu lateral da página de Categorias;
		$category = Mage::getModel('catalog/category')->load(37);
		$this->setData('current_category', $category);
		return $category;
	}
	
	public function getBrandCategory()
	{
		$category = Mage::getModel('catalog/category')->load(7);
		$this->setData('current_category', $category);
		return $category;
	}
}