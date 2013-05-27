<?php
class RedFeet_Boldcron_Model_Source_DebTypes
{
    public function toOptionArray()
    {
        return array(
            #array('value'=>"bradesco", 'label'=>Mage::helper('boldcron')->__('Bradesco')),
            #array('value'=>"bb_debito", 'label'=>Mage::helper('boldcron')->__('Banco do Brasil')),
            #array('value'=>"banrisul_pgta", 'label'=>Mage::helper('boldcron')->__('Banrisul')),
            #array('value'=>"hsbc", 'label'=>Mage::helper('boldcron')->__('HSBC')),
            #array('value'=>"itau", 'label'=>Mage::helper('boldcron')->__('Itaú')),
            #array('value'=>"cielows_maestro", 'label'=>Mage::helper('boldcron')->__('Maestro')),
            array('value'=>"cielows_visaelectron", 'label'=>Mage::helper('boldcron')->__('VISA Electron'))
        );
    }

}