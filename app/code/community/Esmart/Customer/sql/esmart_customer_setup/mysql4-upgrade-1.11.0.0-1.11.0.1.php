<?php

/* @var $installer Esmart_Customer_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();


$dataArray = array();

/**
 * [Begin of]
 * Instalation of Customer Attributes
 * 
 */
$forms = array('checkout_register', 'customer_account_create', 'customer_account_edit', 'adminhtml_checkout', 'adminhtml_customer');
$entityTypeCode = 'customer';

/* Customer RG */
$dataArray[] = array(
	'entity_type_code' => $entityTypeCode,
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

/* Customer Type */
$dataArray[] = array(
    'entity_type_code' => $entityTypeCode,
    'attribute_code' => 'customer_type',
    'frontend_input' => 'select', /* text || textarea || multiline || date || select || multiselect || boolean || file || image */
    'default_value' => '',
    'max_text_length' => '20',
    'is_required' => 0,
    'is_visible' => 1,
    'sort_order' => 20,
    'used_in_forms' => $forms,
    'frontend_label' => 'Tipo de Cliente',
    'option' => array(
        'value' => array(
            'option_0' => 'Pessoa Física',
            'option_1' => 'Pessoa Jurídica',
        ),
        'order' => array(
            'option_0' => 0,
            'option_1' => 1,
        )
    ),
    'default' => array('option_0')
);

/* Customer CPF/CNPJ */
$dataArray[] = array(
	'entity_type_code' => $entityTypeCode,
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

/* Customer IE */
$dataArray[] = array(
	'entity_type_code' => $entityTypeCode,
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
/**
 * 
 * [Ending of]
 * Instalation of Customer Attributes
 * 
 * ------------------------------------------
 * 
 * [Begin of]
 * Instalation of Customer Address Attributes
 * 
 */

$forms = array('customer_register_address', 'customer_address_edit', 'adminhtml_customer_address');
$entityTypeCode = 'customer_address';

/* Number of the Address */
$dataArray[] = array(
	'entity_type_code' => $entityTypeCode,
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

/* Neighborhood */
$dataArray[] = array(
	'entity_type_code' => $entityTypeCode,
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

/* Complement */
$dataArray[] = array(
	'entity_type_code' => $entityTypeCode,
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

/* Cellphone */
$dataArray[] = array(
	'entity_type_code' => $entityTypeCode,
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
/**
 * 
 * [Ending of]
 * Instalation of Customer Attributes
 */


foreach($dataArray as $data) {
	if(!isset($data['entity_type_code'])) {
		continue;
	}

	$eav = Mage::getModel('eav/config');
    $attributeCheck = $eav->getAttribute($data['entity_type_code'], $data['attribute_code']);

    if($attributeCheck->getAttributeId()) {
        continue;
    }

    $helper = Mage::helper('esmart_customer');

    $attribute = Mage::getModel('customer/attribute');
    $attribute->setWebsite(Mage::app()->getStore()->getWebsiteId());
    $entityType = Mage::getSingleton('eav/config')->getEntityType($data['entity_type_code']);

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

    try {
	    
	    $attribute->save();
	    	
	    if($data['entity_type_code'] == 'customer') {
		    Mage::getModel('esmart_customer/sales_quote')->saveNewAttribute($attribute);
	        Mage::getModel('esmart_customer/sales_order')->saveNewAttribute($attribute);
	    } elseif($data['entity_type_code'] == 'customer_address') {
	        Mage::getModel('esmart_customer/sales_quote_address')->saveNewAttribute($attribute);
			Mage::getModel('esmart_customer/sales_order_address')->saveNewAttribute($attribute);
	    }

    } catch(Mage_Core_Exception $e) {
    	continue;
    } catch (Exception $e) {
    	continue;
    }

}




$installer->endSetup();