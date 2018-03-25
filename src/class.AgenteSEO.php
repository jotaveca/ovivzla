<?php
include_once("class.BD.php");
include_once("class.Suscriptor.php");
include_once("class.AgenteRevisor.php");
include_once("class.Twitter.php");
include_once("class.Log.php");
include_once("class.GoogleUrl.php");
require_once("class.Rendimiento.php");
@include_once('../vendor/twitter-api-php-master-2016/TwitterAPIExchange.php');

class AgenteSEO{

	private $bd;		
	private $mensaje;	
	private $log;		
	private $estadisticas;
	private $belief; // BDI Agente arquitecture
	private $desire; // BDI Agente arquitecture
	private $intention; /// BDI Agente arquitecture
	
	function __construct() {	
		
            $this->bd = new BD();	           
            $this->estadisticas = new Rendimiento();
            
            /*OvniVial*/
              /*  $this->settings = array(
				 'oauth_access_token' => '3351640672-qloeho6NasLJq3rbKXUsDyaZd7qB5iQcDpH8GKO',
				 'oauth_access_token_secret' => '60VFcXGaaBvDzOAjvaPPbefjrSQ7UDds5sDPfaeYllqoS',
				 'consumer_key' => '48vcnktQZtbrZd3MLR5zRAxFR',
				 'consumer_secret' => '4HHlrYkh7q6WkiMjD4Nlbl3DDXgmTFoRgON5088yzB60y6EOAS');*/
				 
	    /*Ciencia conciencia*/
              $this->settings = array(
                            'oauth_access_token' => '',
                            'oauth_access_token_secret' => '',
                            'consumer_key' => '',
                            'consumer_secret' => '');
                
                
                
            
           
            $this->log = new Log();	
            $fecha = date("Y-m-d H:i:s");	    
	    $msg = "[AGENTE SEO INICIADO] | $fecha ";
	    $this->log->general($msg);	
      	
   	}  	
   	
   	
   	protected function get_oauth_access_token(){
		return $this->settings['oauth_access_token'];
	}
	protected function get_oauth_access_token_secret(){
		return $this->settings['oauth_access_token_secret'];
	}
	protected function get_consumer_key(){
		return $this->settings['consumer_key'];
	}
	protected function get_consumer_secret(){
		return $this->settings['consumer_secret'];
	}
	public function get_settings(){
		return $this->settings;
	}
	public function set_mensaje($mensaje){
		$this->mensaje = $mensaje;
	}
	public function set_requestMethod($requestMethod){
		$this->requestMethod = $requestMethod;
	}
	public function set_urlApiTwitter($urlApiTwitter){
		$this->urlApiTwitter = $urlApiTwitter;
	}
	public function set_getField($getfield){
		$this->getfield = $getfield;
	}
	public function set_postFields($postfields){
		$this->postfields = $postfields;
	}
	
	
	/**
	* Funcion para enviar mensajes de Twiter
	*
	*/
	public function enviarTweet($mensaje){
		
	  $this->set_requestMethod("POST");
       	  $this->set_urlApiTwitter("https://api.twitter.com/1.1/statuses/update.json");
       	  $postfields = array(
            'status' => $mensaje                    
          );
          $this->set_postFields($postfields);
       	  $twitter = new TwitterAPIExchange($this->settings);       	 	
		
        
          $data     = $twitter->request($this->urlApiTwitter, $this->requestMethod, $this->postfields);
          $data = @json_decode($data, true);
          
          $fecha = date("Y-m-d H:i:s");	
 	  $msg = "[Agente SEO enviarTweet()] | $fecha | $mensaje ";
	  $this->log->general($msg);	
          
          
          return $data;
        
        
	}
	
	private function obtenerMediaId($urlImg) {

        	$this->set_requestMethod("POST");
       		$this->set_urlApiTwitter('https://upload.twitter.com/1.1/media/upload.json');        		 		
        
        	$twitter = new TwitterAPIExchange($this->settings);

        	$file = file_get_contents($urlImg);
        	$data = base64_encode($file);
        	
        	$postfields = array(
            	'media_data' => $data
       		 );
       		$this->set_postFields($postfields);     
        	
        	$data     = $twitter->request($this->urlApiTwitter, $this->requestMethod, $this->postfields);
       
        	/** Store the media id for later **/
        	$data = @json_decode($data, true);       	
        	
        	
        	// media_id en formato string
        	return $data["media_id_string"];
        
        
        	
    }
	
	/**
	* Funcion para enviar mensajes de Twiter con imagenes
	*
	*/
	public function enviarTweetImg($mensaje, $urlImg){
			
	  
        	/** Perform a POST request and echo the response **/
        	$media = $this->obtenerMediaId($urlImg);
        	
       	 	$this->set_requestMethod("POST");
       	  	$this->set_urlApiTwitter("https://api.twitter.com/1.1/statuses/update.json");
       	  	$postfields = array(
            		'status' => $mensaje,
            		'possibly_sensitive' => false,
            		'media_ids' => $media                   
          	);
          	$this->set_postFields($postfields);
       	  	$twitter = new TwitterAPIExchange($this->settings);       	 	
		
        
          	$data     = $twitter->request($this->urlApiTwitter, $this->requestMethod, $this->postfields);
          	$data = @json_decode($data, true);
          	
          	$fecha = date("Y-m-d H:i:s");	
 	        $msg = "[Agente SEO enviarTweetImg()] | $fecha | $mensaje | $urlImg";
	        $this->log->general($msg);	
	        
	        
          	return $data;
	}
	
   	
   	
   	
      	
   	public function setMensaje($mensaje){
   	
   		$this->mensaje = $mensaje;
   	} 
   	
   	public function obtenerFechaHoraActual(){
   	
   		setlocale(LC_TIME,"es_VE");
   		//strftime("%A, %d de %B de %Y");
   		//$fechaHora = strftime("%d de %B de %Y");
         //$dia = 
   		$fechaHora = strftime("#%d%B");
   		//echo $fechaHora;
		return $fechaHora;
   	
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
				   "#RespetaLasNormas",
				   "#VidaSegura",
				   "#ConductorResponsable");
		$indice = mt_rand(0,6);
		$msj = $mensajes[$indice];
		return $msj;
		
	}
	
	/* Retorna un mensaje de seguridad vial de forma aletoria
   	 * 
   	 * 
   	 * */
   	
   	private function obtenerMensajeSeguridadVial($tx_ambito){
		
		$mensajes =   	    array( 
				    array("txt"=>"invitación a suscribirse a OVI"															,"img"=>"../web/images/img-twitter/promocion_ovi_2017.jpg"			,"rsm"=>"$tx_ambito Suscríbete a #OVI y recibe la info del transito en tu teléfono http://goo.gl/qTIqp6"),
				    array("txt"=>"#Ovi te recuerda usar el cinturón de seguridad en todo momento $tx_ambito"										,"img"=>"../web/images/img-twitter/m1-campana-ovi.jpg" 			,"rsm"=>"#OVI #SeguridadVial #CulturaVial #RespetaLasNormas #ConductorResponsable $tx_ambito"),
				    array("txt"=>"#Ovi El cinturón de seguridad reduce en 33% el riesgo de sufrir lesiones cerebrales en un siniestro vial $tx_ambito"				        ,"img"=>"../web/images/img-twitter/m2-campana-ovi.jpg" 			,"rsm"=>"#OVI #SeguridadVial #VidaSegura #RespetaLasNormas #ConductorResponsable $tx_ambito"),
                                    array("txt"=>"publicacion blog ovi 2"                                                                                    						,"img"=>"../web/images/img-twitter/autos_tecnologia.jpg"   						,"rsm"=>"Nuevas #tecnologías que hacen los #automóviles más #seguros $tx_ambito #SeguridadVial #OVI  http://ow.ly/cZUH30bEvHQ"),
                                    array("txt"=>"invitación a suscribirse a OVI"									    						,"img"=>"../web/images/img-twitter/promocion_ovi_2017.jpg"							,"rsm"=>"$tx_ambito Suscríbete a #OVI y recibe la info del transito en tu teléfono http://goo.gl/qTIqp6")
                                    );                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
                                    
                                   
		$indice = mt_rand(0,4);
		$msj = $mensajes[$indice];
		return $msj;
		
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
         * Indica si el tweet cumple con el tamaño máximo establecido por Twitter (136)
         * @param tweet
         * @return true o false
         */
        public function validarTamMaxTweet($tweet){
            $tam = strlen($tweet);
            echo "<br> tamaño: $tam <br>";
            if ($tam <= 136){
                //echo ' Tamaño del tweet '.$tam;
                return true;
            }
            else{
                echo ' Excede el tamaño máximo del tweet '.$tam;
                return false;
            }
        }
        
        
        
        /*
         * Notifica sobre un incidente ocurrido 
         * 
         * 
         */
	public function enviarPostSEO(){
            
            	$mensaje = "¿Qué es el Observatorio Vial Inteligente (OVI)?"; 
            	$url = 'http://blog.ovi.org.ve/seguridad-vial/observatorio-vial-inteligente-ovi/';
            	
            	$fecha = $this->obtenerFechaHoraActual(); 	
            	$ambito = $this->obtenerAmbitoAleatorio();
            	$etiqueta = $this->obtenerEtiquetaVialCorta();
            	
            	$mensaje =      $fecha." ".$mensaje." ".$ambito." ".$etiqueta; 	
            	//echo $mensaje."\n";	           
            	
            	$result = $this->validarTamMaxTweet($mensaje);
            	if (!$result){
            	    $this->enviarPostSEO();                
	        }
            	else{
            
                  	         
 		  $msg = "[AGENTE SEO enviarPostSEO()] | $fecha | $mensaje | $ambito";
	    	  $this->log->general($msg);	
	    	  
	    	 $mensaje = $mensaje.' '.$url; 
	    	  
	    	 echo $mensaje."\n";	   	    				
	    		
               
                 $this->enviarTweet($mensaje); 
                 	
                 	
                }
               
                    
          
            
        }
        
        
	
        /* Envía los datos a la clase Twitter para escribir en el Timeline
	 * 
	 * 
	 * */
	public function enviarTweetPlano($mensaje){	
	
            echo $mensaje."\n";	                          
            $this->enviarTweet($mensaje);
		
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
        
   
   	 	

}//fin clase

$a = new AgenteSEO();
$a->enviarPostSEO();




?>
