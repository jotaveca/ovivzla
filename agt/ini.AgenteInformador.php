<?php


/*
 * Validación de la seguridad

include_once("./class.ValidadorSeguridad.php");
 */
 
 /*	
if(is_null($argv)){ // ejecucion desde la web
                if(!isset($_GET['hash']) || $_GET['hash']!='$1$Cccp8eeR$oIvQBOlR9pPTQanb2FtZY1'){	
                        //echo $hashed_password = crypt('Simón Bolívar y Nikola Tesla');		
                        die("Accion no autorizada");
                }

        }else{ //desde cli - cgi-fcgi

                $hash = $argv[1];	
                if($hash!='$1$Cccp8eeR$oIvQBOlR9pPTQanb2FtZY1'){	

                        die("Accion no autorizada");
                }
        }

*/
header('Content-Type: text/html; charset=UTF-8');
//ini_set('display_errors', 1);
require_once('../src/class.AgenteInformador.php');

$a = new AgenteInformador();
//echo "AgenteInformador";
//$a->correoPrueba();

$a->notificarSuscriptoresEmail();
$a->monitorizarSitioWeb();

echo "<br>Ejecutado Agente Informador";



?>