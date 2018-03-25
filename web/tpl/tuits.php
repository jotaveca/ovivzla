<?php

include_once("../../src/class.ControlWeb.php");
$web = new ControlWeb();

$ip = $_SERVER['REMOTE_ADDR'];
$entorno = $_SERVER['HTTP_USER_AGENT'];
$web->registrarVisitaSitioWeb($ip, $entorno); 



$fecha_inicial 	= $_GET['i'];
$fecha_final 	= $_GET['f'];
$lat 		= $_GET['lat'];
$lon 		= $_GET['lon'];

$fecha_inicial_val = $fecha_inicial;
$fecha_final_val = $fecha_final;

$fecha_inicial = date('Y-m-d H:i:s', $fecha_inicial);  
$fecha_final =   date('Y-m-d H:i:s', $fecha_final); 


$lat 		= filter_var($lat , FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); //numeros convertidos a FLOAT
$lon 		= filter_var($lon , FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION); //numeros convertidos a FLOAT
$fecha_inicial_val 	= filter_var($fecha_inicial_val, FILTER_SANITIZE_NUMBER_INT); //fechas convertidas a INT
$fecha_final_val 	= filter_var($fecha_final_val, FILTER_SANITIZE_NUMBER_INT); //fechas convertidas a INT


if (filter_var($fecha_inicial_val, FILTER_VALIDATE_INT) && filter_var($fecha_final_val, FILTER_VALIDATE_INT) && filter_var($lat , FILTER_VALIDATE_FLOAT) && filter_var($lon , FILTER_VALIDATE_FLOAT) ) 
{
        
        $tweet = $web->obtenerTuitsInteresDetalle($fecha_inicial, $fecha_final, $lat, $lon);
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
       			 <!--<a target="_blank" href="http://cienciaconciencia.org.ve"><img src="../images/publicidad-ovni-izq.png"></a>-->
           	</div>
           	<div id="publicidad-sup-der">
       			 <!--<a href="#"><img src="../images/publicidad-ovni.png"></a>-->
           	</div>
		<div class="about-info">
			<h2>TUITS REPORTADOS POR LOS USUARIOS </h2>
			
			<p >Aquí encontraras todos los tuits que fueron reportados por los usuarios de la red social Twitter y recopilados por <b>OVI</b> para poder analizarlos y brindarte información vial útil de forma rápida en todo momento.</p>
					
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
<!-- //footer -->


</body>
</html>