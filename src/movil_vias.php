<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("class.Vias.php");
header('Access-Control-Allow-Origin: *');
$vias = new Vias();

//var_dump($_GET);
// GET al listar por criterio

	//var_dump($ambito);        
        $listaVias = array();
        //echo "buscar<br>";
	$listaVias = $vias->obtenerVias();
	if ($listaVias != false ){
		//print_r($listaSuscriptor);
		
		echo json_encode($listaVias);
		//echo json_last_error_msg();
		//echo json_encode((object)$listaSuscriptor);
	}else{

		echo "VACIO";
	}



?>
