<?php
die();
header('Content-Type: text/html; charset=UTF-8');
ini_set('display_errors', 1);
require_once('twitter-api-php-master/TwitterAPIExchange.php');
require_once('class.BD.php');
require_once('palabras_interes.php');
 
//Se establecen las credenciales para twitter
$settings = array(
 'oauth_access_token' => '2780514284-jYTNqUUkr1MTNyuv6TKvGwvCE09P3EYgZvDK7qL',
 'oauth_access_token_secret' => 'AxsbksSUaXvIdVN0tzMOphqvr0SI1I9ngIwnSf0cAkakv',
 'consumer_key' => 'oZFZwkNqD2MSRWnV6bdv1uGic',
 'consumer_secret' => 'jPten4itBuvzv2ewHSZWh7yTR2VXSuOXErEfNAHvHY3X1vnRzD');
 
//Url de busqueda de Twitter
$url = "https://api.twitter.com/1.1/search/tweets.json";
//$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$requestMethod = 'GET';
 
//Filtros sobre la busqueda a realizar (nombre del hashtag, número de tweet por consulta, tipo de resultado, etc)
//$getfield = '?q=#goodmorning&count=40&result_type=popular';
//Buscar por  etiqueta
$getfield = '?q=#PNM&count=150&lang=es&result_type=recent';
//Buscar por usuario
//$getfield = '?screen_name=jornastec';
 
$twitter = new TwitterAPIExchange($settings);
$json = $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
$result = json_decode($json,TRUE);

//var_dump($result);
$bd = new BD();


echo "<h1>Analisis de Tuit</h1><br>";
 
foreach($result['statuses'] as $tweet)
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
 
 if (isset($tweet['retweeted_status'])){
   	$retweeted_status = $tweet['retweeted_status']; 
    		//echo "<b/>Mensaje retuiteado</b>";    	
 	
 }else{
    	//echo "<br /><br />";
    	//echo "<b/>Mensaje original de $screen_name </b>";
    	if($bd->validarExisteUsuario($user_id)==false){ 
	    	echo "<br/><br />";
    		echo "<b/>UN: Usuario nuevo $screen_name </b>";   	
    		$bd->agregarUsuarioTweet($user_id, $name, $screen_name, $id_str_user, $location, $followers_count, $description);    		
    	}else{
    		echo "<br/><br />";
    		echo "UR: Usuario repetido $screen_name "; 
    	}
    	if($bd->validarExisteTweet($idTweet)==false){ 
    	        //echo "<br/>Fecha: $date <br />";
    	        echo "<br /><br />";
    		echo "<b/>TN: Tweet nuevo $text</b>";   	
    		$bd->agregarTweet($idTweet,$idTweet,$text,$created_at,$date,$retweet_count, $favorite_count, $user_id); 
    		
    		//Saber si existen un tweet con las palabras de interes
    		$encontrado = extraerDatosTweet($text, $palabras_interes);
    		
    		if($encontrado <= 2){
    		
    			if($bd->validarExisteTweetInteres($idTweet)==false){ 
				echo "<br /> TIN: De Interes: Busqueda muy parecida L: $encontrado <br />";
				$bd->agregarTweetInteres($idTweet,$idTweet,$text,$created_at,$date,$retweet_count, $favorite_count, $user_id,0,0,0); 
			 }
			 
			 //Extrar la ubicacion geografica que reporta el tweet
			 $lugar = extraerUbicacionTweet($text,$lugares_interes);
	
			if(is_array($lugar)){
				echo "<br /> TGeo añadido <br />";
				$bd->modificarGeoTweetInteres($idTweet,$lugar["lat"],$lugar["lon"],$lugar["etiqueta"]);
					
			}
		
		}
    		
    		
    		 		
    	}else{
    		echo "<br/><br />";
    		echo "TR: Tuit repetido $text "; 
    	}
    	
    
    
 	
 }
 
  }// fin for
 

 
 
 function extraerDatosTweet($tweet, $palabras_interes){
  
  $i = 0;
  $texto = explode(" ",$tweet);
  $salida = 999;
  $tam_texto = count($texto); 
  while ($i < $tam_texto){
    $dif_palabra = 0;    
    foreach ($palabras_interes as $palabra) {   
         $dif_palabra = levenshtein($texto[$i], $palabra);
         
         if ( $dif_palabra <= 2){        
		$salida = $dif_palabra;					
	 }
	
     }
     $i++;
   }
   
   return $salida;  
 }
 
 function extraerUbicacionTweet($tweet, $lugares_interes){  
  
  
  $encontrado = false;
  foreach ($lugares_interes as $clave=>$valor) {
    
     //echo "<br>"."id= ".$tweet."palabra= ".$clave;
    if (strripos($tweet, $clave) !== false) {
        //echo "<br>";
        $encontrado = $valor;       
        //break;      
    }
  }
  
  return $encontrado; 
  

}
 
  //var_dump($retweeted);
 /*
 echo "<br /><br />";
 echo "name = $name <br />";
 echo "screen_name = $screen_name <br />";
 echo "location = $location <br />";
 echo "description = $description <br />";
 echo "user id = $user_id <br />";
 echo "user id_str_user = $id_str_user <br />";
 echo "user image profile = $image <br />";
 echo "text = $text <br />";
 echo "followers_count = $followers_count <br />";
 echo "retweet_count = $retweet_count <br />";
 echo "retweeted = $retweeted <br />";
 //echo "retweeted_status =  <br />";
 //var_dump($retweeted_status);
 echo "<br />";
 echo "favorite_count = $favorite_count <br />";
 echo "id_str tweet = $idTweet <br />";
 echo "created_at = $date<br />";
 //echo "<b/>Registro agregado</b>";
 echo "<br /><br />";*/
 

?>
