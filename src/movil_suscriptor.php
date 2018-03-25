<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("class.Suscriptor.php");
header('Access-Control-Allow-Origin: *');
$suscriptor = new Suscriptor();

//var_dump($_GET);
// GET al listar por criterio
if(isset($_GET['correo']) && $_GET['param'] == 'l'){

	$correo = $_GET['correo'];
	//var_dump($ambito);        
        $listaSuscriptor = array();
        //echo "buscar<br>";
	$listaSuscriptor = $suscriptor->obtenerSuscriptoresXCorreo($correo);
	if ($listaSuscriptor != false ){
		//print_r($listaSuscriptor);
		
		echo json_encode($listaSuscriptor);
		//echo json_last_error_msg();
		//echo json_encode((object)$listaSuscriptor);
	}else{

		echo "VACIO";
	}

}

if(isset($_GET['correo']) && $_GET['param'] == 'p'){

	$correo = $_GET['correo'];
	//var_dump($ambito);        
        $listaSuscriptor = array();
        //echo "buscar<br>";
	$listaSuscriptor = $suscriptor->obtenerSuscriptoresXCorreo($correo);
	if ($listaSuscriptor != false ){
		//print_r($listaSuscriptor);
		$preferencias = array();
		
		for($i=0;$i<count($listaSuscriptor);$i++) {
			//echo "$key => $value";
			//echo $listaSuscriptor[$i]['tx_preferencia'];
			$preferencias[]["hora"] = $listaSuscriptor[$i]['tx_preferencia_hora'];
			$preferencias[]["lugar"] = $listaSuscriptor[$i]['tx_preferencia'];

		}
		


		echo json_encode($preferencias);
		//echo json_last_error_msg();
		//echo json_encode((object)$listaSuscriptor);
	}else{

		echo "VACIO";
	}

}

?>

