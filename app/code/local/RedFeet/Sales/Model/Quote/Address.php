<?php
class RedFeet_Sales_Model_Quote_Address extends Mage_Sales_Model_Quote_Address
{
    /**
     * Validate address attribute values
     *
     * @return bool
     */
    public function validate()
    {
        $errors = array();
        $helper = Mage::helper('customer');
        $this->implodeStreetAddress();
        if (!Zend_Validate::is($this->getFirstname(), 'NotEmpty')) {
            $errors[] = $helper->__('Please enter the first name.');
        }

        if (!Zend_Validate::is($this->getLastname(), 'NotEmpty')) {
            $errors[] = $helper->__('Please enter the last name.');
        }

        if (!Zend_Validate::is($this->getStreet(1), 'NotEmpty')) {
            $errors[] = $helper->__('Please enter the street.');
        }

        if (!Zend_Validate::is($this->getCity(), 'NotEmpty')) {
            $errors[] = $helper->__('Please enter the city.');
        }

        if (!Zend_Validate::is($this->getTelephone(), 'NotEmpty')) {
            $errors[] = $helper->__('Please enter the telephone number.');
        }
	
	if(!preg_match("(^\([0-9][0-9]\)[0-9]{4}-[0-9]{4}$)", $this->getTelephone()) &&
	   !preg_match("(^\([0-9][0-9]\)[0-9]{5}-[0-9]{4}$)", $this->getTelephone()) ){
	    $errors[] = $helper->__('Por favor informe um n&uacute;mero de telefone v&aacute;lido.');
	}
	
        if($this->getGroupId() == 3){
            if (!Zend_Validate::is($this->getFax(), 'NotEmpty')) {
                $errors[] = $helper->__('Por favor informe o celular.');
            }

            if(Zend_Validate::is($this->getFax(), 'NotEmpty') && 
                    !(preg_match("(^\([0-9][0-9]\)[0-9]{4}-[0-9]{4}$)", $this->getFax()) 
                    //&& preg_match("((\(11\)9(5[0-9]|6[0-9]|7[01234569]|8[0-9]|9[0-9])).+)", $this->getFax())
                    ) &&
                     !preg_match("(^\([0-9][0-9]\)[0-9]{5}-[0-9]{4}$)", $this->getFax()) 
                    ){
                     $errors[] = $helper->__('Por favor informe um n&uacute;mero de celular v&aacute;lido.');
                }
        }
        $_havingOptionalZip = Mage::helper('directory')->getCountriesWithOptionalZip();
        if (!in_array($this->getCountryId(), $_havingOptionalZip) && !Zend_Validate::is($this->getPostcode(), 'NotEmpty')) {
	    $errors[] = $helper->__('Please enter the zip/postal code.');
        }
	elseif(!in_array($this->getCountryId(), $_havingOptionalZip) && !preg_match("(^[0-9]{5}-[0-9]{3}$)", $this->getPostcode()) && Zend_Validate::is($this->getPostcode(), 'NotEmpty')) {
	    $errors[] = $helper->__('Please enter a valid zip/postal code.');
	}


        if (!Zend_Validate::is($this->getCountryId(), 'NotEmpty')) {
            $errors[] = $helper->__('Please enter the country.');
        }

        if ($this->getCountryModel()->getRegionCollection()->getSize()
               && !Zend_Validate::is($this->getRegionId(), 'NotEmpty')) {
            $errors[] = $helper->__('Please enter the state/province.');
        }

        if (empty($errors) || $this->getShouldIgnoreValidation()) {
            return true;
        }
        return $errors;
    }
}
