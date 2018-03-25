<!DOCTYPE html>
<html>
<head>
<?php
define("_CSS_","/web/css");
define("_JS_","/web/js");
define("_IMG_","/web/images");
?>  
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Observatorio Vial Inteligente (OVI)</title>      
        <link rel="alternate" hreflang="es-VE" href="http://observatoriovial.cienciaconciencia.org.ve/web/" />        
	<link href="<?php echo _CSS_ ?>/style.css" rel="stylesheet" type="text/css" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo _JS_ ?>/datetimepicker-master/jquery.datetimepicker.css"/>
	<!-- css tuits -->
	<link rel="stylesheet" href="<?php echo _CSS_ ?>/reset.css"> <!-- CSS reset -->
	<link rel="stylesheet" href="<?php echo _CSS_ ?>/style-carousel.css"> <!-- Resource style -->
	
	<!--fonts-->	
	<link href='<?php echo _CSS_ ?>/googlefonts.css' rel='stylesheet' type='text/css'> 
	<!--//fonts-->
	<link href="<?php echo _CSS_ ?>/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<link href='<?php echo _JS_ ?>/DataTables/media/css/jquery.dataTables.css ' rel='stylesheet' type='text/css'> 
	<!-- for-mobile-apps -->
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="author" content="Juan Cisneros (OVI)">
	<meta name="description" content="Observatorio Vial Inteligente (OVI)">
	<meta name="keyword" content="Observatorio Vial Inteligente (OVI)" />
		
	
		
	<!-- js -->
	<script type="text/javascript" src="<?php echo _JS_ ?>/jquery.min.js"></script>
	<script src="<?php echo _JS_ ?>/openlayers/OpenLayers.js"></script> 
	<!-- start-smoth-scrolling -->
	<script async type="text/javascript" src="<?php echo _JS_ ?>/move-top.js"></script>
	<script async type="text/javascript" src="<?php echo _JS_ ?>/easing.js"></script>
	<script type="text/javascript" src="<?php echo _JS_ ?>/datetimepicker-master/jquery.datetimepicker.js"></script>		
	<script async src="<?php echo _JS_ ?>/waypoints.min.js"></script>
    	<script src="<?php echo _JS_ ?>/Counter-Up-master/jquery.counterup.min.js"></script>
    	<script src="<?php echo _JS_ ?>/chart-js/Chart.min.js"></script>
    	
    	<script type="text/javascript" src="<?php echo _JS_ ?>/DataTables/media/js/jquery.dataTables.min.js"></script>  
    	
    	<!-- carruzel tuits -->
    	<script src="<?php echo _JS_ ?>/jquery.flexslider-min.js"></script>
	<script src="<?php echo _JS_ ?>/main.js"></script> <!-- Resource jQuery -->
	<script src="<?php echo _JS_ ?>/masonry.pkgd.min.js"></script>
    
         <!-- script for pop-up-box -->
	<script async type="text/javascript" src="<?php echo _JS_ ?>/modernizr.custom.min.js"></script>    	
	
<script type="text/javascript">			
		
    
       /*Se usa para cambiar hover de las redes sociales iconos*/
       
       var sourceSwap = function () {
        var $this = $(this);
        var newSource = $this.data('alt-src');
        $this.data('alt-src', $this.attr('src'));
        $this.attr('src', newSource);
    }

    $(function () {
        $('img.xyz').hover(sourceSwap, sourceSwap);
    });
    
</script>
</head>

<body >

<!-- <div class="se-pre-con"></div> -->

<!-- pre-banner-social-->
<div id="section1">
	<div id="s1menu">
		<div onclick="location.href='http://www.ovi.org.ve';" style="cursor:pointer;" id="s1logo"></div>
		<div id="s1btn1"></div>
		<div id="s1btn2"></div>
		<div id="s1btn3"></div>
		<div id="s1btn4"><a href="http://www.twitter.com/ovivzla" target="_blank"><img class="xyz" width="50" height="50" src="<?php echo _IMG_ ?>/twiit.png" data-alt-src="<?php echo _IMG_ ?>/twiiton.png"></a> </div>
		<div id="s1btn5"><a href="mailto:ovnivial@gmail.com?Subject=Contacto%20OVI" target="_blank"><img class="xyz" width="50" height="50" src="<?php echo _IMG_ ?>/google.png" data-alt-src="<?php echo _IMG_ ?>/googleon.png"> </a>  </div>
	</div>
</div>