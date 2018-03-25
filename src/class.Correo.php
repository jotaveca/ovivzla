<?php
//include_once ('../lib/PHPMailer-master/PHPMailerAutoload.php');
Class Correo{

	private $correo;
	
	function __construct() {
		$this->correo = new PHPMailer;
		//Tell PHPMailer to use SMTP
		$this->correo->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$this->correo->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$this->correo->Debugoutput = 'html';
		//Set the hostname of the mail server
		$this->correo->Host = "localhost";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$this->correo->Port = 25;
		//Whether to use SMTP authentication
		$this->correo->SMTPAuth = false;
		$this->correo->setFrom('xyz@ovi.org.ve', 'Observatorio Vial Inteligente (OVI)');
		//Set an alternative reply-to address
		$this->correo->addReplyTo('xyz@ovi.org.ve', 'Observatorio Vial Inteligente (OVI)');
		     	
   	}
   	
   	public function agregarDirecciones($correo_electronico){
   		
		//Set who the message is to be sent to		
			//$this->correo->addAddress($correos["direccion"], $correos["nombreCompleto"]);
			$this->correo->clearBCCs();
			$this->correo->AddBCC($correo_electronico["direccion"], $correo_electronico["nombreCompleto"]);
		
		
   	}
   	
   	public function agregarAsunto($asunto){
   		
		$this->correo->Subject = $asunto;		
   	
   	}
   	
   	public function agregarMensaje($mensajeHtml){   		
		
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->correo->msgHTML($mensajeHtml);		
   	
   	}
   	
   	public function enviarCorreoElectronico(){
   	
   		//send the message, check for errors
		if (!$this->correo->send()) {
   		 	//echo "Mailer Error: " . $this->correo->ErrorInfo;
   		 	return false;
		} else {
    			//echo "Message sent!";
    			return true;
		}
   	}

}// fin clase

?>