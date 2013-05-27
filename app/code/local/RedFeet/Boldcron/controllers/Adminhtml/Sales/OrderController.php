<?php
require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';

class RedFeet_Boldcron_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
    /**
     * Cancel order
     */
    public function cancelAction()
    {
        if ($order = $this->_initOrder()) {
            try {
                if($order->getPayment()->getMethod() == "boldcron") {
                    $helper = Mage::helper('boldcron/data');
                    
                    $dados_banco = $helper->getBpagData($order->getId());
                    //exit("<pre>".print_r($dados_banco[0], true)."</pre>");
                    if($dados_banco) {
                        $payment_boldcron = $helper->getPayment();
            
                        $url = $payment_boldcron["url"];
                        $merchant = $payment_boldcron["merchant"];
                        $user = $payment_boldcron["user"];
                        $password = $payment_boldcron["password"];
                        
                        $xml_cancel = $helper->createXml("cancel", $dados_banco[0], "Cancel");
                        
                        $retorno_array = $helper->requisicaoWebService($url, "cancel", $merchant, $user, $password, $xml_cancel);
                        
                        $helper->deleteBpagData($order->getId());
                        //echo "<pre>".print_r($retorno_array, true)."</pre>";
                        //exit();
                    }
                }
                $order->cancel()
                    ->save();
                $order->sendOrderUpdateEmail(true);
                $this->_getSession()->addSuccess(
                    $this->__('The order has been cancelled.')
                );
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addError($this->__('The order has not been cancelled.'));
                Mage::logException($e);
            }
            $this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
        }
    }
}