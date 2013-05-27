<?php
class RedFeet_Boldcron_Model_Source_PaymentTypes
{
    public function toOptionArray()
    {
        return array(
            array('value'=>"cartao_de_credito", 'label'=>Mage::helper('boldcron')->__('Cartão de Crédito')),
            array('value'=>"debito_online", 'label'=>Mage::helper('boldcron')->__('Débito Online')),
            array('value'=>"boleto_bancario", 'label'=>Mage::helper('boldcron')->__('Boleto Bancário'))
        );
    }

}