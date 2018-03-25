<?php
include_once("class.Vias.php");
header('Access-Control-Allow-Origin: *');


$vias = new Vias();
$listaVias = $vias->obtenerVias();

if (count($listaVias) > 0){
	//print_r($listaVias);
	echo json_encode($listaVias);
}else{

	echo "VACIO";
}

?>