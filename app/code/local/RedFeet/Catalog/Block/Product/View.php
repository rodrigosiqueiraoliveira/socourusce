<?php
class RedFeet_Catalog_Block_Product_View extends Mage_Catalog_Block_Product_View
{
    public function getGroupedInfoArray($groupName = '') {
        if($groupName == 'detailed_info') {
            return array("productDetails" => array("label" => "Detalhes do produto", "groups" => array("description", "additional")), "aboutBrand" => array("label" => "Sobre a marca", "groups" => array("brand_description")), "reviews" => array("label" => $this->__("Opini&atilde;o sobre o produto"), "groups" => array("reviews", "review_form")));
        }
        return array();
    }
    
    public function rearrangeGroups($detailedInfoGroup = array(), $groupName = '') {
        $arrayGroupBy = $this->getGroupedInfoArray($groupName);
        foreach ($detailedInfoGroup as $alias => $html){
            $this->replaceGroupKey($arrayGroupBy, $alias, $html);
        }
        $this->cleanInvalidGroupKeys($arrayGroupBy);
        return $arrayGroupBy;
    }
    
    public function replaceGroupKey(&$arrayGroupBy, $alias, $html) {
        foreach($arrayGroupBy as &$tabs) {
            foreach($tabs["groups"] as $groupKey => &$groupAlias) {
                if($groupAlias === $alias) {
                    $tabs["groups"][$alias] = $html;
                    unset($tabs["groups"][$groupKey]);
                }
            }
        }
    }
    
    public function cleanInvalidGroupKeys(&$arrayGroupBy) {
        foreach($arrayGroupBy as &$tabs) {
            foreach($tabs["groups"] as $groupKey => &$groupAlias) {
                if(!is_string($groupKey)) {
                    unset($tabs["groups"][$groupKey]);
                }
            }
        }
    }
}
