<?php
ob_start(); 

include_once("class.ControlWeb.php");
$web = new ControlWeb();
	
if (isset($_GET['ac']) && $_GET['ac']=='nuevo' ){


	//print_r($_POST['preferencias']);

	$nombreCompleto 	= filter_var($_POST['nombreCompleto'], FILTER_SANITIZE_STRING); 
	$correoCompleto 	= filter_var($_POST['correoCompleto'], FILTER_SANITIZE_EMAIL); 	
	$asuntoCompleto 	= filter_var($_POST['asuntoCompleto'], FILTER_SANITIZE_STRING); 
	$mensajeCompleto 	= filter_var($_POST['mensajeCompleto'], FILTER_SANITIZE_STRING); 
	
	if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
          echo "<font color='red'>Por favor introduzca la información de validación del formulario ¿Es usted un robot?.</font>";
          exit;
        }
        
             
	
  	$secretKey = "6Lce1iETAAAAAK3jJuSUyp3UY5W6pPQKSqaS4aqe";
	$ip = $_SERVER['REMOTE_ADDR'];
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
	$response = json_decode($response,true);
	
	/*$response["success"] = true;
        $nombreCompleto 	= 'JJ';
	$correoCompleto 	= 'pedroperez@gmail.com';
	$asuntoCompleto 	= 'Prueba 1';
	$mensajeCompleto 	=  'mensaje de prueba dasdasdasafopjsdf f fsfsdp fsd fsd9f9f9sd f9sd fs';*/
	                      
        if ($response["success"] == TRUE) {		
		
		if (filter_var($correoCompleto, FILTER_VALIDATE_EMAIL)) {
						

			if($web->enviarMensajeContactenos($nombreCompleto,$correoCompleto,$asuntoCompleto,$mensajeCompleto)){	
				echo "<font color='blue'>Gracias por comunicarse con el <b>OVI</b>, recibiremos su mensaje y le responderemos tan pronto podamos.</font>";
			}else{
				echo "<font color='red'><b>OVI<b/>No se ha podido enviar su mensaje, por favor intentelo de nuevo.</font>";
			}

		}else{

			echo "<font color='red'>El formato del correo electrónico no es válido, por favor corrijalo y vuelva a intentarlo.</font>";
		}
	
	
	}else{
		
		echo "<font color='red'>Por favor intentelo más tarde, cuando usted no sea un robot.</font>";
	}
	

}// fin nuevo suscriptor




?>