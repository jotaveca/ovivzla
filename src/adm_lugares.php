<?php
include_once("class.Lugar.php");
$lugar = new Lugar();
header('Access-Control-Allow-Origin: *');

$tx_nb_lugar 			= filter_var($_POST['tx_nb_lugar'], FILTER_SANITIZE_STRING); 


// POST al insertar
if(isset($_POST['tx_nb_lugar']) ){
	
	$cod_nb_corto_lugar 	= filter_var($_POST['cod_nb_corto_lugar'], FILTER_SANITIZE_STRING); 
	$cod_lugar_padre 		= filter_var($_POST['cod_lugar_padre'], FILTER_SANITIZE_STRING); 
	$tx_lat 				= filter_var($_POST['tx_lat'], FILTER_SANITIZE_STRING); 
	$tx_long 				= filter_var($_POST['tx_long'], FILTER_SANITIZE_STRING); 
	$tx_img 				= filter_var($_POST['tx_img'], FILTER_SANITIZE_STRING); 
	$esAlias 				= filter_var($_POST['esAlias'], FILTER_SANITIZE_STRING); 
	$esHoja 				= filter_var($_POST['esHoja'], FILTER_SANITIZE_STRING); 
	$tx_nb_siguiente 		= filter_var($_POST['tx_nb_siguiente'], FILTER_SANITIZE_STRING); 
	$tx_nb_anterior 		= filter_var($_POST['tx_nb_anterior'], FILTER_SANITIZE_STRING); 
	$tx_km_rango 			= filter_var($_POST['tx_km_rango'], FILTER_SANITIZE_STRING); 
	$tx_km_aprox 			= filter_var($_POST['tx_km_aprox'], FILTER_SANITIZE_STRING); 
	$in_nivel 				= filter_var($_POST['in_nivel'], FILTER_SANITIZE_STRING); 

	if ($lugar->agregarLugarInteres($tx_nb_lugar,$cod_nb_corto_lugar,$cod_lugar_padre,$tx_lat,$tx_long, $tx_img,$esAlias,$esHoja,$tx_nb_siguiente,$tx_nb_anterior,$tx_km_rango,$tx_km_aprox,$in_nivel)){

		$respuesta = array("valido"=>1);

	}else{
		$respuesta = array("valido"=>0);
	}
	
	echo json_encode($respuesta);

}


// GET al listar
if(isset($_GET['a']) && $_GET['a'] == 'listar' ){

	
	$listaLugares = $lugar->obtenerLugaresInteres();
	//var_dump($listaLugares);

	if (count($listaLugares) > 0){
		//print_r($listaVias);
		echo json_encode($listaLugares);
	}else{

		echo "VACIO";
	}

}

// GET al listar
if(isset($_GET['a']) && $_GET['a'] == 'eliminar' ){
	
	$id_lugar = $_POST['h-id-lugar'];
	$salida = $lugar->eliminarLugarInteres($id_lugar);
	//echo $id_lugar;
	

	if($salida){

		$respuesta = array("valido"=>1,"codigo"=>$id_lugar);

	}else{
		$respuesta = array("valido"=>0,"codigo"=>$id_lugar);
	}
	//$respuesta = array("valido"=>1,"codigo"=>$id_lugar);

	echo json_encode($respuesta);
		
	

}




?>