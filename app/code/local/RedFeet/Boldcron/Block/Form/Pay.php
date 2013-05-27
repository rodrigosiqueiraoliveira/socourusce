<?php
class RedFeet_Boldcron_Block_Form_Pay extends Mage_Payment_Block_Form
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('boldcron/form/card.phtml');
    }

    protected function _getConfig()
    {
            return Mage::getSingleton('payment/config');
    }
    
    /**
     * Return Payment Method model
     */
    public function getPaymentMethod()
    {
    	return Mage::getSingleton('boldcron/paymentMethod');
    }
    
    public function getCcAvailableTypes($if)
    {        
        if ($method = $this->getMethod()) {
            if($if == "cc") {
                $types = $this->_getConfig()->getCcTypes();
                
                $availableTypes = $method->getConfigData('payment_cctypes');
    
                if ($availableTypes) {
    
                    $availableTypes = explode(',', $availableTypes);
    
                    foreach($availableTypes as $code_adm){
                        if($code_adm == "setef_amex" || $code_adm == "amex_webpos" || $code_adm == "cielows2p_amex"){
                            $availableTypesReload[] = "AE";
                        }
                        if($code_adm == "setef_aura"){
                            $availableTypesReload[] = "AU";
                        }
                        if($code_adm == "setef_diners" || $code_adm == "redecard_diners" || $code_adm == "cielows2p_diners"){
                            $availableTypesReload[] = "DN";
                        }
                        if($code_adm == "setef_mastercard" || $code_adm == "redecard_mastercard" || $code_adm == "cielows_mastercard" || $code_adm == "cielows2p_mastercard"){
                            $availableTypesReload[] = "MC";
                        }
                        if($code_adm == "setef_visa" || $code_adm == "redecard_visa" || $code_adm == "cielows_visa" || $code_adm == "cielows2p_visa"){
                            $availableTypesReload[] = "VI";
                        }
                        if($code_adm == "setef_hipercard"){
                            $availableTypesReload[] = "HC";
                        }
                        if($code_adm == "setef_elo" || $code_adm == "cielows2p_elo"){
                            $availableTypesReload[] = "EL";
                        }
                        if($code_adm == "goodcard"){
                            $availableTypesReload[] = "GC";
                        }
                        if($code_adm == "setef_pontocred"){
                            $availableTypesReload[] = "PC";
                        }
                        if($code_adm == "setef_extra"){
                            $availableTypesReload[] = "EX";
                        }
                        if($code_adm == "setef_comprabem"){
                            $availableTypesReload[] = "CB";
                        }
                        if($code_adm == "setef_paoacucar"){
                            $availableTypesReload[] = "PA";
                        }
                        if($code_adm == "setef_sendas"){
                            $availableTypesReload[] = "SE";
                        }
                        if($code_adm == "setef_extrapresentes"){
                            $availableTypesReload[] = "EP";
                        }
                        if($code_adm == "setef_plenocard"){
                            $availableTypesReload[] = "PL";
                        }
                        if($code_adm == "setef_personalcard"){
                            $availableTypesReload[] = "PE";
                        }
                    }
    
                    foreach ($types as $code=>$name) {
                        if (!in_array($code, $availableTypesReload)) {
                            unset($types[$code]);
                        }
                    }
                }
                return $types;
            }
            elseif($if == "dc") {
                $availableTypes = $method->getConfigData('payment_debtypes');
                if ($availableTypes) {
                    $availableTypes = explode(',', $availableTypes);
                    
                    foreach($availableTypes as $code_adm) {
                        if($code_adm == "cielows_visaelectron"){
                            $return[$code_adm] = "VISA Electron";
                        }
                        if($code_adm == "bradesco"){
                            $return[$code_adm] = "Bradesco";
                        }
                        if($code_adm == "itau"){
                            $return[$code_adm] = "Itaú";
                        }
                        if($code_adm == "banrisul_pgta"){
                            $return[$code_adm] = "Banrisul";
                        }
                        if($code_adm == "bb_debito"){
                            $return[$code_adm] = "Banco do Brasil";
                        }
                        if($code_adm == "hsbc"){
                            $return[$code_adm] = "HSBC";
                        }
                        if($code_adm == "cielows_maestro"){
                            $return[$code_adm] = "Maestro";
                        }
                    }
                }
                return $return;
            }
        }
    }
    
    public function getMax()
    { 
        $model = Mage::getModel('boldcron/paymentMethod');
        return $model->getMax(); 
    }
    
    public function getCcMonths()
    {
            $months = $this->getData('cc_months');
            if (is_null($months)) {
                    $months[0] =  $this->__('Month');
                    $months = array_merge($months, $this->_getConfig()->getMonths());
                    $this->setData('cc_months', $months);
            }
            return $months;
    }

    /**
     * Retrieve credit card expire years
     *
     * @return array
     */
    public function getCcYears()
    {
        $years = $this->getData('cc_years');
        if (is_null($years)) {
            $years = $this->_getConfig()->getYears();
            $years = array(0=>$this->__('Year'))+$years;
            $this->setData('cc_years', $years);
        }
        return $years;
    }
    
    public function hasVerification()
    {
        if ($this->getMethod()) {
            $configData = $this->getMethod()->getConfigData('useccv');
            if(is_null($configData)){
                return true;
            }
            return (bool) $configData;
        }
        return true;
    }
}