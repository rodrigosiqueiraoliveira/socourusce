<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Conf
*/
class RedFeet_Conf_Block_Catalog_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{
    protected $_optionProducts;
    
    protected function _afterToHtml($html)
    {
        $attributeIdsWithImages = Mage::registry('amconf_images_attrids');
        $html = parent::_afterToHtml($html);
        if (!Mage::getStoreConfig('amconf/general/enable'))
        {
            return $html;
        }
        if ('product.info.options.configurable' == $this->getNameInLayout())
        {
            if (Mage::getStoreConfig('amconf/general/hide_dropdowns'))
            {
                if (!empty($attributeIdsWithImages))
                {
                    foreach ($attributeIdsWithImages as $attrIdToHide)
                    {
                        $html = preg_replace('@(id="attribute' . $attrIdToHide . '")(\s+)(class=")(.*?)(super-attribute-select)@', '$1$2$3$4$5 no-display', $html);
                    }
                }
            }
            
            if (Mage::getStoreConfig('amconf/general/show_clear'))
            {
                $html = '<a href="#" onclick="javascript: spConfig.clearConfig(); return false;">' . $this->__('Reset Configuration') . '</a>' . $html;
            }
            
            $html = '<script type="text/javascript" src="' . Mage::getBaseUrl('js') . 'amasty/amconf/configurable.js"></script>'
                        . $html;
            $simpleProducts = $this->getProduct()->getTypeInstance(true)->getUsedProducts(null, $this->getProduct());
            
            if ($this->_optionProducts)
            {
                $noimgUrl = Mage::helper('amconf')->getNoimgImgUrl();
                $this->_optionProducts = array_values($this->_optionProducts);
                foreach ($simpleProducts as $simple)
                {
                    $key = array();
                    for ($i = 0; $i < count($this->_optionProducts); $i++)
                    {
                        foreach ($this->_optionProducts[$i] as $optionId => $productIds)
                        {
                            if (in_array($simple->getId(), $productIds))
                            {
                                $key[] = $optionId;
                            }
                        }
                    }
                    if ($key)
                    {
                        // @todo check settings:
                        // array key here is a combination of choosen options
                        $confData[implode(',', $key)] = array(
                            'short_description' => $simple->getShortDescription(),
                            'description'       => $simple->getDescription(),
                        );
                        
                        if (Mage::getStoreConfig('amconf/general/reload_name'))
                        {
                            $confData[implode(',', $key)]['name'] = $simple->getName();
                        }
                        
                        if ($simple->getImage() && Mage::getStoreConfig('amconf/general/reload_images'))
                        {
                            $confData[implode(',', $key)]['media_url'] = $this->getUrl('amconf/media', array('id' => $simple->getId())); // media_url should only exist if we need to re-load images
                        } elseif ($noimgUrl) 
                        {
                            $confData[implode(',', $key)]['noimg_url'] = $noimgUrl;
                        }
                    }
                }

                $html .= '<script type="text/javascript">var confData = new AmConfigurableData(' . Zend_Json::encode($confData) . ');</script>';
                if (Mage::getStoreConfig('amconf/general/hide_dropdowns'))
                {
                    $html .= '<script type="text/javascript">Event.observe(window, \'load\', spConfig.processEmpty);</script>';
                }
                $html .= '<script type="text/javascript">confData.textNotAvailable = "' . $this->__('Choose previous option please...') . '";</script>';
                $html .= '<script type="text/javascript">confData.mediaUrlMain = "' . $this->getUrl('amconf/media', array('id' => $this->getProduct()->getId())) . '";</script>';
                $html .= '<script type="text/javascript">confData.oneAttributeReload = "' . (boolean) Mage::getStoreConfig('amconf/general/oneselect_reload') . '";</script>';
                if ('true' == (string)Mage::getConfig()->getNode('modules/Amasty_Lbox/active'))
                {
                    $html .= '<script type="text/javascript">confData.amlboxInstalled = true;</script>';
                }
            }
        }
        
        return $html;
    }
    
    public function getJsonConfig()
    {
        $attributeIdsWithImages = array();
        $jsonConfig = parent::getJsonConfig();
        if (!Mage::getStoreConfig('amconf/general/enable'))
        {
            return $jsonConfig;
        }
        $config = Zend_Json::decode($jsonConfig);
        
        
        $_product = $this->getProduct();
        $_gallery = $_product->getMediaGalleryImages();
        $arrayCores = array();
        if(count($_gallery) > 0) {
            foreach($_gallery as $_image) {
                $arrayCores[$_image->getLabel()] = (string)Mage::helper('catalog/image')->init($_product, 'thumbnail', $_image->getFile())->resize(48);
            }
        }
        
        foreach ($config['attributes'] as $attributeId => $attribute)
        {
            if (Mage::getModel('amconf/attribute')->load($attributeId, 'attribute_id')->getUseImage())
            {
                $config['attributes'][$attributeId]['use_image'] = 1;
                $attributeIdsWithImages[] = $attributeId;
            }
            foreach ($attribute['options'] as $i => $option)
            {
                $this->_optionProducts[$attributeId][$option['id']] = $option['products'];
                if (Mage::getModel('amconf/attribute')->load($attributeId, 'attribute_id')->getUseImage())
                {
                    $imgUrl = Mage::helper('amconf')->getImageUrl($option['id']);
                    if(strtoupper($attribute['label']) == 'COR' && array_key_exists($option['label'], $arrayCores)) {
                        $imgUrl = $arrayCores[$option['label']];
                    }
                    $config['attributes'][$attributeId]['options'][$i]['image'] = $imgUrl;
                }
            }
        }
        Mage::register('amconf_images_attrids', $attributeIdsWithImages, true);
        return Zend_Json::encode($config);
    }
}