<?php

include_once("../../src/class.ControlWeb.php");
$web = new ControlWeb();

$ip = $_SERVER['REMOTE_ADDR'];
$entorno = $_SERVER['HTTP_USER_AGENT'];
$web->registrarVisitaSitioWeb($ip, $entorno); 

//echo "<br>".date("Y-m-d H:i:s");


if( isset($_GET["fe"])  && $_GET["fe"]!=''  ){
   $fecha_rango     = strtotime("-2 hour",$_GET["fe"]);  
   //echo "<br>".$fecha_rango;  
   $fecha_inicial   = date("Y-m-d H:i:s",$fecha_rango);  
   $fecha_final     = date("Y-m-d H:i:s",$_GET["fe"]);
  
}
//Validate y sanitaze variables
$fecha_inicial_val = strtotime($fecha_inicial);
$fecha_final_val = strtotime($fecha_final);

$fecha_inicial_val 	= filter_var($fecha_inicial_val, FILTER_SANITIZE_NUMBER_INT); //fechas convertidas a INT
$fecha_final_val 	= filter_var($fecha_final_val, FILTER_SANITIZE_NUMBER_INT); //fechas convertidas a INT
    if (filter_var($fecha_inicial_val, FILTER_VALIDATE_INT) && filter_var($fecha_final_val, FILTER_VALIDATE_INT)) {
        
        //echo 'obtenerTuitsInteresXFecha ';
        //echo '<br> fe_inicial '.$fecha_inicial ;
        //echo '<br> fe_final '.$fecha_final;
        $tweet = $web->obtenerTuitsInteresIncidente($fecha_inicial, $fecha_final);
        
               
    }


?>

<html>
<head>
    
       
<!-- incluir cabecera -->
<?php
include_once("cabecera.php");
?>


<!-- banner -->
<header>
		<div class="wrapper">
			<div onclick="location.href='http://www.ovi.org.ve';" style="cursor:pointer;" class="logo3">
			
				<nav>
					<a href="inicio.php#about-us">MAPA EN VIVO</a>
					<a href="inicio.php#contact-us">BÚSQUEDA</a>
					<a href="inicio.php#portfolio">¿QUÉ ES OVI?</a>
					<a href="inicio.php#contacto">SUSCRIBETE</a>
				</nav>
			</div>
		</div>

	</header>
<!-- //banner -->



<!-- about-us -->
<div id="about-us" class="about-us">
	<div class="container">
	<div id="publicidad-sup-izq">
       			<!-- <a target="_blank" href="http://cienciaconciencia.org.ve"><img src="../images/publicidad-ovni-izq.png"></a> -->

           	</div>
           	<div id="publicidad-sup-der">
       			<!-- <a href="#"><img src="../images/publicidad-ovni.png"></a> -->

           	</div>
		<div class="about-info">
			<h2>INCIDENTES DETECTADOS POR OVI</h2>
			
			<p >Aquí encontraras los tuits que fueron reportados por los usuarios de la red social Twitter para este incidencia vial. <b>OVI</b> los ha analizado por ti para brindarte información vial útil de forma rápida en todo momento.</p>
					
		</div>
		<div class="about-grids">		

		<table id="tuits" class="display" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>N°</th>
                <th>Tuits</th>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Latitud</th>
                <th>longitud</th>                
                <th>Usuario</th>
            </tr>
        </thead>
 
        <tfoot>
            <tr>
                <th>N°</th>
                <th>Tuits</th>
                <th>Fecha</th>
                <th>Lugar</th>
                <th>Latitud</th>
                <th>longitud</th>                
                <th>Usuario</th>
            </tr>
        </tfoot>
 
        <tbody>
        <?php
        $i = 1;
        foreach($tweet as $fila)
	{ 
		$usuario = $web->obtenerUsuariosxId($fila["user_id"]);
		//var_dump($usuario);
	
	?>
            <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $fila["text"]?></td>
                <td><?php echo $fila["date"]?></td>
                <td><?php echo $fila["lugar"]?></td>
                <td><?php echo $fila["lat"]?></td>
                <td><?php echo $fila["lon"]?></td>
                <td><?php echo "@".$usuario[0]["screen_name"] ?></td>
            </tr>
       <?php $i++;} ?>
            
        </tbody>
    </table>
      	
      	
		</div>
	</div>
	<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

</div>
<!-- //about-us -->

<script>
$(document).ready(function() {
    $('#tuits').DataTable();
} );
</script>

  
<!-- incluir piepagina -->
<?php
include_once("piepagina.php");
?>

</body>
</html>