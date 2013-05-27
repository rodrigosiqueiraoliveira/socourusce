<?php

class Esmart_Customer_Block_Form_Renderer_Textarea extends Esmart_Eav_Block_Form_Renderer_Textarea
{

	public function _construct()
	{
		$this->setTemplate('esmart/customer/form/renderer/textarea.phtml');
	}

}
