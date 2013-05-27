<?php

class Esmart_Eav_Block_Form_Renderer_Boolean extends Esmart_Eav_Block_Form_Renderer_Select
{
    /**
     * Return array of select options
     *
     * @return array
     */
    public function getOptions()
    {
        return array(
            array(
                'value' => '',
                'label' => ''
            ),
            array(
                'value' => '0',
                'label' => Mage::helper('esmart_eav')->__('No')
            ),
            array(
                'value' => '1',
                'label' => Mage::helper('esmart_eav')->__('Yes')
            ),
        );
    }
}
