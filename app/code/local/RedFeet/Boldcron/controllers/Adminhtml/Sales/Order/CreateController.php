<?php
require_once 'Mage/Adminhtml/controllers/Sales/Order/CreateController.php';

class RedFeet_Boldcron_Adminhtml_Sales_Order_CreateController extends Mage_Adminhtml_Sales_Order_CreateController
{
    /**
     * Saving quote and create order
     */
    public function saveAction()
    {
        try {
            $this->_processActionData('save');
            if ($paymentData = $this->getRequest()->getPost('payment')) {
                $this->_getOrderCreateModel()->setPaymentData($paymentData);
                $this->_getOrderCreateModel()->getQuote()->getPayment()->addData($paymentData);
            }

            $order = $this->_getOrderCreateModel()
                ->setIsValidate(true)
                ->importPostData($this->getRequest()->getPost('order'))
                ->createOrder();

            $payment_boldcron = Mage::helper('boldcron/data')->getPayment();
	
            $url = $payment_boldcron["url"];
            $merchant = $payment_boldcron["merchant"];
            $user = $payment_boldcron["user"];
            $password = $payment_boldcron["password"];
            
            $xml = Mage::helper('boldcron/data')->createXml("payOrder", "", "PayOrder");
            
            //header('Content-type: text/xml');
            //exit($xml->outputMemory(true));
    
            try {
                $retorno_array = Mage::helper('boldcron/data')->requisicaoWebService($url, "payOrder", $merchant, $user, $password, $xml);	    
                //echo "<pre>".print_r($retorno_array, true)."</pre>";
                //exit;
            } catch (Exception $e) {
                $message = $e->getMessage();
                if( !empty($message) ) {
                    $this->_getSession()->addError($message);
                }
                $this->_redirect('*/*/');
            }

            $this->_getSession()->clear();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The order has been created.'));
            $this->_redirect('*/sales_order/view', array('order_id' => $order->getId()));
        } catch (Mage_Payment_Model_Info_Exception $e) {
            $this->_getOrderCreateModel()->saveQuote();
            $message = $e->getMessage();
            if( !empty($message) ) {
                $this->_getSession()->addError($message);
            }
            $this->_redirect('*/*/');
        } catch (Mage_Core_Exception $e){
            $message = $e->getMessage();
            if( !empty($message) ) {
                $this->_getSession()->addError($message);
            }
            $this->_redirect('*/*/');
        }
        catch (Exception $e){
            $this->_getSession()->addException($e, $this->__('Order saving error: %s', $e->getMessage()));
            $this->_redirect('*/*/');
        }
    }

}