<?php
class RedFeet_Boldcron_Model_Source_Types
{
    public function toOptionArray()
    {
        return array(
            array('value'=>"cielows2p_amex", 'label'=>Mage::helper('boldcron')->__('American Express por Cielo')),
            array('value'=>"cielows2p_diners", 'label'=>Mage::helper('boldcron')->__('Diners por Cielo')),
            array('value'=>"cielows2p_elo", 'label'=>Mage::helper('boldcron')->__('ELO por Cielo')),
            array('value'=>"cielows2p_mastercard", 'label'=>Mage::helper('boldcron')->__('Mastercard por Cielo')),
            array('value'=>"cielows2p_visa", 'label'=>Mage::helper('boldcron')->__('VISA por Cielo')),
            array('value'=>"setef_hipercard", 'label'=>Mage::helper('boldcron')->__('Hipercard por Software Express')),
            #array('value'=>"setef_amex", 'label'=>Mage::helper('boldcron')->__('American Express por Software Express')),
            #array('value'=>"amex_webpos", 'label'=>Mage::helper('boldcron')->__('American Express por Web Pos')),
            #array('value'=>"setef_aura", 'label'=>Mage::helper('boldcron')->__('Aura por Software Express')),
            #array('value'=>"setef_diners", 'label'=>Mage::helper('boldcron')->__('Diners Club por Software Express')),
            #array('value'=>"redecard_diners", 'label'=>Mage::helper('boldcron')->__('Komerci RedeCard Diners Club')),
            array('value'=>"setef_mastercard", 'label'=>Mage::helper('boldcron')->__('MasterCard por Software Express')),
            #array('value'=>"redecard_mastercard", 'label'=>Mage::helper('boldcron')->__('Komerci RedeCard MasterCard')),
            #array('value'=>"cielows_mastercard", 'label'=>Mage::helper('boldcron')->__('Cielo E-commerce MasterCard')),
            array('value'=>"setef_visa", 'label'=>Mage::helper('boldcron')->__('VISA por Software Express')),
            #array('value'=>"redecard_visa", 'label'=>Mage::helper('boldcron')->__('Komerci RedeCard Visa')),
            #array('value'=>"cielows_visa", 'label'=>Mage::helper('boldcron')->__('Cielo E-commerce Visa')),
            #array('value'=>"setef_elo", 'label'=>Mage::helper('boldcron')->__('Elo')),
            #array('value'=>"goodcard", 'label'=>Mage::helper('boldcron')->__('Good Card')),
            #array('value'=>"setef_pontocred", 'label'=>Mage::helper('boldcron')->__('Cartão PontoCred por Software Express')),
            #array('value'=>"setef_extra", 'label'=>Mage::helper('boldcron')->__('Cartão Extra por Software Express')),
            #array('value'=>"setef_comprabem", 'label'=>Mage::helper('boldcron')->__('Cartão CompraBem por Software Express')),
            #array('value'=>"setef_paoacucar", 'label'=>Mage::helper('boldcron')->__('Cartão Pão de Açúcar por Software Express')),
            #array('value'=>"setef_sendas", 'label'=>Mage::helper('boldcron')->__('Cartão Sendas por Software Express')),
            #array('value'=>"setef_extrapresentes", 'label'=>Mage::helper('boldcron')->__('Cartão Extra Presentes por Software Express')),
            #array('value'=>"setef_plenocard", 'label'=>Mage::helper('boldcron')->__('Cartão Plenocard processado na Telenet por Software Express')),
            #array('value'=>"setef_personalcard", 'label'=>Mage::helper('boldcron')->__('Cartão Personalcard processado na Softnex por Software Express'))
        );
    }

}