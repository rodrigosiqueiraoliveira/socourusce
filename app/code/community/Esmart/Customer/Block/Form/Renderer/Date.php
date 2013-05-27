<?php

class Esmart_Customer_Block_Form_Renderer_Date extends Esmart_Eav_Block_Form_Renderer_Date
{

	public function _construct()
	{
		$this->setTemplate('esmart/customer/form/renderer/date.phtml');
	}

}
