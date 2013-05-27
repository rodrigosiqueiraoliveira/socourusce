<?php
class Esmart_DefaultSetup_Model_Locale extends Mage_Core_Model_Abstract
{

	protected $_defaultLocale = 'pt_BR';

	public function changeLocale($locale = null)
	{
		if(!$locale) {
			$locale = $this->_defaultLocale;
		}

		Mage::getSingleton('adminhtml/session')->setLocale($locale);
	}

}