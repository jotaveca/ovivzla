<?php
include_once("class.Suscriptor.php");
header('Access-Control-Allow-Origin: *');
$suscriptor = new Suscriptor();


// POST al insertar
if(isset($_POST['tx_nombre_apellido']) ){
	
	$tx_nombre_apellido 	= filter_var($_POST['tx_nombre_apellido'], FILTER_SANITIZE_STRING); 
	$tx_correo_electronico 	= filter_var($_POST['tx_correo_electronico'], FILTER_SANITIZE_STRING); 
	$tx_preferencia 		= $_POST['tx_preferencia']; 
	$tx_preferencia_hora 	= filter_var($_POST['tx_preferencia_hora'], FILTER_SANITIZE_STRING);	
	$tx_telefono 			= filter_var($_POST['tx_telefono'], FILTER_SANITIZE_STRING); 

	//var_dump($tx_preferencia);

	$suscriptor->__set("nombreApellido",$tx_nombre_apellido);
    $suscriptor->__set("correoElectronico",$tx_correo_electronico);
	$suscriptor->__set("preferencias",$tx_preferencia );
	$suscriptor->__set("preferenciasHora",$tx_preferencia_hora );
    $suscriptor->__set("telefono",$tx_telefono);
	$suscriptor->__set("activo",1);

	if ($suscriptor->agregarSuscriptor()){

		$respuesta = array("valido"=>1);

	}else{
		$respuesta = array("valido"=>0);
	}
	
	echo json_encode($respuesta);

}

// POST al editar
if(isset($_POST['activo_e']) ){
	
	$tx_nombre_apellido 	= filter_var($_POST['tx_nombre_apellido_e'], FILTER_SANITIZE_STRING); 
	$tx_correo_electronico 	= filter_var($_POST['tx_correo_electronico_e'], FILTER_SANITIZE_STRING); 
	$tx_preferencia 		= $_POST['tx_preferencia_e']; 
	$id_suscriptor 			= $_POST['h-id-suscriptor_e']; 
	$tx_preferencia_hora 	= filter_var($_POST['tx_preferencia_hora_e'], FILTER_SANITIZE_STRING);	
	$tx_telefono 			= filter_var($_POST['tx_telefono_e'], FILTER_SANITIZE_STRING); 
	$activo 				= filter_var($_POST['activo_e'], FILTER_SANITIZE_STRING); 
	

	//var_dump($tx_preferencia);

	$suscriptor->__set("nombreApellido",$tx_nombre_apellido);
    $suscriptor->__set("correoElectronico",$tx_correo_electronico);
	$suscriptor->__set("preferencias",$tx_preferencia );
	$suscriptor->__set("preferenciasHora",$tx_preferencia_hora );
    $suscriptor->__set("telefono",$tx_telefono);
	$suscriptor->__set("activo",$activo);

	if ($suscriptor->modificarSuscriptor($id_suscriptor)){

		$respuesta = array("valido"=>1);

	}else{
		$respuesta = array("valido"=>0);
	}
	
	echo json_encode($respuesta);

}

// GET al listar
if(isset($_GET['a']) && $_GET['a'] == 'buscar' ){

	$id_suscriptor = $_GET['id_suscriptor'];
	$listaSuscriptor = array();

	$listaSuscriptor = $suscriptor->obtenerSuscriptoresXID($id_suscriptor);

	if (count($listaSuscriptor) > 0){
		//print_r($listaSuscriptor);
		
		echo json_encode($listaSuscriptor);
		//echo json_last_error_msg();
		//echo json_encode((object)$listaSuscriptor);
	}else{

		echo "VACIO";
	}

}


// GET al listar
if(isset($_GET['a']) && $_GET['a'] == 'listar' ){

	
	$listaSuscriptor = array();

	$listaSuscriptor = $suscriptor->obtenerSuscriptores();

	if (count($listaSuscriptor) > 0){
		//print_r($listaSuscriptor);
		
		echo json_encode($listaSuscriptor);
		//echo json_last_error_msg();
		//echo json_encode((object)$listaSuscriptor);
	}else{

		echo "VACIO";
	}

}

// GET al eliminar
if(isset($_GET['a']) && $_GET['a'] == 'eliminar' ){
	
	$id_suscriptor = $_POST['h-id-suscriptor'];
	$salida = $suscriptor->eliminarSuscriptor($id_suscriptor);
	//echo $id_lugar;
	

	if($salida){

		$respuesta = array("valido"=>1,"codigo"=>$id_suscriptor);

	}else{
		$respuesta = array("valido"=>0,"codigo"=>$id_suscriptor);
	}
	//$respuesta = array("valido"=>1,"codigo"=>$id_lugar);

	echo json_encode($respuesta);
		
	

}




?>
