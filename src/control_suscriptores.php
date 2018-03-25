<?php
ob_start(); 

include_once("class.ControlWeb.php");
$web = new ControlWeb();
	
if (isset($_GET['ac']) && $_GET['ac']=='nuevo' ){


	//print_r($_POST['preferencias']);

	$nombreApellido 	= filter_var($_POST['nombreApellido'], FILTER_SANITIZE_STRING); 
	$sexo 			= filter_var($_POST['sexo'], FILTER_SANITIZE_STRING); 
	$fecha_nac 		= filter_var($_POST['datepicker'], FILTER_SANITIZE_STRING); 
	$correoElectronico 	= filter_var($_POST['correoElectronico'], FILTER_SANITIZE_EMAIL); 
	$preferencias 		= $_POST['preferencias']; 
	$preferenciasHora 	= filter_var($_POST['preferenciasHora'], FILTER_SANITIZE_STRING); 
	$telefono 		= filter_var($_POST['telefono'], FILTER_SANITIZE_STRING); 
	//$eresRobot 	= filter_var($_POST['eresRobot'], FILTER_SANITIZE_STRING);

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
	
	//var_dump($response);	
       /* if(intval($response["success"]) !== 1) {
          echo '<h2>You are spammer ! Get the @$%K out</h2>';
        } else {
          echo '<h2>Thanks for posting comment.</h2>';
        }*/
	                      
                                
	//if ($eresRobot == 'no'){	
	if ($response["success"] == TRUE) {		
		
		if (filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {

			
			

			if($web->registrarSuscriptor($nombreApellido,$correoElectronico,$preferencias,$preferenciasHora,$telefono,$fecha_nac,$sexo) > 0){	
				echo "<font color='blue'>Gracias por suscribirse a <b>OVI</b>, de ahora en adelante empezará a recibir información vial de su interés en su correo electrónico.</font>";
			}else{
				echo "<font color='red'><b>OVI<b/> no ha podido completar su suscripción, parece ser que usted ya esta suscrito al servicio.</font>";
			}

		}else{

			echo "<font color='red'>El formato del correo electrónico no es válido, por favor corrijalo y vuelva a intentarlo.</font>";
		}
	
	
	}else{
	
		//$_SESSION["error-captcha"] =  $resp->error;
		echo "<font color='red'>Por favor intentelo más tarde, cuando usted no sea un robot.</font>";
	}
	
	
	

}// fin nuevo suscriptor


// Dar a alta a suscriptor
if (isset($_GET['ac']) && $_GET['ac']=='deshabilitar' ){

	if (isset($_GET['ce'])) $ce = $_GET['ce'];
	$correoElectronico = filter_var($ce, FILTER_SANITIZE_EMAIL); 
	
	if (filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
	
		$resultado = $web->deshabilitarSuscriptor($correoElectronico);
		ob_end_clean(); 
		header("Location: ../web/tpl/deshabilitar.php?ce=$correoElectronico");
		//http_redirect("../web/tpl/deshabilitar.php?ce=$correoElectronico");
	}
	
	
	
		//var_dump($resultado);

}

?>