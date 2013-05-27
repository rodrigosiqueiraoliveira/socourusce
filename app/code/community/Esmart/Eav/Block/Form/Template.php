<?php

class Esmart_Eav_Block_Form_Template extends Mage_Core_Block_Abstract
{
    /**
     * Array of attribute renderers data keyed by attribute front-end type
     *
     * @var array
     */
    protected $_renderBlocks    = array();

    /**
     * Add custom renderer block and template for rendering EAV entity attributes
     *
     * @param string $type
     * @param string $block
     * @param string $template
     * @return Esmart_Eav_Block_Form_Template
     */
    public function addRenderer($type, $block, $template)
    {
        $this->_renderBlocks[$type] = array(
            'block'     => $block,
            'template'  => $template,
        );

        return $this;
    }

    /**
     * Return array of attribute renderers block and template data keyed by front-end type
     *
     * @return array
     */
    public function getRenderers()
    {
        return $this->_renderBlocks;
    }
}
