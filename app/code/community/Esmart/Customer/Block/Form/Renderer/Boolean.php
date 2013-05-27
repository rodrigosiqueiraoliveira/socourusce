<?php

class Esmart_Customer_Block_Form_Renderer_Boolean extends Esmart_Eav_Block_Form_Renderer_Boolean
{

	public function _construct()
	{
		$this->setTemplate('esmart/customer/form/renderer/boolean.phtml');
	}

}
