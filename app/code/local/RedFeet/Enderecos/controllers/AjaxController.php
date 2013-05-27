<?php

class RedFeet_Enderecos_AjaxController extends Mage_Core_Controller_Front_Action {
    
	protected $_baseurlConfig;
	
	/**
	 * Metodo executado pela url via ajax para recuperar informacoes do cep do cliente.
	 * 
	 */
	public function indexAction() {
		$this->_baseurlConfig = Mage::getStoreConfig('shipping/autocomplete/base_url');
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_URL, $this->_idUrl() );
		$data = $this->_tratamentoRetorno( curl_exec( $ch ) );
		
		curl_close( $ch );
		echo $data;
    }

    /**
     * Identifica qual base Url deve ser feita a consulta via webservice.
     * 
     */
    protected function _idUrl() {
        return 'http://buscacep.redfeet.com.br/cep.php?cep='.urlencode( $this->getRequest()->getParam('cep', false) );
    }
    
    protected function _tratamentoRetorno( $dados='' ) {
        return $dados;
    	/*switch( $this->_baseurlConfig ) {
			case 1:
				return $dados; 
				break;
			case 2:
				return urldecode( substr($dados, strpos($dados, '{')) ); 
				break;
			case 3:
				return 'locaweb: nao funcionando';
				break;
		}*/
    }

}
