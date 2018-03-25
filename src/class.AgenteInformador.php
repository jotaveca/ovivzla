<?php

include_once("class.BD.php");
include_once("class.Correo.php");
include_once("class.Suscriptor.php");
include_once("class.Twitter.php");
include_once("class.AgenteRevisor.php");
include_once("class.Log.php");
require_once("class.Rendimiento.php");
include_once ('../vendor/PHPMailer-master/PHPMailerAutoload.php');
class AgenteInformador{

	private $bd;
	private $correo;
	private $suscriptor; //array
	private $suscriptores = array(); //array
	private $asunto;
	private $mensaje;
	private $twitter;
	private $agenteRevisor;
	private $log;	
	private $estadisticas;	
	
	
	
	
	function __construct() {	
		
		$this->bd = new BD();
		$this->correo = new Correo();
		$this->suscriptor = new Suscriptor();
		$this->twitter = new Twitter();
		$this->estadisticas = new Rendimiento();
		//$this->agenteRevisor = new AgenteRevisor(0);
		$this->log = new Log();	
          	$fecha = date("Y-m-d H:i:s");	    
	    	$msg = "[AGENTE INFORMADOR INICIADO] | $fecha ";
	    	$this->log->general($msg);	
		
      	
   	}
   	
   	public function setAsunto($asunto){
   	
   		$this->asunto = $asunto;
   	}
   	
   	public function setMensaje($mensaje){
   	
   		$this->mensaje = $mensaje;
   	}
   	
   	public function obtenerTweetsInteresResumen(){
   		
   		return $this->twitter->obtenerTuitsInteresRecientes();
   		
   	}
   	
   	public function obtenerTweetsInteresResumenAreaInteres($tx_preferencia){
   		
   		return $this->twitter->obtenerTuitsInteresRecientesAreInteres($tx_preferencia);
   		
   	}
   	
   	public function obtenerSuscriptores(){
   		
   		//$this->correoagregarDirecciones();
   		$suscriptores = $this->suscriptor -> obtenerSuscriptoresActivos();
   		
   		foreach($suscriptores as $suscriptor){
			$s = array("direccion"=>$suscriptor["tx_correo_electronico"],"nombreCompleto"=>$suscriptor["tx_nombre_apellido"],"preferenciaHora" => $suscriptor["tx_preferencia_hora"]);
   			array_push($this->suscriptores,$s);
   		}
   	
   		return $this->suscriptores;
   	}
   	
   	public function obtenerSuscriptoresAreaInteres($tx_preferencia){
   		
   		//$this->correoagregarDirecciones();
      $suscriptores = array();
      $dataSuscriptores = array();
   		$suscriptores = $this->suscriptor -> obtenerSuscriptoresActivosAreaInteres($tx_preferencia);
   		
   		foreach($suscriptores as $suscriptor){
			   $s = array("direccion"=>$suscriptor["tx_correo_electronico"],"nombreCompleto"=>$suscriptor["tx_nombre_apellido"],"preferenciaHora" => $suscriptor["tx_preferencia_hora"]);
   			array_push($dataSuscriptores,$s);
   		}
   	
   		return $dataSuscriptores;
   	}
   	
   	/**
   	*
   	* Metodo para enviar correo electronico a los usuarios suscritos
   	*
   	*/
   	public function notificarSuscriptoresEmail(){
   	
   		 $this->notificarPorAreaInteres("#PNM");
       $this->notificarPorAreaInteres("#AGMA");
       $this->notificarPorAreaInteres("#CotaMil");
       $this->notificarPorAreaInteres("#AFF");
       $this->notificarPorAreaInteres("#ARC");
       $this->notificarPorAreaInteres("#PDE");
		
   	}//fin metodo
   	
    /**
    *
    * Verificar si el sitio web esta funcionando
    */

    public function checkWebSite($host, $find) {
      $str = '';
      $fp = fsockopen($host, 80, $errno, $errstr, 10);
      if (!$fp) {
          $m = "$errstr ($errno)\n";
          echo $m;
          $fecha = date("Y-m-d H:i:s"); 
          $msg = "[AGENTE INFORMADOR checkWebSite()] | $fecha | $m";
          $this->log->general($msg);  
      } else {
         $header = "GET / HTTP/1.1\r\n";
         $header .= "Host: $host\r\n";
         $header .= "Connection: close\r\n\r\n";
         fputs($fp, $header);
         while (!feof($fp)) {
             $str .= fgets($fp, 1024);
         }
         fclose($fp);
         return (strpos($str, $find) !== false);
      }
  }


    /*
    * Verificar si el sitio web esta funcionando
    */

    public function monitorizarSitioWeb(){

        $host = 'www.ovi.org.ve';
        $find = 'Observatorio Vial Inteligente (OVI)';

        if (!$this->checkWebSite($host, $find)){

          $suscriptor = array("direccion"=>'juanv.cisneros@gmail.com',"nombreCompleto"=>'Juan Cisneros');
          $this->setMensaje(utf8_decode("Sitio web ovi.org.ve fuera de linea"));       
          $this->correo->agregarDirecciones($suscriptor);
          $this->correo->agregarAsunto('Agente Informador - Sitio web ovi.org.ve fuera de linea');      
          $this->correo->agregarMensaje($this->mensaje);
          $this->correo->enviarCorreoElectronico();
                
        }

    }

 
   	
	/*
	*
	* Notificar por correo electronico a suscriptores dependiende de su area de interes (#PNM, #AGMA)
	*
	*/
	private function notificarPorAreaInteres($tx_preferencia){
		
		$rendimiento_inicio = Rendimiento::obtenerTiempoSeg();
   		
   		/* eliminando tuits repetidos antes de enviar correo */
   		//$this->agenteRevisor->revisarTweetRepetidosUltimaCaptura();
                //$this->agenteRevisor->aplicarMetodosRevision(0);
   		
   		
   		//echo "notificarSuscriptoresEmail";
   		$hora_envio = date("d-m-y h:i a");
   		$this->setAsunto("[OVI] Reporte de incidente vial en la $tx_preferencia: $hora_envio");
   		//$tuits = $this->obtenerTweetsInteresResumen();
   		$tuits = $this->obtenerTweetsInteresResumenAreaInteres($tx_preferencia);
   		//$tuits = $this->twitter->eliminarTuitsRepetidos($tuits);
   		$suscriptores = array();
      $suscriptores = $this->obtenerSuscriptoresAreaInteres($tx_preferencia);
   		
   		if(is_array($tuits)) $m = "Correo enviado"; 
   		else $m = "Correo no enviado"; 
      $t_tuits = count($tuits);

   		
   		echo "<br>Total de tuits obtenidos de obtenerTweetsInteresResumen($tx_preferencia): <b>$m</b> <br>";
   		//var_dump($tuits);
   		//var_dump($this->suscriptores);
   		
   		$fecha = date("Y-m-d H:i:s");	
 		  $msg = "[AGENTE INFORMADOR notificarPorAreaInteres($tx_preferencia)] | $fecha | $m | N° Tuits = $t_tuits";
	    $this->log->general($msg);	

   		   		
   		if(is_array($tuits)){ // tuits nuevos
   		
   		$dia = date("N");//lun a domingo [1-7]
   		$hora = date("G");//hora [0-23]  		
   		//echo "<br> dia: $dia - hora: $hora <br>"; 
   		
   			foreach($suscriptores as $suscriptor){
   			
   				$correo_electronico = $suscriptor["direccion"];   			
   				$preferencia_hora = $suscriptor["preferenciaHora"];
          $nombre_completo = $suscriptor["nombreCompleto"];
   				//echo "<br>".$correo_electronico." - ".$preferencia_hora."<br>";
   				$preferencia_suscriptor_d_h = explode("x", $preferencia_hora);
   				//print_r($preferencia_suscriptor_d_h);
   				
    				// dias
    				if ($preferencia_suscriptor_d_h[0] == 'd_1_5_'){
    					 $dia_inicio = 1; $dia_fin = 5; // 24 horas
    				}
    				if ($preferencia_suscriptor_d_h[0] == 'd_1_7_'){
    					 $dia_inicio = 1; $dia_fin = 7; // 24 horas
    				}
    				
    				
    				// horas
    				if ($preferencia_suscriptor_d_h[1] == '_h_24'){
    					 $hora_inicio_1 = 0; $hora_fin_1 = 24; // 24 horas
    					 $hora_inicio_2 = 0; $hora_fin_2 = 24; // 24 horas
    				}
    				if ($preferencia_suscriptor_d_h[1] == '_h_5_6_7_17_18_19'){
    					 $hora_inicio_1 = 5; $hora_fin_1 = 7; // horas pico
    					 $hora_inicio_2 = 17; $hora_fin_2 = 19; // horas pico
    				}    				
   				
   				  			
   				if ($dia >= $dia_inicio && $dia <= $dia_fin ){
   				
   					//echo "<br>$dia - $dia_inicio - $dia_fin";
   					if (($hora >= $hora_inicio_1 && $hora <= $hora_fin_1) || ($hora >= $hora_inicio_2 && $hora <= $hora_fin_2) ){
   						//echo "<br>$hora - $hora_inicio_1 - $hora_fin_1";
   						//echo "<br>$hora - $hora_inicio_2 - $hora_fin_2";
   						
   						//$texto = $this->obtenerMensaje($tuits, $correo_electronico, $hora_envio);
   						$texto = $this->obtenerMensajeHtml($tuits, $correo_electronico, $hora_envio, $tx_preferencia, $nombre_completo);   						
				
						//echo $texto;
		
   						$this->setMensaje(utf8_decode($texto));  			
  						$this->correo->agregarDirecciones($suscriptor);
						  $this->correo->agregarAsunto($this->asunto);
				
						  $this->correo->agregarMensaje($this->mensaje);
						  $this->correo->enviarCorreoElectronico();
						  //echo "<br>mensaje enviado <br>";
   					}   					
   					 
   				}   				
   			
   			
   			} //fin foreach			
   	
		}else{//fin if
		
			echo "<br> No hay tuits nuevos que reportar por correo electrónico <br>";
		}
		
		 if(is_array($tuits)){
		  	$t_generales = count($tuits);
		  }else{
		  	$t_generales = 0;
		  }
		
		 // Rendimiento para las estadisticas del agente
  		 $rendimiento_fin = Rendimiento::obtenerTiempoSeg();
  		 $memoria_usada = Rendimiento::obtenerMemoriaUsada();
	   	 $tiempo_ejecucion = Rendimiento::tiempoEjecucion($rendimiento_fin, $rendimiento_inicio);	   	 
	   	 $param = "tuits: $t_generales ";
	   	 
	   	 $this->estadisticas->guardarEstadistica(__CLASS__,__METHOD__,$param,$tiempo_ejecucion,$memoria_usada);	
	
	}
   	
   	
   	
   	 
   	
   	
   	public function obtenerMensaje($tuits, $correo_electronico, $hora_envio){
   		
   		$texto = "<h1 href='http://ovi.org.ve' target='_blank'><a>Observatorio Vial Inteligente (OVI)</a></h1>     				
   				 <h2>Reporte de incidentes detectados: $hora_envio </h2>   				
   				 <h3>Listado de tweets de interés</h3>
   				  <ul>";
   				foreach ($tuits as $t){
   					
   					$id_tweet = $t['id_tweet'];
   					$this->twitter->modificarReportadoTweetInteresCorreo($id_tweet);
   					$detalle =  $t['text'];
   					//echo " <br> ===>".$t['text'];
 					$texto .= "<li> $detalle </li>";
 					
 					$fecha = date("Y-m-d H:i:s");	
 					$msg = "[AGENTE INFORMADOR obtenerMensaje()] | $fecha | $detalle | $id_tweet | $correo_electronico";
	    		$this->log->general($msg);	
 					
  				 	
				}
   		
   		$texto .= "</ul> 
				<br>
				<br>
				<br>
				Si quieres obtener mas información visita nuestra página web: www.ovi.org.ve y seguinos en Twitter a través de <a href='https://twitter.com/ovivzla'>@ovivzla</a>
				<br>
				<br>
				<br>
				<b>Cancelar su suscripción</b>
				<br>
				Si usted no desea recibir más información de <b>OVI</b> por favor dirijase a la dirección que aparece a continuación y cancele su suscripción.
				<br>
				<a href='http://ovi.org.ve/src/control_suscriptores.php?ac=deshabilitar&ce=$correo_electronico' >No deseo recibir más notificaciones de OVI</a>";
				
				return $texto;
   	
   	}
   	
   	
   	public function obtenerMensajeHtml($tuits, $correo_electronico, $hora_envio, $tx_preferencia, $nombre_completo){
   		
   			$incidentes = '';
   			
   			/*$incidentes = "
   			<li style='font-size: 14px; line-height: 16px;'><span style='color: rgb(0, 0, 0); font-size: 20px; line-height: 24px;'>Incidente 1.</span><br></li>
	        	<li style='font-size: 14px; line-height: 16px;'><span style='color: rgb(0, 0, 0); font-size: 20px; line-height: 24px;'>Incidente 2</span></li>
	        	<li style='font-size: 14px; line-height: 16px;'><span style='color: rgb(0, 0, 0); font-size: 20px; line-height: 24px;'>Incidente 3</span></li>
	        	";*/
	        	//$correo_electronico = 'juanv.cisneros@gmail.com';
   			//echo "<br>obtenerMensajeHtml<br>";
        //var_dump($tuits);
        //echo "<br><br>";
   			foreach ($tuits as $t){
   					
   					$id_tweet = $t['id_tweet'];
   					$this->twitter->modificarReportadoTweetInteresCorreo($id_tweet);
   					$detalle =  $t['text'];
   					//echo " <br> ===>".$t['text'];
 					$incidentes .= "<li style='font-size: 14px; line-height: 16px;'><span style='color: rgb(0, 0, 0); font-size: 20px; line-height: 24px;'>$detalle.</span><br></li>";
 					
 					$fecha = date("Y-m-d H:i:s");	
 					$msg = "[AGENTE INFORMADOR obtenerMensajeHtml($id_tweet)] | $fecha | $detalle | $id_tweet | $correo_electronico";
	    		$this->log->general($msg);	
 					
  				 	
			}
			
			$url = "http://ovi.org.ve/src/control_suscriptores.php?ac=deshabilitar&ce=$correo_electronico";
   			
   			
   			//$hora_envio = date("d-m-y h:i a");
   		$body = file_get_contents("../web/tpl/notificacion.html");
      $body = str_replace("#SUSCRIPTOR#",ucwords($nombre_completo),$body);
			$body = str_replace("#FECHA#",$hora_envio,$body);
      $body = str_replace("#AMBITO#",$tx_preferencia,$body);
			$body = str_replace("#INCIDENTES#",$incidentes,$body);
			$body = str_replace("#URL#",$url,$body);
			
			//echo $body;			
			return $body;
   	
   	}
   	
   	
   	
   	public function correoPrueba(){
   	
			
			$suscriptor = array("direccion"=>'juanv.cisneros@gmail.com',"nombreCompleto"=>'Juan Cisneros');
			
			$this->setMensaje(utf8_decode("Correo de prueba"));  			
			$this->correo->agregarDirecciones($suscriptor);
			$this->correo->agregarAsunto('Agente Informador Prueba');
	
			$this->correo->agregarMensaje($this->mensaje);
			$this->correo->enviarCorreoElectronico();
			echo "<br>mensaje enviado correoPrueba() <br>";
   	
   	}
   	 	

}//fin clase


//$a = new AgenteInformador();
//$a->obtenerMensajeHtml();
/*$a->notificarSuscriptoresEmail();
$a->obtenerTweetsInteresResumen();*/


?>