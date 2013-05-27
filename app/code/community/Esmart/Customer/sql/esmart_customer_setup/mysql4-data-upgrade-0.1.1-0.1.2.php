<?php

/** @var $helper Esmart_Customer_Helper_Data */
$helper = Mage::helper('esmart_customer');
$customerAttributes = $helper->getCustomerAttributeFormOptions();

$attributes = array(
    'dob',
    'gender',
    'taxvat',
);

foreach ($attributes as $attributeCode) {
    $attribute      = Mage::getSingleton('eav/config')->getAttribute('customer', $attributeCode);
    $getUsedInForms = $attribute->getUsedInForms();
    $usedInForms    = array();
    foreach($customerAttributes as $customerAttribute) {
        if (!in_array($customerAttribute['value'], $getUsedInForms)) {
            $usedInForms[] = $customerAttribute['value'];
        }
    }
    if (!empty($usedInForms)) {
        $attribute->setData('used_in_forms', $usedInForms);
        $attribute->save();
    }
}
