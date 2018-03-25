<?php

/*
 * Validación de la seguridad

include_once("./class.ValidadorSeguridad.php");
 */
//phpinfo();
// die();	
 
 $via_ejec = "";
 $hash = "";
if(!is_null($_GET['hash'])){ // ejecucion desde la web
                if(!isset($_GET['hash']) || $_GET['hash']!='$1$Cccp8eeR$oIvQBOlR9pPTQanb2FtZY1'){	                        
                        echo date("H:m");
                        die("Accion no autorizada por la web");
                }
                $hash = $_GET['hash'];
                
                $via_ejec = "web";

}else{ //desde cli - cgi-fcgi

                $hash = $argv[1];
                
                $via_ejec = "ldc";
                /*if($hash!='$1$Cccp8eeR$oIvQBOlR9pPTQanb2FtZY1'){	
                    //var_dump($argv);
                    echo date("H:m");
                    die("Accion no autorizada por la línea de comandos");
                }
                var_dump($argv);
                die();*/
        }


        
header('Content-Type: text/html; charset=UTF-8');
//ini_set('display_errors', 1);
require_once('../src/class.AgenteRevisor.php');

echo "<br>Vía ejecución: ".$via_ejec;
echo "<br>Hash: ".$hash;

$hora_depuracion =  date("H"); // hora del servidor
echo "<br><h1> Hora del servidor $hora_depuracion </h1>";


//correr la actualización de las notificaciones desde las 12m y hasta las 3am
if( /*$hora_depuracion >= 23 ||*/ ($hora_depuracion >= 0 && $hora_depuracion<=3) ){ 
//if(0){ 

    echo "<br><h1> Rutina de depuración </h1>";
    echo "<br><h2> Revisión aleatoria </h2>";
    $obj = new AgenteRevisor();
    $obj->inicializarCorrida(1,$via_ejec);
    //$obj->aplicarMetodosRevision(1);
    $obj->revisarVia();
    
    echo "<br><h2> RelocalizarTweet() </h2>";
    $obj->relocalizarTweet();

    echo "<br><br> Realizando labores de limpieza";
    $obj->actualizarMensajesAnalizados();
    

}
else{
    $obj = new AgenteRevisor();
    $obj->inicializarCorrida(0,$via_ejec);
    //$obj->aplicarMetodosRevision(0);
    $obj->revisarVia();
    //$obj->aplicarMetodosRevision(1);
}


?>