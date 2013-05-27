<?php

class RedFeet_Boldcron_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getPayment() {
        $payment = Mage::getStoreConfig('payment');
        $boldcron = $payment['boldcron'];
        return $boldcron;
    }

    //Convert Std Class Object to Array
    public function object_to_array(stdClass $Class) {
        # Typecast to (array) automatically converts stdClass -> array.
        $Class = (array)$Class;
        
        # Iterate through the former properties looking for any stdClass properties.
        # Recursively apply (array).
        foreach($Class as $key => $value){
            if(is_object($value)&&get_class($value)==='stdClass'){
                $Class[$key] = self::object_to_array($value);
            }
            if(is_object($value)&&get_class($value)==='SimpleXMLElement'){
                $Class[$key] = self::object_to_array($value);
            }
        }
        return $Class;
    }
    
    public function getBpagPaymentMethod($cc_type) {
        $types = Mage::getSingleton('payment/config')->getCcTypes();
        $payment_cctypes = Mage::getStoreConfig('payment');
        $availableTypes = $payment_cctypes['boldcron']['payment_cctypes'];
        $code = "";
        $label = "";
        $availableTypes = explode(',', $availableTypes);
        
        foreach($types as $_type => $desc) {
            if($cc_type == $_type) {
                if($_type == "AE") {
                    $label = "Amex";
                    foreach($availableTypes as $_availableType) {
                        if($_availableType == "setef_amex")
                            $code = "setef_amex";
                        elseif($_availableType == "amex_webpos")
                            $code = "amex_webpos";
                        elseif($_availableType == "cielows2p_amex")
                            $code = "cielows2p_amex";
                    }
                }
                if($_type == "AU") {
                    $label = "Aura";
                    $code = "setef_aura";
                }
                if($_type == "DN") {
                    $label = "Diners";
                    foreach($availableTypes as $_availableType) {
                        if($_availableType == "setef_diners")
                            $code = "setef_diners";
                        elseif($_availableType == "redecard_diners")
                            $code = "redecard_diners";
                        elseif($_availableType == "cielows2p_diners")
                            $code = "cielows2p_diners";
                    }
                }
                if($_type == "MC") {
                    $label = "MasterCard";
                    foreach($availableTypes as $_availableType) {
                        if($_availableType == "setef_mastercard")
                            $code = "setef_mastercard";
                        elseif($_availableType == "redecard_mastercard")
                            $code = "redecard_mastercard";
                        elseif($_availableType == "cielows_mastercard")
                            $code = "cielows_mastercard";
                        elseif($_availableType == "cielows2p_mastercard")
                            $code = "cielows2p_mastercard";
                    }
                }
                if($_type == "VI") {
                    $label = "Visa";
                    foreach($availableTypes as $_availableType) {
                        if($_availableType == "setef_visa")
                            $code = "setef_visa";
                        elseif($_availableType == "redecard_visa")
                            $code = "redecard_visa";
                        elseif($_availableType == "cielows_visa")
                            $code = "cielows_visa";
                        elseif($_availableType == "cielows2p_visa")
                            $code = "cielows2p_visa";
                    }
                }
                if($_type == "HC") {
                    $label = "Hipercard";
                    $code = "setef_hipercard";
                }
                if($_type == "EL") {
                    $label = "Elo";
                    foreach($availableTypes as $_availableType) {
                        if($_availableType == "setef_elo")
                            $code = "setef_elo";
                        elseif($_availableType == "cielows2p_elo")
                            $code = "cielows2p_elo";
                    }
                }
                if($_type == "GC") {
                    $label = "Good Card";
                    $code = "goodcard";
                }
                if($_type == "PC") {
                    $label = "Cartão PontoCred";
                    $code = "setef_pontocred";
                }
                if($_type == "EX") {
                    $label = "Cartão Extra";
                    $code = "setef_extra";
                }
                if($_type == "CB") {
                    $label = "Cartão CompreBem";
                    $code = "setef_comprabem";
                }
                if($_type == "PA") {
                    $label = "Cartão Pão de Açúcar";
                    $code = "setef_paoacucar";
                }
                if($_type == "SE") {
                    $label = "Cartão Sendas";
                    $code = "setef_sendas";
                }
                if($_type == "EP") {
                    $label = "Cartão Extra Presentes";
                    $code = "setef_extrapresentes";
                }
                if($_type == "PL") {
                    $label = "Cartão Plenocard";
                    $code = "setef_plenocard";
                }
                if($_type == "PE") {
                    $label = "Cartão Personalcard";
                    $code = "setef_personalcard";
                }
            }
        }
        $array_retorno = array($code, $label);
        return $array_retorno;
    }
    
    function requisicaoWebService($url, $action, $merchant, $user, $password, $xml) {
        $wsdl = new SoapClient($url);

        $return = $wsdl->doService(
            array(
                "version"  	=> '1.1.0',
                "action"   	=> $action,
                "merchant" 	=> $merchant,
                "user"     	=> $user,
                "password" 	=> $password,
                "data"     	=> $xml,
            )
        );
        
        $trata_retorno = simplexml_load_string($return->doServiceReturn);
        $retorno_array = $this->object_to_array($trata_retorno);
        
        return $retorno_array;
    }
    
    function createXml($name, $dados, $functionData) {
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->startElement($name);
        
        $this->write($xml, $this->getXmlData($dados, $functionData));
        
        $xml->endElement();
        $xml->endDocument();
        
        $xml = $xml->outputMemory(true);
        
        return $xml;
    }
    
    function write($xml, $data) {
        
	foreach ($data as $key => $value) {
	    if (is_array($value)) {
		if(strstr($key,"order_item")) {
		    $key = substr($key,0,11);
			if($key != "order_items"){
			    $key = substr($key,0,10);
			}
		}
		$xml->startElement($key);
		$this->write($xml, $value);
		$xml->endElement();
		continue;
	    }
	    $xml->writeElement($key, $value);
	}
    }
    
    function EncryptData($source)
    {                   
       $publicKey = file_get_contents($_SERVER['DOCUMENT_ROOT']."/criptografia_boldcron.pem");
       
       openssl_public_encrypt($source, $encrypted, $publicKey);
       
       return base64_encode($encrypted);  //encrypted string   
    }
    
    function trataZeroEsquerda($value){
	if(strlen($value) == 1){
	    $value = "0".$value;
	}
	return $value;
    }
    
    public function getXmlData($dados, $action)
    {
	$XmlData = array();
	
        if($action == "Bell") {
            $XmlData['status']          = $dados['status'];
            $XmlData['msg']             = $dados['msg'];
        }
        
        elseif($action == "Probe") {
            $XmlData['merch_ref'] 	= $dados['merch_ref'];
            $XmlData['id']        	= $dados['id'];
        }
        
        elseif($action == "Cancel") {
            $XmlData['merch_ref'] 	= $dados['merch_ref'];
            $XmlData['id']        	= $dados['bpag_id'];
        }
        
        elseif($action == "PayOrder") {
            $paymentData = Mage::getSingleton('core/session')->getPaymentTempData();
            //exit("<pre>".print_r($paymentData,true)."</pre>");
            
            $orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
            if(empty($orderId)) {
                $orders = Mage::getModel('sales/order')->getCollection()
                    ->setOrder('created_at','DESC')
                    ->setPageSize(1)
                    ->setCurPage(1);
               
                $orderId = $orders->getFirstItem()->getEntityId();
            }
            
            $order = Mage::getModel('sales/order')
                      ->load($orderId)->getData();
                      
            $totalItemCount = $order['total_item_count'];
            
            // Get the id of the orders shipping address
            $shippingId = Mage::getModel('sales/order')
                      ->load($orderId)->getShippingAddress()->getId();
        
            // Get shipping address data using the id
            $address_shipping = Mage::getModel('sales/order_address')->load($shippingId);
            $address_shipping_data = $address_shipping->getData();
            
            // Get the id of the orders billing address
            $billingId = Mage::getModel('sales/order')
                      ->load($orderId)->getBillingAddress()->getId();
        
            // Get billing address data using the id
            $address_billing = Mage::getModel('sales/order_address')->load($billingId);
            $address_billing_data = $address_billing->getData();
    
            //exit("<pre>".print_r($address2->getData(),true)."</pre>");
            
            $products = Mage::getResourceModel('sales/order_item_collection')
                      ->setOrderFilter($orderId)
                      ->addAttributeToSelect('item_id')
                      ->addAttributeToSelect('product_id')
                      ->addAttributeToSelect('name')
                      ->addAttributeToSelect('description')
                      ->addAttributeToSelect('qty_ordered')
                      ->addAttributeToSelect('price')
                      ->getData();
            
            $products_last = array();
            for($i=0;$i<sizeof($products);$i++){
                $prod_model = Mage::getModel('catalog/product')->load($products[$i]['product_id']);
                if($prod_model->getVisibility() != 1)
                    $products_last[] = $products[$i];
            }
            
            //get Bpag payment method by cc type
            if($paymentData['forma_pagamento'] == "CartaoCredito")
                $payment_method = $this->getBpagPaymentMethod($paymentData['cc_type']);
            elseif($paymentData['forma_pagamento'] == "BoletoBancario") {
                $payment_boltypes = $this->getPayment();
                $payment_method = explode(",", $payment_boltypes['payment_boltypes']);
            }
            elseif($paymentData['forma_pagamento'] == "CartaoDebito") {
                $payment_method[0] = $paymentData['dc_type'];
            }
            
            $XmlData = array();
            
            $XmlData['order_data']['merch_ref']         = $order['entity_id'];
            $XmlData['order_data']['origin']            = 'e-commerce';
            $XmlData['order_data']['currency']          = $order['base_currency_code'];
            $XmlData['order_data']['tax_boarding']      = number_format($order['shipping_tax_amount'],2,'','');
            $XmlData['order_data']['tax_freight']	= number_format($order['shipping_amount'],2,'','');
            $XmlData['order_data']['tax_others']	= number_format($order['tax_amount'],2,'','');
            $XmlData['order_data']['discount_plus']	= number_format($order['discount_amount'],2,'','');
            $XmlData['order_data']['order_subtotal']    = number_format($order['subtotal'],2,'','');
            $XmlData['order_data']['interests_value']   = number_format($order['hidden_tax_amount'],2,'','');
            $XmlData['order_data']['order_total']       = number_format($order['grand_total'],2,'','');
            
                for($i=0;$i<sizeof($products_last);$i++){		
                    $XmlData['order_data']['order_items']['order_item_'.$i]['code'] = $products_last[$i]['item_id'];
                    $XmlData['order_data']['order_items']['order_item_'.$i]['description'] = $products_last[$i]['name'];
                    $XmlData['order_data']['order_items']['order_item_'.$i]['units'] = (int)$products_last[$i]['qty_ordered'];
                    $XmlData['order_data']['order_items']['order_item_'.$i]['unit_value'] = number_format($products_last[$i]['price'],2,'','');
                }
                
            $XmlData['behavior_data']['language'] = 'ptbr';
            $XmlData['behavior_data']['url_post_bell'] = Mage::getUrl().'boldcron/post/bell';
            $XmlData['behavior_data']['url_redirect_success'] = Mage::getUrl().'checkout/onepage/success';
            $XmlData['behavior_data']['url_redirect_error'] = Mage::getUrl().'checkout/onepage/failure';
                
            $XmlData['payment_data']['payment']['payment_method'] = $payment_method[0];
            if($paymentData['forma_pagamento'] == "CartaoDebito") {
                $XmlData['payment_data']['payment']['installments']   = 1;
            }
            if($paymentData['forma_pagamento'] == "CartaoCredito") {
                $XmlData['payment_data']['payment']['installments']   = $paymentData['cc_parcelas'];
                $XmlData['payment_data']['payment']['cc_brand']       = $payment_method[1];
                $XmlData['payment_data']['payment']['cc_number']      = $this->EncryptData($paymentData['cc_number']);
                $XmlData['payment_data']['payment']['cc_cvv']         = $this->EncryptData($paymentData['cc_cid']);
                $XmlData['payment_data']['payment']['cc_exp_month']   = $this->trataZeroEsquerda($paymentData['cc_exp_month']);
                $XmlData['payment_data']['payment']['cc_exp_year']    = $paymentData['cc_exp_year'];
                $XmlData['payment_data']['payment']['cc_card_holder'] = $paymentData['cc_owner'];
            }
            
            $XmlData['customer_data']['customer_id']                      = $order['customer_id'];
            $XmlData['customer_data']['customer_info']['first_name']      = $order['customer_firstname'];
            $XmlData['customer_data']['customer_info']['last_name']       = $order['customer_lastname'];
            $XmlData['customer_data']['customer_info']['email']           = $order['customer_email'];
            $XmlData['customer_data']['customer_info']['document_type']   = 0;
            $XmlData['customer_data']['customer_info']['document']        = $order['customer_taxvat'];
            $XmlData['customer_data']['customer_info']['address_street']  = $address_billing_data['street'];
            $XmlData['customer_data']['customer_info']['address_city']    = $address_billing_data['city'];
            $XmlData['customer_data']['customer_info']['address_state']   = $address_billing_data['region'];
            $XmlData['customer_data']['customer_info']['address_country'] = $address_billing_data['country_id'];
            $XmlData['customer_data']['customer_info']['address_zip']     = $address_billing_data['postcode'];
        
        }
        
        //echo "<pre>".print_r($XmlData,true)."</pre>";
        //exit;
        return $XmlData;
    }
    
    public function getBpagData($order) {
        $this->prefix = Mage::getConfig()->getTablePrefix();
        
        $query = "SELECT * FROM " . $this->prefix . "boldcron_data WHERE merch_ref = " . $order . "";
        $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query);
        
        return $data;
    }
    
    public function deleteBpagData($order) {
        $this->prefix = Mage::getConfig()->getTablePrefix();
        
        $query = "DELETE FROM " . $this->prefix . "boldcron_data WHERE merch_ref = " . $order . "";
        $return = Mage::getSingleton('core/resource')->getConnection('core_read')->query($query);
        
        return $return;
    }
    
    public function getUrlBoleto($order) {
        $this->prefix = Mage::getConfig()->getTablePrefix();
        
        $query = "SELECT * FROM " . $prefix . "boldcron_boleto_data WHERE order_id = " . $order . "";
        $data = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query);
        
        return $data;
    }
    
    public function returnXmlStatusPayment(){

	$xml = new XMLWriter();
	$xml->openMemory();
	$xml->startDocument('1.0','ISO-8859-1');		
	$xml->startElement('status'); // braspag-payment-status
	$xml->text('OK');
	$xml->endElement(); // braspag-payment-status
	$xml->endDocument();
	header( 'Content-type: text/xml' );
	print $xml->outputMemory(true);

    }
}