<?php
include_once("class.BD.php");
class Rendimiento
{
 	private $bd;

	function __construct() {
		$this->bd = new BD();
	}
	
   /**
    * Get current microtime as a float. Can be used for simple profiling.
    */
   static public function obtenerTiempoSeg() {
      return microtime(true);
   }
 
   /**
    * Return a string with the elapsed time.
    * Order of $end and $start can be switched.
    */
   static public function tiempoEjecucion($end, $start) {
      return sprintf("%.4f sec.", abs($end - $start));
   }
   
    public function medirRendimientoQuery() {
      
      $this->bd->medirRendimientoQuery();
   }
   
   static public function mostrarRendimientoQuery() {
     
      $this->bd->mostrarRendimientoQuery();
   }
   
   static public function obtenerMemoriaUsada(){
   
   	$mem_usage = memory_get_usage(true); 
   	return round($mem_usage/1048576,2) ;
   }
   
    
   public function guardarEstadistica($tx_clase,$tx_metodo,$tx_param,$nu_cant_seg_ejec,$nu_memoria_usada_mb){
   
   	$sql = "INSERT INTO tr003_estadisticas (tx_clase,tx_metodo,tx_param,fe_corrida,nu_cant_seg_ejec,nu_memoria_usada_mb) VALUES (:tx_clase,:tx_metodo,:tx_param,:fe_corrida,:nu_cant_seg_ejec,:nu_memoria_usada_mb)";			
   		
   	$fe_corrida = date("Y-m-d H:i:s");     	
   	
   	$con = $this->bd->obtenerConexion();						
	$stmt = $con->prepare($sql);
	$stmt->execute(array(':tx_clase'=>$tx_clase,':tx_metodo'=>$tx_metodo,':tx_param'=>$tx_param,':fe_corrida'=>$fe_corrida,':nu_cant_seg_ejec'=>$nu_cant_seg_ejec,':nu_memoria_usada_mb'=>$nu_memoria_usada_mb));	
				   
	return $lastId = $con->lastInsertId();
		
	}
   
}


?>