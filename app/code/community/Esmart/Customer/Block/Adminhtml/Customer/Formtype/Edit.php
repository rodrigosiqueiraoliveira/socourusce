<?php

class Esmart_Customer_Block_Adminhtml_Customer_Formtype_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Retrieve current form type instance
     *
     * @return Mage_Eav_Model_Form_Type
     */
    protected function _getFormType()
    {
        return Mage::registry('current_form_type');
    }

    /**
     * Initialize Form Container
     *
     */
    public function __construct()
    {
        $this->_objectId   = 'type_id';
        $this->_blockGroup = 'esmart_customer';
        $this->_controller = 'adminhtml_customer_formtype';

        parent::__construct();

        $editMode = Mage::registry('edit_mode');
        if ($editMode == 'edit') {
            $this->_updateButton('save', 'onclick', 'formType.save(false)');
            $this->_addButton('save_and_edit_button', array(
                'label'     => Mage::helper('esmart_customer')->__('Save and Continue Edit'),
                'onclick'   => 'formType.save(true)',
                'class'     => 'save'
            ));

            if ($this->_getFormType()->getIsSystem()) {
                $this->_removeButton('delete');
            }

            $this->_headerText = Mage::helper('esmart_customer')->__('Edit Form Type "%s"', $this->_getFormType()->getCode());
        } else {
            $this->_headerText = Mage::helper('esmart_customer')->__('New Form Type');
        }
    }
}
