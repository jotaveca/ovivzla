<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("class.Incidente.php");
header('Access-Control-Allow-Origin: *');
$incidente = new Incidente();

//var_dump($_GET);
// GET al listar por criterio
if(isset($_GET['a']) && $_GET['a'] == 'buscar' ){

	$ambito = "#".$_GET['ambito'];
	//var_dump($ambito);        
        $listaIncidentes = array();
        //echo "buscar<br>";
	$listaIncidentes = $incidente->obtenerTuitsInteresRecientesAreInteres($ambito);
	if (count($listaIncidentes) > 0){
		//print_r($listaSuscriptor);
		
		echo json_encode($listaIncidentes);
		//echo json_last_error_msg();
		//echo json_encode((object)$listaSuscriptor);
	}else{

		echo "VACIO";
	}

}


