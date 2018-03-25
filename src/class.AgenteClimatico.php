<?php
/**
* Clase para obtener desde el sitio web http://openweathermap.org las condiciones climaticas 
* de las ciudades en estudio con el proposito de relacionarlo con las incidencias viales
*
*/
include_once("class.BD.php");
include_once("class.Log.php");
require_once("class.Rendimiento.php");


class AgenteClimatico{

	private $log;	
	private $estadisticas;	
	private $bd;
	private $appid='';
	private $url_owm = 'http://api.openweathermap.org/data/2.5/weather?';
	
	
	function __construct() {
		
		
		$this->bd = new BD();
		$this->estadisticas = new Rendimiento();		
		$this->log = new Log();	
          	$fecha = date("Y-m-d H:i:s");	    
	    	$msg = "[AGENTE CLIMATICO INICIADO] | $fecha ";
	    	$this->log->general($msg);			
		
      }
      
      public function obtenerCiudadesEstudio(){
      
      	//Gran Caracas (24 municipios)
      	$ciudades = array(
      		array("ciudad"=>"Caracas, VE","id"=>"3646738"),
      		array("ciudad"=>"Los Teques, VE","id"=>"3633622"), 
      		array("ciudad"=>"Carrizal, VE","id"=>"3646451"), 
      		array("ciudad"=>"San Antonio de Los Altos, VE","id"=>"3628550"),
      		array("ciudad"=>"Guarenas, VE","id"=>"3640049"),
      		array("ciudad"=>"Guatire, VE","id"=>"3639898"),
      		array("ciudad"=>"La Guaira, VE","id"=>"3637721"),
      		array("ciudad"=>"Charallave, VE","id"=>"3645854"),
      		array("ciudad"=>"Ocumare del Tuy, VE","id"=>"3631412"),
      		array("ciudad"=>"El Hatillo, VE","id"=>"3643031")
      	);
      	
      	return $ciudades;     	
      
      }
      
      public function verClimaCiudad($ciudad){
      
      	$nb_ciudad = $ciudad['ciudad'];
      	$id_ciudad = $ciudad['id'];
      	$url = $this->url_owm.'id='.$id_ciudad.'&appid='.$this->appid.'&lang=es&units=metric';
      	
      	$datos = json_decode(file_get_contents($url),true);
      	
        //print_r($datos);
      	$tx_estado_clima = $datos['weather'][0]['main'];
      	$tx_descripcion_clima = $datos['weather'][0]['description'];
      	$nu_temp = $datos['main']['temp'];
      	$nu_presion_at = $datos['main']['pressure'];
      	$nu_humedad = $datos['main']['humidity'];  
      	//$nu_precipitacion = $datos['main']['precipitation'];
      	
      	
      	$fecha = date("Y-m-d H:i:s");  	      	
      	
      	echo "<br><br> $nb_ciudad = $url";
	echo "<br> weather main: $tx_estado_clima";
	echo "<br> weather description: $tx_descripcion_clima";
      	echo "<br> temp: $nu_temp °C";
      	echo "<br> pressure: $nu_presion_at hpa";
      	echo "<br> humidity: $nu_humedad %";
      	//echo "<br> precipitation: $nu_precipitacion mm";
      	
      	$salida = $this->agregarCiudadClima($id_ciudad, $nb_ciudad, $tx_estado_clima, $tx_descripcion_clima, $nu_temp, $nu_presion_at, $nu_humedad, $fecha);
      	
      
      }  
      
      /**
      * Agregar los valores climaticos de 4 ciudades en la BD
      */
      public function obtenerClimaCiudades(){
      
      	 echo "<br><br> Iniciado AgenteClimatico <br>";
      	 // Rendimiento para las estadisticas del agente
      	 $rendimiento_inicio = Rendimiento::obtenerTiempoSeg();      	 
      	 
      	 $ciudades = $this->obtenerCiudadesEstudio();
      	 $total_ciudades = count($ciudades);
      	 foreach ($ciudades as $ciudad){
      	 
      	 	$this->verClimaCiudad($ciudad);
      
     	 }
     	 
     	 // Rendimiento para las estadisticas del agente
  	 $rendimiento_fin = Rendimiento::obtenerTiempoSeg();
  	 $memoria_usada = Rendimiento::obtenerMemoriaUsada();
	 $tiempo_ejecucion = Rendimiento::tiempoEjecucion($rendimiento_fin, $rendimiento_inicio);	   	 
	 $param = "Total ciudades analizadas: $total_ciudades";	   	 
	 $this->estadisticas->guardarEstadistica(__CLASS__,__METHOD__,$param,$tiempo_ejecucion,$memoria_usada);	   	
      
      } 
      
      public function agregarCiudadClima($id_ciudad, $nb_ciudad, $tx_estado_clima, $tx_descripcion_clima, $nu_temp, $nu_presion_at, $nu_humedad, $fecha){
      	
      		         
      		$fecha = date("Y-m-d H:i:s");
    		$sql = "INSERT INTO tr011_clima (id_ciudad, nb_ciudad, tx_estado_clima, tx_descripcion_clima, nu_temp, nu_presion_at, nu_humedad, fecha) VALUES (:id_ciudad, :nb_ciudad, :tx_estado_clima, :tx_descripcion_clima, :nu_temp, :nu_presion_at, :nu_humedad, :fecha)";			
   		
   		$con = $this->bd->obtenerConexion();						
		$stmt = $con->prepare($sql);
		$stmt->execute(array(':id_ciudad'=>$id_ciudad,':nb_ciudad'=>$nb_ciudad,':tx_estado_clima'=>$tx_estado_clima,
		':tx_descripcion_clima'=>$tx_descripcion_clima,':nu_temp'=>$nu_temp,':nu_presion_at'=>$nu_presion_at,
		':nu_humedad'=>$nu_humedad,':fecha'=>$fecha));	
		
		/*echo "\nPDOStatement::errorInfo():\n";
		$arr = $stmt->errorInfo();
		print_r($arr);*/
				  
		return $lastId = $con->lastInsertId();
		
		
      
      }
      

}

//$a = new AgenteClimatico();
//$a->verClimaCiudad(array("ciudad"=>"Caracas, VE","id"=>"3646738"));

?>