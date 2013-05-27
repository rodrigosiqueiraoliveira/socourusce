<?php

class RedFeet_Sessioncart_IndexController extends Mage_Core_Controller_Front_Action
{
     public function loadPriceQntAction()
     {
	  $retorno_qnt = Mage::getSingleton('core/session')->getQuantidade();
	  $retorno_total = Mage::getSingleton('core/session')->getTotal();
	  
	  $qnt_atual = $retorno_qnt + $_GET['qnt'];
	  $total_atual = $retorno_total + $_GET['price'];
	  
	  echo "
	       <div id='divResultado'>
		    <div id='qnt'>
			Total Un.: ".$qnt_atual."
		    </div>
		    
		    <div id='total'>
			Total: ".$total_atual."
		    </div>
		</div>
	  ";
	  
	  Mage::getSingleton('core/session')->setQuantidade($qnt_atual);
	  Mage::getSingleton('core/session')->setTotal($total_atual);
     }
}