<?php
include_once("class.Agente.php");
include_once("class.Log.php");
include_once("class.BD.php");
require_once('class.AgenteExtractor.php');
class AgenteOrquestador extends Agente{
	private $log;	
	private $bd;
	
	function __construct() {	
		
		$this->bd = new BD();
		$this->log = new Log();	
            	$fecha = date("Y-m-d H:i:s");	    
	    	$msg = "[AGENTE ORQUESTADOR INICIADO] | $fecha ";
	    	$this->log->general($msg);	
      	
   	}
   	
   	/**
   	* BDI Agente arquitecture
   	*/   	
   	public function ini_bdi(){
   		//informacion del mundo
	   	$this->belief = array(  "nombre_agente"=>"AgenteOrquestador",
	   				"frecuencia_ejecucion_min"=>15,
	   				"entorno"=>"ovi",
	   			        "agentes_mundo"=>array("AgenteClimatico"=>0,"AgenteTuitero"=>0,"AgenteInformador"=>0,"AgenteRevisor"=>0)
	   		     );
   	       //metas a alcanzar	
   	       $this->desire = array("meta1" => "orquestar_agentes");
   	       
   	       //plan de accion para alcanzar la meta
   	       $this->intention = array("orquestar_agentes"=> array ("plan_accion"=>
   	       										array("0" => "iniciar_sincronizacion_agentes")
   	       									)
   	       				);
   	
   	}
   	
   	private function config(){
   		return $agentes = array("agentes_mundo"=>
   						array("AgenteExtractor"=>array("pre"=>0,"post"=>0),
   						/*"AgenteTuitero"=>array("pre"=>0,"post"=>0),
   						"AgenteInformador"=>array("pre"=>0,"post"=>0),
   						"AgenteRevisor"=>array("pre"=>0,"post"=>0),
   						"AgenteClimatico"=>array("pre"=>0,"post"=>0)*/)
   		);
   	
   	}
   	
   	
   	
   	public function iniciar_sincronizacion_agentes(){
   	
   		$config = $this->config();
   		$agentes = $config['agentes_mundo'];
   		foreach($agentes as $clase=>$valor){
   			//echo "<br>".$clase." => ".$valor;
   			
			$agt = new $clase();
			$agt->ini_bdi();
			$frecuencia_ejecucion_min = $agt->belief['frecuencia_ejecucion_min'];
			$nombre_agente = $agt->belief['nombre_agente'];
			echo "<br><b>".$nombre_agente." se ejecuta cada $frecuencia_ejecucion_min min </b>";
			
				//Si la cantidad de minutos entre la ultima accion del agente y la fecha actual 
				//es igual o mayor que la frecuencia de ejecucion en minutos entonces se autoriza 
				if ($this->autorizarAccionAgente($nombre_agente,$frecuencia_ejecucion_min) == TRUE ) {
				
					$metas = $agt->desire;
					$plan_accion = $agt->intention;
	
					foreach($metas as $meta){
						echo "<br> <font color ='red'>Meta Agente ".$meta."</font>";
						$plan = $plan_accion[$meta]['plan_accion'];
						//print_r($plan);
						foreach($plan as $accion){
							echo "<br> Plan Accion => ".$accion;
							$agt->{$accion}();
						}
					}
				
				}//fin autorizarAccionAgente
			
			
			
   		}
   		
   		
   		
   		
   	
   	}

}


?>