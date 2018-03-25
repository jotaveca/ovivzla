<?php
include_once("class.PalabraInteres.php");
$palabras = new PalabraInteres();
header('Access-Control-Allow-Origin: *');



// GET al listar
if(isset($_GET['a']) && $_GET['a'] == 'listar' ){

	
	$listaPalabras = $palabras->obtenerPalabrasInteresTodo();
	//var_dump($listaLugares);

	if (count($listaPalabras) > 0){
		//print_r($listaVias);
		echo json_encode($listaPalabras);
	}else{

		echo "VACIO";
	}

}






?>