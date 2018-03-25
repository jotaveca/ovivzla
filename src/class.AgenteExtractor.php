<?php
//Tf-idf (del inglés Term frequency – Inverse document frequency), 
include_once("class.Agente.php");
include_once("class.BD.php");
include_once("class.Cadena.php");
include_once("class.Log.php");
require_once("class.Twitter.php");
require_once("class.PalabraInteres.php");
require_once("class.Rendimiento.php");

class AgenteExtractor extends Agente{

	private $ambito; //ambito busqueda #PNM, #ARC
	private $ambitoAgt;
	private $parametrosAgt;
	private $palabras_interes;
	private $lugares_interes;
    	private $kms_vecinos;
	private $tweet;
	private $bd;
	private $cadena;
	private $log;
	private $palabraInteres;	
	private $twitter;	
	private $estadisticas;	
	private $belief; // BDI Agente arquitecture
	private $desire; // BDI Agente arquitecture
	private $intention; /// BDI Agente arquitecture
	
	function __construct() {
	
		
		include_once("../config/palabras_interes.php");	

        $this->kms_vecinos          = $kms_vecinos;
        $this->lugares_interes      = $lugares_interes;
		
		$this->bd = new BD();
		$this->cadena = new Cadena();
		$this->twitter = new Twitter();
		$this->estadisticas = new Rendimiento();
				

		
		
		$this->log = new Log();	
        $fecha = date("Y-m-d H:i:s");	    
	    $msg = "[AGENTE EXTRACTOR INICIADO] | $fecha ";
	    $this->log->general($msg);	
      	
   	}
   	
   	/**
   	* BDI Agente arquitecture
   	*/   	
   	public function ini_bdi(){
   		//informacion del mundo
	   	$this->belief = array(  "nombre_agente"=>"AgenteExtractor",
	   				"frecuencia_ejecucion_min"=>30,
	   				"entorno"=>"ovi",
	   			        "agentes_mundo"=>array("AgenteClimatico"=>0,"AgenteTuitero"=>0,"AgenteInformador"=>0,"AgenteRevisor"=>0)
	   		     );
   	       //metas a alcanzar	
   	       $this->desire = array("meta1" => "extraer_informacion_vial_pnm",
   	       			     "meta2" => "extraer_informacion_vial_agma",
   	       			     "meta3" => "extraer_informacion_vial_cota1000",
   	       			     "meta4" => "extraer_informacion_vial_aff",
   	       			     "meta5" => "extraer_informacion_vial_arc",
   	       			     "meta6" => "extraer_informacion_vial_pde",
   	       			     "meta7" => "registrar_bitacora_agente");
   	       
   	       //plan de accion para alcanzar la meta
   	       $this->intention = array("extraer_informacion_vial_pnm"=> array ("plan_accion"=>
   	       											array("0" => "def_param_agt_pnm","1" => "definir_ambito_busqueda","2" => "definir_parametros_extraccion","3"=>"iniciar_extraccion_pnm")
   	       											
   	       									)
   	       				,"extraer_informacion_vial_agma"=> array ("plan_accion"=>   	       											
   	       											array("0" => "def_param_agt_agma","1" => "definir_ambito_busqueda","2" => "definir_parametros_extraccion","3"=>"iniciar_extraccion_agma")
   	       								)
   	       				,"extraer_informacion_vial_cota1000"=> array ("plan_accion"=>   	       											
   	       											array("0" => "def_param_agt_cota1000","1" => "definir_ambito_busqueda","2" => "definir_parametros_extraccion","3"=>"iniciar_extraccion_cota1000")
   	       								)
   	       				
   	       				,"extraer_informacion_vial_aff"=> array ("plan_accion"=>   	       											
   	       											array("0" => "def_param_agt_aff","1" => "definir_ambito_busqueda","2" => "definir_parametros_extraccion","3"=>"iniciar_extraccion_aff")
   	       								)
						,"extraer_informacion_vial_arc"=> array ("plan_accion"=>   	       											
   	       											array("0" => "def_param_agt_arc","1" => "definir_ambito_busqueda","2" => "definir_parametros_extraccion","3"=>"iniciar_extraccion_arc")
   	       								)
						,"extraer_informacion_vial_pde"=> array ("plan_accion"=>   	       											
   	       											array("0" => "def_param_agt_pde","1" => "definir_ambito_busqueda","2" => "definir_parametros_extraccion","3"=>"iniciar_extraccion_pde")
   	       								)
   	       				,"registrar_bitacora_agente"   => array ("plan_accion"=>   	       											
   	       											array("0"=>"registrar_accion_agente")
   	       								)
   	       				);
   	
   	}
   	
   	public function registrar_accion_agente(){
   	
   		$frecuencia_ejecucion_min = $this->belief['frecuencia_ejecucion_min'];
   		$nombre_agente = $this->belief['nombre_agente'];
   		$this->registrarAccionAgente($nombre_agente,$frecuencia_ejecucion_min);
   		
   	}
   	
   	/*
   	/* Inicializar los parametros globales del agente para la PNM
   	*/
	public function def_param_agt_pnm(){
	
		$this->ambitoAgt = "#PNM";
		$this->parametrosAgt = '?q=#PNM+OR+PNM&count=100&lang=es&result_type=recent&include_entities=true';
	}

	/*
   	/* Inicializar los parametros globales del agente para la ARC
   	*/
	public function def_param_agt_arc(){
	
		$this->ambitoAgt = "#ARC";
		$this->parametrosAgt = '?q=#ARC+OR+ARC&count=100&lang=es&result_type=recent&include_entities=true';
	}

	/*
   	/* Inicializar los parametros globales del agente para la PDE
   	*/
	public function def_param_agt_pde(){
	
		$this->ambitoAgt = "#PDE";
		$this->parametrosAgt = '?q=#PDE+OR+#APE&count=100&lang=es&result_type=recent&include_entities=true';
	}
	
	/*
   	/* Inicializar los parametros globales del agente para la AGMA
   	*/
	public function def_param_agt_agma(){
	
		$this->ambitoAgt = "#AGMA";
		$this->parametrosAgt = '?q=#AGMA+OR+AGMA&count=100&lang=es&result_type=recent&include_entities=true';
	}
	
   	/*
   	/* Inicializar los parametros globales del agente para la CotaMil
   	*/
	public function def_param_agt_cota1000(){
	
		$this->ambitoAgt = "#CotaMil";
		$this->parametrosAgt = '?q=#CotaMil+OR+Cota Mil&count=100&lang=es&result_type=recent&include_entities=true';
	}

	/*
   	/* Inicializar los parametros globales del agente para la CotaMil
   	*/
	public function def_param_agt_aff(){
	
		$this->ambitoAgt = "#AFF";
		//$this->parametrosAgt = '?q=#AFF+OR+AFF&count=100&lang=es&result_type=recent&include_entities=true';
		$this->parametrosAgt = '?q=#AFF+OR+AFF&count=100&lang=es&result_type=recent&include_entities=true';
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
   	 
   	 public function setTweet($tweet){
	
	   	 
   	 	$this->tweet = $this->cadena->formatearCadena($tweet);
   	 	//echo $this->tweet;   	 	
   	 
   	 }
   	 
   	 public function definir_ambito_busqueda(){	
	   	 
   	 	$this->ambito = $this->ambitoAgt;   	 	
   	 
   	 }
   	 
   	 
   	 
   	 /**
   	 * Establecer parametros de conexion con el API de Twitter
   	 */
   	 public function definir_parametros_extraccion(){
   	 
   	 	//$twitter->set_getField('?q=#PNM&count=100&lang=es&result_type=recent&include_entities=true');
   	 	$this->twitter->set_getField($this->parametrosAgt);
   	 	
   	 }
   	 
     /**
   	 * Extraer datos desde el flujo de datos del API de Twitter y solo guardar en tabla tuits, solo en periodo de prueba
   	 */
   	 public function iniciar_extraccion_arc(){
   	 
   	 	 $rendimiento_inicio = Rendimiento::obtenerTiempoSeg();
   	 	 $tuits_interes = array();
	   	 $result = $this->twitter->leerTweet();
	   	 $result = $result['statuses'];
	   	 
	   	 echo "<br><h1>Analisis de Tuit: $this->ambito</h1><br>";
		
		 //$this->extraerUbicacionTweet();

		$t_generales = 0;
		$t_interes = 0;
		$i = 0;
		foreach($result as $tweet)
		{
 			$name = $tweet['user']['name'];
 			$screen_name = $tweet['user']['screen_name'];
 			$user_id = $tweet['user']['id'];
 			$id_str_user = $tweet['user']['id_str'];
 			$image = $tweet['user']['profile_image_url'];
 			$location = $tweet['user']['location'];
 			$followers_count = $tweet['user']['followers_count'];
			$retweet_count = $tweet['retweet_count'];
 			$favorite_count = $tweet['favorite_count'];
 			$description = $tweet['user']['description'];
 			$text = $tweet['text'];
 			$idTweet = $tweet['id_str'];
 			$created_at = $tweet['created_at'];
 			//$date = date("Y-m-d H:i:s",strtotime($tweet["created_at"]));
 			$date = date("Y-m-d H:i:s");
 			$retweeted = $tweet['retweeted'];
 			$entidades = @$tweet['entities']['media'];
 			
 
 
 			echo "<br>$i - ";
 			if(is_array($entidades)){
 				  $media_url = @$tweet['entities']['media'][0]['media_url'];
 				  echo "<br>media url : $media_url<br>";
 			}else{
 				  $media_url = 'sin_url';
 			}
 			
 

 			if (isset($tweet['retweeted_status'])){
   				$retweeted_status = $tweet['retweeted_status']; 
    				echo "<br>Mensaje retuiteado: $idTweet - $date - $text  <br>";    	
 	
 			}else{

		    		if($this->twitter->validarExisteUsuario($user_id)==false){ 
			    	echo "<br/><br />";
		    		echo "<b/>UN: Usuario nuevo $screen_name </b>";   	
		    		$this->twitter->agregarUsuarioTweet($user_id, $name, $screen_name, $id_str_user, $location, $followers_count, $description);    		
		    		}else{
		    		echo "<br/><br />";
		    		echo "UR: Usuario repetido $screen_name "; 
		    		}
		    		
		    		//No existe el tuits registrado
    				if($this->twitter->validarExisteTweet($idTweet)==false){ 
		    	        	//echo "<br/>Fecha: $date <br />";
		    	        	echo "<br /><br />";
		    			echo "<b/>TN: Tweet nuevo $idTweet - $date - $text</b>";   	
		    			$this->twitter->agregarTweet($idTweet,$idTweet,$text,$created_at,$date,$retweet_count, $favorite_count, $user_id, $this->ambito); 

		    			$msg = "[AGENTE EXTRACTOR agregarTweet($idTweet)] | $date | $text | $this->ambito \n";
	    				$this->log->general($msg);	


		    			$t_generales++;
    		
    		
	    				//Saber si existen un tweet con las palabras de interes
			    		$this->setTweet($text);
			    		$encontrado = $this->extraerTipoIncidente();
	    		
			    		$dif_palabra = $encontrado["dif_palabra"];
				       	$incidente = $encontrado["incidente"];
				       	$clase_incidente = $encontrado["clase_incidente"];	
				       	
				       	
				       	$encontradoVeh = $this->extraerTipoIncidenteVehiculo();
	    		
			    		$dif_palabraVeh = $encontradoVeh["dif_palabra"];
				       	$vehiculo = $encontradoVeh["vehiculo"];
				       	$clase_vehiculo = $encontradoVeh["clase_vehiculo"];	
				       	
				       		 	       		
			       		
	    		
			    		// Si la diferencia de palabras es menor o igual de 2 se considera tuits de interes			    		
			    		if($dif_palabra <= 2){
	    		
	    						    					
	    					if($this->twitter->validarExisteTweetInteres($idTweet)==false){ 
	    						//insertamos en otro arreglo los tuits de interes
	    						array_push($tuits_interes, $tweet); 
							echo "<br /> TINuevo: De Interes: Busqueda muy parecida: $incidente (L:$dif_palabra) <br />";
							$this->twitter->agregarTweetInteres($idTweet,$idTweet,$text,$created_at,$date,$media_url,$retweet_count, $favorite_count, $user_id,0,0,0,$incidente,"",$clase_incidente,"",$this->ambito, $vehiculo, $clase_vehiculo); 
							$t_interes++;
							
							$msg = "[AGENTE EXTRACTOR agregarTweetInteres($idTweet)] | $date | $text | $this->ambito \n";
	    					$this->log->general($msg);	            						
	    					
							
							//array_push($ids_tweets, $idTweet);
						}
							 
					 	//Extrar la ubicacion geografica que reporta el tweet
						$lugar = $this->extraerUbicacionTweet();
					
						if(is_array($lugar)){
							echo "<br /> geolocalización añadido <br />";
							$this->twitter->modificarGeoTweetInteres($idTweet,$lugar["lat"],$lugar["lon"],$lugar["etiqueta"],$lugar["clase_lugar"],$lugar["km_aprox"]);
									
						}else{
					
							echo "<br /> Sin geolocalización añadido <br />";
						}	   			
	    			
					} //fin dif_palabra
    		
    		 		
    				}else{
			    		echo "<br/><br />";
			    		echo "TR: Tuit repetido $idTweet - $date - $text "; 
    				}
    	
 	
	 		}//mensaje nuevo
 
 			$i++;
 
  		}// fin for
  		
  		 
                if($tuits_interes>0){
                    include_once 'class.AgenteRevisor.php';
                    $agtRevisor = new AgenteRevisor();
                    $agtRevisor->reportarIncidenteEnVia("#ARC");
                } 
                // Rendimiento para las estadisticas del agente
  		 $rendimiento_fin = Rendimiento::obtenerTiempoSeg();
  		 $memoria_usada = Rendimiento::obtenerMemoriaUsada();
	   	 $tiempo_ejecucion = Rendimiento::tiempoEjecucion($rendimiento_fin, $rendimiento_inicio);
	   	 
	   	 $param = "Ambito: $this->ambito / tuits: $t_generales / tuits_interes: $t_interes";
	   	 
	   	 $this->estadisticas->guardarEstadistica(__CLASS__,__METHOD__,$param,$tiempo_ejecucion,$memoria_usada);	   	 
	   	 
	   	 
	   	 return $tuits_interes;
   	 
   	 } // fin metodo



   	 /**
   	 * Extraer datos desde el flujo de datos del API de Twitter y solo guardar en tabla tuits, solo en periodo de prueba
   	 */
   	 public function iniciar_extraccion_pde(){
   	 
   	 	 $rendimiento_inicio = Rendimiento::obtenerTiempoSeg();
   	 	 $tuits_interes = array();
	   	 $result = $this->twitter->leerTweet();
	   	 $result = $result['statuses'];
	   	 
	   	 echo "<br><h1>Analisis de Tuit: $this->ambito</h1><br>";
		
		 //$this->extraerUbicacionTweet();

		$t_generales = 0;
		$t_interes = 0;
		$i = 0;
		foreach($result as $tweet)
		{
 			$name = $tweet['user']['name'];
 			$screen_name = $tweet['user']['screen_name'];
 			$user_id = $tweet['user']['id'];
 			$id_str_user = $tweet['user']['id_str'];
 			$image = $tweet['user']['profile_image_url'];
 			$location = $tweet['user']['location'];
 			$followers_count = $tweet['user']['followers_count'];
			$retweet_count = $tweet['retweet_count'];
 			$favorite_count = $tweet['favorite_count'];
 			$description = $tweet['user']['description'];
 			$text = $tweet['text'];
 			$idTweet = $tweet['id_str'];
 			$created_at = $tweet['created_at'];
 			//$date = date("Y-m-d H:i:s",strtotime($tweet["created_at"]));
 			$date = date("Y-m-d H:i:s");
 			$retweeted = $tweet['retweeted'];
 			$entidades = @$tweet['entities']['media'];
 			
 
 
 			echo "<br>$i - ";
 			if(is_array($entidades)){
 				  $media_url = @$tweet['entities']['media'][0]['media_url'];
 				  echo "<br>media url : $media_url<br>";
 			}else{
 				  $media_url = 'sin_url';
 			}
 			
 

 			if (isset($tweet['retweeted_status'])){
   				$retweeted_status = $tweet['retweeted_status']; 
    				echo "<br>Mensaje retuiteado: $idTweet - $date - $text  <br>";    	
 	
 			}else{

		    		if($this->twitter->validarExisteUsuario($user_id)==false){ 
			    	echo "<br/><br />";
		    		echo "<b/>UN: Usuario nuevo $screen_name </b>";   	
		    		$this->twitter->agregarUsuarioTweet($user_id, $name, $screen_name, $id_str_user, $location, $followers_count, $description);    		
		    		}else{
		    		echo "<br/><br />";
		    		echo "UR: Usuario repetido $screen_name "; 
		    		}
		    		
		    		//No existe el tuits registrado
    				if($this->twitter->validarExisteTweet($idTweet)==false){ 
		    	        	//echo "<br/>Fecha: $date <br />";
		    	        	echo "<br /><br />";
		    			echo "<b/>TN: Tweet nuevo $idTweet - $date - $text</b>";   	
		    			$this->twitter->agregarTweet($idTweet,$idTweet,$text,$created_at,$date,$retweet_count, $favorite_count, $user_id, $this->ambito); 

		    			$msg = "[AGENTE EXTRACTOR agregarTweet($idTweet)] | $date | $text | $this->ambito \n";
	    				$this->log->general($msg);	


		    			$t_generales++;
    		
    		
	    				//Saber si existen un tweet con las palabras de interes
			    		$this->setTweet($text);
			    		$encontrado = $this->extraerTipoIncidente();
	    		
			    		$dif_palabra = $encontrado["dif_palabra"];
				       	$incidente = $encontrado["incidente"];
				       	$clase_incidente = $encontrado["clase_incidente"];	
				       	
				       	$encontradoVeh = $this->extraerTipoIncidenteVehiculo();
	    		
			    		$dif_palabraVeh = $encontradoVeh["dif_palabra"];
				       	$vehiculo = $encontradoVeh["vehiculo"];
				       	$clase_vehiculo = $encontradoVeh["clase_vehiculo"];		 	       		
			       		
	    		
			    		// Si la diferencia de palabras es menor o igual de 2 se considera tuits de interes			    		
			    		if($dif_palabra <= 2){
	    		
	    						    					
	    					if($this->twitter->validarExisteTweetInteres($idTweet)==false){ 
	    						//insertamos en otro arreglo los tuits de interes
	    						array_push($tuits_interes, $tweet); 
							echo "<br /> TINuevo: De Interes: Busqueda muy parecida: $incidente (L:$dif_palabra) <br />";
							$this->twitter->agregarTweetInteres($idTweet,$idTweet,$text,$created_at,$date,$media_url,$retweet_count, $favorite_count, $user_id,0,0,0,$incidente,"",$clase_incidente,"",$this->ambito, $vehiculo, $clase_vehiculo); 
							$t_interes++;
							
							$msg = "[AGENTE EXTRACTOR agregarTweetInteres($idTweet)] | $date | $text | $this->ambito \n";
	    					$this->log->general($msg);	            						
	    					
							
							//array_push($ids_tweets, $idTweet);
						}
							 
					 	//Extrar la ubicacion geografica que reporta el tweet
						$lugar = $this->extraerUbicacionTweet();
					
						if(is_array($lugar)){
							echo "<br /> geolocalización añadido <br />";
							$this->twitter->modificarGeoTweetInteres($idTweet,$lugar["lat"],$lugar["lon"],$lugar["etiqueta"],$lugar["clase_lugar"],$lugar["km_aprox"]);
									
						}else{
					
							echo "<br /> Sin geolocalización añadido <br />";
						}	   			
	    			
					} //fin dif_palabra
    		
    		 		
    				}else{
			    		echo "<br/><br />";
			    		echo "TR: Tuit repetido $idTweet - $date - $text "; 
    				}
    	
 	
	 		}//mensaje nuevo
 
 			$i++;
 
  		}// fin for
  		
                if($tuits_interes>0){
                    include_once 'class.AgenteRevisor.php';
                    $agtRevisor = new AgenteRevisor();
                    $agtRevisor->reportarIncidenteEnVia("#PDE");
                }   		 
                // Rendimiento para las estadisticas del agente
  		 $rendimiento_fin = Rendimiento::obtenerTiempoSeg();
  		 $memoria_usada = Rendimiento::obtenerMemoriaUsada();
	   	 $tiempo_ejecucion = Rendimiento::tiempoEjecucion($rendimiento_fin, $rendimiento_inicio);
	   	 
	   	 $param = "Ambito: $this->ambito / tuits: $t_generales / tuits_interes: $t_interes";
	   	 
	   	 $this->estadisticas->guardarEstadistica(__CLASS__,__METHOD__,$param,$tiempo_ejecucion,$memoria_usada);	   	 
	   	 
	   	 
	   	 return $tuits_interes;
   	 
   	 } // fin metodo





   	 /**
   	 * Extraer datos desde el flujo de datos del API de Twitter y solo guardar en tabla tuits, solo en periodo de prueba
   	 */
   	 public function iniciar_extraccion_agma(){
   	 
   	 	 $rendimiento_inicio = Rendimiento::obtenerTiempoSeg();
   	 	 $tuits_interes = array();
	   	 $result = $this->twitter->leerTweet();
	   	 $result = $result['statuses'];
	   	 
	   	 echo "<br><h1>Analisis de Tuit: $this->ambito</h1><br>";
		
		 //$this->extraerUbicacionTweet();

		$t_generales = 0;
		$t_interes = 0;
		$i = 0;
		foreach($result as $tweet)
		{
 			$name = $tweet['user']['name'];
 			$screen_name = $tweet['user']['screen_name'];
 			$user_id = $tweet['user']['id'];
 			$id_str_user = $tweet['user']['id_str'];
 			$image = $tweet['user']['profile_image_url'];
 			$location = $tweet['user']['location'];
 			$followers_count = $tweet['user']['followers_count'];
			$retweet_count = $tweet['retweet_count'];
 			$favorite_count = $tweet['favorite_count'];
 			$description = $tweet['user']['description'];
 			$text = $tweet['text'];
 			$idTweet = $tweet['id_str'];
 			$created_at = $tweet['created_at'];
 			//$date = date("Y-m-d H:i:s",strtotime($tweet["created_at"]));
 			$date = date("Y-m-d H:i:s");
 			$retweeted = $tweet['retweeted'];
 			$entidades = @$tweet['entities']['media'];
 			
 
 
 			echo "<br>$i - ";
 			if(is_array($entidades)){
 				  $media_url = @$tweet['entities']['media'][0]['media_url'];
 				  echo "<br>media url : $media_url<br>";
 			}else{
 				  $media_url = 'sin_url';
 			}
 			
 

 			if (isset($tweet['retweeted_status'])){
   				$retweeted_status = $tweet['retweeted_status']; 
    				echo "<br>Mensaje retuiteado: $idTweet - $date - $text  <br>";    	
 	
 			}else{

		    		if($this->twitter->validarExisteUsuario($user_id)==false){ 
			    	echo "<br/><br />";
		    		echo "<b/>UN: Usuario nuevo $screen_name </b>";   	
		    		$this->twitter->agregarUsuarioTweet($user_id, $name, $screen_name, $id_str_user, $location, $followers_count, $description);    		
		    		}else{
		    		echo "<br/><br />";
		    		echo "UR: Usuario repetido $screen_name "; 
		    		}
		    		
		    		//No existe el tuits registrado
    				if($this->twitter->validarExisteTweet($idTweet)==false){ 
		    	        	//echo "<br/>Fecha: $date <br />";
		    	        	echo "<br /><br />";
		    			echo "<b/>TN: Tweet nuevo $idTweet - $date - $text</b>";   	
		    			$this->twitter->agregarTweet($idTweet,$idTweet,$text,$created_at,$date,$retweet_count, $favorite_count, $user_id, $this->ambito); 

		    			$msg = "[AGENTE EXTRACTOR agregarTweet($idTweet)] | $date | $text | $this->ambito \n";
	    				$this->log->general($msg);	


		    			$t_generales++;
    		
    		
	    				//Saber si existen un tweet con las palabras de interes
			    		$this->setTweet($text);
			    		$encontrado = $this->extraerTipoIncidente();
	    		
			    		$dif_palabra = $encontrado["dif_palabra"];
				       	$incidente = $encontrado["incidente"];
				       	$clase_incidente = $encontrado["clase_incidente"];	
				       	
				       	
				       	$encontradoVeh = $this->extraerTipoIncidenteVehiculo();
	    		
			    		$dif_palabraVeh = $encontradoVeh["dif_palabra"];
				       	$vehiculo = $encontradoVeh["vehiculo"];
				       	$clase_vehiculo = $encontradoVeh["clase_vehiculo"];		 	       		
			       		
	    		
			    		// Si la diferencia de palabras es menor o igual de 2 se considera tuits de interes			    		
			    		if($dif_palabra <= 2){
	    		
	    						    					
	    					if($this->twitter->validarExisteTweetInteres($idTweet)==false){ 
	    						//insertamos en otro arreglo los tuits de interes
	    						array_push($tuits_interes, $tweet); 
							echo "<br /> TINuevo: De Interes: Busqueda muy parecida: $incidente (L:$dif_palabra) <br />";
							$this->twitter->agregarTweetInteres($idTweet,$idTweet,$text,$created_at,$date,$media_url,$retweet_count, $favorite_count, $user_id,0,0,0,$incidente,"",$clase_incidente,"",$this->ambito, $vehiculo, $clase_vehiculo); 
							$t_interes++;
							
							$msg = "[AGENTE EXTRACTOR agregarTweetInteres($idTweet)] | $date | $text | $this->ambito \n";
	    					$this->log->general($msg);	            						
	    					
							
							//array_push($ids_tweets, $idTweet);
						}
							 
					 	//Extrar la ubicacion geografica que reporta el tweet
						$lugar = $this->extraerUbicacionTweet();
					
						if(is_array($lugar)){
							echo "<br /> geolocalización añadido <br />";
							$this->twitter->modificarGeoTweetInteres($idTweet,$lugar["lat"],$lugar["lon"],$lugar["etiqueta"],$lugar["clase_lugar"],$lugar["km_aprox"]);
									
						}else{
					
							echo "<br /> Sin geolocalización añadido <br />";
						}	   			
	    			
					} //fin dif_palabra
    		
    		 		
    				}else{
			    		echo "<br/><br />";
			    		echo "TR: Tuit repetido $idTweet - $date - $text "; 
    				}
    	
 	
	 		}//mensaje nuevo
 
 			$i++;
 
  		}// fin for
  		
  		if($tuits_interes>0){
                    include_once 'class.AgenteRevisor.php';
                    $agtRevisor = new AgenteRevisor();
                    $agtRevisor->reportarIncidenteEnVia("#AGMA");
                } 

                // Rendimiento para las estadisticas del agente
  		 $rendimiento_fin = Rendimiento::obtenerTiempoSeg();
  		 $memoria_usada = Rendimiento::obtenerMemoriaUsada();
	   	 $tiempo_ejecucion = Rendimiento::tiempoEjecucion($rendimiento_fin, $rendimiento_inicio);
	   	 
	   	 $param = "Ambito: $this->ambito / tuits: $t_generales / tuits_interes: $t_interes";
	   	 
	   	 $this->estadisticas->guardarEstadistica(__CLASS__,__METHOD__,$param,$tiempo_ejecucion,$memoria_usada);	   	 
	   	 
	   	 
	   	 return $tuits_interes;
   	 
   	 } // fin metodo
   	 
   	 
   	  /**
   	 * Extraer datos desde el flujo de datos del API de Twitter y solo guardar en tabla tuits, solo en periodo de prueba
   	 */
   	 public function iniciar_extraccion_aff(){
   	 
   	 	 $rendimiento_inicio = Rendimiento::obtenerTiempoSeg();
   	 	 $tuits_interes = array();
	   	 $result = $this->twitter->leerTweet();
	   	 $result = $result['statuses'];
	   	 
	   	 echo "<br><h1>Analisis de Tuit: $this->ambito</h1><br>";
		
		 //$this->extraerUbicacionTweet();

		$t_generales = 0;
		$t_interes = 0;
		$i = 0;
		foreach($result as $tweet)
		{
 			$name = $tweet['user']['name'];
 			$screen_name = $tweet['user']['screen_name'];
 			$user_id = $tweet['user']['id'];
 			$id_str_user = $tweet['user']['id_str'];
 			$image = $tweet['user']['profile_image_url'];
 			$location = $tweet['user']['location'];
 			$followers_count = $tweet['user']['followers_count'];
			$retweet_count = $tweet['retweet_count'];
 			$favorite_count = $tweet['favorite_count'];
 			$description = $tweet['user']['description'];
 			$text = $tweet['text'];
 			$idTweet = $tweet['id_str'];
 			$created_at = $tweet['created_at'];
 			$date = date("Y-m-d H:i:s",strtotime($tweet["created_at"]));
 			$retweeted = $tweet['retweeted'];
 			$entidades = @$tweet['entities']['media'];
 			
 
 
 			echo "<br>$i - ";
 			if(is_array($entidades)){
 				  $media_url = @$tweet['entities']['media'][0]['media_url'];
 				  echo "<br>media url : $media_url<br>";
 			}else{
 				  $media_url = 'sin_url';
 			}
 			
 

 			if (isset($tweet['retweeted_status'])){
   				$retweeted_status = $tweet['retweeted_status']; 
    				echo "<br>Mensaje retuiteado: $idTweet - $date - $text  <br>";    	
 	
 			}else{

		    		if($this->twitter->validarExisteUsuario($user_id)==false){ 
			    	echo "<br/><br />";
		    		echo "<b/>UN: Usuario nuevo $screen_name </b>";   	
		    		$this->twitter->agregarUsuarioTweet($user_id, $name, $screen_name, $id_str_user, $location, $followers_count, $description);    		
		    		}else{
		    		echo "<br/><br />";
		    		echo "UR: Usuario repetido $screen_name "; 
		    		}
		    		
		    		//No existe el tuits registrado
    				if($this->twitter->validarExisteTweet($idTweet)==false){ 
		    	        	//echo "<br/>Fecha: $date <br />";
		    	        	echo "<br /><br />";
		    			echo "<b/>TN: Tweet nuevo $idTweet - $date - $text</b>";   	
		    			$this->twitter->agregarTweet($idTweet,$idTweet,$text,$created_at,$date,$retweet_count, $favorite_count, $user_id, $this->ambito); 

		    			$msg = "[AGENTE EXTRACTOR agregarTweet($idTweet)] | $date | $text | $this->ambito \n";
	    				$this->log->general($msg);	

		    			$t_generales++;
    		
    		
	    				//Saber si existen un tweet con las palabras de interes
			    		$this->setTweet($text);
			    		$encontrado = $this->extraerTipoIncidente();
	    		
			    		$dif_palabra = $encontrado["dif_palabra"];
				       	$incidente = $encontrado["incidente"];
				       	$clase_incidente = $encontrado["clase_incidente"];	
				       	
				       	
				       	$encontradoVeh = $this->extraerTipoIncidenteVehiculo();
	    		
			    		$dif_palabraVeh = $encontradoVeh["dif_palabra"];
				       	$vehiculo = $encontradoVeh["vehiculo"];
				       	$clase_vehiculo = $encontradoVeh["clase_vehiculo"];		 	       		
			       		
	    		
			    		// Si la diferencia de palabras es menor o igual de 2 se considera tuits de interes			    		
			    		if($dif_palabra <= 2){
	    		
	    						    					
	    					if($this->twitter->validarExisteTweetInteres($idTweet)==false){ 
	    						//insertamos en otro arreglo los tuits de interes
	    						array_push($tuits_interes, $tweet); 
								echo "<br /> TINuevo: De Interes: Busqueda muy parecida: $incidente (L:$dif_palabra) <br />";
								$this->twitter->agregarTweetInteres($idTweet,$idTweet,$text,$created_at,$date,$media_url,$retweet_count, $favorite_count, $user_id,0,0,0,$incidente,"",$clase_incidente,"",$this->ambito, $vehiculo, $clase_vehiculo); 
								$t_interes++;
							
							            						
	    					$msg = "[AGENTE EXTRACTOR agregarTweetInteres($idTweet)] | $date | $text | $this->ambito \n";
	    					$this->log->general($msg);	
							
							//array_push($ids_tweets, $idTweet);
						}
							 
					 	//Extrar la ubicacion geografica que reporta el tweet
						$lugar = $this->extraerUbicacionTweet();
					
						if(is_array($lugar)){
							echo "<br /> geolocalización añadido <br />";
							$this->twitter->modificarGeoTweetInteres($idTweet,$lugar["lat"],$lugar["lon"],$lugar["etiqueta"],$lugar["clase_lugar"],$lugar["km_aprox"]);
									
						}else{
					
							echo "<br /> Sin geolocalización añadido <br />";
						}	   			
	    			
					} //fin dif_palabra
    		
    		 		
    				}else{
			    		echo "<br/><br />";
			    		echo "TR: Tuit repetido $idTweet - $date - $text "; 
    				}
    	
 	
	 		}//mensaje nuevo
 
 			$i++;
 
  		}// fin for
                
                if($tuits_interes>0){
                    include_once 'class.AgenteRevisor.php';
                    $agtRevisor = new AgenteRevisor();
                    $agtRevisor->reportarIncidenteEnVia("#AFF");
                }
  		
  		 // Rendimiento para las estadisticas del agente
  		 $rendimiento_fin = Rendimiento::obtenerTiempoSeg();
  		 $memoria_usada = Rendimiento::obtenerMemoriaUsada();
	   	 $tiempo_ejecucion = Rendimiento::tiempoEjecucion($rendimiento_fin, $rendimiento_inicio);
	   	 
	   	 $param = "Ambito: $this->ambito / tuits: $t_generales / tuits_interes: $t_interes";
	   	 
	   	 $this->estadisticas->guardarEstadistica(__CLASS__,__METHOD__,$param,$tiempo_ejecucion,$memoria_usada);	   	 
	   	 
	   	 
	   	 return $tuits_interes;
   	 
   	 } // fin metodo




   	 /**
   	 * Extraer datos desde el flujo de datos del API de Twitter y solo guardar en tabla tuits, solo en periodo de prueba
   	 */
   	 public function iniciar_extraccion_cota1000(){
   	 
   	 	 $rendimiento_inicio = Rendimiento::obtenerTiempoSeg();
   	 	 $tuits_interes = array();
	   	 $result = $this->twitter->leerTweet();
	   	 $result = $result['statuses'];
	   	 
	   	 echo "<br><h1>Analisis de Tuit: $this->ambito</h1><br>";
		
		 //$this->extraerUbicacionTweet();

		$t_generales = 0;
		$t_interes = 0;
		$i = 0;
		foreach($result as $tweet)
		{
 			$name = $tweet['user']['name'];
 			$screen_name = $tweet['user']['screen_name'];
 			$user_id = $tweet['user']['id'];
 			$id_str_user = $tweet['user']['id_str'];
 			$image = $tweet['user']['profile_image_url'];
 			$location = $tweet['user']['location'];
 			$followers_count = $tweet['user']['followers_count'];
			$retweet_count = $tweet['retweet_count'];
 			$favorite_count = $tweet['favorite_count'];
 			$description = $tweet['user']['description'];
 			$text = $tweet['text'];
 			$idTweet = $tweet['id_str'];
 			$created_at = $tweet['created_at'];
 			$date = date("Y-m-d H:i:s",strtotime($tweet["created_at"]));
 			$retweeted = $tweet['retweeted'];
 			$entidades = @$tweet['entities']['media'];
 			
 
 
 			echo "<br>$i - ";
 			if(is_array($entidades)){
 				  $media_url = @$tweet['entities']['media'][0]['media_url'];
 				  echo "<br>media url : $media_url<br>";
 			}else{
 				  $media_url = 'sin_url';
 			}
 			
 

 			if (isset($tweet['retweeted_status'])){
   				$retweeted_status = $tweet['retweeted_status']; 
    				echo "<br>Mensaje retuiteado: $idTweet - $date - $text  <br>";    	
 	
 			}else{

		    		if($this->twitter->validarExisteUsuario($user_id)==false){ 
			    	echo "<br/><br />";
		    		echo "<b/>UN: Usuario nuevo $screen_name </b>";   	
		    		$this->twitter->agregarUsuarioTweet($user_id, $name, $screen_name, $id_str_user, $location, $followers_count, $description);    		
		    		}else{
		    		echo "<br/><br />";
		    		echo "UR: Usuario repetido $screen_name "; 
		    		}
		    		
		    		//No existe el tuits registrado
    				if($this->twitter->validarExisteTweet($idTweet)==false){ 
		    	        	//echo "<br/>Fecha: $date <br />";
		    	        	echo "<br /><br />";
		    			echo "<b/>TN: Tweet nuevo $idTweet - $date - $text</b>";   	
		    			$this->twitter->agregarTweet($idTweet,$idTweet,$text,$created_at,$date,$retweet_count, $favorite_count, $user_id, $this->ambito); 

		    			$msg = "[AGENTE EXTRACTOR agregarTweet($idTweet)] | $date | $text | $this->ambito \n";
	    				$this->log->general($msg);	

		    			$t_generales++;
    		
    		
	    				//Saber si existen un tweet con las palabras de interes
			    		$this->setTweet($text);
			    		$encontrado = $this->extraerTipoIncidente();
	    		
			    		$dif_palabra = $encontrado["dif_palabra"];
				       	$incidente = $encontrado["incidente"];
				       	$clase_incidente = $encontrado["clase_incidente"];	
				       	
				       	
				       	$encontradoVeh = $this->extraerTipoIncidenteVehiculo();
	    		
			    		$dif_palabraVeh = $encontradoVeh["dif_palabra"];
				       	$vehiculo = $encontradoVeh["vehiculo"];
				       	$clase_vehiculo = $encontradoVeh["clase_vehiculo"];		 	       		
			       		
	    		
			    		// Si la diferencia de palabras es menor o igual de 2 se considera tuits de interes			    		
			    		if($dif_palabra <= 2){
	    		
	    						    					
	    					if($this->twitter->validarExisteTweetInteres($idTweet)==false){ 
	    						//insertamos en otro arreglo los tuits de interes
	    						array_push($tuits_interes, $tweet); 
							echo "<br /> TINuevo: De Interes: Busqueda muy parecida: $incidente (L:$dif_palabra) <br />";
							$this->twitter->agregarTweetInteres($idTweet,$idTweet,$text,$created_at,$date,$media_url,$retweet_count, $favorite_count, $user_id,0,0,0,$incidente,"",$clase_incidente,"",$this->ambito, $vehiculo, $clase_vehiculo); 
							$t_interes++;
							
							            						
	    					$msg = "[AGENTE EXTRACTOR agregarTweetInteres($idTweet)] | $date | $text | $this->ambito \n";
	    					$this->log->general($msg);	
							
							//array_push($ids_tweets, $idTweet);
						}
							 
					 	//Extrar la ubicacion geografica que reporta el tweet
						$lugar = $this->extraerUbicacionTweet();
					
						if(is_array($lugar)){
							echo "<br /> geolocalización añadido <br />";
							$this->twitter->modificarGeoTweetInteres($idTweet,$lugar["lat"],$lugar["lon"],$lugar["etiqueta"],$lugar["clase_lugar"],$lugar["km_aprox"]);
									
						}else{
					
							echo "<br /> Sin geolocalización añadido <br />";
						}	   			
	    			
					} //fin dif_palabra
    		
    		 		
    				}else{
			    		echo "<br/><br />";
			    		echo "TR: Tuit repetido $idTweet - $date - $text "; 
    				}
    	
 	
	 		}//mensaje nuevo
 
 			$i++;
 
  		}// fin for
  		
  		 
                if($tuits_interes>0){
                    include_once 'class.AgenteRevisor.php';
                    $agtRevisor = new AgenteRevisor();
                    $agtRevisor->reportarIncidenteEnVia("#CotaMil");
                } 
                // Rendimiento para las estadisticas del agente
  		 $rendimiento_fin = Rendimiento::obtenerTiempoSeg();
  		 $memoria_usada = Rendimiento::obtenerMemoriaUsada();
	   	 $tiempo_ejecucion = Rendimiento::tiempoEjecucion($rendimiento_fin, $rendimiento_inicio);
	   	 
	   	 $param = "Ambito: $this->ambito / tuits: $t_generales / tuits_interes: $t_interes";
	   	 
	   	 $this->estadisticas->guardarEstadistica(__CLASS__,__METHOD__,$param,$tiempo_ejecucion,$memoria_usada);	   	 
	   	 
	   	 
	   	 return $tuits_interes;
   	 
   	 } // fin metodo
   	 
   	 
   	 
   	 /**
   	 * Extraer datos desde el flujo de datos del API de Twitter
   	 */
   	 public function iniciar_extraccion_pnm(){
   	 
   	 	 $rendimiento_inicio = Rendimiento::obtenerTiempoSeg();
   	 	 $tuits_interes = array();
	   	 $result = $this->twitter->leerTweet();
	   	 $result = $result['statuses'];
	   	 
	   	 //$this->extraerUbicacionTweet();
	   	 
	   	 echo "<br><h1>Analisis de Tuit: $this->ambito</h1><br>";


		$t_generales = 0;
		$t_interes = 0;
		$i = 0;
		foreach($result as $tweet)
		{
 			$name = $tweet['user']['name'];
 			$screen_name = $tweet['user']['screen_name'];
 			$user_id = $tweet['user']['id'];
 			$id_str_user = $tweet['user']['id_str'];
 			$image = $tweet['user']['profile_image_url'];
 			$location = $tweet['user']['location'];
 			$followers_count = $tweet['user']['followers_count'];
			$retweet_count = $tweet['retweet_count'];
 			$favorite_count = $tweet['favorite_count'];
 			$description = $tweet['user']['description'];
 			$text = $tweet['text'];
 			$idTweet = $tweet['id_str'];
 			$created_at = $tweet['created_at'];
 			$date = date("Y-m-d H:i:s",strtotime($tweet["created_at"]));
 			$retweeted = $tweet['retweeted'];
 			$entidades = @$tweet['entities']['media'];
 			
 
 
 			echo "<br>$i - ";
 			if(is_array($entidades)){
 				  $media_url = @$tweet['entities']['media'][0]['media_url'];
 				  echo "<br>media url : $media_url<br>";
 			}else{
 				  $media_url = 'sin_url';
 			}
 			
 

 			if (isset($tweet['retweeted_status'])){
   				$retweeted_status = $tweet['retweeted_status']; 
    				echo "<br>Mensaje retuiteado: $idTweet - $date - $text  <br>";    	
 	
 			}else{

		    		if($this->twitter->validarExisteUsuario($user_id)==false){ 
			    	echo "<br/><br />";
		    		echo "<b/>UN: Usuario nuevo $screen_name </b>";   	
		    		$this->twitter->agregarUsuarioTweet($user_id, $name, $screen_name, $id_str_user, $location, $followers_count, $description);    		
		    		}else{
		    		echo "<br/><br />";
		    		echo "UR: Usuario repetido $screen_name "; 
		    		}
		    		
		    		//No existe el tuits registrado
    				if($this->twitter->validarExisteTweet($idTweet)==false){ 
		    	        	//echo "<br/>Fecha: $date <br />";
		    	        	echo "<br /><br />";
		    			echo "<b/>TN: Tweet nuevo $idTweet - $date - $text</b>";   	
		    			$this->twitter->agregarTweet($idTweet,$idTweet,$text,$created_at,$date,$retweet_count, $favorite_count, $user_id, $this->ambito); 

		    			$msg = "[AGENTE EXTRACTOR agregarTweet($idTweet)] | $date | $text | $this->ambito \n";
	    				$this->log->general($msg);	

		    			$t_generales++;
    		
    		
	    				//Saber si existen un tweet con las palabras de interes
			    		$this->setTweet($text);
			    		$encontrado = $this->extraerTipoIncidente();
	    		
			    		$dif_palabra = $encontrado["dif_palabra"];
				       	$incidente = $encontrado["incidente"];
				       	$clase_incidente = $encontrado["clase_incidente"];	
				       	
				       	$encontradoVeh = $this->extraerTipoIncidenteVehiculo();
	    		
			    		$dif_palabraVeh = $encontradoVeh["dif_palabra"];
				       	$vehiculo = $encontradoVeh["vehiculo"];
				       	$clase_vehiculo = $encontradoVeh["clase_vehiculo"];		 	       		
			       		
	    		
			    		// Si la diferencia de palabras es menor o igual de 2 se considera tuits de interes			    		
			    		if($dif_palabra <= 2){
	    		
	    						    					
	    					if($this->twitter->validarExisteTweetInteres($idTweet)==false){ 
	    						//insertamos en otro arreglo los tuits de interes
	    						array_push($tuits_interes, $tweet); 
							echo "<br /> TINuevo: De Interes: Busqueda muy parecida: $incidente (L:$dif_palabra) <br />";
							$this->twitter->agregarTweetInteres($idTweet,$idTweet,$text,$created_at,$date,$media_url,$retweet_count, $favorite_count, $user_id,0,0,0,$incidente,"",$clase_incidente,"",$this->ambito, $vehiculo, $clase_vehiculo); 
							$t_interes++;
							
							            						
	    					$msg = "[AGENTE EXTRACTOR agregarTweetInteres($idTweet)] | $date | $text | $this->ambito \n";
	    					$this->log->general($msg);	
							
							//array_push($ids_tweets, $idTweet);
						}
							 
					 	//Extrar la ubicacion geografica que reporta el tweet
						$lugar = $this->extraerUbicacionTweet();
					
						if(is_array($lugar)){
							echo "<br /> geolocalización añadido <br />";
							$this->twitter->modificarGeoTweetInteres($idTweet,$lugar["lat"],$lugar["lon"],$lugar["etiqueta"],$lugar["clase_lugar"],$lugar["km_aprox"]);
									
						}else{
					
							echo "<br /> Sin geolocalización añadido <br />";
						}	   			
	    			
					} //fin dif_palabra
    		
    		 		
    				}else{
			    		echo "<br/><br />";
			    		echo "TR: Tuit repetido $idTweet - $date - $text "; 
    				}
    	
    
    
 	
	 		}//mensaje nuevo
 
 			$i++;
 
  		}// fin for
  		
                if($tuits_interes>0){
                    include_once 'class.AgenteRevisor.php';
                    $agtRevisor = new AgenteRevisor();
                    $agtRevisor->reportarIncidenteEnVia("#PNM");
                }   		 
                // Rendimiento para las estadisticas del agente
  		 $rendimiento_fin = Rendimiento::obtenerTiempoSeg();
  		 $memoria_usada = Rendimiento::obtenerMemoriaUsada();
	   	 $tiempo_ejecucion = Rendimiento::tiempoEjecucion($rendimiento_fin, $rendimiento_inicio);
	   	 
	   	 $param = "Ambito: $this->ambito / tuits: $t_generales / tuits_interes: $t_interes";
	   	 
	   	 $this->estadisticas->guardarEstadistica(__CLASS__,__METHOD__,$param,$tiempo_ejecucion,$memoria_usada);	   	 
	   	 
	   	 
	   	 return $tuits_interes;
   	 
   	 } // fin metodo
   	 
   	 
   	 /**
   	 *
   	 * Encontrar las palabras claves dentro de un tuit 
   	 */
   	 public function extraerUbicacionTweet(){ 
  
  
  		$encontrado = false;
  		$lugares = $this->lugares_interes[$this->ambito];	 		
  		
  		
  		
  		foreach ($lugares as $clave=>$valor) {
    
     		//$clave = strtolower($clave);
     		//echo "<br>"."id= ".$tweet."palabra= ".$clave;
     		//echo "<br>"."clave: ".$clave;
    		if (strpos($this->tweet, $clave) !== false) {    		
     		//if (strripos($this->tweet, $clave) !== false) {    		
        		         //echo "<br> $clave";
        			 $encontrado = $valor;       
         		//break;      
   		 }
   		}//fin foreach
  
   		return $encontrado; 
  

	}
   	 
   	   	 
   	 
   	
	/*
	* Saber si existen un tweet con las palabras de interes
	*
	*/
   	public function extraerTipoIncidente(){
  
 		$this->palabraInteres = new PalabraInteres();		
 		$palabra_interes_bd = $this->palabraInteres->obtenerPalabrasInteres();

 		//echo $this->tweet;
 		$i = 0;
  		//$texto = explode(" ",$this->tweet);//divide la frase de 140 caraceres en palabras (division de espacios)
  		$texto = preg_split('/\s+/', $this->tweet); 	
  		$salida = array("incidente" => "","dif_palabra"=> 999, "clase_incidente" => "");	
  		$tam_texto = count($texto); 
  		while ($i < $tam_texto){
    			$dif_palabra = 0; 	
		
			for ($j=0;$j<count($palabra_interes_bd);$j++) {				

    			$palabra = $palabra_interes_bd[$j]['tx_nb_palabra_interes'];
    			$clase = $palabra_interes_bd[$j]['tx_clase_palabra_interes'];
    			
         			$dif_palabra = levenshtein($texto[$i], $palabra);
         			//echo "<br><b>Palabra Interes BD </b>:  $texto[$i] = $palabra => $clase";
         
         			//La tolerancia de letras entre palabras, se usa la distancia de levenshtein
         			//if ( $dif_palabra <= 1){        
         			if ( $dif_palabra == 0){        
					$salida = array("incidente" => $palabra,"dif_palabra"=>$dif_palabra,"clase_incidente"=>$clase);					
				}
	
     			}//fin for

     			$i++;
   		}//fin while
   
   		return $salida;  
 	}
 	
 	
 	/*
	* Saber si existen un tweet con las palabras de interes
	*
	*/
   	public function extraerTipoIncidenteVehiculo(){
  
 		$this->palabraInteres = new PalabraInteres();		
 		$palabra_interes_bd = $this->palabraInteres->obtenerPalabrasInteresVehiculo();

 		//echo $this->tweet;
 		$i = 0;
  		//$texto = explode(" ",$this->tweet);//divide la frase de 140 caraceres en palabras (division de espacios)
  		$texto = preg_split('/\s+/', $this->tweet); 	
  		$salida = array("vehiculo" => "","dif_palabra"=> 999, "clase_vehiculo" => "");	
  		$tam_texto = count($texto); 
  		while ($i < $tam_texto){
    			$dif_palabra = 0; 	
		
			for ($j=0;$j<count($palabra_interes_bd);$j++) {				

    			$palabra = $palabra_interes_bd[$j]['tx_nb_palabra_interes'];
    			$clase = $palabra_interes_bd[$j]['tx_clase_palabra_interes'];
    			
         			$dif_palabra = levenshtein($texto[$i], $palabra);
         			//echo "<br><b>Palabra Interes BD </b>:  $texto[$i] = $palabra => $clase";
         
         			//La tolerancia de letras entre palabras, se usa la distancia de levenshtein
         			//if ( $dif_palabra <= 1){        
         			if ( $dif_palabra == 0){        
					$salida = array("vehiculo" => $palabra,"dif_palabra"=>$dif_palabra,"clase_vehiculo"=>$clase);					
				}
	
     			}//fin for

     			$i++;
   		}//fin while
   
   		return $salida;  
 	}
   	
   	/**
   	* Registrar accion en memoria
   	*/
   	public function registrarEnMemoria($ids_tweets){
    
    	
    		$cantidad = count($ids_tweets);
		//if ($cantidad > 0){
			$ids_tweets = implode(",", $ids_tweets);	
			echo "<br><br> Agregados a la memoria de OVNI $cantidad tuits";
		//}
    	
    		$accion = 'nuevo';
    		$fecha = date("Y-m-d H:i:s");
    		$sql = "INSERT INTO tr006_memoria_ovni (ids_tweets, cantidad, accion, fecha) VALUES (:ids_tweets, :cantidad, :accion, :fecha)";			
   		
   		$con = $this->bd->obtenerConexion();						
		$stmt = $con->prepare($sql);
		$stmt->execute(array(':ids_tweets'=>$ids_tweets,':cantidad'=>$cantidad,':accion'=>$accion,':fecha'=>$fecha));	
				   
		return $lastId = $con->lastInsertId();
    
    	}
   	
   	
   	
   	function __destruct() {
       
   	}
   	
   	
   	
   	
   	 	

}//fin clase

//$obj = new AgenteExtractor();

//echo $obj->registrarEnMemoria("212313123,53453534534,6346464645645,8768678678768768,63463464645645645,",9,"nuevo");



//$t = "@Transito_SAA: Gracias @wcarao vehículo accidentado en el Km 17 al 0 de la #PNM sentido #SAA genera fuerte retraso a esta Km 1 hora 4:20 pm";
//$t = "via @anambravo: motivo de la cola en la #PNM metrobus accidentado justo en la entrada de la vega sentido SAA #Caracas";
//$t = $obj->formatearCadena($t);


//var_dump($obj->extraerDatosTweet($t));
//$obj->extraerCaracteresTweet($t);
//echo "<br><br>";
//print_r($obj->extraerUbicacionTweet());

/**/



?>