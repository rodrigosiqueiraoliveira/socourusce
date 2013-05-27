<?php
class RedFeet_Catalog_Model_Category extends Mage_Catalog_Model_Category
{
    /**
     * Get all children categories IDs
     *
     * @param boolean $asArray return result as array instead of comma-separated list of IDs
     * @param array $ignoreList list of category IDs to be ignored in cascade mode, i.e.: if you specify parent ID, all of its children will be ignored
     * @return array|string
     */
    public function getAllChildren($asArray = false, $ignoreList = array())
    {
        $children = $this->getResource()->getAllChildren($this);
        if(count($ignoreList) > 0) {
            foreach($ignoreList as $catIgnore){
                $_cat = Mage::getModel('catalog/category')->load($catIgnore);
                $children = array_diff($children, $this->getResource()->getAllChildren($_cat));
            }
        }
        if ($asArray) {
            return $children;
        }
        else {
            return implode(',', $children);
        }
    }
    
    /**
     * Get category url
     *
     * @return string
     */
    public function getUrl($asGomageAjaxUrl = false)
    {
        $url = $this->_getData('url');
        if (is_null($url)) {
            Varien_Profiler::start('REWRITE: '.__METHOD__);
            
            if($asGomageAjaxUrl) {
                $_cat = Mage::getModel('catalog/category')->load(7);
                $this->setData('url', $this->getUrlInstance()->getDirectUrl(Mage::helper('catalog/category')->getFirstCategoryUrl().'?'.(in_array($this->getId(), $this->getResource()->getAllChildren($_cat)) ? 'brand_' : '').'cat='.$this->getId().'&gan_data=true'));
                
                Varien_Profiler::stop('REWRITE: '.__METHOD__);
                return $this->getData('url');
            }
            

            if ($this->hasData('request_path') && $this->getRequestPath() != '') {
                $this->setData('url', $this->getUrlInstance()->getDirectUrl($this->getRequestPath()));
                
                Varien_Profiler::stop('REWRITE: '.__METHOD__);
                return $this->getData('url');
            }

            Varien_Profiler::stop('REWRITE: '.__METHOD__);

            $rewrite = $this->getUrlRewrite();
            if ($this->getStoreId()) {
                $rewrite->setStoreId($this->getStoreId());
            }
            $idPath = 'category/' . $this->getId();
            $rewrite->loadByIdPath($idPath);

            if ($rewrite->getId()) {
                $this->setData('url', $this->getUrlInstance()->getDirectUrl($rewrite->getRequestPath()));
                Varien_Profiler::stop('REWRITE: '.__METHOD__);
                return $this->getData('url');
            }

            Varien_Profiler::stop('REWRITE: '.__METHOD__);

            $this->setData('url', $this->getCategoryIdUrl());
            return $this->getData('url');
        }
        return $url;
    }
}
