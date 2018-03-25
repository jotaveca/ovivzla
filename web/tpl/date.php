<?php
$minutos = 35;
		$minutos_atras = "- $minutos minute";                
                $fecha_actual = date("Y-m-d H:i:s");
                echo "<br><br>fecha_actual: ".$fecha_actual;
	     	//$nuevafecha = strtotime ( '-35 minute' , strtotime ( $fecha_actual ) ) ;
                $nuevafecha = strtotime ( $minutos_atras , strtotime ( $fecha_actual ) ) ;
	     	
	     	$nuevafecha = date("Y-m-d H:i:s", $nuevafecha );
	     	echo "<br><br> nuevafecha: ".$nuevafecha;
	     	//$nuevafecha = '2015-05-18 08:35:17';
	     	
	     	?>