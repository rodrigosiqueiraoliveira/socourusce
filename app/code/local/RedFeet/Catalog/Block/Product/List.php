<?php
class RedFeet_Catalog_Block_Product_List extends Mage_Catalog_Block_Product_List
{

	public function getCategoryUrl($productId){
		$product = Mage::getModel('catalog/product')->load($productId);
		$categoryCollection = $product->getCategoryCollection();
		foreach($categoryCollection as $_category){
			if($_category->getParentId() == Mage::helper('catalog/category')->getGenericBrandCategoryId()){
				return Mage::getModel("catalog/category")->load($_category->getId())->getUrl();
			}
		}
	}

}
