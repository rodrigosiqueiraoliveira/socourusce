<?php
class Esmart_DefaultSetup_Model_Attributes extends Mage_Core_Model_Abstract
{


    /**
     * Prepares customer attributes to be created
     * 
     * @author Tiago Sampaio
     */
	public function setupCustomerAttributes()
	{
        $dataArray = array();
        $forms = array('checkout_register', 'customer_account_create', 'customer_account_edit', 'adminhtml_checkout', 'adminhtml_customer');

        $dataArray[] = array(
        	'attribute_code' => 'rg',
        	'frontend_input' => 'text', /* text || textarea || multiline || date || select || multiselect || boolean || file || image */
        	/*'default_value_text' => '',*/
            'default_value' => '',
        	'input_validation' => '', /* alphanumeric || numeric || alpha || url || email */
        	'min_text_length' => '',
        	'max_text_length' => '20',
        	'is_required' => 0,
        	'is_visible' => 1,
        	'sort_order' => 10,
        	'used_in_forms' => $forms,
        	'frontend_label' => 'RG',
        );
        
        $dataArray[] = array(
        	'attribute_code' => 'cpf_cnpj',
        	'frontend_input' => 'text', /* text || textarea || multiline || date || select || multiselect || boolean || file || image */
        	/*'default_value_text' => '',*/
            'default_value' => '',
        	'input_validation' => '', /* alphanumeric || numeric || alpha || url || email */
        	'min_text_length' => '',
        	'max_text_length' => '20',
        	'is_required' => 0,
        	'is_visible' => 1,
        	'sort_order' => 20,
        	'used_in_forms' => $forms,
        	'frontend_label' => 'CPF/CNPJ',
        );

        $dataArray[] = array(
        	'attribute_code' => 'ie',
        	'frontend_input' => 'text',
        	/*'default_value_text' => '',*/
            'default_value' => '',
        	'input_validation' => '',
        	'min_text_length' => '',
        	'max_text_length' => '50',
        	'is_required' => 0,
        	'is_visible' => 1,
        	'sort_order' => 30,
        	'used_in_forms' => $forms,
        	'frontend_label' => 'Inscrição Estadual',
        );

        foreach($dataArray as $data)
        {
            $this->_createAttribute('customer', $data);
        }
	}


    /**
     * Prepares customer address attributes to be created
     * 
     * @author Tiago Sampaio
     */
    public function setupCustomerAddressAttributes()
    {
        $dataArray = array();
        $forms = array('customer_register_address', 'customer_address_edit', 'adminhtml_customer_address');

        $dataArray[] = array(
            'attribute_code' => 'address_number',
            'frontend_input' => 'text', /* text || textarea || multiline || date || select || multiselect || boolean || file || image */
            /*'default_value_text' => '',*/
            'default_value' => '',
            'input_validation' => '', /* alphanumeric || numeric || alpha || url || email */
            'min_text_length' => '1',
            'max_text_length' => '6',
            'is_required' => 0,
            'is_visible' => 1,
            'sort_order' => 10,
            'used_in_forms' => $forms,
            'frontend_label' => 'Número',
        );

        $dataArray[] = array(
            'attribute_code' => 'address_neighborhood',
            'frontend_input' => 'text', /* text || textarea || multiline || date || select || multiselect || boolean || file || image */
            /*'default_value_text' => '',*/
            'default_value' => '',
            'input_validation' => '', /* alphanumeric || numeric || alpha || url || email */
            'min_text_length' => '',
            'max_text_length' => '255',
            'is_required' => 0,
            'is_visible' => 1,
            'sort_order' => 20,
            'used_in_forms' => $forms,
            'frontend_label' => 'Bairro',
        );

        $dataArray[] = array(
            'attribute_code' => 'address_complement',
            'frontend_input' => 'text',
            /*'default_value_text' => '',*/
            'default_value' => '',
            'input_validation' => '',
            'min_text_length' => '',
            'max_text_length' => '255',
            'is_required' => 0,
            'is_visible' => 1,
            'sort_order' => 30,
            'used_in_forms' => $forms,
            'frontend_label' => 'Complemento',
        );

        $dataArray[] = array(
            'attribute_code' => 'cellphone',
            'frontend_input' => 'text',
            /*'default_value_text' => '',*/
            'default_value' => '',
            'input_validation' => '',
            'min_text_length' => '',
            'max_text_length' => '15',
            'is_required' => 0,
            'is_visible' => 1,
            'sort_order' => 40,
            'used_in_forms' => $forms,
            'frontend_label' => 'Celular',
        );

        foreach($dataArray as $data)
        {
            $this->_createAttribute('customer_address', $data);
        }
    }


    /**
     * Creates the attributes for any entity type
     * 
     * @param string $entityTypeCode
     * @param array $data
     * 
     * @author Tiago Sampaio
     */
    protected function _createAttribute($entityTypeCode, $data = array())
    {
        if(!$this->_canCreateAttribute($entityTypeCode, $data['attribute_code'])) {
            return false;
        }

        $helper = Mage::helper('esmart_customer');

        $attribute = Mage::getModel('customer/attribute');
        $attribute->setWebsite(Mage::app()->getStore()->getWebsiteId());
        $entityType = Mage::getSingleton('eav/config')->getEntityType($entityTypeCode);

        $data['backend_model']      = $helper->getAttributeBackendModelByInputType($data['frontend_input']);
        $data['source_model']       = $helper->getAttributeSourceModelByInputType($data['frontend_input']);
        $data['backend_type']       = $helper->getAttributeBackendTypeByInputType($data['frontend_input']);
        $data['is_user_defined']    = 1;
        $data['is_system']          = 0;
        
        $data['attribute_set_id']   = $entityType->getDefaultAttributeSetId();
        $data['attribute_group_id'] = Mage::getModel('eav/entity_attribute_set')->getDefaultGroupId($data['attribute_set_id']);

        $data['entity_type_id']     = $entityType->getId();
        $data['validate_rules']     = $helper->getAttributeValidateRules($data['frontend_input'], $data);

        // $validateRulesErrors = $helper->checkValidateRules($data['frontend_input'], $data['validate_rules']);
        
        $attribute->addData($data);

        $attribute->save();
    }


    /**
     * Checks if the attribute can be created
     * 
     * @param string $entityType
     * @param string $attributeCode
     * 
     * @return boolean
     * 
     * @author Tiago Sampaio
     */
    protected function _canCreateAttribute($entityType, $attributeCode)
    {
        $eav = Mage::getModel('eav/config');
        $attribute = $eav->getAttribute($entityType, $attributeCode);

        if($attribute->getAttributeId()) {
            unset($eav, $attributeCode, $attribute);
            return false;
        }

        return true;
    }

}