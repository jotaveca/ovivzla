<?php
/*
 * Validación de la seguridad
*/
/*
if(is_null($argv)){ // ejecucion desde la web
                if(!isset($_GET['hash']) || $_GET['hash']!='$1$Cccp8eeR$oIvQBOlR9pPTQanb2FtZY1'){	
                        //echo $hashed_password = crypt('Simón Bolívar y Nikola Tesla');		
                        die("Accion no autorizada");
                }

}else{ //desde cli - cgi-fcgi

                $hash = $argv[1];	
                if($hash!='$1$Cccp8eeR$oIvQBOlR9pPTQanb2FtZY1'){	

                        die("Accion no autorizada");
               }
}	
*/

//header('Content-Type: text/html; charset=UTF-8');
//ini_set('display_errors', 1);

require_once('../src/class.AgenteExtractor.php');

$bd = new BD();
$clase = 'AgenteExtractor';
$agt = new $clase();
$agt->ini_bdi();
echo "<br>".$nombre_clase = $agt->belief['nombre_agente'];
$metas = $agt->desire;
$plan_accion = $agt->intention;

foreach($metas as $meta){
	echo "<br>".$meta;
	$plan = $plan_accion[$meta]['plan_accion'];
	foreach($plan as $accion){
		echo "<br> => ".$accion;
		$agt->{$accion}();
	}
}


//print_r($agenteExtractor->intention);
//#PNM Autopista Panamericana
//$agenteExtractor->setAmbitoBusqueda("#PNM");
//$agenteExtractor->establecerParametrosExtraccion('?q=#PNM&count=100&lang=es&result_type=recent&include_entities=true');
//$tuits_interes = $agenteExtractor->extraerTuitsAPI();
//$ids_tweets = array(); // se usa para registrar los tuits nuevos para la memoria del agente   
//echo "<br><h1>Tuits de interes registrados (".count($tuits_interes)." )</h1><br>";


//#AGMA Autopista Gran Mariscal de Ayacucho
//$agenteExtractor->setAmbitoBusqueda("#AGMA");
//$agenteExtractor->establecerParametrosExtraccion('?q=#AGMA&count=100&lang=es&result_type=recent&include_entities=true');
//$tuits_interes = $agenteExtractor->guardarTuitsAPITemp();
//$ids_tweets = array(); // se usa para registrar los tuits nuevos para la memoria del agente   
//echo "<br><h1>Tuits de interes registrados (".count($tuits_interes)." )</h1><br>";


 
// fin del proceso se registra en memoria todas las acciones realizadas
//$agenteExtractor->registrarEnMemoria($ids_tweets);



?>