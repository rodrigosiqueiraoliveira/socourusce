<?php

class Esmart_Customer_Block_Form_Renderer_Text extends Esmart_Eav_Block_Form_Renderer_Text
{

	public function _construct()
	{
		$this->setTemplate('esmart/customer/form/renderer/text.phtml');
	}

}
