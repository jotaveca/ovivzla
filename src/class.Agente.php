<?php
include_once("class.BD.php");

Class Agente{
	
	private $bd;

	public function registrarAccionAgente($tx_agente,$frecuencia){  		
   		
   		$this->bd = new BD();   	
   		$fecha = date("Y-m-d H:i:s");
   		
   		//Si existe actualizamos la hora de ejecucion de la ultima accion
   		if($this->existeAccionAgente($tx_agente) == TRUE ){
   			
   			$sql = "UPDATE tr012_cron_agente SET fecha =:fecha WHERE tx_agente = '$tx_agente'";				
			$con = $this->bd->obtenerConexion();						
			$stmt = $con->prepare($sql);						 
			$salida = $stmt->execute(array(':fecha'=>$fecha));				   
		  
		return $salida;	
   		
   		}else{ // Si no existe creamos la accion en BD
   			  			
   			
    			$sql = "INSERT INTO tr012_cron_agente (tx_agente,fecha,nu_frecuencia) VALUES (:tx_agente, :fecha, :nu_frecuencia)";	   		
   			$con = $this->bd->obtenerConexion();						
			$stmt = $con->prepare($sql);
			$stmt->execute(array(':tx_agente'=>$tx_agente,':fecha'=>$fecha,':nu_frecuencia'=>$frecuencia));	
				   
			/*echo "\nPDOStatement::errorInfo():\n";
			$arr = $stmt->errorInfo();
			print_r($arr);   */
			
			return $lastId = $con->lastInsertId();
				   
   		
   		}
   		
   		
		
   	
   	}
   	
   	public function autorizarAccionAgente($tx_agente, $frecuencia_ejecucion_min ){  		
   		
   		$this->bd = new BD();   
   		
   		$sql  = "SELECT * FROM tr012_cron_agente WHERE tx_agente = '$tx_agente'";
		//echo "<br>".$sql;
   		$resultado =  $this->bd->listarRegistros($sql);
   		
   		$fecha_bd = $resultado[0]['fecha'];   		
   		
   		$fecha_almacenada = date("Y-m-d H:i:s",strtotime($fecha_bd));
    		$time_difference = time() - strtotime($fecha_almacenada) ; 
    		echo "<br>now()".date("Y-m-d H:i:s")." - ".$fecha_bd ;   		
    		echo "<br> seg".$seconds = $time_difference ; 
		echo  "<br> min ".$minutes = round($time_difference / 60 ); 
		
		//Si la cantidad de minutos entre la ultima accion del agente y la fecha actual es igual o mayor que la frecuencia de ejecucion en minutos entonces se autoriza 
		if($minutes >= $frecuencia_ejecucion_min ){		
			return true;
		}else{
			return false;
		}
    
    
   		//abs((strtotime($fecha1) - strtotime($fecha2))/86400);  
   		//echo $segundos/180;
   		
   		//print_r($resultado);
   			
   	}
   	
   	public function existeAccionAgente($tx_agente){  		
   		
   		$this->bd = new BD();   
   		
   		$sql  = "SELECT * FROM tr012_cron_agente WHERE tx_agente = '$tx_agente'";
		//echo "<br>".$sql;
   		$resultado =  $this->bd->listarRegistros($sql);
   		
   		if(is_array($resultado)){
   			return true;
   		}else{
   			return false;
   		}
   		
   		//print_r($resultado);
   			
   	}
   	
}

?>