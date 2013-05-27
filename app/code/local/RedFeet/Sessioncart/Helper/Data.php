<?php
 
class RedFeet_Sessioncart_Helper_Data extends Mage_Core_Helper_Abstract
{
    var $db;    
    var $prefix;

    public function __construct()
    {
        $this->db = Mage::getSingleton('core/resource')->getConnection('core_write');
        $this->prefix = Mage::getConfig()->getTablePrefix();
    }
    
    public function getSessionCart()
    {
        $session = Mage::getSingleton('core/session')->getSessioncartIntro();
        if(is_array($session))
        {
                return $session;	
        }		
    }
    
    public function setSessionCart($data, $posicao)
    {
        $session = $this->getSessionCart();
        $session[$posicao] = $data;
        Mage::getSingleton('core/session')->setSessioncartIntro($session);
    }
    
    public function unsetSessionCart()
    {
        Mage::getSingleton('core/session')->setSessioncartIntro(null);
    }
    
    public function unsetMessageError()
    {
        //Mage::getSingleton('core/session')->clear();
        Mage::getSingleton('core/session')->setMessageErrorSession(null);
    }
    
    public function setMessageError($m)
    {
        $dado = Mage::getSingleton('core/session')->getMessageErrorSession();
        if(!isset($dado) || empty($dado))
            Mage::getSingleton('core/session')->setMessageErrorSession($m);
    }
    
    public function getMessageError()
    {
        return Mage::getSingleton('core/session')->getMessageErrorSession();
    }
    
    public function setInfoCart($m)
    {
        $dado = Mage::getSingleton('core/session')->getInfoCartSession();
        if(!isset($dado) || empty($dado))
            Mage::getSingleton('core/session')->setInfoCartSession($m);
    }
    /*
    public function getInfoTeste()
    {
        exit(Mage::getSingleton('core/session')->getInfoCartSession());
    }
    */
}