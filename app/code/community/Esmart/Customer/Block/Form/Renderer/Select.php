<?php

class Esmart_Customer_Block_Form_Renderer_Select extends Esmart_Eav_Block_Form_Renderer_Select
{

	public function _construct()
	{
		$this->setTemplate('esmart/customer/form/renderer/select.phtml');
	}

}
