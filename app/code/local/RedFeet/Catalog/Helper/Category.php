<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2011 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * Catalog category helper
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class RedFeet_Catalog_Helper_Category extends Mage_Catalog_Helper_Category
{
    
    private $genericBrandCategoryId = 7;

    public function getBrandImgUrl($productId){
	$catId = $this->getBrandCategoryId($productId);
	$brandCategoryUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."/catalog/category/".Mage::getModel('catalog/category')->load($catId)->getImage();
	return (Mage::getModel('catalog/category')->load($catId)->getImage() == '' ? '' : $brandCategoryUrl);
    }

    public function getGenericBrandCategoryId(){
	return (empty($this->genericBrandCategoryId)) ? '' : $this->genericBrandCategoryId;
    }
    
    private function getBrandCategoryId($productId){
	if($productId instanceof Mage_Catalog_Model_Product) $productId = $productId->getId();
	$product = Mage::getModel('catalog/product')->load($productId);
	$categoryCollection = $product->getCategoryCollection();
	
	// Descobrindo os Ids dos filhos diretos da Categoria Marcas, pois não queremos as imagens deles;
	$brandCatChildrensIds = explode(',', Mage::getModel('catalog/category')->load(7)->getChildren());
	foreach($categoryCollection as $_category){
	    // Se a categoria atual for algum dos filhos diretos da Categoria Marcas,
	    // passa para o próximo
	    if(in_array($_category->getId(), $brandCatChildrensIds)) continue;
	    if(in_array($this->getGenericBrandCategoryId(), $_category->getParentIds())){
		return $_category->getId();
	    }
	}
	return false;
    }
    
    public function getBrandCategory($productId){
	return Mage::getModel('catalog/category')->load($this->getBrandCategoryId($productId));
    }
    
    public function getFirstCategoryUrl()
    {
        if (!Mage::registry('first_cat_url')) {
            $catPath = preg_replace('#^/#i', '', Mage::helper('catalog/category')->getCategoryUrlPath($this->getChildById(35)->getUrlPath(), false, Mage::app()->getStore()->getId()));
            if(substr($catPath, -5) != '.html') $catPath .= '.html';
            Mage::register('first_cat_url', $catPath);
            return $catPath;
        }
        return Mage::registry('first_cat_url');
    }
    
    public function getRootFirstChild()
    {
	if (!Mage::registry('first_cat')) {
            Mage::register('first_cat', Mage::getModel('catalog/category')->load(Mage::app()->getStore()->getRootCategoryId()));
        }
        return Mage::registry('first_cat');
    }
    
    public function getChildById($id = null)
    {
	if(is_null($id)) $id = Mage::app()->getStore()->getRootCategoryId();
        return Mage::getModel('catalog/category')->load($id);
    }
}
