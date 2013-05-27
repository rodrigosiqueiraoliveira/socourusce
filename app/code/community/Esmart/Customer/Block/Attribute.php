<?php
class Esmart_Customer_Block_Attribute extends Mage_Core_Block_Abstract
{

	const ENTITY_TYPE_CUSTOMER 			= 'customer/customer';
	const ENTITY_TYPE_CUSTOMER_ADDRESS 	= 'customer/address';

	protected $_renderer = null;
	protected $_entityTypeClass = null;
	protected $_entity = null;


	/**
	 * Get customer attribute block content in html
	 * 
	 * @param string $attributeCode
	 * @param string $usedInForm (billing || shipping)
	 * 
	 * @return html
	 * 
	 * @author Tiago Sampaio
	 */
	public function getCustomerAttributeHtml($attributeCode = null, $usedInForm = null)
	{
		if(!$attributeCode) {
			return false;
		}

		$block = $this->getCustomerAttribute($attributeCode, $usedInForm);
		if($block) {
			return $block->toHtml();
		}
	}


	/**
	 * Get customer attribute block
	 * 
	 * @param string $attributeCode
	 * @param string $usedInForm (billing || shipping)
	 * 
	 * @return Esmart_Customer_Block_Form_Renderer_Abstract
	 * 
	 * @author Tiago Sampaio
	 */
	public function getCustomerAttribute($attributeCode = null, $usedInForm = null)
	{
		if(!$attributeCode) {
			return false;
		}

		$this->_setEntityTypeClass(self::ENTITY_TYPE_CUSTOMER);
		$attribute = $this->_getAttribute('customer', $attributeCode);
		$block = null;
		if($attribute instanceof Mage_Eav_Model_Attribute) {
			$block = $this->_getAttributeBlock($attribute, $usedInForm);
		}

		return $block;
	}
	

	/**
	 * Get customer address attribute block content in html
	 * 
	 * @param string $attributeCode
	 * @param string $usedInForm (billing || shipping)
	 * 
	 * @return html
	 * 
	 * @author Tiago Sampaio
	 */
	public function getCustomerAddressAttributeHtml($attributeCode = null, $usedInForm = null)
	{
		if(!$attributeCode) {
			return false;
		}

		$block = $this->getCustomerAddressAttribute($attributeCode, $usedInForm);
		if($block) {
			return $block->toHtml();
		}
	}


	/**
	 * Get customer address attribute block
	 * 
	 * @param string $attributeCode
	 * @param string $usedInForm (billing || shipping)
	 * 
	 * @return Esmart_Customer_Block_Form_Renderer_Abstract
	 * 
	 * @author Tiago Sampaio
	 */
	public function getCustomerAddressAttribute($attributeCode = null, $usedInForm = null)
	{
		if(!$attributeCode) {
			return false;
		}

		$this->_setEntityTypeClass(self::ENTITY_TYPE_CUSTOMER_ADDRESS);
		$attribute = $this->_getAttribute('customer_address', $attributeCode);
		$block = null;
		if($attribute instanceof Mage_Eav_Model_Attribute) {
			$block = $this->_getAttributeBlock($attribute, $usedInForm);
		}

		return $block;
	}


	/**
	 * Get the attribute block according to it's type (text, textarea, select, etc)
	 * 
	 * @param Mage_Eav_Model_Attribute $attribute
	 * 
	 * @return Esmart_Customer_Block_Form_Renderer_Abstract
	 * 
	 * @author Tiago Sampaio
	 */
	protected function _getAttributeBlock(Mage_Eav_Model_Attribute $attribute, $usedInForm = null)
	{
		$this->_getRenderer()->setAttributeObject($attribute);
		$this->_getRenderer()->setEntity($this->_getEntity());

		if ($usedInForm == 'billing' || $usedInForm == 'shipping') {
			$usedInForm = strtolower($usedInForm);

			$this->_getRenderer()->setFieldNameFormat($usedInForm.'[%s]');
	    	$this->_getRenderer()->setFieldIdFormat($usedInForm.':%s');
		}

		return $this->_getRenderer();
	}


	/**
	 * Get the entity model class according to attribute entity type
	 * 
	 * @return Mage_Customer_Model_Customer || Mage_Customer_Model_Customer_Address
	 * 
	 * @author Tiago Sampaio
	 */
	protected function _getEntity()
	{
		if(is_null($this->_entity) && $this->_getEntityTypeClass()) {
			$this->_entity = Mage::getModel($this->_getEntityTypeClass());
		}

		return $this->_entity;
	}


	/**
	 * Get the entity model class according to attribute entity type
	 * 
	 * @param Mage_Customer_Model_Customer || Mage_Customer_Model_Customer_Address
	 * 
	 * @author Tiago Sampaio
	 */
	public function setEntity(Mage_Customer_Model_uiyAbstract $entity)
	{
		if(is_null($this->_entity) && $this->_getEntityTypeClass()) {
			$this->_entity = Mage::getModel($this->_getEntityTypeClass());
		}

		return $this->_entity;
	}


	/**
	 * Sets the entity type class for customer or customer address
	 * 
	 * @param string $entityType
	 * 
	 * @return Esmart_Customer_Block_Attribute
	 * 
	 * @author Tiago Sampaio
	 */
	protected function _setEntityTypeClass($entityType = null)
	{
		if(!is_null($entityType)) {
			$this->_entityTypeClass = $entityType;
		}

		return $this;
	}


	/**
	 * Returns the entity type class
	 * 
	 * @return string
	 * 
	 * @author Tiago Sampaio
	 */
	protected function _getEntityTypeClass()
	{
		return $this->_entityTypeClass;
	}


	/**
	 * Gets the attribute object
	 * 
	 * @param string $entityType
	 * @param string $attributeCode
	 * 
	 * @return Mage_Eav_Model_Attribute || false
	 * 
	 * @author Tiago Sampaio
	 */
	protected function _getAttribute($entityType = null, $attributeCode = null)
	{
		$eav = Mage::getModel('eav/config');
        $attribute = $eav->getAttribute($entityType, $attributeCode);

        if($attribute->getAttributeId()) {
        	$this->_setRenderer($attribute);
            return $attribute;
        }

        return false;
	}


	/**
	 * Sets the renderer according to attribute input type
	 * 
	 * @param Mage_Eav_Model_Attribute $attribute
	 * 
	 * @return Esmart_Customer_Block_Attribute
	 * 
	 * @author Tiago Sampaio
	 */
	protected function _setRenderer(Mage_Eav_Model_Attribute $attribute)
	{
		if($attribute->getAttributeId()) {
			$frontendInput = $attribute->getFrontendInput();
			$blockClass = 'esmart_customer/form_renderer_'.$frontendInput;
			$this->_renderer = $this->getLayout()->createBlock($blockClass);
		}

		return $this;
	}


	/**
	 * Gets the renderer
	 * 
	 * @return Esmart_Customer_Block_Form_Renderer_Abstract
	 * 
	 * @author Tiago Sampaio
	 */
	protected function _getRenderer()
	{
		return $this->_renderer;
	}

}