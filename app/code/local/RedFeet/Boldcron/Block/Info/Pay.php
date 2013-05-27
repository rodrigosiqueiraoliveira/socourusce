<?php

class RedFeet_Boldcron_Block_Info_Pay extends Mage_Payment_Block_Info
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

        $info = $this->getInfo();

        if($this->getInfo()->getCcLast4()) {
            $transport = new Varien_Object(array(Mage::helper('payment')->__('Name on the Card') => $info->getCcOwner(),));
            $transport = parent::_prepareSpecificInformation($transport);
        
            if (!$this->getIsSecureMode()) {
                $transport->addData(array(
                    Mage::helper('payment')->__('Expiration Date') => $this->_formatCardDate(
                        $info->getCcExpYear(), $this->getCcExpMonth()
                    ),
                ));
            }
    
            if ($ccType = $this->getCcTypeName()) {
                $data[Mage::helper('payment')->__('Credit Card Type')] = $ccType;
            }
            if ($this->getInfo()->getCcLast4()) {
                $data[Mage::helper('payment')->__('Credit Card Number')] = sprintf('xxxx-%s', $this->getInfo()->getCcLast4());
            }
            if (!$this->getIsSecureMode()) {
                if ($ccSsIssue = $this->getInfo()->getCcSsIssue()) {
                    $data[Mage::helper('payment')->__('Switch/Solo/Maestro Issue Number')] = $ccSsIssue;
                }
                $year = $this->getInfo()->getCcSsStartYear();
                $month = $this->getInfo()->getCcSsStartMonth();
                if ($year && $month) {
                    $data[Mage::helper('payment')->__('Switch/Solo/Maestro Start Date')] =  $this->_formatCardDate($year, $month);
                }
            }
            $info = $this->getInfo();
            $transport = new Varien_Object();
            $transport = parent::_prepareSpecificInformation($transport);
            $transport->addData(array(
                Mage::helper('payment')->__('Número de parcelas') => $info->getCcParcelas()
            ));
            $transport->addData(array(
                Mage::helper('payment')->__('Forma de Pagamento') => Mage::helper('payment')->__('Cartão de Crédito')
            ));
        }
        else {
            $transport = new Varien_Object();
            $transport->addData(array(
                Mage::helper('payment')->__('Forma de Pagamento') => Mage::helper('payment')->__('Boleto Bancário')
            ));
            
            echo $this->loadPaymentInfo();
        }
        return $transport->setData(array_merge($data, $transport->getData()));
    }
    
    public function loadPaymentInfo(){
        
        $info = $this->getInfo();
        
        $orderid = $info->getId();
        
        $order = Mage::getModel('sales/order')->load($orderid)->getData();
        
        if ($order['status'] == "processing") {
            $data = Mage::helper('boldcron/data')->getUrlBoleto($orderid);
            
            $urlboleto = "<a style=\"color:#ffb81e\" href=\"" . $data[0]["url"] . "\" class=\"link-cart\" onclick=\"this.target='_blank'\">" . $this->__('Gerar 2a Via do Boleto') . "</a></span>";
            
            return $urlboleto;
        }
    }
    
    /**
     * Retrieve credit card type name
     *
     * @return string
     */
    public function getCcTypeName()
    {
        $types = Mage::getSingleton('payment/config')->getCcTypes();
        $ccType = $this->getInfo()->getCcType();
        if (isset($types[$ccType])) {
            return $types[$ccType];
        }
        return (empty($ccType)) ? Mage::helper('payment')->__('N/A') : $ccType;
    }

    /**
     * Retrieve CC expiration month
     *
     * @return string
     */
    public function getCcExpMonth()
    {
        $month = $this->getInfo()->getCcExpMonth();
        if ($month<10) {
            $month = '0'.$month;
        }
        return $month;
    }

    /**
     * Retrieve CC expiration date
     *
     * @return Zend_Date
     */
    public function getCcExpDate()
    {
        $date = Mage::app()->getLocale()->date(0);
        $date->setYear($this->getInfo()->getCcExpYear());
        $date->setMonth($this->getInfo()->getCcExpMonth());
        return $date;
    }

    /**
     * Format year/month on the credit card
     *
     * @param string $year
     * @param string $month
     * @return string
     */
    protected function _formatCardDate($year, $month)
    {
        return sprintf('%s/%s', sprintf('%02d', $month), $year);
    }
}