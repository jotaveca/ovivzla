<?php

/*
 * Validación de la seguridad
*/

/*
 * Validación de la seguridad
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
include_once('../src/class.AgenteTuitero.php');

$reporte = date("d-m-Y H:i:s");
echo "\nReporte:".$reporte.": <br>";

$a = new AgenteTuitero();
echo "<br>";
//var_dump($a->obtenerImagenUbicacion("Kilómetro 5"));
echo "<br>";

$result = $a->hayIncidentes();
if ($result != FALSE){
    $a->alertarIncidentes($result);
}


$horario_revisar_segui = $a->esHoraRevisarSeguidores();
if($horario_revisar_segui == TRUE){
    $usuario = $a->usuarioAleatorioNoMeSigue();
    $a->revisarSiMeSigue($usuario);
}

/*$horario_invitar = $a->esHoraInvitar();
if($horario_invitar == TRUE){
    $usuario = $a->usuarioAleatorioNoMeSigue();
    $a->invitarSeguir($usuario);
}*/

$horario_invitar = $a->esHoraCrearConscienciaVial();
if($horario_invitar == TRUE){
    $a->EnviarMensajeSegVial();
    
}



$horario_actualizar_cant_tuis_interes = $a->esHoraActualizarCantTuitsInteres();
if ($horario_actualizar_cant_tuis_interes==TRUE){
    $a->actualizarCantTuitsInteres(); 
}


?>
