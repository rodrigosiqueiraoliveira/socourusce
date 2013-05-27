<?php

class RedFeet_Boldcron_PostController extends Mage_Core_Controller_Front_Action
{
    public function _construct()
    {
	$this->db = Mage::getSingleton('core/resource')->getConnection('core_write');
	$this->prefix = Mage::getConfig()->getTablePrefix();
	$this->helper = Mage::helper('boldcron/data');
	$this->payment_boldcron = $this->helper->getPayment();
    }
    
    public function bellAction() {
	
	$url = $this->payment_boldcron["url"];
	$merchant = $this->payment_boldcron["merchant"];
	$user = $this->payment_boldcron["user"];
	$password = $this->payment_boldcron["password"];
	
	try {
	    //recebendo parametros POST
	    $params = $this->getRequest()->getParams();
	    //$params = array();
	    //$params["merch_ref"] = 56;
	    //$params["id"] = 1521828;

	    if(!empty($params)) {
		$dados = array();
		$dados['status'] = 1;
		$dados['msg'] = "Campainha recebida com sucesso!";
		
		/* inicio da requisicao bell */
		$xml_bell = $this->helper->createXml("bell", $dados, "Bell");
		
		header('Content-type: text/xml');
		print $xml_bell;
		/* fim da requisicao bell */
	    }
	} catch (Exception $e) {
	    Mage::throwException("Erro: " . $e);
	    return false;
	}
	
	/* inicio da requisicao probe */
	$xml_probe = $this->helper->createXml("probe", $params, "Probe");
	
	//header('Content-type: text/xml');
	//print $xml_probe->outputMemory(true);
	
	try {
	    $retorno_array = $this->helper->requisicaoWebService($url, "probe", $merchant, $user, $password, $xml_probe);
	    //echo "<pre>".print_r($retorno_array, true)."</pre>";
	    //exit();
	    /* fim da requisicao probe */
	    
	    $order_model = Mage::getModel('sales/order')->load($params["merch_ref"]);
	    //echo "<pre>".print_r($order_model->getData(), true)."</pre>";
	    //exit;
	    /* inicio da requisicao capture */
	    if($retorno_array['order_data']['order']['bpag_data']['status'] == 0) {
		$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		$connection->beginTransaction();
		$sql = "INSERT INTO `" . $this->prefix . "boldcron_data` (`merch_ref`, `bpag_id`) VALUES (?, ?)";
		$bind = array($order_model->getId(), $retorno_array['order_data']['order']['bpag_data']['id']);
		$connection->query($sql, $bind);
		$connection->commit();
		
		$order_model->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();
		
		if ($order_model->canInvoice()) {

		    $convertor = Mage::getModel('sales/convert_order');
		    $invoice = $convertor->toInvoice($order_model);
		    foreach ($order_model->getAllItems() as $orderItem) {
			if (!$orderItem->getQtyToInvoice()) {
			    continue;
			}
			$item = $convertor->itemToInvoiceItem($orderItem);
			$item->setQty($orderItem->getQtyToInvoice());
			$invoice->addItem($item);
		    }

		    $invoice->collectTotals();
		    $invoice->register()->capture();

		    Mage::getModel('core/resource_transaction')
				->addObject($invoice)
				->addObject($invoice->getOrder())
				->save();
		    $invoice->sendEmail(true);
		} else{
		    $order_model->addStatusHistoryComment(Mage::helper('boldcron')->__('Erro ao criar pagamento (fatura).'),'processing');
		    $order_model->save();
		}
	    }
	    
	    else {
		if ($order_model->canCancel()) {
		    $order_model->cancel()
			->save();
		    $order_model->sendOrderUpdateEmail(true);
		}
	    }
	} catch (Exception $e) {
	    Mage::throwException("Erro: " . $e);
	    return false;
	}
    }
}