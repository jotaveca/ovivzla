<?php
$correoElectronico 	= $_GET['ce'];

if(!isset($correoElectronico)) die("Accion no permitida");


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
					<a href="inicio.php#portfolio">¿QUÉ ES OVNI?</a>
					<a href="inicio.php#contacto">SUSCRIBETE</a>
				</nav>
			</div>
		</div>
	</header>
<!-- //banner -->



<!-- about-us -->
<div id="about-us" class="about-us">
	<div class="container">
		<div class="about-info">
			<h2>Usuario desafialiado correctamente  </h2>
			
			<p >Estimado usuario su cuenta asociada al correo electrónico <b><?php echo $correoElectronico?></b> ha sido desafiliada correctamente, ya usted no recibirá más notificaciones de <b>OVI</b>. Esperamos en un futuro volver a contar con usted para seguir mejorando.
			<br>
			<br>
			Gracias.
			<br>
			Equipo OVI.</p>
					
		</div>
		<div class="about-grids">		

		
      	
      	
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
</div>
<!-- //about-us -->


<!-- incluir piepagina -->
<?php
include_once("piepagina.php");
?>


</body>
</html>