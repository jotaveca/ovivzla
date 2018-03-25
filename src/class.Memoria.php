<?php
include_once("class.BD.php");
class Memoria{


	private $bd;	
	
	function __construct() {	
		
		$this->bd = new BD();
		     	
   	}
   	
   	/**
   	* Registrar accion en memoria
   	*/
   	public function registrarEnMemoriaActualizado( ){
    
		$ids_tweets	= array();
		$cantidad = count($ids_tweets);
		$accion = 'actualizado';
    		$fecha = date("Y-m-d H:i:s");
     		$sql = "INSERT INTO tr006_memoria_ovni (ids_tweets, cantidad, accion, fecha) VALUES (:ids_tweets, :cantidad, :accion, :fecha)";		
     		
   		
   		$con = $this->bd->obtenerConexion();						
		$stmt = $con->prepare($sql);
		 $stmt->execute(array(':ids_tweets'=>$ids_tweets,':cantidad'=>$cantidad,':accion'=>$accion,':fecha'=>$fecha));	
				   
		return $lastId = $con->lastInsertId();
    
   	 }
   	
   	public function actualizarUltimosMensajes($reportado) {
		
					
			$sql = "UPDATE tr006_memoria_ovni SET reportado =:reportado WHERE reportado = 0";	
			
			$con = $this->bd->obtenerConexion();						
			$stmt = $con->prepare($sql);		
						 
			$salida = $stmt->execute(array(':reportado'=>$reportado));
			
			//registrar la accion en la memoria
			$this->registrarEnMemoriaActualizado();
				   
		  
			return $salida;	
		
	}
	
   	public function obtenerUltimosMensajesNuevos(){
   	
   		$sql  = "SELECT SUM(cantidad) as cantidad_total, fecha, GROUP_CONCAT(ids_tweets SEPARATOR ', ') as ids_tweets  FROM tr006_memoria_ovni  WHERE accion = :accion AND reportado = :reportado AND cantidad > 0 ORDER BY fecha ASC";
   		
   		   		
   		$parametros = array("accion"=>'nuevo',"reportado" => "0" );   		
   		return $this->bd->listarRegistrosSeguro($sql,$parametros);
   	}
   	
   	public function obtenerUltimosMensajesEliminados(){
   	
   		$sql  = "SELECT SUM(cantidad) as cantidad_total, fecha, GROUP_CONCAT(ids_tweets SEPARATOR ', ') as ids_tweets  FROM tr006_memoria_ovni  WHERE accion = :accion AND reportado = :reportado AND cantidad > 0 ORDER BY fecha ASC";
   		
   		   		
   		$parametros = array("accion"=>'eliminado',"reportado" => "0" );   		
   		return $this->bd->listarRegistrosSeguro($sql,$parametros);
   	}
   	
   	
   	
 }//fin clase
 
// $a = new Memoria();
//var_dump( $a->obtenerUltimosMensajesNuevos());

//var_dump( $a->ActualizarUltimosMensajes(1));
 
 ?>

