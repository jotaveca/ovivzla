<?php
header('Access-Control-Allow-Origin: *');

//var_dump($_POST);
$password 	= filter_var($_POST['password'], FILTER_SANITIZE_STRING); 
$email 	= filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 

//echo  "$email ---- ";

if ($email == '' ) {	
	$respuesta = array("valido"=>1);
	echo json_encode($respuesta);
}else{
	$respuesta = array("valido"=>0);
	echo json_encode($respuesta);
}

?>