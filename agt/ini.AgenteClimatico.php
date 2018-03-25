<?php

//phpinfo();

//var_dump($argv);

 /*if(!isset($_GET['hash']) || $_GET['hash']!='$1$Cccp8eeR$oIvQBOlR9pPTQanb2FtZY1'){	
                        //echo $hashed_password = crypt('Simón Bolívar y Nikola Tesla');		
                        die("Accion no autorizada");
 }
          */      


require_once('../src/class.AgenteClimatico.php');

$obj = new AgenteClimatico();
$obj->obtenerClimaCiudades();


?>