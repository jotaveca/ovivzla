<?php
die();
session_start();
//echo session_start();

require_once('../src/class.BD.php');
require_once('../src/class.Twitter.php');
require_once('../src/class.AgenteExtractor.php');

ini_set('max_execution_time', 600); // 10 minutos
//$hora_depuracion =  date("Y-m-d H:i:s");// hora del servidor
//echo "<br><h1> Hora del servidor $hora_depuracion </h1>";
//echo "<br><h1>Transferencia masiva hacia Tuits de Interés</h1><br>";
$bd = new BD();
$twitter = new Twitter();


//
//$sql = $bd->crearSelect("tr001_tweet","*","","");
//$sql = $bd->crearSelect("tr002_tweet_interes","text,id_tweet,created_at,date,retweet_count,favorite_count,user_id","","");
//$sql = $bd->crearSelect("tr002_tweet_interes","*","","");
//$sql = "SELECT * FROM tr001_tweet ORDER BY date ASC limit 1,5000";

$sql = "";

if ( !isset($_SESSION['iterar'])){
    $sql = "SELECT * FROM tr001_tweet ORDER BY date ASC limit 1,40000";
    $_SESSION['iterar'] = 1;
    $_SESSION['total'] = 0;
}else{
    if ( $_SESSION['iterar'] == 1){
        $sql = "SELECT * FROM tr001_tweet ORDER BY date ASC limit 40000,40000";
        $_SESSION['iterar'] = 2;
    }
    else{
        if ( $_SESSION['iterar'] == 2){
            $sql = "SELECT * FROM tr001_tweet ORDER BY date ASC limit 80000,40000";
            $_SESSION['iterar'] = 3;
        }
        else{
            if ( $_SESSION['iterar'] == 3){
                $sql = "SELECT * FROM tr001_tweet ORDER BY date ASC limit 120000,40000";
                $_SESSION['iterar'] = 4;
            }
        }
    }
}

//echo $sql;
$tweet = $bd->listarRegistros($sql);

//var_dump($tweet);
$ids_tweets = array();
//echo "<br><h2>Cantidad tuits a analizar en iteración ".$_SESSION['iterar'].":".count($tweet)."</h2><br>";
//die();

$i = 1;
$lugar = false;
$agenteExtractor = new AgenteExtractor();
foreach($tweet as $fila)
{
       $agenteExtractor->setTweet($fila["text"]);
       //$encontrado = $agenteExtractor->extraerDatosTweet();
       $encontrado = $agenteExtractor->extraerTipoIncidente();
       
       //var_dump($encontrado);
       
        $dif_palabra    = $encontrado["dif_palabra"];
       	$incidente      = $encontrado["incidente"];
       	$clase_incidente= $encontrado["clase_incidente"];
       
       if($dif_palabra <= 2){ // similitud de palabras de interes con contenido de tweet	
            $_SESSION['total'] = $_SESSION['total'] +1;
            //echo  "<br>Analizando tuit: ".$i."<br>";           

            if($twitter->validarExisteTweetInteres($fila["id_tweet"])==false){ // prueba para verificar que el tweet no está repetido
                    array_push($ids_tweets, $fila["id_tweet"]);
                    $media_url = "sin_url";
                        
                    $twitter->agregarTweetInteres($fila["id_tweet"],
                            $fila["id_tweet"],
                            $fila["text"],
                            $fila["created_at"],
                            $fila["date"],
                            $media_url,
                            $fila["retweet_count"],
                            $fila["favorite_count"],
                            $fila["user_id"],
                            0,
                            0,
                            0,
                            $incidente,
                            "",
                            $clase_incidente,
                            ""); 
            }	
		
		
	$lugar = $agenteExtractor->extraerUbicacionTweet();
	//var_dump($lugar);
	if(is_array($lugar)){
                        //echo "<br><h1>km_aprox ".$lugar["km_aprox"]."</h1><br>";
            $twitter->modificarGeoTweetInteres($fila["id_tweet"],
                    $lugar["lat"],
                    $lugar["lon"],
                    $lugar["etiqueta"],                                
                    $lugar["clase_lugar"],
                    $lugar["km_aprox"]);				
	}
	
	$i++;
     }
}

//echo "<br>Terminó iteración :". $_SESSION['iterar'];

//echo "<br>Cantidad de tuits procesados en la iteración :". count($ids_tweets);
/*if ($cantidad > 0){
	$ids_tweets = implode(",", $ids_tweets);
	echo $agenteExtractor->registrarEnMemoria($ids_tweets,$cantidad,"nuevo");
}
*/

if ( $_SESSION['iterar'] != 4){
   header('Location:transfTweet.php');
   
}
else
{
    echo "<br>Terminó la corrida :". $_SESSION['iterar'];    
    echo "<br>Registros transferidos :". $_SESSION['total'];    
    session_destroy();
    
}

?>
