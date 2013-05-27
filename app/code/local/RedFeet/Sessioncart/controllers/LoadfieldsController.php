<?php

class RedFeet_Sessioncart_LoadfieldsController extends Mage_Core_Controller_Front_Action
{
    private $helper;
    
    public function _construct()
    {
            $this->helper = Mage::helper('sessioncart/data');		
    }
    
    public function getFieldsAction() {
        if(count($_POST) > 0){
            $idProduto = $_POST["id_produto"];
            $cor = $_POST["cor"];
            $tam = $_POST["tam"];
            $qty = trim($_POST["qty"]);
            Mage::app();
            //Mage::getSingleton('core/session')->clear();
            //exit();
            
            $dados = Mage::getSingleton('core/session')->getProdutosSession();
            if(!isset($dados) || empty($dados)) {
                $dados[0]["id_produto"] = $idProduto;
                $dados[0]["cor"] = $cor;
                $dados[0]["tam"] = $tam;
                $dados[0]["qty"] = $qty;
            } else {
                $key = array_keys($dados);
                $size = sizeof($key);
                $att = false;
                $arr = array();
                for($i = 0; $i < $size; $i++) {
                    if($dados[$i]['id_produto'] == $idProduto && $dados[$i]["cor"] == $cor && $dados[$i]["tam"] == $tam) {
                        if($qty > 0)
                            $dados[$i]["qty"] = $qty;
                        elseif($qty == 0 || $qty == "") {
                            $key2 = array_keys($dados);
                            $size2 = sizeof($key2);
                            for($k = 0; $k < $size2; $k++) {
                                if($dados[$i]["cor"] != $dados[$k]["cor"] || $dados[$i]["tam"] != $dados[$k]["tam"])
                                    $arr[] = $dados[$k];
                            }
                            unset($dados);
                            $dados = $arr;
                        }
                        $att = true;
                    }
                }
                if($att == false) {
                    $dados[$size]["id_produto"] = $idProduto;
                    $dados[$size]["cor"] = $cor;
                    $dados[$size]["tam"] = $tam;
                    $dados[$size]["qty"] = $qty;
                }
            }
            
            $array_teste = $dados;
            //exit("<pre>".print_r($array_teste, true)."</pre>");
            
            Mage::getSingleton('core/session')->setProdutosSession($array_teste);
        }
    }
    
    public function getMessageErrorAction()
    {
        $var = Mage::getSingleton('core/session')->getMessageErrorSession();
        $this->helper->unsetMessageError();
        echo $var;
    }
    /*
    public function getInfoCartAction()
    {
        $var = Mage::getSingleton('core/session')->getInfoCartSession();
        echo $var;
    }
    */
}