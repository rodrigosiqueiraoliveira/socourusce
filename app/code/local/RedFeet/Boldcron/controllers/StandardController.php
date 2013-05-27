<?php

class RedFeet_Boldcron_StandardController extends Mage_Core_Controller_Front_Action
{
    
    public function _construct()
    {
	$this->db = Mage::getSingleton('core/resource')->getConnection('core_write');
	$this->prefix = Mage::getConfig()->getTablePrefix();
	$this->helper = Mage::helper('boldcron/data');
    }
    
    public function saveAction()
    {
	$payment_boldcron = $this->helper->getPayment();
	
	$url = $payment_boldcron["url"];
	$merchant = $payment_boldcron["merchant"];
	$user = $payment_boldcron["user"];
	$password = $payment_boldcron["password"];
	
	$xml = $this->helper->createXml("payOrder", "", "PayOrder");
	
	//header('Content-type: text/xml');
	//exit($xml);

        try {
	    $retorno_array = $this->helper->requisicaoWebService($url, "payOrder", $merchant, $user, $password, $xml);
	    //exit("<pre>".print_r($retorno_array, true)."</pre>");
	    
	    $paymentData = Mage::getSingleton('core/session')->getPaymentTempData();
	    if($retorno_array['status'] == 0 || $retorno_array['status'] == -73) {
		$orderId = Mage::getSingleton('checkout/session')->getLastOrderId();
		if($paymentData['forma_pagamento'] == "CartaoDebito") {
		    Mage::getModel('sales/order')->load($orderId)->sendNewOrderEmail();
		    $this->_redirectUrl($retorno_array['bpag_data']['url']);
		}
		elseif($paymentData['forma_pagamento'] == "CartaoCredito") {
		    Mage::getModel('sales/order')->load($orderId)->sendNewOrderEmail();
		    $this->_redirect('checkout/onepage/success', array('_secure'=>true));
		}
		elseif($paymentData['forma_pagamento'] == "BoletoBancario") {
		    try {
			Mage::getSingleton('core/session')->setUrlBoleto($retorno_array['bpag_data']['url']);
			$prefix = Mage::getConfig()->getTablePrefix();
			$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
			$connection->beginTransaction();
			$sql = "INSERT INTO `" . $prefix . "boldcron_boleto_data` (`order_id`, `url`) VALUES (?, ?)";
			$bind = array($orderId, $retorno_array['bpag_data']['url']);
			$connection->query($sql, $bind);
			$connection->commit();
			$this->sendEmailBoleto($orderId);
		    } catch (Exception $e) {
			Mage::throwException($e);
		    }
		    Mage::getModel('sales/order')->load($orderId)->sendNewOrderEmail();
		    $this->_redirect('checkout/onepage/success', array('_secure'=>true));
		}
	    } else {
		if($retorno_array["status"] == 1){
		    $message = 'Pedido não pago. Transação: '.$retorno_array["bpag_data"]["id"];
		}elseif($retorno_array["status"] == 2){
		    $message = 'Pedido inválido. Transacao: '.$retorno_array["bpag_data"]["id"];
		}elseif($retorno_array["status"] == 4){
		    $message = 'Pedido não efetivado. Transação: '.$retorno_array["bpag_data"]["id"];
		}elseif($retorno_array["status"] == 5){
		    $message = 'Saldo insuficiente. Transação: '.$retorno_array["bpag_data"]["id"];
		}elseif($retorno_array["status"] == 12){
		    $message = 'Em análise. Transação: '.$retorno_array["bpag_data"]["id"];
		}
		Mage::getSingleton('core/session')->setMsgStatusError($message);
		$this->_redirect('checkout/onepage/failure', array('_secure'=>true));
	    }
	    $quoteID = Mage::getSingleton("checkout/session")->getQuote()->getId();
	 
	    if($quoteID) {
		$quote = Mage::getModel("sales/quote")->load($quoteID);
		$quote->setIsActive(false);
		$quote->delete();
	    }
	} catch (Exception $e) {
	    echo "Exceção pega: ",  $e->getMessage(), "\n";
	}
    }
    
    public function sendEmailBoleto($orderId) {
	$url_boleto = $this->helper->getUrlBoleto($orderId);
	$order = Mage::getModel('sales/order')->load($orderId)->getData();
	
	$to_email = $order['customer_email'];
	$to_name = $order['customer_firstname']." ".$order['customer_lastname'];
	$subject = 'Boleto do pedido # '.$order['increment_id'];
	
	$sender_email = Mage::getStoreConfig('trans_email/ident_sales/email');
	$sender_name = Mage::getStoreConfig('trans_email/ident_sales/name');
	/*
	require_once 'Zend/Mail/Transport/Smtp.php';
	$transport = new Zend_Mail_Transport_Sendmail($order['customer_email']); 
	$mail = new Zend_Mail();
	$mail->setBodyHtml($body);
	$mail->setFrom($sender_email, $sender_name);
	$mail->addTo($to_email, $to_name);
	$mail->setSubject($subject);
	$mail->send($transport);
	*/
	$emailTemplate  = Mage::getModel('core/email_template')
		->loadDefault('boleto_email_template');                                 
		 
	$emailTemplate->setSenderName($sender_name);
	$emailTemplate->setSenderEmail($sender_email);
	$emailTemplate->setTemplateSubject($subject); 
	 
	//Create an array of variables to assign to template
	$emailTemplateVariables = array();
	$emailTemplateVariables['numero_pedido'] = "# ".$order['increment_id'];
	$emailTemplateVariables['link_boleto'] = $url_boleto[0]["url"];
	$emailTemplateVariables['customer_name'] = $to_name;
	
	$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
	
	$emailTemplate->send($to_email, $to_name, $processedTemplate);
    }
}