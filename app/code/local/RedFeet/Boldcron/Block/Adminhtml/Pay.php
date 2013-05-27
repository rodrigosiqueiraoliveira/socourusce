<?php

class RedFeet_Boldcron_Block_Adminhtml_Pay extends Mage_Payment_Block_Info_Ccsave
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('boldcron/info/payinfo.phtml');
    }
    
    protected function _getConfig()
    {
        return Mage::getSingleton('payment/config');
    }
    
    protected function _prepareSpecificInformation($transport = null)
    {
        if (null !== $this->_paymentSpecificInformation) {
            return $this->_paymentSpecificInformation;
        }
        $payment_temp_data = Mage::getSingleton('core/session')->getPaymentTempData();
        if($payment_temp_data['forma_pagamento'] == "CartaoCredito") {
            $info = $this->getInfo();
            $transport = new Varien_Object();
            $transport = parent::_prepareSpecificInformation($transport);
            $transport->addData(array(
                Mage::helper('payment')->__('Número de parcelas') => $info->getCcParcelas()
            ));
        }
        elseif($payment_temp_data['forma_pagamento'] == "BoletoBancario") {
            $transport = new Varien_Object();
            $transport->addData(array(
                Mage::helper('payment')->__('Forma de Pagamento') => Mage::helper('payment')->__('Boleto Bancário')
            ));
        }
        return $transport;
    }
    
    public function loadPaymentInfo(){
        
        $info = $this->getInfo();
        
        $orderid = $info->getId();
        
        $order = Mage::getModel('sales/order')->load($orderid)->getData();
        echo "adada";
        if ($order['status'] == "pending") {
            $data = Mage::helper('boldcron/data')->getUrlBoleto($orderid);
            
            $urlboleto = "<a style=\"color:#ffb81e\" href=\"" . $data[0]["url"] . "\" class=\"link-cart\" onclick=\"this.target='_blank'\">" . $this->__('Gerar 2a Via do Boleto') . "</a></span>";
            
            return $urlboleto;
        }
    }
}