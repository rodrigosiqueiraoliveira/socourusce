<?php

class Esmart_Customer_Block_Form_Renderer_Multiselect extends Esmart_Eav_Block_Form_Renderer_Multiselect
{

	public function _construct()
	{
		$this->setTemplate('esmart/customer/form/renderer/multiselect.phtml');
	}

}
