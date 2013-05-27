<?php

class Esmart_Eav_Block_Form_Renderer_Select extends Esmart_Eav_Block_Form_Renderer_Abstract
{
    /**
     * Return array of select options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->getAttributeObject()->getSource()->getAllOptions();
    }
}
