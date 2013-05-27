<?php

class Esmart_Eav_Block_Form_Renderer_File extends Esmart_Eav_Block_Form_Renderer_Abstract
{
    /**
     * Return escaped value
     *
     * @return string
     */
    public function getEscapedValue()
    {
        if ($this->getValue()) {
            return $this->escapeHtml(Mage::helper('core')->urlEncode($this->getValue()));
        }
        return '';
    }
}
