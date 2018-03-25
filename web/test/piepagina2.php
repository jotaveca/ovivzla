<?php
include_once("src/class.Web.php");
$web = new ControlWeb();
$total_visitas = $web->obtenerTotalVisitaSitioWeb();

?>
<div class="footer">
	 <p>Plantilla web diseñada por <a href="http://w3layouts.com/">W3layouts</a> y adaptada por <b>OVI</b><br>Sitio web visitado <?php echo $total_visitas ?> veces.
	
	
</div>
<!-- //footer -->
<!-- smooth scrolling -->
	<script type="text/javascript">
		$(document).ready(function() {
		/*
			var defaults = {
			containerID: 'toTop', // fading element id
			containerHoverID: 'toTopHover', // fading element hover id
			scrollSpeed: 1200,
			easingType: 'linear' 
			};
		*/								
		$().UItoTop({ easingType: 'easeOutQuart' });
		});
	</script>
	
	<a href="#" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
<!-- smooth scrolling -->

