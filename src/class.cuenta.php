<?php


/**
 * Cuenta de institución o persona
 */


class Cuenta
{

	/** Atributos */

	private $atributos = array();
	private $cuentaTwitter;
	private $id;
	private $nombre;
	private $locacion;
	private $seguidores;
	private $descrp;
	private $esAutoridad;
        private $esSeguidor;
	

	function __construct() {
		
	}

	public function get_cuentaTwitter(){
		return $this->cuentaTwitter;
	}
	public function get_id(){
		return $this->id;
	}
	public function get_nombre(){
		return $this->nombre;
	}
	public function get_locacion(){
		return $this->locacion;
	}
	public function get_seguidores(){
		return $this->seguidores;
	}
	public function get_descrp(){
		return $this->descrp;
	}
	public function get_esAutoridad(){
		return $this->esAutoridad;
	}
        
        public function get_esSeguidor(){
            return $this->esSeguidor;
        }
	
	public function set_cuentaTwitter($cuentaTwitter){
		$this->cuentaTwitter = $cuentaTwitter;
	}
	public function set_id($id){
		$this->id = $id;
	}
	public function set_nombre($nombre){
		$this->nombre = $nombre;
	}
	public function set_locacion($locacion){
		$this->locacion = $locacion;
	}
	public function set_seguidores($seguidores){
		$this->seguidores = $seguidores;
	}
	public function set_descrp($descrp){
		$this->descrp = $descrp;
	}
	public function set_esAutoridad($esAutoridad){
		$this->esAutoridad = $esAutoridad;
	}
	
        public function set_esSeguidor($param) {
            $this->esSeguidor = $param;
        }
        
	public function a_cadena(){
		
		return $this->id . ";" . 
				$this->cuentaTwitter . ";" . 
				$this->nombre . ";" .
				$this->locacion . ";" .
				$this->seguidores . ";" .
				$this->descrp . ";" .
				$this->esAutoridad;
		
	}
	

}




?>
