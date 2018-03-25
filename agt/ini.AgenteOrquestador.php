<?php
require_once('../src/class.AgenteOrquestador.php');

$clase = 'AgenteOrquestador';
$agt = new $clase();
$agt->ini_bdi();
$frecuencia_ejecucion_min = $agt->belief['frecuencia_ejecucion_min'];
echo "<br><b>".$nombre_clase = $agt->belief['nombre_agente']." se ejecuta cada $frecuencia_ejecucion_min min </b>";
$metas = $agt->desire;
$plan_accion = $agt->intention;

foreach($metas as $meta){
	echo "<br>Meta ".$meta;
	$plan = $plan_accion[$meta]['plan_accion'];
	foreach($plan as $accion){
		echo "<br> Plan Accion => ".$accion;
		$agt->{$accion}();
	}
}

?>