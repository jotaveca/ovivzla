<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class
 *
 * @author elias
 */
class Incidente {
    
    private $bd;
    private $log;
    private $text;
    private $date;
    private $clase_incidente;
    private $tx_ambito;
    private $lugar;
    private $km_aprox;
    private $name;
    private $screen_name;
	
	function __construct() {
	
            include_once("class.Log.php");
            $this->log = new Log();	
            $fecha = date("Y-m-d H:i:s");	    
	    $msg = "[Incidente] | $fecha ";
	    $this->log->general($msg);            
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

          /**
      * Obtener tuits de interés desde los últimos N minutos
      * Usado por Agente Informador
      */
      public function obtenerIncidentesRecientesAreaInteres($tx_ambito, $minutos=/*720*//*1440*/2880){        
        
        $minutos_atras = "- $minutos minute";                
                $fecha_actual = date("Y-m-d H:i:s");
               // echo "<br><br>fecha_actual: ".$fecha_actual;
        //$nuevafecha = strtotime ( '-35 minute' , strtotime ( $fecha_actual ) ) ;
                $nuevafecha = strtotime ( $minutos_atras , strtotime ( $fecha_actual ) ) ;
        
        $nuevafecha = date("Y-m-d H:i:s", $nuevafecha );
        //echo "<br><br> nuevafecha: ".$nuevafecha;
        //$nuevafecha = '2015-05-18 08:35:17';
         
                
                $sql  = "SELECT * FROM v005_tuits_interes_usuario WHERE date >= :fecha AND tx_ambito = :ambito";      
                //$sql  = "SELECT * FROM v005_tuits_interes_usuario WHERE tx_ambito = :ambito";      
          
                //die(); 
              //  echo $sql2 = "<br>SELECT * FROM v005_tuits_interes_usuario WHERE date >= $nuevafecha  AND tx_ambito = $tx_ambito <br>";     
              //echo "<br>".date("Y-m-d H:i:s"); 
              $parametros = array("fecha"=> $nuevafecha, "ambito"=>$tx_ambito);     
      
      
              $fecha = date("Y-m-d H:i:s"); 
              $msg = "[Incidente obtenerIncidentesRecientesAreInteres()] | $fecha | $sql2 ";
              $this->log->general($msg);  
      
             
               return $this->bd->listarRegistrosSeguro($sql,$parametros);
      
      
      }


        
        /**
    	* Obtener tuits de interés desde los últimos N minutos
    	* Usado por Agente Informador
    	*/
    	public function obtenerTuitsInteresRecientesAreInteres($tx_ambito, $minutos=45){    		
	     	
	     	$minutos_atras = "- $minutos minute";                
                $fecha_actual = date("Y-m-d H:i:s");
               // echo "<br><br>fecha_actual: ".$fecha_actual;
	     	//$nuevafecha = strtotime ( '-35 minute' , strtotime ( $fecha_actual ) ) ;
                $nuevafecha = strtotime ( $minutos_atras , strtotime ( $fecha_actual ) ) ;
	     	
	     	$nuevafecha = date("Y-m-d H:i:s", $nuevafecha );
	     	//echo "<br><br> nuevafecha: ".$nuevafecha;
	     	//$nuevafecha = '2015-05-18 08:35:17';
	     	 
	     		     	
	     	//$sql  = "SELECT * FROM v005_tuits_interes_usuario WHERE date >= :fecha AND tx_ambito = :ambito";   		
                //$sql  = "SELECT * FROM v005_tuits_interes_usuario WHERE tx_ambito = :ambito";   		
                $sql  = "SELECT * FROM v005_tuits_interes_usuario WHERE tx_ambito = '$tx_ambito'";
                //echo "<br>".$sql;
                //die(); 
                //echo 	$sql2 = "<br>SELECT * FROM v005_tuits_interes_usuario WHERE tx_ambito = '$tx_ambito' <br>";   	
   		//echo "<br>".date("Y-m-d H:i:s"); 
   		$parametros = array("fecha"=> $nuevafecha, "ambito"=>$tx_ambito);   	
   		
   		
   		$fecha = date("Y-m-d H:i:s");	
 		$msg = "[TWITTER obtenerTuitsInteresRecientes()] | $fecha | $sql2 ";
	    	$this->log->general($msg);	
   		
   			
   		//return $this->bd->listarRegistrosSeguro($sql,$parametros);
                return $this->bd->listarRegistros($sql);
   		
    	
    	}
}

/*$incidente = new Incidente();
$result = $incidente->obtenerTuitsInteresRecientesAreInteres("#PNM", 320);
var_dump($result);
print_r($result);
echo $result;
*/