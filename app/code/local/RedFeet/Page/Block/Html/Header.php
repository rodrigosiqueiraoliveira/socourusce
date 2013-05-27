<?php

class RedFeet_Page_Block_Html_Header extends Mage_Page_Block_Html_Header
{
    public function getWelcome()
    {
        if (empty($this->_data['welcome'])) {
            /*
            if (Mage::isInstalled() && Mage::getSingleton('customer/session')->isLoggedIn()) {
                $this->_data['welcome'] = $this->__('Welcome, %s!', $this->escapeHtml(Mage::getSingleton('customer/session')->getCustomer()->getName()));
            } else {
                $this->_data['welcome'] = Mage::getStoreConfig('design/header/welcome');
            }
            */
            if (Mage::isInstalled() && Mage::getSingleton('customer/session')->isLoggedIn()) {
                $person = $this->escapeHtml(Mage::getSingleton('customer/session')->getCustomer()->getName());
            }
            else {
                $person = $this->__('Guest');
            }
            $person = '<span>'.$person.'</span>';
            $this->_data['welcome'] = str_replace('@person', $person, Mage::getStoreConfig('design/header/welcome'));
        }

        return $this->_data['welcome'];
    }
}
