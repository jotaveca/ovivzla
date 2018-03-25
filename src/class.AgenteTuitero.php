<?php
include_once("class.BD.php");
include_once("class.Suscriptor.php");
include_once("class.AgenteRevisor.php");
include_once("class.Twitter.php");
include_once("class.Log.php");
include_once("class.GoogleUrl.php");
require_once("class.Rendimiento.php");

class AgenteTuitero{

	private $bd;	
	private $suscriptor; //array
	private $suscriptores = array(); //array	
	private $mensaje;
	private $twitter;	
	private $log;	
	private $google;
	private $agenteRevisor;
	private $estadisticas;
	private $belief; // BDI Agente arquitecture
	private $desire; // BDI Agente arquitecture
	private $intention; /// BDI Agente arquitecture
	
	function __construct() {	
		
            $this->bd = new BD();		
            $this->suscriptor = new Suscriptor();
            $this->twitter = new Twitter();	
            $this->estadisticas = new Rendimiento();
            
            //$this->agenteRevisor = new AgenteRevisor(0);
            $this->log = new Log();	
            $fecha = date("Y-m-d H:i:s");	    
	    $msg = "[AGENTE TUITERO INICIADO] | $fecha ";
	    $this->log->general($msg);	
      	
   	}  	
   	
   	/**
   	* BDI Agente arquitecture
   	*/   	
   	private function inicializarBDI(){
   		//informacion del mundo
	   	$this->belief = array(  "nombre_agente"=>"AgenteTuitero",
	   				"ejecucion_min"=>15,
	   				"entorno"=>"ovi",
	   			        "agentes_mundo"=>array("AgenteClimatico"=>0,"AgenteExtractor"=>0,"AgenteInformador"=>0,"AgenteRevisor"=>0)
	   		     );
   	       //metas a alcanzar	
   	       $this->desire = array(	"meta_primaria" => "informar_incidente_vial_x_tuiter",
   	       				"meta_secundaria" => "crear_conciencia_vial_x_tuiter");
   	       
   	       //plan de accion para alcanzar la meta
   	       $this->intention = array("paso1" => "validar_conexion_api_ok","paso2" => "obtener_nuevos_tuits","paso3"=>"alertar_incidentes_vial_x_tuiter");
   	
   	}
      	
   	public function setMensaje($mensaje){
   	
   		$this->mensaje = $mensaje;
   	} 
   	
   	public function obtenerFechaHoraActual(){
   	
   		setlocale(LC_TIME,"es_VE");
   		//strftime("%A, %d de %B de %Y");
   		//$fechaHora = strftime("%d de %B de %Y");
         //$dia = 
   		$fechaHora = strftime("#%d%B%Y");
   		//echo $fechaHora;
		return $fechaHora;
   	
   	} 	   	
   	
   	/*
   	 * 
   	 * */
   	
   	public function obtenerSuscriptoresTwitter(){
   		
   		//$this->correoagregarDirecciones();
   		$suscriptores = $this->suscriptor -> obtenerSuscriptoresActivos();
   		
   		foreach($suscriptores as $suscriptor){
			$s = array("direccion"=>$suscriptor["tx_correo_electronico"],"nombreCompleto"=>$suscriptor["tx_nombre_apellido"],"preferenciaHora" => $suscriptor["tx_preferencia_hora"]);
   			array_push($this->suscriptores,$s);
   		}
   	
   		return $this->suscriptores;
   	}
   	
   	/* Retorna un mensaje de invitacion de forma aletoria
   	 * 
   	 * 
   	 * */
   	
   	private function obtenerMensajeSeguir(){
		
		$mensajes =   array("#Ovi te informa sobre la #PNM",
                                    "Mi interés es informar sobre la #PNM #Ovi",
                                    "Ayúdame a informar sobre la #PNM #Ovi",
                                    "Somos muchos, únete tu también al reporte de la #PNM #Ovi",
                                    "Subiendo o bajando, te reporto sobre la #PNM #Ovi",
                                    "Sígueme si te interesa estar informado sobre la #PNM #Ovi",
                                    "Con tu ayuda mejoramos nuestros reportes sobre la #PNM #Ovi",                                    
                                    "Se parte de los reportes de la #PNM #Ovi");
		$indice = mt_rand(0,7);
		$msj = $mensajes[$indice];
		return $msj;
		
	}
		
	
	/* Retorna una etiqueta vial aleatoria
   	 * 
   	 * 
   	 * */
   	
   	private function obtenerEtiquetaVial(){
		
		$mensajes =   array("#SeguridadVial",
				   "#HábitoVial",
				   "#CulturaVial",
				   "#ConscienciaVial",
				   "#RespetaLasNormas",
				   "#VidaSegura",
				   "#ConductorResponsable");
		$indice = mt_rand(0,6);
		$msj = $mensajes[$indice];
		return $msj;
		
	}
	
	/* Retorna una etiqueta vial aleatoria
   	 * 
   	 * 
   	 * */
   	
   	private function obtenerEtiquetaVialCorta(){
		
		$mensajes =   array("#SeguridadVial",
				   "#HábitoVial",
				   "#CulturaVial",
				   "#ConscienciaVial",				   
				   "#VidaSegura");
		$indice = mt_rand(0,4);
		$msj = $mensajes[$indice];
		return $msj;
		
	}
	
	/* Retorna un mensaje de seguridad vial de forma aletoria
   	 * 
   	 * 
   	 * */
   	
   	private function obtenerMensajeSeguridadVial($tx_ambito){
		
		$mensajes =   	               array( 
                                    array("txt"=>"#Ovi En 2015 el 56% de los incidentes viales se produjeron los fines de semana #Vzla #Precaución $tx_ambito"									,"img"=>"../web/images/img-twitter/m11-campana-ovi.jpg"			,"rsm"=>"#OVI #ConscienciaVial #RespetaLasNormas #ConductorResponsable #SeguridadVial $tx_ambito"),
                                    array("txt"=>"#Ovi En 2015 el 81% de los incidentes viales registrados ocurrieron por imprudencia #Precaución $tx_ambito #Vzla"								,"img"=>"../web/images/img-twitter/m12-campana-ovi.jpg"			,"rsm"=>"#OVI #SeguridadVial #ConscienciaVial #RespetaLasNormas #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"#Ovi Conoce los resultados del III Informe de seguridad vial en Venezuela http://goo.gl/JQGZe5 $tx_ambito"									,"img"=>"../web/images/img-twitter/3-informe-seg-vial-vzla.jpg"		,"rsm"=>"#OVI #SeguridadVial Cónoce el III Informe Seguridad Vial #Vzla $tx_ambito http://goo.gl/JQGZe5"),
                                    array("txt"=>"#Ovi Creada la Red Venezolana de Seguridad Vial en Venezuela http://goo.gl/41rS8k $tx_ambito"											        ,"img"=>"../web/images/img-twitter/twitter-venezuela.jpg"		,"rsm"=>"#OVI #SeguridadVial Creada la Red Venezolana de Seguridad Vial en $tx_ambito http://goo.gl/41rS8k"),
                                    array("txt"=>"Recuerda encender tus luces cuando conduzcas con lluvia, es por tu seguridad y la de los demás #Ovi $tx_ambito"								,"img"=>"../web/images/img-twitter/m13-campana-ovi.jpg"			,"rsm"=>"#OVI #SeguridadVial #VidaSegura #CulturaVial #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"#Ovi Recuerda mantener una distancia prudencial con el vehículo que tienes adelante, evita accidentes $tx_ambito"								,"img"=>"../web/images/img-twitter/m14-campana-ovi.jpg"			,"rsm"=>"#OVI #SeguridadVial #VidaSegura #RespetaLasNormas #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"#Ovi los motorizados son uno de los principales grupos de riesgo en los accidentes viales, usa el casco $tx_ambito"								,"img"=>"../web/images/img-twitter/m15-campana-ovi.jpg"			,"rsm"=>"#OVI ##CulturaVial #VidaSegura #RespetaLasNormas #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"#Ovi Los vehículos de emergencia que se anuncien con sirenas, tienen derecho preferencial de paso $tx_ambito"									,"img"=>"../web/images/img-twitter/m16-campana-ovi.jpg"			,"rsm"=>"#OVI #SeguridadVial #RespetaLasNormas #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"Los niños son uno de los principales grupos de riesgo en accidentes de transito, ponle su cinturón de seguridad $tx_ambito"			,"img"=>"../web/images/img-twitter/m17-campana-ovi.jpg"			,"rsm"=>"#OVI #CulturaVial #RespetaLasNormas #ConductorResponsable $tx_ambito"), 
                                    array("txt"=>"#Ovi Mientras conduzcas no ingieras bebidas alcohólicas, cuida tu vida y la de tu familia $tx_ambito"										,"img"=>"../web/images/img-twitter/m18-campana-ovi.jpg"			,"rsm"=>"#OVI #SeguridadVial #ConscienciaVial #VidaSegura $tx_ambito"),
                                    array("txt"=>"#Ovi Mientras conduzcas no ingieras bebidas alcohólicas, cuida tu vida y la de tu familia $tx_ambito"										,"img"=>"../web/images/img-twitter/m19-campana-ovi.jpg"			,"rsm"=>"#OVI #SeguridadVial #RespetaLasNormas #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"#Ovi Mientras conduzcas no ingieras bebidas alcohólicas, cuida tu vida y la de tu familia $tx_ambito"										,"img"=>"../web/images/img-twitter/m20-campana-ovi.jpg"			,"rsm"=>"#OVI #SeguridadVial #ConscienciaVial #VidaSegura $tx_ambito"),
                                    array("txt"=>"#Ovi Mientras conduzcas no ingieras bebidas alcohólicas, cuida tu vida y la de tu familia $tx_ambito"										,"img"=>"../web/images/img-twitter/m21-campana-ovi.jpg"			,"rsm"=>"#OVI #CulturaVial #RespetaLasNormas #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"#Ovi Mientras conduzcas no ingieras bebidas alcohólicas, cuida tu vida y la de tu familia $tx_ambito"										,"img"=>"../web/images/img-twitter/m22-campana-ovi.jpg"			,"rsm"=>"#OVI #SeguridadVial #ConscienciaVial #VidaSegura $tx_ambito"),                                                                                                                                                
                                    array("txt"=>"Conducción segura en época de lluvia"                                                                                                   ,"img"=>"../web/images/img-twitter/lluvia-precaucion-autos.jpg"         ,"rsm"=>"$tx_ambito Conducción segura en época de lluvia https://goo.gl/xbq9AC"),
                                    array("txt"=>"campaña internacional de seguridad vial 1"																	                                      ,"img"=>"../web/images/img-twitter/alcohol-velocidad.jpg"		,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #CulturaVial $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 2"																	                                      ,"img"=>"../web/images/img-twitter/exceso-velocidad.jpg"		,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 3"																	                                      ,"img"=>"../web/images/img-twitter/exceso-velocidad-golpe.jpg"		,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #VidaSegura $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 4"																	                                      ,"img"=>"../web/images/img-twitter/velocidad-distraccion.jpg"		,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 5"																	                                      ,"img"=>"../web/images/img-twitter/velocidad-cinturon-seguridad.jpg"	,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 6"																	                                      ,"img"=>"../web/images/img-twitter/prudencia-moto.jpg"			,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #RespetaLasNormas $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 7"																	                                      ,"img"=>"../web/images/img-twitter/seguridad-vial-vp-cinturon.jpg"	,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 8"                                                                                        ,"img"=>"../web/images/img-twitter/yo_uso_cinturon_internacional.jpg"   		,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #ConscienciaVial $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 9"                                                                                        ,"img"=>"../web/images/img-twitter/casco-internacional.jpg"   			,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #UsaElCasco $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 10"                                                                                       ,"img"=>"../web/images/img-twitter/cinturon-seg-internacional.jpg"   		,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #PonteCinturon $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 11"                                                                                    ,"img"=>"../web/images/img-twitter/licor-internacional.jpg"   				,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #SiManejasNoTomes $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 12"                                                                                    ,"img"=>"../web/images/img-twitter/consejos_seg_vial.png"   				,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"campaña internacional de seguridad vial 13"                                                                                    ,"img"=>"../web/images/img-twitter/seg_vial_distancias_frenado.jpg"   			,"rsm"=>"#OVI Campaña internacional de #SeguridadVial #ConscienciaVial $tx_ambito")
                                );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
                                    
                                   
		$indice = mt_rand(0,28);
		$msj = $mensajes[$indice];
		return $msj;
		
	}
	
	/* Retorna un mensaje de Alerta de forma aletoria
   	 * 
   	 * 
   	 * */   	
   	private function obtenerMensajeAlerta($ubicacionReferencia, $tx_ambito){
		
            $mensajes = array("Ha ocurrido un incidente en ".$ubicacionReferencia." $tx_ambito #Ovi #Miranda",
                              "#Ovi ha detectado un incidente en ".$ubicacionReferencia." $tx_ambito #Miranda",
                              "Atención $tx_ambito se generó un incidente en ".$ubicacionReferencia." #Ovi #Miranda",
                              "Revisa el mapa, #Ovi detectó un incidente en ".$ubicacionReferencia." $tx_ambito #Miranda",
                              "#Ovi Hay un nuevo incidente en el lugar: ".$ubicacionReferencia." $tx_ambito #Miranda",
                              "#Ovi detecta en el mapa un incidente en ".$ubicacionReferencia." $tx_ambito #Miranda",
                              "Un colaborador ha detectado un incidente en ".$ubicacionReferencia." $tx_ambito #Ovi #Miranda",
                              "Revisa la etiqueta $tx_ambito, incidente en ".$ubicacionReferencia." #Ovi #Miranda");
		$indice = mt_rand(0,7);		
		return $mensajes[$indice];
		
	}
	
	/* Retorna un mensaje de Alerta de Corto forma aletoria
   	 * 
   	 * 
   	 * */   	
   	private function obtenerMensajeAlertaCorto($ubicacionReferencia, $tx_ambito){
		
            $mensajes = array("Ocurrió un incidente en ".$ubicacionReferencia." $tx_ambito #Miranda",                              
                              "Se generó un incidente en ".$ubicacionReferencia." $tx_ambito #Miranda",
                              "Se detectó un incidente en ".$ubicacionReferencia." $tx_ambito #Miranda",                    
			      "Reporte de incidente en ".$ubicacionReferencia." $tx_ambito #Miranda");
		$indice = mt_rand(0,3);		
		return $mensajes[$indice];
		
	}
	
	/* Retorna un ambito vial (ONM, AGMA) de forma aletoria
   	 * 
   	 * 
   	 * */   	
   	private function obtenerAmbitoAleatorio(){
		
                $ambito = array("#PNM","#AFF","#AGMA","#ARC","#CotaMil","#PDE");
		$indice = mt_rand(0,5);		
		return $ambito[$indice];
		
	}
	
	
	
	
	/* 
   	 * Obtener cuentas relevanets para conectar sobre un incidente para tener mas exposición
   	 * http://www.twven.com/c/trafico
   	 * 
   	 * */   	
   	private function obtenerCuentasRelevantes(){
		
            $cuentas = array("EUtrafico","MaquinaDelAire","trafficMIRANDA","FMCENTER","traffiCARACAS","traficovv");
		$indice = mt_rand(0,5);		
		return $cuentas[$indice];
		
	}
        
        /*
         * Notifica sobre un incidente ocurrido 
         * 
         * 
         */
	public function enviarMsjAnonimo($ubicacionReferencia, $media_url, $tx_ambito){
            
            	$msj = $this->obtenerMensajeAlerta($ubicacionReferencia, $tx_ambito);
            	
            	//Llamado a acortador de url de google URL Shortener API 
                $keyAPI = '';
                $this->google = new GoogleURLAPI($keyAPI);                
                
                $fecha = strtotime (date("Y-m-d H:i:s"));
                $url = 'http://ovi.org.ve/web/tpl/incidente.php?fe='.$fecha;                
                $urlCorta = $this->google->shorten($url);	
                
                $msj = $msj." ".$urlCorta;             
            	
            	$result = $this->twitter->validarTamMaxTweet($msj);
            	if (!$result){
            	    $this->enviarMsjAnonimo($ubicacionReferencia, $media_url, $tx_ambito);                
	        }
            	else{
            
                  $fecha = date("Y-m-d H:i:s");	
 		            $msg = "[AGENTE TUITERO enviarMsjAnonimo()] | $fecha | $msj | $ubicacionReferencia | $tx_ambito";
	    	         $this->log->general($msg);		    				
	    		
                if ($media_url == 'sin_url'){
                	$media_local = $this->obtenerImagenUbicacion($ubicacionReferencia);
                 	if($media_local == NULL){ 
                 		// No se encontro ninguna imagen de referencia localmente, se tuitea solo texto
                 		$this->enviarTweet($msj); 
                 	}else{
                 		
                 		$this->twitter->enviarTweetImg($msj,$media_local['img']);    
                 	}
                 	
                }else{ // si existe una imagen junto al tuits se sube como referencia
                 	$this->twitter->enviarTweetImg($msj,$media_url); 
                 	
                 	/*Conectar incidente con otras cuentas relevantes*/                 	
                 	$mensajeAlertaCorto = $this->obtenerMensajeAlertaCorto($ubicacionReferencia, $tx_ambito)." ".$urlCorta;                 	
                 	$this->InformarOtrosUsuarios($mensajeAlertaCorto,$media_url);           
                }
               
                    
            }//fin validartamano
            
        }
        
        /**
        * Obtener la imagen de la ubicación a partir del lugar de referencia
        */
        public function obtenerImagenUbicacion($ubicacionReferencia){
        
        $lugares = array("Kilómetro 0" => array("img"=>"../web/images/img-twitter/lugares/pnm-km0.png"), /*#PNM*/
        		 "Kilómetro 1" => array("img"=>"../web/images/img-twitter/lugares/pnm-km1.png"),
        		 "Kilómetro 2" => array("img"=>"../web/images/img-twitter/lugares/pnm-km2.png"),
        		 "Kilómetro 3" => array("img"=>"../web/images/img-twitter/lugares/pnm-km3.png"),
        		 "Kilómetro 4" => array("img"=>"../web/images/img-twitter/lugares/pnm-km4.png"),
        		 "Kilómetro 5" => array("img"=>"../web/images/img-twitter/lugares/pnm-km5.png"),
        		 "Kilómetro 8" => array("img"=>"../web/images/img-twitter/lugares/pnm-km8.png"),
        		 "Kilómetro 9" => array("img"=>"../web/images/img-twitter/lugares/pnm-km9.png"),
        		 "Kilómetro 11" => array("img"=>"../web/images/img-twitter/lugares/pnm-km11.png"),
        		 "Kilómetro 18" => array("img"=>"../web/images/img-twitter/lugares/pnm-km18.png"),
        		 "Kilómetro 19" => array("img"=>"../web/images/img-twitter/lugares/pnm-km19.png"),
             "E/S El Bohio" => array("img"=>"../web/images/img-twitter/lugares/agma-es-bohio.png"),
             "Mampote" => array("img"=>"../web/images/img-twitter/lugares/agma-mampote.png"), /*AGMA*/
             "Terminal de Oriente" => array("img"=>"../web/images/img-twitter/lugares/agma-terminal-oriente.png"),
             "Cementerio Jardines del Cercado" => array("img"=>"../web/images/img-twitter/lugares/agma-jardin-cercado.png"),
             "Tunel de Turumo" => array("img"=>"../web/images/img-twitter/lugares/agma-tunel-turumo.png"),
             "Viaducto de Turumo" => array("img"=>"../web/images/img-twitter/lugares/agma-tunel-turumo.png"),
             "Helipuerto Ávila" => array("img"=>"../web/images/img-twitter/lugares/agma-heli-avila.png"),
             "Distribuidor Metropolitano" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-metropolitano.png"), /*#CotaMil*/
             "Distribuidor El Marqués" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-marques.png"),
             "Distribuidor Boleita" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-boleita.png"),
             "Distribuidor Sebucán" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-sebucan.png"),
             "Distribuidor Altamira" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-altamira.png"),
             "Distribuidor La Castellana" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-castellana.png"),
             "Distribuidor La Florida" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-florida.png"),
             "Distribuidor Maripérez" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-mariperez.png"),
             "Distribuidor San Bernardino" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-san-bernardino.png"),
             "Distribuidor Baralt" => array("img"=>"../web/images/img-twitter/lugares/cota1000-dist-baralt.png"),
             "Tunel Los Ocumitos" => array("img"=>"../web/images/img-twitter/lugares/arc-los-ocumitos.png"), /*#ARC*/
             "Maitana" => array("img"=>"../web/images/img-twitter/lugares/arc-maitana.png"),
             "Tunel La Cabrera" => array("img"=>"../web/images/img-twitter/lugares/arc-tunel-cabrera.png"),
             "Distribuidor La Encrucijada" => array("img"=>"../web/images/img-twitter/lugares/arc-la-encrucijada.png"),
             "Jardin Botánico" => array("img"=>"../web/images/img-twitter/lugares/aff-jardin-botanico.png"),/*#AFF*/
             "Plaza Venezuela" => array("img"=>"../web/images/img-twitter/lugares/aff-plaza-vzla.png"),/*#AFF*/
             "Distribuidor Altamira" => array("img"=>"../web/images/img-twitter/lugares/aff-distribuidor-altamira.png"),
             "Distribuidor La Araña" => array("img"=>"../web/images/img-twitter/lugares/aff-distribuidor-la-arana.png"),
             "La Urbina" => array("img"=>"../web/images/img-twitter/lugares/aff-la-urbina.png")
             
        );
        
        if(array_key_exists($ubicacionReferencia,$lugares))             
        	return $lugares[$ubicacionReferencia];
        else
        	return NULL;
        
        }
        
        
        
         /*
         * Revisa si existen incidentes 
         * 
         * 
         */
        public function hayIncidentes(){
            
            //$this->agenteRevisor->aplicarMetodosRevision(0);
            
            $tweets = $this->buscarTweetsInteresRecientes();
            //print_r($tweets);
            
           //tuits sinteticos
           /*$tweets = array (
           array('id_tweet' => 'XXX','text'=> '#Denuncia Situación irregular en Cumbre Roja un Polimiranda muerto #PNM Presuntos sospechosos enconchados en #LaMacarena @DiarioAvanceWeb'	,'lugar' => 'Sector La Macarena', 'clase_incidente' => 'persona fallecida'),
           array('id_tweet' => 'YYY','text'=> 'RT trafficMIRANDA: via TioraFM: Choque en el ivic #pnm retraso sentido #ccs dsd #saa, está pesada feliz día. Reporta carlos_raga2601'	 	,'lugar' => 'IVIC', 'clase_incidente' => 'siniestro vehí­culo'),
           array('id_tweet' => 'XYZ','text'=> 'RT yaninele: Choque en semÃ¡foro km 11 #pnm bajando 7:35am #Miranda'									 	,'lugar' => 'Kilómetro 11', 'clase_incidente' => 'siniestro vehí­culo'),
           array('id_tweet' => 'YZX','text'=> '#GPTUTIL VÃ­a @Traffic_Mix: #Miranda #PNM Choque antes dl IVIC Km 13 bajando a #CCS&gt;@Wallybca @FMCENTER pic.twi... https://t.co/cOnt02JUV2'	,'lugar' => 'Kilómetro 13', 'clase_incidente' => 'siniestro vehí­culo'),
                     
           );*/ 
            
            if(is_array($tweets)){
                return $tweets;
            }
            else{
                echo 'No hay tweets';
                return FALSE;
            }
        }

        /*
         * Realiza las notificaciones de incidentes
         * 
         * 
         */
        public function alertarIncidentes($tweets) {           
                
                $rendimiento_inicio = Rendimiento::obtenerTiempoSeg();
                $cont_incidentes = 0;              
                
                $incidentes = array();
                $lugares = array();
                $medios = array();
                $ambito = array(); //PNM , AGMA
                foreach ($tweets as $t){
                    $ubicacionReferencia =  $t['lugar'];
                    $tipo_incidente =  $t['clase_incidente'];
                    $id_tweet = $t['id_tweet'];
                    $media_url = $t['media_url'];
                    $tx_ambito = $t['tx_ambito'];
                    //echo $ubicacionReferencia.' '.$tipo_incidente;
                    //$segundos = mt_rand(5,30);                    
                    //sleep($segundos);
                    $this->twitter->modificarReportadoTweetInteresTuiter($id_tweet);
                        // Verifica que en el arreglo solo hayan tipos de incidentes diferentes a reportar con su ubicacion
                        // para evitar un tuits por cada registro encontrado cuando esten repetidos
                   	//if (!in_array($tipo_incidente, $incidentes)) { 
                   	if (!in_array($ubicacionReferencia, $lugares)) { 
    				array_push($incidentes, $tipo_incidente);
    				array_push($lugares, $ubicacionReferencia);
    				array_push($medios, $media_url);
    				array_push($ambito, $tx_ambito);
			}
		    $cont_incidentes++;

                }      
                
                //envia notificaciones por tuiter solo la cantidad de tipos de incidentes diferente con su ubicacion
                //evita la duplicidad de incidentes informando la ubicacion
                $incidentes_unicos = 0;
                //for($i = 0; $i<count($incidentes);$i++){
                for($i = 0; $i<count($lugares);$i++){
                	$this->enviarMsjAnonimo($lugares[$i],$medios[$i],$ambito[$i]);
                	$incidentes_unicos++;
                	
                }
                   
                 // Rendimiento para las estadisticas del agente
  		 $rendimiento_fin = Rendimiento::obtenerTiempoSeg();
  		 $memoria_usada = Rendimiento::obtenerMemoriaUsada();
	   	 $tiempo_ejecucion = Rendimiento::tiempoEjecucion($rendimiento_fin, $rendimiento_inicio);
	   	 
	   	 $param = "incidentes: $cont_incidentes / incidentes_unicos: $incidentes_unicos";
	   	 
	   	 $this->estadisticas->guardarEstadistica(__CLASS__,__METHOD__,$param,$tiempo_ejecucion,$memoria_usada);	   	   
            
            
        }
        
        
	/*Envia un mensaje de seguridad vial
	 * 
	 * 
	 * */
	public function EnviarMensajeSegVial(){
            
            
            $tx_ambito = $this->obtenerAmbitoAleatorio();
            
            $tweet = $this->obtenerMensajeSeguridadVial($tx_ambito);
            //$tweet = $this->obtenerFechaHoraActual()." ".$tweet;
            //." ".$this->obtenerEtiquetaVial()
            //echo $tweet;
            
            $rsm = $this->obtenerFechaHoraActual()." ".$tweet['rsm']; // mensaje resumen
            $img = $tweet['img']; //ruta de la imagen a subir
            
            echo "\n $rsm";
            
            $this->twitter->enviarTweetImg($rsm,$img);
            
            /*$result = $this->twitter->validarTamMaxTweet($tweet);
            if (!$result){
                $this->EnviarMensajesSegVial ();
            }
            else{
                $this->enviarTweet($tweet);  
               
            }*/
		
	}
	
	/*Informar sobre un incidente a otros usuarios
	 * 
	 * 
	 * */
	public function InformarOtrosUsuarios($mensajeAlertar,$media_url){
                 
            $cuenta_relevante = $this->obtenerCuentasRelevantes();
            $msj = $mensajeAlertar." ".$this->obtenerEtiquetaVialCorta();
            $tweet = $msj." @".$cuenta_relevante;
            
            $result = $this->twitter->validarTamMaxTweet($tweet);
            if(!$result) $s = "validarTamMaxTweet error"; 
            else $s = "validarTamMaxTweet ok";
            
            $fecha = date("Y-m-d H:i:s");	
 	    $msg = "[AGENTE TUITERO InformarOtrosUsuarios()] | $fecha | $tweet | $media_url | $s ]";
	    $this->log->general($msj);	
            
            
            if (!$result){
                $this->InformarOtrosUsuarios ($mensajeAlertar,$media_url);
            }
            else{
            	
            	$fecha = date("Y-m-d H:i:s");	
 	        $msg = "[AGENTE TUITERO InformarOtrosUsuarios() => enviarTweetImg() | $fecha | $tweet | $media_url ]";
	        $this->log->general($msg);	
                $this->twitter->enviarTweetImg($tweet,$media_url); 
               
            }
		
	}
	
	
	/*Invita a un tuitero a seguirme de forma aleatoria
	 * 
	 * 
	 * */
	public function invitarSeguir($usuario){
            //$usuario = $this->usuarioAleatorioNoMeSigue();
            $cuenta_usuario =$usuario['screen_name'];
            $invitaciones   =$usuario['fue_invitado'];
            $msj = $this->obtenerMensajeSeguir()." ".$this->obtenerEtiquetaVial();
            $tweet = $msj." @".$cuenta_usuario;
            
            $result = $this->twitter->validarTamMaxTweet($tweet);
            if (!$result){
                $this->invitarSeguir ($cuenta_usuario);
            }
            else{
                $this->enviarTweet($tweet);  
                $this->fueInvitado($cuenta_usuario,$invitaciones);
            }
		
	}
	
        /* Envía los datos a la clase Twitter para escribir en el Timeline
	 * 
	 * 
	 * */
	private function enviarTweet($mensaje){
            echo $mensaje."\n";	                          
            $this->twitter->enviarTweet($mensaje);
		
	}   	
  
   	
   	
         /**
         * Cambia el estado de un usuario de twitter a 'seguidor'
         * @param type $id_usuario
         * 
         * 
         */
        private function ahoraMeSigue($id_usuario){
            $result = $this->twitter->actualizarSeguidor($id_usuario,'es_seguidor','1');
            return $result;
            
        }
        
        /**
         * Saber si un usuario me sigue y cambiar su estatus en la BD
         * 
         */
        public function revisarSiMeSigue($usuario){
            //$usuario = $this->usuarioAleatorioNoMeSigue();
            //$usuario['screen_name']	= 'jornastec';
            $result = $this->twitter->meSigue($usuario['screen_name']);
            if ($result==true){
                echo "\n".$usuario['screen_name'].": Si me sigue";
                //$this->ahoraMeSigue ($usuario['screen_name']);
                return true;
            }
            else{ 
                echo "\n".$usuario['screen_name'].":No me sigue";
                return false;
            }
        }


        /**
         * Lógica para obtener Tuiteros que no son seguidores
   	 * Retorna un arreglo con las direcciones de los Tuiteros
         * 
         */
        public function buscarUsuariosNoMeSiguen(){
            $paramentros = array();
            //$paramentros['fue_invitado']= "'0'";
            $paramentros['es_seguidor']= "'0'";
            //$result = $this->twitter->buscarUsuarios($paramentros, 'AND');
            $result = $this->twitter->buscarUsuarios($paramentros, '');
            //print_r($result);
            return $result;
        }
        /**
         * Busca aleatoriamente un usuario que no me sigue en la Base de datos
   	 * Retorna un usuario de Twitter que no me sigue
         * 
         */
        public function usuarioAleatorioNoMeSigue(){
            $usuarios = $this->buscarUsuariosNoMeSiguen();            
            $total_usuarios = sizeof($usuarios); 
            $usuario_aleatorio = mt_rand(1,$total_usuarios); 
            //print_r($usuarios[$usuario_aleatorio]);
            return $usuarios[$usuario_aleatorio];
        }
         /*
         * Indica que un usuario fue invitado a seguir la cuenta del OVNI
         * Se lleva un registro de la cantidad de invitaciones realizadas
         * antes de aceptar la invitación a seguir.
         */
        private function fueInvitado($id_usuario,$invitaciones){
            echo "\nUsuario: ".$id_usuario." invitaciones ".$invitaciones;
            $this->twitter->fueInvitado($id_usuario,$invitaciones);
        }
        
        /*
         * retorna los últimos tweets de interes
         * @return arreglos tweets
         * 
         */
        private function buscarTweetsInteresRecientes(){
            //echo 'buscarTweetsInteresRecientes';
            $tweets = $this->twitter->obtenerTuitsInteresRecientesTuitero();
            
            return $tweets;
        }
        
        public function actualizarCantTuitsInteres(){
            $resul = $this->suscriptor->actualizarCantTuitsInteres();
            echo "\nResultado de la actualización de Tuits de interés: ".$resul;
            return $resul;
        }
        
        /*
         * Revisa si es hora de invitar, entre 7am y 7am
         * @return true o false
         * 
         */
        public function esHoraInvitar(){
            $hora_corrida = date("H");
            $hora_invitar_ini = 7;
            $hora_invitar_fin = 7;
            
            if($hora_corrida >= $hora_invitar_ini && $hora_corrida<= $hora_invitar_fin){
                echo "\nEs Hora Invitar Seguidores";
                return TRUE;
            }
            else {
                echo "\nNo es Hora Invitar Seguidores";
                return FALSE;
            }
            
        }  
        
        /*
         * Revisa si es hora de crear consciencia vial, entre 9am y 7pm
         * @return true o false
         * 
         */
        public function esHoraCrearConscienciaVial(){
            $hora_corrida = date("H");
            $hora_invitar_ini = 6;
            $hora_invitar_fin = 6;

            $hora_invitar_ini_2 = 17;
            $hora_invitar_fin_2 = 17;
            
            
            
            if($hora_corrida >= $hora_invitar_ini && $hora_corrida<= $hora_invitar_fin){
                echo "\nEs Hora Crear consciencia vial 6 am";
                return TRUE;
            }elseif ($hora_corrida >= $hora_invitar_ini_2 && $hora_corrida<= $hora_invitar_fin_2) {
               echo "\nEs Hora Crear consciencia vial 5 pm";
                return TRUE;
            }
            else {
                echo "\nNo es Hora crear consciencia vial";
                return FALSE;
            }
            
        }  
                
        
         /*
         * Revisa si es hora de revisar seguidores, entre 20:00 pm y 23:59 pm 
         * @return true o false
         * 
         */
        public function esHoraRevisarSeguidores(){
            $hora_corrida = date("H");
            $hora_revisar_ini = 23;
            $hora_revisar_fin = 23;
            
            if($hora_corrida >= $hora_revisar_ini && $hora_corrida<= $hora_revisar_fin){
                echo "\nEs Hora Revisar Seguidores";
                return TRUE;
            }
            else {
                echo "\nNo es Hora Revisar Seguidores";
                return FALSE;
            }
            
        }
        
         /*
         * Revisa si es hora de revisar seguidores, entre 20:00 pm y 23:59 pm 
         * @return true o false
         * 
         */
        public function esHoraActualizarCantTuitsInteres(){
            $hora_corrida = date("H");
            $hora_revisar = 2;
            
            
            if($hora_corrida == $hora_revisar){
                echo "\nEs Hora Actualizar Cantidad Tuits de Interés";
                return TRUE;
            }
            else {                
                return FALSE;
            }
            
        }
        
        
        
   	 	

}//fin clase

?>
