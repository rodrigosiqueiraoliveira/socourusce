<?php
class RedFeet_Navigation_Model_Layer_Filter_Item extends GoMage_Navigation_Model_Layer_Filter_Item
{
    public function getUniqueUrl($ajax = false)
    {
    	if($this->hasData('url')){
    	    return $this->getData('url');
    	}
    	$query = array(
	    $this->getFilter()->getRequestVar()=>$this->getFilter()->getUniqueValue($this->getValue()),
	    Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
	);
	$query['ajax'] = null;
    	if($ajax){
            $query['ajax'] = 1;
        }
        return Mage::helper('gomage_navigation')->getFilterUrl('*/*/*', array('_current'=>true, '_nosid'=>true, '_use_rewrite'=>true, '_query'=>$query, '_escape'=>false)); 
    }
    
    public function getUniqueId() {
	return $this->getFilter()->getUniqueValue($this->getValue());
    }
}
