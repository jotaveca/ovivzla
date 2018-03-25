<?php
class Vias{

	private $nombre;
	private $siglas;
	private $activo;
	private $estado; 
	private $bd;
	
	function __construct() {
	
		require_once('class.BD.php');
		
		$this->bd = new BD();
      	
   	}
   	
   	public function __set($property, $value) {
        	if (property_exists($this, $property)) {
	            $this->$property = $value;
        	}
    	}
    	
    	public function __get($property) {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
   	 }
   	

   	function __destruct() {
       
   	}
   	
   	public function obtenerVias(){
   	
   		$sql  = "SELECT * FROM tr014_vias";
		 //echo $sql;
   		$listado =  $this->bd->listarRegistros($sql);
		  return $listado;
   	
   	}  	
  

}



?>