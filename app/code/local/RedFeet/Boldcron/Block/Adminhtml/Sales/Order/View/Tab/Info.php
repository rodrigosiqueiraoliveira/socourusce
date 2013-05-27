<?php

class RedFeet_Boldcron_Block_Adminhtml_Sales_Order_View_Tab_Info extends Mage_Adminhtml_Block_Sales_Order_View_Tab_Info
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('boldcron/sales/order/view/tab/info.phtml');
    }
}