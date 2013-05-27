<?php
 

class RedFeet_Boldcron_Model_PaymentMethod extends Mage_Payment_Model_Method_Abstract
{

    //protected $_code = 'boldcron';
    protected $_code = 'boldcron';
    protected $_formBlockType = 'boldcron/form_pay';
    protected $_infoBlockType = 'boldcron/info_pay';
 
    /**
     * Here are examples of flags that will determine functionality availability
     * of this module to be used by frontend and backend.
     *
     * @see all flags and their defaults in Mage_Payment_Model_Method_Abstract
     *
     * It is possible to have a custom dynamic logic by overloading
     * public function can* for each flag respectively
     */
     
    /**
     * Is this payment method a gateway (online auth/charge) ?
     */
    protected $_isGateway               = true;
 
    /**
     * Can authorize online?
     */
    protected $_canAuthorize            = true;
 
    /**
     * Can capture funds online?
     */
    protected $_canCapture              = true;
 
    /**
     * Can capture partial amounts online?
     */
    protected $_canCapturePartial       = false;
 
    /**
     * Can refund online?
     */
    protected $_canRefund               = false;
 
    /**
     * Can void transactions online?
     */
    protected $_canVoid                 = true;
 
    /**
     * Can use this payment method in administration panel?
     */
    protected $_canUseInternal          = true;
 
    /**
     * Can show this payment method as an option on checkout payment page?
     */
    protected $_canUseCheckout          = true;
 
    /**
     * Is this payment method suitable for multi-shipping checkout?
     */
    protected $_canUseForMultishipping  = true;
 
    /**
     * Can save credit card information for future processing?
     */
    protected $_canSaveCc = false;
 
    /**
     * Here you will need to implement authorize, capture and void public methods
     *
     * @see examples of transaction specific public methods such as
     * authorize, capture and void in Mage_Paygate_Model_Authorizenet
     */
    
    public function getMax()
    {
        $max =intval($this->getConfigData('maximoparcelas'));
        if($max <= 0)
                $max = 1;
        return $max;
    }
    
    /**
     * Get payment methods
     */
    public function getPaymentMethods() {
        return $this->getConfigData('payment_methods');
    }
    
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('boldcron/standard/save', array());
    }
    
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }

        $info = $this->getInfoInstance();

        $info->setCcType($data->getBoldcronCcType())
            ->setCcOwner($data->getBoldcronCcOwner())
            ->setCcLast4(substr($data->getBoldcronCcNumber(), -4))
            ->setCcNumber($data->getBoldcronCcNumber())
            ->setCcCid($data->getBoldcronCcCid())
            ->setCcExpMonth($data->getBoldcronCcExpMonth())
            ->setCcExpYear($data->getBoldcronCcExpYear())
            ->setCcSsIssue($data->getBoldcronCcSsIssue())
            ->setCcSsStartMonth($data->getBoldcronCcSsStartMonth())
            ->setCcSsStartYear($data->getBoldcronCcSsStartYear())
            ->setCcParcelas($data->getBoldcronCcParcelas())
            ->setDcOwner($data->getBoldcronDcOwner())
            ->setDcType($data->getBoldcronDcType())
            ->setDcNumber($data->getBoldcronDcNumber())
            ->setFormaPagamento($data->getFormaPagamento());

            //echo "<pre>".print_r($data, true)."</pre>";
        
        return $this;
    }
    
    public function validate()
    {
        //parent::validate();
 
        $info = $this->getInfoInstance();

        $boldcron_temp_data = array(
            'method'            => 'boldcron',
            'cc_owner'          => $info->getCcOwner(),
            'cc_type'           => $info->getCcType(),
            'cc_number'         => $info->getCcNumber(),
            'cc_exp_month'      => $info->getCcExpMonth(),
            'cc_exp_year'       => $info->getCcExpYear(),
            'cc_cid'            => $info->getCcCid(),
            'cc_parcelas'       => $info->getCcParcelas(),
            'dc_type'           => $info->getDcType(),
            'forma_pagamento'   => $info->getFormaPagamento()
        );
        
        if ($info->getFormaPagamento() == "") {
            $validate_forma_pagamento = Mage::getSingleton('core/session')->getPaymentTempData();
            if($validate_forma_pagamento['forma_pagamento'] == "") {
                $errorCode = 'forma_pagamento';
                $errorMsg = $this->_getHelper()->__('Selecione uma forma de pagamento.');
                Mage::throwException($errorMsg);
            }
        }
        elseif ($boldcron_temp_data['forma_pagamento'] == "BoletoBancario") {
            Mage::getSingleton('core/session')->setPaymentTempData($boldcron_temp_data);
            return $this;
        }
        elseif($boldcron_temp_data['forma_pagamento'] == "CartaoDebito") {
            Mage::getSingleton('core/session')->setPaymentTempData($boldcron_temp_data);
            return $this;
        }
        elseif($boldcron_temp_data['forma_pagamento'] == "CartaoCredito") {
            Mage::getSingleton('core/session')->setPaymentTempData($boldcron_temp_data);
            
            $errorMsg = false;
            $availableTypes = explode(',',$this->getConfigData('cctypes'));
            
            $ccNumber = $info->getCcNumber();
            
            // remove credit card number delimiters such as "-" and space
            $ccNumber = preg_replace('/[\-\s]+/', '', $ccNumber);
            $info->setCcNumber($ccNumber);
    
            $ccType = '';
    
            if (in_array($info->getCcType(), $availableTypes)){
                if ($this->validateCcNum($ccNumber)
                    // Other credit card type number validation
                    || ($this->OtherCcType($info->getCcType()) && $this->validateCcNumOther($ccNumber))) {
                    
                    $ccType = 'OT';
                    $ccTypeRegExpList = array(
                        //Solo, Switch or Maestro. International safe
                        //'SS'  => '/^((6759[0-9]{12})|(6334|6767[0-9]{12})|(6334|6767[0-9]{14,15})|(5018|5020|5038|6304|6759|6761|6763[0-9]{12,19})|(49[013][1356][0-9]{12})|(633[34][0-9]{12})|(633110[0-9]{10})|(564182[0-9]{10}))([0-9]{2,3})?$/', // Maestro / Solo
                        'SO' => '/(^(6334)[5-9](\d{11}$|\d{13,14}$))|(^(6767)(\d{12}$|\d{14,15}$))/', // Solo only
                        'SM' => '/(^(5[0678])\d{11,18}$)|(^(6[^05])\d{11,18}$)|(^(601)[^1]\d{9,16}$)|(^(6011)\d{9,11}$)|(^(6011)\d{13,16}$)|(^(65)\d{11,13}$)|(^(65)\d{15,18}$)|(^(49030)[2-9](\d{10}$|\d{12,13}$))|(^(49033)[5-9](\d{10}$|\d{12,13}$))|(^(49110)[1-2](\d{10}$|\d{12,13}$))|(^(49117)[4-9](\d{10}$|\d{12,13}$))|(^(49118)[0-2](\d{10}$|\d{12,13}$))|(^(4936)(\d{12}$|\d{14,15}$))/',
                        'VI'  => '/^4[0-9]{12}([0-9]{3})?$/',             // Visa
                        'MC'  => '/^5[1-5][0-9]{14}$/',                   // Master Card
                        'AE'  => '/^3[47][0-9]{13}$/',                    // American Express
                        'DI'  => '/^6011[0-9]{12}$/',                     // Discovery
                        'JCB' => '/^(3[0-9]{15}|(2131|1800)[0-9]{11})$/', // JCB
                        'DN'  => '/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/', //Diners
                        'EL' => '/^([0-9]{3}|[0-9]{4})?$/',   //Elo - Other copy
                        'AU' => '/^([0-9]{3}|[0-9]{4})?$/',   //Aura - Other copy
                        'HC' => '/^([0-9]{3}|[0-9]{4})?$/'   //Hipercard - Other copy
                    );
    
                    foreach ($ccTypeRegExpList as $ccTypeMatch=>$ccTypeRegExp) {
                        if (preg_match($ccTypeRegExp, $ccNumber)) {
                            
                            //Don't know how to validate this cards, payment gateway will deal with it
                            if($info->getCcType() == 'AU'){
    
                                $ccType = 'AU';
    
                            }elseif($info->getCcType() == 'HC'){
    
                                $ccType = 'HC';                            
    
                            }elseif($info->getCcType() == 'EL'){
    
                                $ccType = 'EL';                            
    
                            }else{
    
                                $ccType = $ccTypeMatch;    
    
                            }
                            break;
                        }
                    }
    
                    if (!$this->OtherCcType($info->getCcType()) && $ccType!=$info->getCcType()) {
                        $errorCode = 'ccsave_cc_type,ccsave_cc_number';
                        $errorMsg = $this->_getHelper()->__('Credit card number mismatch with credit card type.');
                    }
                    
                }
                else {
                    //Hipercard validation it's impossible so I had to to this
                    if($info->getCcType() == 'HC'){
                        return $this;
                    }
    
                    $errorCode = 'ccsave_cc_number';
                    $errorMsg = $this->_getHelper()->__('Invalid Credit Card Number');
                }
    
            }
            else {
                $errorCode = 'ccsave_cc_type';
                $errorMsg = $this->_getHelper()->__('Credit card type is not allowed for this payment method.');
            }
    
            //validate credit card verification number
            if ($errorMsg === false && $this->hasVerification()) {
                $verifcationRegEx = $this->getVerificationRegEx();
                $regExp = isset($verifcationRegEx[$info->getCcType()]) ? $verifcationRegEx[$info->getCcType()] : '';
                if (!$info->getCcCid() || !$regExp || !preg_match($regExp ,$info->getCcCid())){
                    $errorMsg = $this->_getHelper()->__('Please enter a valid credit card verification number.');
                }
            }
    
            if ($ccType != 'SS' && !$this->_validateExpDate($info->getCcExpYear(), $info->getCcExpMonth())) {
                $errorCode = 'ccsave_expiration,ccsave_expiration_yr';
                $errorMsg = $this->_getHelper()->__('Incorrect credit card expiration date.');
            }
    
            $parcelas = $info->getCcParcelas();
                
            if(empty($parcelas)){
                $errorCode = 'invalid_data';
                $errorMsg = $this->_getHelper()->__('Número de parcelas inválido');
            }
    
            if($errorMsg){
                Mage::throwException($errorMsg);
                //throw Mage::exception('Mage_Payment', $errorMsg, $errorCode);
            }
    
            //This must be after all validation conditions
            if ($this->getIsCentinelValidationEnabled()) {
                $this->getCentinelValidator()->validate($this->getCentinelValidationData());
            }
    
            return $this;
        }
    }
    
    public function hasVerification()
    {
        $configData = $this->getConfigData('useccv');
        if(is_null($configData)){
            return true;
        }
        return (bool) $configData;
    }

    public function getVerificationRegEx()
    {
        $verificationExpList = array(
            'VI' => '/^[0-9]{3}$/', // Visa
            'MC' => '/^[0-9]{3}$/',       // Master Card
            'AE' => '/^[0-9]{4}$/',        // American Express
            'DI' => '/^[0-9]{3}$/',          // Discovery
            'DN' => '/^[0-9]{3}$/',          // Diners
            'EL' => '/^[0-9]{3}$/',          // - Elo Tryout
            'AU' => '/^[0-9]{3}$/',          // - Aura Tryout
            'HC' => '/^[0-9]{3}$/',          // - Hipercard Tryout
            'SS' => '/^[0-9]{3,4}$/',
            'SM' => '/^[0-9]{3,4}$/', // Switch or Maestro
            'SO' => '/^[0-9]{3,4}$/', // Solo
            'OT' => '/^[0-9]{3,4}$/',
            'JCB' => '/^[0-9]{4}$/' //JCB
        );
        return $verificationExpList;
    }

    protected function _validateExpDate($expYear, $expMonth)
    {
        $date = Mage::app()->getLocale()->date();
        if (!$expYear || !$expMonth || ($date->compareYear($expYear)==1) || ($date->compareYear($expYear) == 0 && ($date->compareMonth($expMonth)==1 )  )) {
            return false;
        }
        return true;
    }
    
    public function OtherCcType($type)
    {
        return $type=='OT';
    }

    /**
     * Validate credit card number
     *
     * @param   string $cc_number
     * @return  bool
     */
    public function validateCcNum($ccNumber)
    {
        $cardNumber = strrev($ccNumber);
        $numSum = 0;

        for ($i=0; $i<strlen($cardNumber); $i++) {
            $currentNum = substr($cardNumber, $i, 1);

            /**
             * Double every second digit
             */
            if ($i % 2 == 1) {
                $currentNum *= 2;
            }

            /**
             * Add digits of 2-digit numbers together
             */
            if ($currentNum > 9) {
                $firstNum = $currentNum % 10;
                $secondNum = ($currentNum - $firstNum) / 10;
                $currentNum = $firstNum + $secondNum;
            }

            $numSum += $currentNum;
        }

        /**
         * If the total has no remainder it's OK
         */
        return ($numSum % 10 == 0);
    }

    /**
     * Other credit cart type number validation
     *
     * @param string $ccNumber
     * @return boolean
     */
    public function validateCcNumOther($ccNumber)
    {
        return preg_match('/^\\d+$/', $ccNumber);
    }

    /**
     * Check whether there are CC types set in configuration
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        return $this->getConfigData('cctypes', ($quote ? $quote->getStoreId() : null))
            && parent::isAvailable($quote);
    }
    
    /*
    public function authorize(Varien_Object $payment, $amount)
    {
        $this->helper = Mage::helper('boldcron/data');

        //Payment Data of New Order
	$paymentData = Mage::getSingleton('core/session')->getPaymentTempData();

	//Load Order Data
	$order_model = $payment->getOrder();

        //Last Order Id
	$orderId = $order_model->getId();
        
        //set session order
        Mage::getSingleton('core/session')->setOrderSessionData($order_model);

	//Check Number of Payment Times
	if($paymentData['cc_parcelas'] != 1){
	    $typePayment = $this->getConfigData('type_payment');
	}else{
	    $typePayment = "0";   
	}

        $payment_boldcron = $this->helper->getPayment();
	
	$url = $payment_boldcron["url"];
	$merchant = $payment_boldcron["merchant"];
	$user = $payment_boldcron["user"];
	$password = $payment_boldcron["password"];
        
	$xml = $this->helper->createXml("payOrder", "", "PayOrder");
        $retorno_array = $this->helper->requisicaoWebService($url, "payOrder", $merchant, $user, $password, $xml);

	//Add Returned Information of authorizeTransaction Method to the Payment
	$order_model->getPayment()->setAdditionalInformation(array(
            'step'                      => 'Authorize',
            'message' 			=> $retorno_array['msg'],
            'status' 			=> $retorno_array['status']
        ));
        
	//Check Status of the authorizeTransaction Method Return
	if($retorno_array["status"] == 0){
	    $message = 'Pedido autorizado e pago. Transação: '.$retorno_array["bpag_data"]["id"];
            
            if($paymentData['forma_pagamento'] == "CartaoCredito") {
                //Mage::getModel('sales/order')->load($orderId)->sendNewOrderEmail();
                //$this->_redirect('checkout/onepage/success', array('_secure'=>true));
            }
            if($paymentData['forma_pagamento'] == "CartaoDebito") {
                Mage::getModel('sales/order')->load($orderId)->sendNewOrderEmail();
                $paymentData["url_debito"] = $retorno_array['bpag_data']['url'];
                Mage::getSingleton('core/session')->setPaymentTempData($paymentData);
                //$this->_redirectUrl($retorno_array['bpag_data']['url']);
            }
            if($paymentData['forma_pagamento'] == "BoletoBancario") {
                try {
                    Mage::getSingleton('core/session')->setUrlBoleto($retorno_array['bpag_data']['url']);
                    $prefix = Mage::getConfig()->getTablePrefix();
                    $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
                    $connection->beginTransaction();
                    $sql = "INSERT INTO `" . $prefix . "boldcron_boleto_data` (`order_id`, `url`) VALUES (?, ?)";
                    $bind = array($orderId, $retorno_array['bpag_data']['url']);
                    $connection->query($sql, $bind);
                    $connection->commit();
                    //$this->sendEmailBoleto($order_model);
                } catch (Exception $e) {
                    Mage::throwException($e);
                }
                //$this->_redirect('checkout/onepage/success', array('_secure'=>true));
            }
            Mage::getModel('sales/order')->load($orderId)->sendNewOrderEmail();
	}elseif($retorno_array["status"] == 1){

	    $message = 'Pedido não pago. Transação: '.$retorno_array["bpag_data"]["id"];
            Mage::throwException("Erro: ". $message);
            return false;

	}elseif($retorno_array["status"] == 2){

	    $message = 'Pedido inválido. Transacao: '.$retorno_array["bpag_data"]["id"];
            Mage::throwException("Erro: ". $message);
            return false;

	}elseif($retorno_array["status"] == 4){

	    $message = 'Pedido não efetivado. Transação: '.$retorno_array["bpag_data"]["id"];
            Mage::throwException("Erro: ". $message);
            return false;

	}elseif($retorno_array["status"] == 5){

	    $message = 'Saldo insuficiente. Transação: '.$retorno_array["bpag_data"]["id"];
            Mage::throwException("Erro: ". $message);
            return false;

	}elseif($retorno_array["status"] == 12){

	    $message = 'Em análise. Transação: '.$retorno_array["bpag_data"]["id"];
            Mage::throwException("Erro: ". $message);
            return false;

	}

	//Add Status to the Order Comments and Save
	$order_model->addStatusHistoryComment(Mage::helper('boldcron')->__($message), $this->getConfigData('order_status'))->setIsVisibleOnFront(1);
	$order_model->save();

        if (!$this->canAuthorize()) {
            Mage::throwException($this->_getHelper()->__('Authorize action is not available.'));
        }
        return $this;
    }
    */
}
?>