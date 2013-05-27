<?php
class RedFeet_Boldcron_Block_Checkout_Onepage_Success extends Mage_Checkout_Block_Onepage_Success
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('boldcron/checkout/success.phtml');
        
    }
}