<?php
class PalabraInteres{

	private $tx_nb_palabra_interes;
	private $tx_clase_palabra_interes;
	private $tx_tipo_palabra_interes;
	private $bd;
  private $log; 
	
	function __construct() {
	
		require_once('class.BD.php');
    include_once("class.Log.php");
		
		$this->bd = new BD();
    $this->log = new Log(); 
      	
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

     	public function obtenerPalabrasInteresTodo(){
   	
   		$sql  = "SELECT * FROM tr015_palabras_interes";		
   		$palabraInteres =  $this->bd->listarRegistros($sql);
		return $this->bd->utf8_converter($palabraInteres);
      
   	
   	}
   	
   	
   	
   	public function obtenerPalabrasInteres(){
   	
   		$sql  = "SELECT * FROM tr015_palabras_interes WHERE tx_tipo_palabra_interes='palabra'";		
   		$palabraInteres =  $this->bd->listarRegistros($sql);
		return $this->bd->utf8_converter($palabraInteres);
      
   	
   	} 	
   	
   	public function obtenerPalabrasInteresVehiculo(){
   	
   		$sql  = "SELECT * FROM tr015_palabras_interes WHERE tx_tipo_palabra_interes='vehiculo'";		
   		$palabraInteres =  $this->bd->listarRegistros($sql);
		return $this->bd->utf8_converter($palabraInteres);
      
   	
   	}
   	

}?>