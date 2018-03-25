<?php
class ControlWeb{
	private $bd;
	private $twitter;
	private $memoria;
   private $suscriptor;
   private $vias;
	
	function __construct() {
	
		include_once('class.BD.php');
		include_once('class.Twitter.php');		
		include_once('class.Memoria.php');
		include_once('class.Suscriptor.php');
      		include_once('class.Vias.php');
   		include_once('class.Log.php');   		
   		include_once('class.Correo.php');  
   		include_once ('../vendor/PHPMailer-master/PHPMailerAutoload.php');
		
		$this->bd = new BD();
		$this->twitter = new Twitter();
		$this->memoria = new Memoria();      
      		$this->suscriptor = new Suscriptor();     
      		$this->vias = new Vias();     
      	
   	}

   	function __destruct() {
       
   	}  	


      /**
      *
      *
      *
      */
      public function listarVias(){

         return $this->vias->obtenerVias();
      }
   	
   	/*
   	*
   	* Enviar mensaje de contactenos
   	*/
   	public function enviarMensajeContactenos($nombreCompleto,$correoCompleto,$asuntoCompleto,$mensajeCompleto){
   	
   		$a = new Correo();
		$correo = array("direccion"=>"juanv.cisneros@gmail.com","nombreCompleto"=>"Juan Cisneros");
		
		$a->agregarDirecciones($correo);
		$a->agregarAsunto("OVI - $asuntoCompleto ".date("dmy"));
		$a->agregarMensaje("Mensaje desde Página web
		<br><b>Nombres y Apellidos:</b>$nombreCompleto
		<br><b>Correo electrónico:</b>$correoCompleto
		<br><b>Asunto:</b>$asuntoCompleto
		<br><b>Mensaje:</b>$mensajeCompleto");
		return $a->enviarCorreoElectronico();
   	}
   	
   	
   	/*
   	*
   	* Obtener ultimos tweets analizados para mostrarlos como noticias
   	*/
   	public function obtenerUltimosTweetsAnalizados(){
   	
   		$tweets = $this->memoria->obtenerUltimosMensajesNuevos();
   		foreach($tweets as $fila)
		{ 
	
			$cantidad_total = $fila["cantidad_total"];
			$fecha = $fila["fecha"];
			$ids_tweets = $fila["ids_tweets"];
			$mensaje = "[$fecha] Se han añadido $cantidad_total tweets nuevos desde la última extracción en Twitter " ;
			$mensaje = array("mensaje"=>$mensaje,"ids_tweets"=>$ids_tweets);
			return 	$mensaje;
			
 			
  		}
   	}
   	
   	/*
   	*
   	* Obtener ultimos tweets eliminados para mostrarlos como noticias
   	*/
   	public function obtenerUltimosTweetsEliminados(){
   	
   		$tweets = $this->memoria->obtenerUltimosMensajesEliminados();
   		foreach($tweets as $fila)
		{ 
	
			$cantidad_total = $fila["cantidad_total"];
			$fecha = $fila["fecha"];
			$ids_tweets = $fila["ids_tweets"];
			$mensaje = "[$fecha] Se han eliminado $cantidad_total tweets desde la última extracción en Twitter " ;
			$mensaje = array("mensaje"=>$mensaje,"ids_tweets"=>$ids_tweets);
			return 	$mensaje;
			
 			
  		}
   	}
   	
   	
   	/*
   	*
   	* Obtener el total de tuits analizados
   	*/
   	public function obtenerTotalTuits(){
   	
   		return number_format($this->twitter->total_tuits(),0, '', '.');  
   	
   	}

      /*
      *
      * Obtener el total de suscriptores
      */
      public function obtenerTotalSuscriptores(){
      
         return number_format($this->suscriptor ->total_suscriptores(),0, '', '.');  
      
      }

         /*
      *
      * Obtener el total de tuits analizados
      */
      public function obtenerTotalTuitsAmbito($tx_ambito){
      
         return number_format($this->twitter->total_tuits_ambito($tx_ambito),0, '', '.');  
      
      }
   	
   	/*
   	*
   	* Obtener el total de tuits analizados
   	*/
   	public function obtenerTotalTuitsInteres(){
   	
   		return number_format($this->twitter->total_tuits_interes(),0, '', '.');  
   	
   	}
   	
   	/*
   	*
   	* Obtener los ultimos tuits de interes analizados
   	*/
   	public function obtenerUltimos30TuitsInteres(){
   	
   		return $this->twitter->obtenerUltimos30TuitsInteres(); 
   	
   	}
   	
   	
   	
   	
   	/*
   	*
   	* Obtener el total de usuarios analizados
   	*/
   	public function obtenerTotalUsuarios(){
   	
   		return number_format($this->twitter->total_usuarios(),0, '', '.');  
   	
   	}
   	
   	/*
   	*
   	* Obtener el total de visitas del sitio web
   	*/
   	public function obtenerTotalVisitaSitioWeb(){
   	
   		return number_format($this->bd->verTotalVisitas(),0, '', '.');  
   	
   	}
   	
   	
   	/*
   	*
   	* Registrar estadisticas del sitio web
   	*/
   	public function registrarVisitaSitioWeb($ip, $entorno){
   	
   		$this->bd->registrarVisitasWeb($ip, $entorno);  
   	
   	}
   	
   	/*
   	*
   	* Registrar suscriptores a OVNI
   	*/
   	public function registrarSuscriptor($nombreApellido, $correoElectronico, $preferencias,$preferenciasHora,$telefono,$fecha_nac,$sexo){
   	
   		
   		$s = new Suscriptor();
   		$log = new Log();
		$s->__set("nombreApellido",$nombreApellido);
		$s->__set("correoElectronico",$correoElectronico);
		$s->__set("preferencias",$preferencias);
		$s->__set("preferenciasHora",$preferenciasHora);
      		$s->__set("telefono",$telefono);
      		$s->__set("fecha_nac",$fecha_nac);
      		$s->__set("sexo",$sexo);
      		$s->__set("activo",1);
		
		$suscriptor = $s->agregarSuscriptor();

		if($suscriptor > 0 ){		
		
			$msg = "[NUEVO SUSCRIPTOR] | ".__CLASS__." | ".__METHOD__." | Suscriptor: $correoElectronico añadido satisfactoriamente";
			$log->general($msg);
		
		}
		
		return $suscriptor;
   		
   		
   	
   	}
   	
   	public function deshabilitarSuscriptor($correoElectronico){
   		
   		$s = new Suscriptor();
   		$log = new Log();
   		$suscriptor = $s->deshabilitarSuscriptor($correoElectronico);
   		
   		
   		if($suscriptor >0 ){		
		
			$msg = "[SUSCRIPTOR DESHABILITADO] | ".__CLASS__." | ".__METHOD__." | Suscriptor: $correoElectronico deshabilitado satisfactoriamente";
			$log->general($msg);
		
		}
		
		return $suscriptor;
		
   		
		
   	}
   	
   	
   	/*
   	*
   	* Obtener los tuits de interes
   	*/
   	public function obtenerTuitsInteres(){
   	
   		$sql = $this->bd->crearSelect("tr002_tweet_interes","*","","");		
   		return $this->bd->listarRegistros($sql);
   	
   	}
   	
   	
      /*
      *
      * Obtener los tuits de interes por zona vial
      */
      public function obtenerTuitsInteresZonaVial($zonaVial){
      
               
         if($zonaVial == '') $where = '';
         else  $where = "tx_ambito = '".$zonaVial."'";


         $sql = $this->bd->crearSelect("v013_total_tuits_x_ambito","*",$where,"total DESC");      
         return $this->bd->listarRegistros($sql);
         
      }



      /*
   	*
   	* Obtener los tuits de interes por fecha
   	*/
   	public function obtenerTuitsInteresXFecha($fechaInicio, $fechaFin ){
   	
   		/*$sql = $this->bd->crearSelect("tr002_tweet_interes","*"," date >= '$fechaInicio' AND date <= '$fechaFin' ","");   			 
   		return $this->bd->listarRegistros($sql);*/   		
   		//echo $sql  = "SELECT * FROM tr002_tweet_interes WHERE date >= $fechaInicio AND date <= $fechaFin ";   		 
   		$sql  = "CALL sp_listar_tuits_interes_filtrados(:fechaInicio,:fechaFin)";
   		//$fechaInicio    = "'2015-08-19 18:47:46'";
                //$fechaFin       = "'2015-08-19 22:47:46'";
   		$parametros = array("fechaInicio"=>$fechaInicio,"fechaFin"=>$fechaFin );   		
                $val =  $this->bd->listarRegistrosSeguro($sql,$parametros);
                //print_r($val);
   		return $val;
   		
   	}
   	
   	/*
   	*
   	* Obtener los tuits de interes por fecha y ubicacion
   	*/
   	
   	public function obtenerTuitsInteresDetalle($fechaInicio, $fechaFin, $lat, $lon ){
   	
   		
   		$sql  = "SELECT * FROM tr002_tweet_interes WHERE date >= :fechaInicio AND date <= :fechaFin AND lon = :lon AND lat = :lat ORDER BY date ASC";
   		$parametros = array("fechaInicio"=>$fechaInicio,"fechaFin"=>$fechaFin,"lon"=>$lon ,"lat"=>$lat  );   		
   		return $this->bd->listarRegistrosSeguro($sql,$parametros);
   	
   	
   	}
   	
   	/*
   	*
   	* Obtener los tuits de interes por fecha 
   	*/
   	
   	public function obtenerTuitsInteresIncidente($fechaInicio, $fechaFin ){
   	
   		
   		//echo "SELECT * FROM tr002_tweet_interes WHERE (date >= $fechaInicio AND date <= $fechaFin) AND (lat <> 0 AND lon <> 0) ORDER BY date ASC";
   		$sql  = "SELECT * FROM tr002_tweet_interes WHERE (date >= :fechaInicio AND date <= :fechaFin) AND (lat <> 0 AND lon <> 0) ORDER BY date ASC";
   		$parametros = array("fechaInicio"=>$fechaInicio,"fechaFin"=>$fechaFin);   	
   		//echo $sql;	
   		return $this->bd->listarRegistrosSeguro($sql,$parametros);
   	
   	
   	}
   	
   	
   	/*
   	*
   	* Obtener el detalle del clima por cada ciudad
   	*/
   	
   	public function obtenerClimaDetalleCiudad($fecha, $ciudad) {
   	
   		
   		$sql  = "SELECT * FROM v012_clima_detalle_x_ciudad WHERE nb_ciudad = '$ciudad' AND fecha >= '$fecha'";  
   		//SELECT * FROM `v012_clima_detalle_x_ciudad` WHERE nb_ciudad = "Los Teques, VE" AND fecha >= "20016-06-12" 		 		
   		$clima = $this->bd->listarRegistros($sql);   		
   		
		foreach($clima as $fila)
		{ 			
			$hora_fecha = explode(" ",$fila["fecha"]);
			$hora = explode(":",$hora_fecha[1]);
			$hora_1= $hora[0];
			$temp = $fila["nu_temp"];
			$etiqueta.= "'$hora_1',";
			$valores.= $temp.',';			
			//echo "<br>".$fila["nb_ciudad"]."-".$temp."-".$hora_1;			
		}


		$etiqueta =  trim($etiqueta, ',');
		$valores = trim($valores,',');
		
		return array("etiqueta"=>$etiqueta,"valores"=>$valores);
   	
   	
   	}
   	
   	
   	/*
   	*
   	* Obtener desde una vista de BD la cantidad de incidentes por día
   	*/
   	
   	public function obtenerCantidadIncidentesxDia() {
   	
   		
   		$sql  = "SELECT * FROM v002_resumen_dia_semana_incidente order by dia_semana_incidente";   		 		
   		return $this->bd->listarRegistros($sql);
   	
   	
   	}
   	
   	/*
   	*
   	* Obtener desde una vista de BD la cantidad de clases de incidentes
   	*/
   	
   	public function obtenerCantidadClasesIncidentes() {
   	
   		
   		$sql  = "SELECT * FROM v003_resumen_clase_incidente";   		 		
   		return $this->bd->listarRegistros($sql);
   	
   	
   	}
   	
   	/*
   	*
   	* Obtener desde una vista de BD la cantidad de incidentes por hora
   	*/
   	
   	public function obtenerCantidadIncidentesHora() {
   	
   		
   		$sql  = "SELECT * FROM v004_resumen_incidentes_hora";   		 		
   		return $this->bd->listarRegistros($sql);
   	
   	
   	}
   	
   	/*
   	*
   	* Obtener los usuarios por user_id
   	*/
   	
   	public function obtenerUsuariosxId($user_id){
   	
   		
   		$sql  = "SELECT user_id,screen_name FROM tr002_user WHERE  user_id = :user_id ";
   		$parametros = array("user_id"=>$user_id );   		
   		return $this->bd->listarRegistrosSeguro($sql,$parametros);
   	
   	}


   	

}//fin clase

?>