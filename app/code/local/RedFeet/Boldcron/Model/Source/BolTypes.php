<?php
class RedFeet_Boldcron_Model_Source_BolTypes
{
    public function toOptionArray()
    {
        return array(
            array('value'=>"boleto_bradesco", 'label'=>Mage::helper('boldcron')->__('Boleto Bradesco')),
            array('value'=>"boleto_itau", 'label'=>Mage::helper('boldcron')->__('Boleto Itaú'))
        );
    }

}