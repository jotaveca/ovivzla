<?php
/**
 * Manejo de la conexión a twitter
 */
@include_once('../vendor/twitter-api-php-master-2016/TwitterAPIExchange.php');
include_once("class.Log.php");
//https://github.com/J7mbo/twitter-api-php/blob/master/test/TwitterAPIExchangeTest.php

class Twitter
{

	# Credenciales de Twitter

	private $settings = array();
	private $oauth_access_token;
	private $oauth_access_token_secret;
	private $consumer_key;
	private $consumer_secret;
	private $urlApiTwitter;
	private $requestMethod;
	private $getfield;
	private $postfields;
	private $mensaje = "";
	private $respuesta = "";
	private $bd;
	private $log;	

	function __construct() {
		
		$this->log = new Log();	
            	$fecha = date("Y-m-d H:i:s");	    
	    	$msg = "[TWITTER INICIADO] | $fecha ";
	    	$this->log->general($msg);	
		
		$this->bd = new BD();
		
		/*OvniVial*/
                $this->settings = array(
				 'oauth_access_token' => '',
				 'oauth_access_token_secret' => '',
				 'consumer_key' => '',
				 'consumer_secret' => '');
                 
                 
             
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
 	  $msg = "[TWITTER enviarTweet()] | $fecha | $mensaje ";
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
 	        $msg = "[TWITTER enviarTweetImg()] | $fecha | $mensaje | $urlImg";
	        $this->log->general($msg);	
	        
	        
          	return $data;
	}
	
	
	
	
	/**
	* Funcion para leer mensajes de Twiter
	*
	*/
	public function leerTweet(){
		
        	$this->set_urlApiTwitter("https://api.twitter.com/1.1/search/tweets.json");
		$this->set_requestMethod('GET');
        	$twitter = new TwitterAPIExchange($this->settings);
        	
        	$data = $twitter->request($this->urlApiTwitter, $this->requestMethod, $this->getfield);
        	$data = (array)@json_decode($data, true);        
		
		return $data;
	}
	
	/**
    	 * Permite conocer la relación que existe entre mi usuario y otro usuario
    	 * @param $usuario (screen_name)
    	 */
    	 private function conocerRelacionUsuario($usuario){
            				
            $this->set_requestMethod("GET");
            $this->set_urlApiTwitter("https://api.twitter.com/1.1/friendships/lookup.json");
            $this->getfield = '?screen_name='.$usuario;            
            //user_id       		
            $twitter = new TwitterAPIExchange($this->settings);
            $data = $twitter->request($this->urlApiTwitter, $this->requestMethod, $this->getfield);
            //$json = json_decode($json,TRUE);
            //var_dump($json);
            //die();
            return $data;			 
	}
	
		
	
	/**
	 * 
	 * Agregar un tweet a la BD
	 * @param $id_tweet,$id_str,$text,$created_at,$date,$retweet_count,$favorite_count,$user_id
	 * @return lastInsertId()
	 *
	 */
	 public function agregarTweet($id_tweet,$id_str,$text,$created_at,$date,$retweet_count,$favorite_count,$user_id, $tx_ambito) {
		
				
		$sql = "INSERT INTO tr001_tweet (id_tweet,id_str,text,created_at,date,retweet_count,favorite_count,user_id, tx_ambito) VALUES (:id_tweet,:id_str,:text,:created_at,:date,:retweet_count,:favorite_count,:user_id, :tx_ambito)";					
		
   		
   		$con = $this->bd->obtenerConexion();						
		$stmt = $con->prepare($sql);
			$stmt->execute(array(':id_tweet'=>$id_tweet,':id_str'=>$id_str,':text'=>$text,':created_at'=>$created_at,':date'=>$date,':retweet_count'=>$retweet_count,':favorite_count'=>$favorite_count,':user_id'=>$user_id, ':tx_ambito'=>$tx_ambito));				   
		
				   
		return $lastId = $con->lastInsertId();
							
		
	}
	
	/**
	 * 
	 * Eliminar un tweet de interes de la BD
	 * @param $id_tweet,$id_str,$text,$created_at,$date,$retweet_count,$favorite_count,$user_id
	 * @return lastInsertId()
	 *
	 */
	 public function eliminarTweetInteres($id_tweet) {
		
				
		$sql = "DELETE FROM tr002_tweet_interes WHERE id_tweet = :id_tweet";
   		//echo $sql;
   		$con = $this->bd->obtenerConexionSegura();						
		$stmt = $con->prepare($sql);
		return $stmt->execute(array(':id_tweet'=>$id_tweet));	
					   
		
			
	}
	
	
	/**
	 * 
	 * Agregar un tweet a la BD
	 * @param $id_tweet,$id_str,$text,$created_at,$date,$retweet_count,$favorite_count,$user_id
	 * @return lastInsertId()
	 *
	 */
	 public function agregarTweetInteres($id_tweet,$id_str,$text,$created_at,$date,  $media_url, $retweet_count,$favorite_count,$user_id,$lat,$lon, $lugar, $incidente, $clase_lugar, $clase_incidente, $lematizacion, $tx_ambito, $vehiculo, $clase_vehiculo) {
		
			
			$sql = "INSERT INTO tr002_tweet_interes (id_tweet,id_str,text,created_at, date, media_url,hora_incidente,dia_semana_incidente,dia_mes_incidente,mes_incidente,anio_incidente,retweet_count,favorite_count,user_id,lat,lon,lugar,incidente,clase_lugar,clase_incidente,lematizacion,tx_ambito, vehiculo, clase_vehiculo) VALUES (:id_tweet,:id_str,:text,:created_at,:date,:media_url,:hora_incidente,:dia_semana_incidente,:dia_mes_incidente,:mes_incidente,:anio_incidente,:retweet_count,:favorite_count,:user_id,:lat,:lon,:lugar,:incidente,:clase_lugar, :clase_incidente,:lematizacion, :tx_ambito, :vehiculo, :clase_vehiculo)";	
			
			
   			$con = $this->bd->obtenerConexion();						
			$stmt = $con->prepare($sql);
			
			//var_dump($stmt);
			
			//Desconposición de las fechas para crear una vista minable por atributo
			$fecha_interes = date("l;j;F;Y;g a",strtotime($date)); 
			$fecha_partes = explode(";", $fecha_interes);
			
			$dia_semana_incidente = $fecha_partes[0];
			$dia_mes_incidente = $fecha_partes[1];
			$mes_incidente = $fecha_partes[2];
			$anio_incidente = $fecha_partes[3];
			$hora_incidente = $fecha_partes[4];
			
				$parametros = array(':id_tweet'=>$id_tweet,
                                    ':id_str'=>$id_str,
                                    ':text'=>$text,
                                    ':created_at'=>$created_at,
                                    ':date'=>$date,
                                    ':media_url'=>$media_url,
                                    ':hora_incidente' => $hora_incidente,
                                    ':dia_semana_incidente' => $dia_semana_incidente,
                                    ':dia_mes_incidente' => $dia_mes_incidente,
                                    ':mes_incidente' => $mes_incidente,
                                    ':anio_incidente' => $anio_incidente,
                                    ':retweet_count'=>$retweet_count,
                                    ':favorite_count'=>$favorite_count,
                                    ':user_id'=>$user_id,
                                    ':lat'=>$lat,
                                    ':lon'=>$lon,
                                    ':lugar'=>$lugar,
                                    ':incidente'=>$incidente,
                                    ':clase_lugar'=>$clase_lugar,
                                    ':clase_incidente'=>$clase_incidente,
                                    ':lematizacion'=>$lematizacion,
                                    ':tx_ambito'=>$tx_ambito,
                                    ':vehiculo'=>$vehiculo,
                                    ':clase_vehiculo'=>$clase_vehiculo                                   
                                    );
				 
				   $stmt->execute($parametros);
				   
				   /*echo "\nPDOStatement::errorInfo():\n";
				   $arr = $stmt->errorInfo();
				   print_r($arr);*/


		
				   
		return $lastId = $con->lastInsertId();
							
		
	}
        
        /**
	 * 
	 * Agregar un tweet a la BD
	 * @param $id_tweet,$id_str,$text,$created_at,$date,$retweet_count,$favorite_count,$user_id
	 * @return lastInsertId()
	 *
	 */
	 public function agregarHistoricoTweetInteresEliminados($id_tweet,$text,$date,$user_id,$id_corrida,$id_tweet_eliminador,$id_corrida_met) {
		
			
			$sql = "INSERT INTO tr008_tweet_interes_eliminados (id_tweet,text,date,user_id,id_corrida,id_tweet_eliminador,id_corrida_met) VALUES (:id_tweet, :text, :date, :user_id, :id_corrida, :id_tweet_eliminador, :id_corrida_met)";	
			//echo "<br>".$sql."<br>";
                        
			echo "<br>$id_tweet,$text,$date,$user_id,$id_corrida,$id_tweet_eliminador<br>";
   			$con = $this->bd->obtenerConexion();	
			$stmt = $con->prepare($sql);
			$mysqltime = strtotime ($date);                        
                        $fecha = date("Y-m-d H:i:s",$mysqltime);
			$parametros = array(':id_tweet'=>$id_tweet,                                            
                                            ':text'=>$text,                                            
                                            ':date'=>$fecha,
                                            ':user_id'=>$user_id,
                                            ':id_corrida'=>$id_corrida,
                                            ':id_tweet_eliminador'=>$id_tweet_eliminador,
                                            'id_corrida_met'=>$id_corrida_met);
                        
                        //print_r($parametros);
                        $stmt->execute($parametros);                        
                        print_r($stmt->errorInfo());	
                        $stmt->debugDumpParams();
		return $lastId = $con->lastInsertId();
							
		
	}
	
	
	/**
	 * 
	 * Modificar un tweet a la BD para localizar su ubicación geográfica
	 * @return lastInsertId()
	 *
	 */
	 public function modificarGeoTweetInteres($id_tweet,$lat,$lon,$lugar,$clase_lugar,$km_aprox) {
		
					
			$sql = "UPDATE tr002_tweet_interes SET lat =:lat ,lon = :lon, lugar = :lugar, clase_lugar = :clase_lugar, km_aprox=:km_aprox WHERE id_tweet = :id_tweet";	
			
			$con = $this->bd->obtenerConexion();						
			$stmt = $con->prepare($sql);		
			
                        $parametros = array(':lat'=>$lat,
                            ':lon'=>$lon,
                            ':lugar'=>$lugar,
                            ':clase_lugar'=>$clase_lugar,
                            ':id_tweet'=>$id_tweet,
                            ':km_aprox'=>$km_aprox
                            );
                        //echo "<br><h1>km_aprox ".$km_aprox."</h1><br>";
			$salida = $stmt->execute($parametros);
				   
		  
		return $salida;	
		
	}
	
	
	/**
	 * 
	 * Agregar un usuario la BD
	 * @param $id_tweet,$id_str,$text,$created_at,$retweet_count,$favorite_count,$user_id
	 * @return lastInsertId()
	 *
	 */
	 public function agregarUsuarioTweet($user_id, $name, $screen_name, $id_str, $location, $followers_count, $description) {
		
			
			$sql = "INSERT INTO tr002_user (user_id,name,screen_name,id_str,location,followers_count,description) VALUES (:user_id,:name,:screen_name,:id_str,:location,:followers_count,:description)";					
			
			$con = $this->bd->obtenerConexion();						
			$stmt = $con->prepare($sql);					 
			$stmt->execute(array(':user_id'=>$user_id,':name'=>$name,':screen_name'=>$screen_name,':id_str'=>$id_str,':location'=>$location,':followers_count'=>$followers_count,':description'=>$description ));
				   
		return $lastId = $con->lastInsertId();
							
		
	}
	
	/**
	*
	* Validar si existe un usuario en la BD
	*
	*/
	public function validarExisteUsuario($user_id){
	
		$sql = $this->bd->crearSelect("tr002_user","user_id","user_id = $user_id","");
		$con = $this->bd->obtenerConexion();	
		$stmt = $con->query($sql);		
		
   
  		if ($stmt->fetchColumn() > 0) return true;
  		else return false;
  	
	
	}
	
	
	/**
	*
	* Validar si existe un tuit en la BD
	*
	*/
	public function validarExisteTweet($id_tweet){
	
		$sql = $this->bd->crearSelect("tr001_tweet","id_tweet","id_tweet = $id_tweet","");
		$con = $this->bd->obtenerConexion();	
		$stmt = $con->query($sql);	
   
  		if ($stmt->fetchColumn() > 0) return true;
  		else return false;
  	
	
	}
	
	/**
	*
	* Validar si existe un tuit de interes en la BD
	*
	*/
	public function validarExisteTweetInteres($id_tweet){
	
		$sql = $this->bd->crearSelect("tr002_tweet_interes","id_tweet","id_tweet = $id_tweet","");
		$con = $this->bd->obtenerConexion();	
		$stmt = $con->query($sql);	
	
  		if ($stmt->fetchColumn() > 0) return true;
  		else return false;
  	
	
	}
	
	
	   /**
    	* Total de tuits analizados
    	*
    	*/
    	public function total_tuits(){    		
    	
    		$con = $this->bd->obtenerConexion();	
    		//echo "SELECT count(id_tweet) FROM tr001_tweet WHERE tx_ambito = $tx_ambito";
    		//return $count = current($con->query("SELECT count(id_tweet) FROM tr001_tweet WHERE tx_ambito = '$tx_ambito'")->fetch());
    		return $count = current($con->query("SELECT count(id_tweet) FROM tr001_tweet")->fetch());
    	
    	}

    	/**
    	* Total de tuits analizados
    	*
    	*/
    	public function total_tuits_ambito($tx_ambito){    		
    	
    		$con = $this->bd->obtenerConexion();	
    		//echo "SELECT count(id_tweet) FROM tr001_tweet WHERE tx_ambito = $tx_ambito";
    		//return $count = current($con->query("SELECT count(id_tweet) FROM tr001_tweet WHERE tx_ambito = '$tx_ambito'")->fetch());
    		return $count = current($con->query("SELECT count(id_tweet) FROM tr001_tweet WHERE tx_ambito = '$tx_ambito'")->fetch());
    	
    	}


    	
    	/**
    	* Total de tuits interes clasificados analizados
    	*
    	*/
    	public function total_tuits_interes(){    		
    		$con = $this->bd->obtenerConexion();	
    		//return $count = current($con->query("SELECT count(id_tweet) FROM tr002_tweet_interes where lugar <> '0'")->fetch());
    		return $count = current($con->query("SELECT count(id_tweet) FROM tr002_tweet_interes")->fetch());
    	
    	}
    	
        /**
    	* Total de tuits interes clasificados analizados con ambito
    	*
    	*/
    	public function total_tuits_interes_ambito($ambito){    		
    		$con = $this->bd->obtenerConexion();	
    		//return $count = current($con->query("SELECT count(id_tweet) FROM tr002_tweet_interes where lugar <> '0'")->fetch());
    		return $count = current($con->query("SELECT count(id_tweet) FROM tr002_tweet_interes WHERE tx_ambito='$ambito'")->fetch());
    	
    	}
        
    	/**
    	* Total de tuits interes sin clasificados de localizacion
    	*
    	*/
    	public function total_tuits_interes_sin_localizacion(){    		
    		$con = $this->bd->obtenerConexion();	
    		return $count = current($con->query("SELECT count(id_tweet) FROM tr002_tweet_interes WHERE lugar = '0'")->fetch());
    	
    	}
    	
    	
    	
    	/**
    	* Total de usuarios analizados
    	*
    	*/
    	public function total_usuarios(){    		
	     	 $con = $this->bd->obtenerConexion();	
    		 return $count = current($con->query("SELECT count(id) FROM tr002_user")->fetch());
    	
    	}
    	
    	/**
    	* Obtener tuits de interés desde los últimos N minutos
    	* Usado por Agente Informador
    	*/
    	public function obtenerTuitsInteresRecientes($minutos=45){    		
	     	
	     	$minutos_atras = "- $minutos minute";                
                $fecha_actual = date("Y-m-d H:i:s");
                //echo "<br><br>fecha_actual: ".$fecha_actual;
	     	//$nuevafecha = strtotime ( '-35 minute' , strtotime ( $fecha_actual ) ) ;
                $nuevafecha = strtotime ( $minutos_atras , strtotime ( $fecha_actual ) ) ;
	     	
	     	$nuevafecha = date("Y-m-d H:i:s", $nuevafecha );
	     	//echo "<br><br> nuevafecha: ".$nuevafecha;
	     	//$nuevafecha = '2015-05-18 08:35:17';
	     	 
	     		     	
	     	$sql  = "SELECT id_tweet, text,lugar,clase_incidente FROM tr002_tweet_interes WHERE date >= :fecha AND lugar <> '0' AND reportado_correo = 0";   		
   		//echo 	$sql2 = "<br><br>SELECT id_tweet, text,lugar,clase_incidente FROM tr002_tweet_interes WHERE date >= '$nuevafecha' AND lugar <> '0' AND reportado_correo = 0 <br>";   	
   		//echo "<br>".date("Y-m-d H:i:s"); 
   		$parametros = array("fecha"=> $nuevafecha);   	
   		
   		
   		$fecha = date("Y-m-d H:i:s");	
 		$msg = "[TWITTER obtenerTuitsInteresRecientes()] | $fecha | $sql2 ";
	    	$this->log->general($msg);	
   		
   			
   		return $this->bd->listarRegistrosSeguro($sql,$parametros);
   		
    	
    	}
    	
    	/**
    	* Obtener tuits de interés desde los últimos N minutos
    	* Usado por Agente Informador
    	*/
    	public function obtenerTuitsInteresRecientesAreInteres($tx_ambito, $minutos=90){    		
	     	
	     	$minutos_atras = "- $minutos minute";                
                $fecha_actual = date("Y-m-d H:i:s");
                //echo "<br><br>fecha_actual: ".$fecha_actual;
	     	//$nuevafecha = strtotime ( '-35 minute' , strtotime ( $fecha_actual ) ) ;
                $nuevafecha = strtotime ( $minutos_atras , strtotime ( $fecha_actual ) ) ;
	     	
	     	$nuevafecha = date("Y-m-d H:i:s", $nuevafecha );
	     	//echo "<br><br> nuevafecha: ".$nuevafecha;
	     	//$nuevafecha = '2015-05-18 08:35:17';
	     	 
	     		     	
	     	$sql  = "SELECT id_tweet, text,lugar,clase_incidente FROM tr002_tweet_interes WHERE date >= :fecha AND lugar <> '0' AND reportado_correo = 0 AND tx_ambito = '$tx_ambito'";   		
   		 	$sql2 = "<br><br>SELECT id_tweet, text,lugar,clase_incidente FROM tr002_tweet_interes WHERE date >= '$nuevafecha' AND lugar <> '0' AND reportado_correo = 0 AND tx_ambito = '$tx_ambito' <br>";   	
   		//echo "<br>".date("Y-m-d H:i:s"); 
   		$parametros = array("fecha"=> $nuevafecha);   	
   		
   		
   		$fecha = date("Y-m-d H:i:s");	
 		$msg = "[TWITTER obtenerTuitsInteresRecientesAreInteres($tx_ambito)] | $fecha | $sql2 ";
	    $this->log->general($msg);	
   		
   			
   		return $this->bd->listarRegistrosSeguro($sql,$parametros);
   		
    	
    	}
    	
    	
    	
    	/**
    	* Obtener tuits de interés desde los últimos N minutos
    	* Usado por Agente Tuitero
    	*/
    	public function obtenerTuitsInteresRecientesTuitero($minutos=45){    		
	     	
	     	$minutos_atras = "- $minutos minute";                
                $fecha_actual = date("Y-m-d H:i:s");
                echo "<br><br>fecha_actual: ".$fecha_actual;
	     	//$nuevafecha = strtotime ( '-35 minute' , strtotime ( $fecha_actual ) ) ;
                $nuevafecha = strtotime ( $minutos_atras , strtotime ( $fecha_actual ) ) ;
	     	
	     	$nuevafecha = date("Y-m-d H:i:s", $nuevafecha );
	     	echo "<br><br> nuevafecha: ".$nuevafecha;
	     	//$nuevafecha = '2015-05-18 08:35:17';
	     	 
	     		     	
	     	$sql  = "SELECT id_tweet, text, lugar, clase_incidente, media_url, tx_ambito FROM tr002_tweet_interes WHERE date >= :fecha AND lugar <> '0' AND reportado_tuiter = 0";   		
   		echo 	$sql2 = "<br> SELECT id_tweet, text, lugar, clase_incidente, media_url, tx_ambito FROM tr002_tweet_interes WHERE date >= '$nuevafecha' AND lugar <> '0' AND reportado_tuiter = 0 <br>"; 
   		//echo "<br>".date("Y-m-d H:i:s"); 
   		$parametros = array("fecha"=> $nuevafecha);   		
   		
   		
   		$fecha = date("Y-m-d H:i:s");	
 		$msg = "[TWITTER obtenerTuitsInteresRecientesTuitero()] | $fecha | $sql2 ";
	    	$this->log->general($msg);	
	    	
	    	
   		return $this->bd->listarRegistrosSeguro($sql,$parametros);
    	
    	}
    	
    	
    	
    	/**
 	*
 	* Obtener ultimos 30 tuits de interes
 	*/
 	
 	public function obtenerUltimos30TuitsInteres(){
 	
 	       // $total_tuits = $this->total_tuits_interes();
 	        //$total_tuits = $con->query("SELECT count(name) FROM v005_tuits_interes_usuario")->fetch();
 	        //echo "sdfsdfs <br> <br>".$total_tuits;
 	        $sql_pre  = "SELECT * FROM v005_tuits_interes_usuario";
 	        $tweet_pre =  $this->bd->listarRegistros($sql_pre);
 	        $total_tuits = count($tweet_pre);
		$inicio = $total_tuits - 30;
		$sql  = "SELECT * FROM v005_tuits_interes_usuario ORDER BY date ASC limit $inicio, 30";
		//echo $sql;
   		$tweet_bd =  $this->bd->listarRegistros($sql);
   		return $tweet_bd;
 	
 	}
 	
    	
    	
    	
    	
        
        
        /**
    	 * Permite conocer si un usuario me sigue
    	 * @param $usuario (screen_name)
    	 */
        public function meSigue($usuario){
            $cadena = $this->conocerRelacionUsuario($usuario);
            $pos= strpos($cadena,'following');
            if ($pos === false) {
                return false;
            } else {
                return true;
            }
            
        }

	/**
	 * 
	 * Actualiza si un tuits de interes ya fue notificado por correo electronico
	 * @param $id_tweet         
	 * @return 
	 *
	 */
	 public function modificarReportadoTweetInteresCorreo($id_tweet) {
		
					
			$sql = "UPDATE tr002_tweet_interes SET reportado_correo =:reportado_correo WHERE id_tweet = :id_tweet";	
			echo "<br><br> UPDATE tr002_tweet_interes SET reportado_correo = 1 WHERE id_tweet = $id_tweet";	
			
			$con = $this->bd->obtenerConexion();						
			$stmt = $con->prepare($sql);	
			$reportado = 1;	
						 
			$salida = $stmt->execute(array(':reportado_correo'=>$reportado,':id_tweet'=>$id_tweet));	
			
			echo "\nPDOStatement::errorInfo():\n";
				   $arr = $stmt->errorInfo();
				   print_r($arr);				   
		  
		return $salida;	
		
	}
	
	/**
	 * 
	 * Actualiza si un tuits de interes ya fue notificado por correo electronico
	 * @param $id_tweet         
	 * @return 
	 *
	 */
	 public function modificarReportadoTweetInteresTuiter($id_tweet) {
		
					
			$sql = "UPDATE tr002_tweet_interes SET reportado_tuiter = :reportado_tuiter WHERE id_tweet = :id_tweet";	
			echo "<br><br> UPDATE tr002_tweet_interes SET reportado_tuiter = 1 WHERE id_tweet = $id_tweet <br> ";
			
			$con = $this->bd->obtenerConexion();						
			$stmt = $con->prepare($sql);	
			$reportado = 1;	
						 
			$salida = $stmt->execute(array(':reportado_tuiter'=>$reportado,':id_tweet'=>$id_tweet));	
			
			echo "\nPDOStatement::errorInfo():\n";
				   $arr = $stmt->errorInfo();
				   print_r($arr);			   
		  
		return $salida;	
		
	}
       
        /**
	 * 
	 * Actualiza la información de una cuenta de Twitter
	 * @param nb_usuario
         * @param nb_campo
         * @param valor_campo
	 * @return 
	 *
	 */
	 public function actualizarSeguidor($nb_usuario, $nb_campo, $valor_campo) {
            
            $sql = "UPDATE tr002_user SET ".$nb_campo. " =:valor_campo WHERE screen_name = :nb_usuario";	
            $con = $this->bd->obtenerConexion();						
            $stmt = $con->prepare($sql); 
            $parametros = array(':nb_usuario'=>$nb_usuario,':valor_campo'=>$valor_campo);          
            //print_r($parametros);
            
            $result= $stmt->execute($parametros);            
            return $result;							
		
	}
        
        /**
         * Luego de invitar a un usuario se guarda en la BD
         * @param type $id_usuario         * 
         * 
         */
        public function fueInvitado($id_usuario,$invitaciones){
            $this->actualizarSeguidor($id_usuario, 'fue_invitado',$invitaciones+1);
            
        }
    	
        
        
        /**
         * Busca usuarios en BD según un criterio de búsqueda
         * @param arreglo de criterios
         * @param operador AND, OR
         */
        public function buscarUsuarios($criterio, $operador){
            
            //print_r($criterio);
            $where = '';
            $tam = sizeof($criterio);
            $i=0;
            foreach($criterio as $clave=>$valor){
	
		//$param[":$clave"] = $valor;
                if ($i <$tam-1){
                    $where = $where." ".$clave.'='.$valor." ".$operador;
                }else{
                    $where = $where." ".$clave.'='.$valor;
                }
                $i++;
            }            
            
            $sql = $this->bd->crearSelect("tr002_user","*",$where,"");
            //print_r($sql);
            $stmt = $this->bd->listarRegistros($sql);
            return $stmt;
        }
        
        
        /*
         * Indica si el tweet cumple con el tamaño máximo establecido por Twitter (136)
         * @param tweet
         * @return true o false
         */
        public function validarTamMaxTweet($tweet){
            $tam = strlen($tweet);
            if ($tam <= 136){
                //echo ' Tamaño del tweet '.$tam;
                return true;
            }
            else{
                echo ' Excede el tamaño máximo del tweet '.$tam;
                return false;
            }
        }
    	
    	    	
    	
	
	
	
}//fin clase


?>
