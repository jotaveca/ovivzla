<?php
include_once("../../src/class.ControlWeb.php");

//echo "<br>H1".$fecha = date("Y-m-d H:i:s"); 
//echo "<br>H1".$fecha = date("d-m-y h:i a");

$tx_ambito = "#PNM";
$web = new ControlWeb();
$zonaVial =  '';


$ip = $_SERVER['REMOTE_ADDR'];
$entorno = $_SERVER['HTTP_USER_AGENT'];
$web->registrarVisitaSitioWeb($ip, $entorno); 



if(isset($_POST["zonaVial"]) && $_POST["zonaVial"]!= '0' ){
  
  $zonaVial = $_POST["zonaVial"];
  
}

//echo "Zona Vial == ".$zonaVial;



$tweet = $web->obtenerTuitsInteresZonaVial($zonaVial); 

$fecha_inicial = "2015-01-12 00:00:00"; //Fecha de inicio de operacion de OVNI
$fecha_final = date('Y-m-d')." 23:59:59";

$fecha_inicial_val = strtotime($fecha_inicial);
$fecha_final_val = strtotime($fecha_final);

$fecha_mem_1 = $fecha_inicial;  
$fecha_mem_2 = $fecha_final; 


//print_r($tweet);



$i = 0;
$marcadorAlto = '';
$marcadorMedio = '';
$marcadorBajo = '';

foreach($tweet as $fila)
{ 
	
	$lon = $fila["lon"];
 	$lat = $fila["lat"];  
  	$lugar = $fila["lugar"];
  	$total = $fila["total"];
  	$tx_ambito = $fila["tx_ambito"];
	
	
	if ($i < 5){
		//$lonlat = explode("|",$clave);
		//print_r($lonlat);
    		$marcadorAlto.= "[$lon, $lat,'$lugar', $total, '$tx_ambito'],"; 
    		//echo "<br> $marcadorAlto";
    	}
    		
    	if ($i >= 5 && $i < 15 ){
     		//$lonlat = explode("|",$clave);
		//print_r($lonlat);
     		$marcadorMedio.= "[$lon, $lat,'$lugar', $total, '$tx_ambito'],"; 
     		//echo "<br> > 25 y < 50";
    	}
    		
    	if ($i > 15 ){
    		//$lonlat = explode("|",$clave);
		//print_r($lonlat);
    		$marcadorBajo.= "[$lon, $lat,'$lugar', $total, '$tx_ambito'],"; 
    		//echo "<br> < 25";
	}	
	
	$i++;
	
}



 //elimina la ultima coma
 $marcadorAlto = substr($marcadorAlto, 0, -1);
 $marcadorMedio = substr($marcadorMedio, 0, -1);
 $marcadorBajo = substr($marcadorBajo, 0, -1);


//$profile_end = Rendimiento::get_microtime();
//print "<br>Tiempo de carga busqueda tweet interes y organizacion".Rendimiento::elapsed_time($profile_end, $profile_start);
//print_r($ult_tuits_interes );

?>

<html>	

<!-- incluir cabecera -->
<?php
include_once("cabecera.php");
?>


<!-- banner -->
<header>

<div class="wrapper">
			
			<div class ="logo3" onclick="location.href='http://www.ovi.org.ve';" style="cursor:pointer;" >
				 <nav>
					<a href="#about-us">MAPA EN VIVO</a>
					<a href="#contactenos">CONTÁCTENOS</a> 
					<a href="#portfolio">¿QUÉ ES OVI?</a>
					<a href="#contacto">SUSCRIBETE</a>
					
				</nav>
			</div>
			
			
			
			
			
</div>

</header>
	
<!-- //banner --> 

<script type="text/javascript">
		
	
			<!-- start-count-up -->
	
        		jQuery(document).ready(function($) {
           			 $('.counter').counterUp({
              			  delay: 20,
              		 	 time: 10000
            			});
       			 });
       			 
       			<!-- start-smoth-scrolling -->
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
       			
       			//suavizado de los vinculos
       			$(document).ready(function(){ 
	
			$( "nav a" ).click(function() {
			$('html, body').animate({ //smoth scrooll
        		scrollTop: $( $(this).attr('href') ).offset().top
    			}, 1000);
    			return false;
			});
 
			});
       
</script>


<!-- Inicio -->
<div id="inicio" class="inicio">     
	<div class="container">
		<div class="contact-info">
		<div id="publicidad-sup-izq">
       			 <!--<a target="_blank" href="http://cienciaconciencia.org.ve"><img src="../images/publicidad-ovni-izq.png"></a>-->
           	</div>
           	<div id="publicidad-sup-der">
       			 <!--<a href="#"><img src="../images/publicidad-ovni.png"></a>-->       			 
           	</div>
		<br>		
			<h2>OBSERVATORIO VIAL INTELIGENTE (OVI) </h2>
			<p >El <b>Observatorio Vial Inteligente (OVI) <i><small>Beta</small></i></b>, es un sistema de información inteligente, que extrae de la red social Twitter información relacionada con los accidentes y siniestros viales reportados por usuarios en la Gran Caracas, la visión es contar con toda la información vial del país. Ayúdanos a seguir construyendo esta red ciudadana de información vial usando la etiquetas viales en cada uno de tus reportes viales.</p>
				
			<h2>LO QUE ESTÁ OCURRIENDO EN ESTE MOMENTO</h2>
			
			<div style="border-radius: 10px; border: 5px solid;" class="cd-testimonials-wrapper cd-container">
			<ul class="cd-testimonials">
			
			<?php 
				$ult_tuits_interes = $web->obtenerUltimos30TuitsInteres();
				$total = count($ult_tuits_interes)- 1;
				for ($i=$total; $i >= 0 ; $i--){
				$fecha = $ult_tuits_interes[$i]['date'];
				$fecha_div = explode(" ", $fecha);
				

				//echo "$i - ".$ult_tuits_interes[$i]['text']." - ".$ult_tuits_interes[$i]['date']." - ".$ult_tuits_interes[$i]['name']." - ".$ult_tuits_interes[$i]['screen_name']." - ".$ult_tuits_interes[$i]['clase_incidente']."<br>";

			?>
			
			<li>
			<p><?php echo $ult_tuits_interes[$i]['text']." [$fecha_div[0]] "?></p>
			<div class="cd-author">
				<img src="../images/twitter-logo.png" alt="Author image">
				<ul class="cd-author-info">
					<li><?php echo $ult_tuits_interes[$i]['name']?></li>
					<li>@<?php echo $ult_tuits_interes[$i]['screen_name']?></li>
				</ul>
			</div>
			</li>		
			
			<?php } ?>			
		
			</ul> <!-- cd-testimonials -->
	
</div> <!-- cd-testimonials-wrapper -->

		</div>
	</div>
</div>


	<!-- Estadisticas -->	
	<div id="estadisticas" class="estadisticas">
	<div class="container">
		<div class="estadisticas-info">	
		<br>
		<h2>ESTADÍSTICAS GENERALES DE OVI</h2>
		<br>
		<br>
		<table width="100%" align="center">
			
							
			<tr>
			<td colspan="2">
			<div id="canvas-holder">
				<canvas id="chart-area" width="300" height="300"/>
			</div>
			
			</td>
			
			<td colspan="2"> 			
			<div id="canvas-holder">
				<canvas id="chart-area-1" width="300" height="300"/>
			</div>
			</td>
			
			<td colspan="2"> 					
			<div id="canvas-holder">
				<canvas id="chart-area-2" width="300" height="300"/>
			</div>
			</td>			
			</tr>
			
			<tr>
			<td colspan="2">
			<p>Cantidad de incidentes reportados por día de la semana.</p>
			<br>		
			</td>
			<td colspan="2"> 
			<p>Cantidad de incidentes reportados por hora del día.</p>	
			<br>
			</td>
			<td colspan="2"> 
			<p>Cantidad de incidentes reportados por tipo.</p>	
			<br>	
			</td>
			</tr>	
			
			<tr>
			<td colspan="6"><hr></td>			
			</tr>
			
			<tr>
			<td colspan="2"> <br></td>
			<td colspan="2"> <br></td>
			<td colspan="2"> <br></td>			
			</tr>
			
			<?php
				
		
			$total_tuits =   $web->obtenerTotalTuits(); 
			$total_usuarios = $web->obtenerTotalUsuarios();			
			$total_tuits_interes = $web->obtenerTotalTuitsInteres();				
		
			?>
			
			<tr>	
			
			<td colspan="2" align="center"><span class="counter" ><?php echo $total_tuits ?></span></td>
			
			<td colspan="2" align="center"><span class="counter" ><?php echo $total_tuits_interes ?></span></td>

			<td colspan="2" align="center"><span class="counter" ><?php echo $total_usuarios ?></span></td>
			
			</tr>
			<tr>
			<td colspan="2" align="center">Tuits analizados</td>			
			
			<td colspan="2" align="center">Tuits de interés clasificados </td>			

			<td colspan="2" align="center">Usuarios de twitter analizados</td>
			
			</tr>	
			<tr>
			<td colspan="2"> <br></td>
			<td colspan="2"> <br></td>
			<td colspan="2"> <br></td>
			</tr>
			
			<?php
			
			/*$ciudad_ltq = "Los Teques, VE";
			$clima = $web->obtenerClimaDetalleCiudad(date('Y-m-d'),$ciudad_ltq );
			//echo "Los Teques, VE<br>".$clima["etiqueta"]."<br>".$clima["valores"] ;
			$etiqueta_ltq = $clima["etiqueta"];
			$valores_ltq = $clima["valores"];
			
			$ciudad_ccs = "Caracas, VE";
			$clima = $web->obtenerClimaDetalleCiudad(date('Y-m-d'),$ciudad_ccs);
			//echo "<br>Caracas, VE<br>".$clima["etiqueta"]."<br>".$clima["valores"] ;
			$etiqueta_ccs = $clima["etiqueta"];
			$valores_ccs = $clima["valores"];
			
			$ciudad_ssa = "San Antonio de Los Altos, VE";
			$clima = $web->obtenerClimaDetalleCiudad(date('Y-m-d'),$ciudad_ssa);
			//echo "<br>San Antonio de Los Altos, VE<br>".$clima["etiqueta"]."<br>".$clima["valores"] ;
			$etiqueta_ssa = $clima["etiqueta"];
			$valores_ssa = $clima["valores"];
			
			$ciudad_crz = "Carrizal, VE";
			$clima = $web->obtenerClimaDetalleCiudad(date('Y-m-d'),$ciudad_crz);
			//echo "<br>Carrizal, VE<br>".$clima["etiqueta"]."<br>".$clima["valores"] ;
			$etiqueta_crz = $clima["etiqueta"];
			$valores_crz = $clima["valores"];*/
			
			
			?>
			<tr>
			<td colspan="6"><hr></td>			
			</tr>
			
			<!-- <tr>
			<td colspan="2"> <br></td>
			<td colspan="2"> <br></td>
			<td colspan="2"> <br></td>			
			</tr>
			<tr>
			<td colspan="6"> <h2>Condiciones meteorológicas del día <?php //echo date("j-n-Y");?> </h2></td>						
			</tr>
			<tr>
			<td colspan="2"> <br></td>
			<td colspan="2"> <br></td>
			<td colspan="2"> <br></td>			
			</tr>
			<tr>
			<td colspan="6">
			<div id="canvas-holder">
				<canvas id="plot-area" />
			</div>
			
			</td>			
				
			</tr> -->
			
		</table>
			<br>		
		
			<div class="clearfix"></div>
		</div>
	</div>
</div>


<script>
		
		Chart.types.Line.extend({
		  name: "LineAlt",
		  initialize: function (data) {
		    this.chart.height -= 30;
		
		    Chart.types.Line.prototype.initialize.apply(this, arguments);
		
		    var ctx = this.chart.ctx;
		    ctx.save();
		    // text alignment and color
		    ctx.textAlign = "center";
		    ctx.textBaseline = "bottom";
		    ctx.fillStyle = this.options.scaleFontColor;
		    // position
		    var x = this.chart.width / 2;
		    var y = this.chart.height + 15 + 5;
		    // change origin
		    ctx.translate(x, y)
		    ctx.fillText("Hora", 0, 0);
		    ctx.restore();
		  }
		});
		
		
		
		var lineChartData = {
			
			labels : [<?php //echo $etiqueta_ltq ?>],
			datasets : [
				{
					label: "<?php //echo "$ciudad_ltq" ?>",
					/*fillColor : "rgba(220,220,220,0.2)",*/
					'fillColor': "rgba(0,0,0,0)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",					
					data: [<?php //echo $valores_ltq ?>]
				},
				{
					label: "<?php //echo "$ciudad_ccs" ?>",
					/*fillColor : "rgba(151,187,205,0.2)",*/
					'fillColor': "rgba(0,0,0,0)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : [<?php //echo $valores_ccs ?>]
				},
				{
					label: "<?php //echo "$ciudad_ssa" ?>",
					/*fillColor : "rgba(255,0,0,0.1)",*/
					'fillColor': "rgba(0,0,0,0)",
					strokeColor : "rgba(255,0,0,0.7)",
					pointColor : "rgba(255,0,0,0.7)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(255,0,0,1)",
					data : [<?php //echo $valores_ssa ?>]
				}
				,
				{
					label: "<?php //echo "$ciudad_crz" ?>",
					/*fillColor : "rgba(0,128,0,0.1)",*/
					'fillColor': "rgba(0,0,0,0)",
					strokeColor : "rgba(0,128,0,0.7)",
					pointColor : "rgba(0,128,0,0.7)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(0,128,0,1)",
					data : [<?php //echo $valores_crz ?>]
				}
			]

		}


	</script>





<?php

// por día 
$torta1 = $web->obtenerCantidadIncidentesxDia();

//var_dump($torta1);

if ($torta1[1]['dia_semana_incidente'] == 'Monday')$torta1[1]['dia_semana_incidente'] = 'Lunes';
if ($torta1[5]['dia_semana_incidente'] == 'Tuesday')$torta1[5]['dia_semana_incidente'] = 'Martes';
if ($torta1[6]['dia_semana_incidente'] == 'Wednesday')$torta1[6]['dia_semana_incidente'] = 'Miércoles';
if ($torta1[4]['dia_semana_incidente'] == 'Thursday')$torta1[4]['dia_semana_incidente'] = 'Jueves';
if ($torta1[0]['dia_semana_incidente'] == 'Friday')$torta1[0]['dia_semana_incidente'] = 'Viernes';
if ($torta1[2]['dia_semana_incidente'] == 'Saturday')$torta1[2]['dia_semana_incidente'] = 'Sábado';
if ($torta1[3]['dia_semana_incidente'] == 'Sunday')$torta1[3]['dia_semana_incidente'] = 'Domingo';

$total_incidentes = $torta1[0]['total']+$torta1[1]['total']+$torta1[2]['total']+$torta1[3]['total']+$torta1[4]['total']+$torta1[5]['total']+$torta1[6]['total'];
//porcentaje
$p_torta_1_0 = number_format(($torta1[0]['total']*100)/$total_incidentes,1);
$p_torta_1_1 = number_format(($torta1[1]['total']*100)/$total_incidentes,1);
$p_torta_1_2 = number_format(($torta1[2]['total']*100)/$total_incidentes,1);
$p_torta_1_3 = number_format(($torta1[3]['total']*100)/$total_incidentes,1);
$p_torta_1_4 = number_format(($torta1[4]['total']*100)/$total_incidentes,1);
$p_torta_1_5 = number_format(($torta1[5]['total']*100)/$total_incidentes,1);
$p_torta_1_6 = number_format(($torta1[6]['total']*100)/$total_incidentes,1);


$torta_1_0 = $torta1[0]['dia_semana_incidente'].' ['.$p_torta_1_0.'%]';
$torta_1_1 = $torta1[1]['dia_semana_incidente'].' ['.$p_torta_1_1.'%]';
$torta_1_2 = $torta1[2]['dia_semana_incidente'].' ['.$p_torta_1_2.'%]';
$torta_1_3 = $torta1[3]['dia_semana_incidente'].' ['.$p_torta_1_3.'%]';
$torta_1_4 = $torta1[4]['dia_semana_incidente'].' ['.$p_torta_1_4.'%]';
$torta_1_5 = $torta1[5]['dia_semana_incidente'].' ['.$p_torta_1_5.'%]';
$torta_1_6 = $torta1[6]['dia_semana_incidente'].' ['.$p_torta_1_6.'%]';


// por clases de incidentes
$torta2 = $web->obtenerCantidadClasesIncidentes();

$total_incidentes_tipo = $torta2[0]['total']+$torta2[1]['total']+$torta2[2]['total']+$torta2[3]['total']+$torta2[4]['total'];
//porcentaje
$p_torta_2_0 = number_format(($torta2[0]['total']*100)/$total_incidentes_tipo,1);
$p_torta_2_1 = number_format(($torta2[1]['total']*100)/$total_incidentes_tipo,1);
$p_torta_2_2 = number_format(($torta2[2]['total']*100)/$total_incidentes_tipo,1);
$p_torta_2_3 = number_format(($torta2[3]['total']*100)/$total_incidentes_tipo,1);
$p_torta_2_4 = number_format(($torta2[4]['total']*100)/$total_incidentes_tipo,1);

$torta_2_0 = $torta2[0]['clase_incidente'].' ['.$p_torta_2_0.'%]';
$torta_2_1 = $torta2[1]['clase_incidente'].' ['.$p_torta_2_1.'%]';
$torta_2_2 = $torta2[2]['clase_incidente'].' ['.$p_torta_2_2.'%]';
$torta_2_3 = $torta2[3]['clase_incidente'].' ['.$p_torta_2_3.'%]';
$torta_2_4 = $torta2[4]['clase_incidente'].' ['.$p_torta_2_4.'%]';


// por hora
$barras = $web->obtenerCantidadIncidentesHora();

//var_dump($barras);

$etiquetas_horas = "['".$barras[6]['hora_incidente']."','".$barras[0]['hora_incidente']."','".$barras[8]['hora_incidente']."','".$barras[10]['hora_incidente']
."','".$barras[12]['hora_incidente']."','".$barras[14]['hora_incidente']."','".$barras[16]['hora_incidente']."','".$barras[18]['hora_incidente']."','".$barras[20]['hora_incidente']
."','".$barras[22]['hora_incidente']."','".$barras[2]['hora_incidente']."','".$barras[4]['hora_incidente']."','".$barras[7]['hora_incidente']."','".$barras[1]['hora_incidente']
."','".$barras[9]['hora_incidente']."','".$barras[11]['hora_incidente']."','".$barras[13]['hora_incidente']."','".$barras[15]['hora_incidente']."','".$barras[17]['hora_incidente']
."','".$barras[19]['hora_incidente']."','".$barras[21]['hora_incidente']."','".$barras[23]['hora_incidente']."','".$barras[3]['hora_incidente']
."','".$barras[5]['hora_incidente']."'],";

//"12am","1am","2am","3am","4am","5am","6am","7am","8am","9am","10am","11am","12p","1pm","2pm","3pm","4pm","5pm","6pm","7pm","8pm","9pm","10pm","11pm"


$total_horas = $barras[6]['total'].",".$barras[0]['total'].",".$barras[8]['total'].",".$barras[10]['total']
.",".$barras[12]['total'].",".$barras[14]['total'].",".$barras[16]['total'].",".$barras[18]['total'].",".$barras[20]['total']
.",".$barras[22]['total'].",".$barras[2]['total'].",".$barras[4]['total'].",".$barras[7]['total'].",".$barras[1]['total']
.",".$barras[9]['total'].",".$barras[11]['total'].",".$barras[13]['total'].",".$barras[15]['total'].",".$barras[17]['total']
.",".$barras[19]['total'].",".$barras[21]['total'].",".$barras[23]['total'].",".$barras[0]['total'].",".$barras[3]['total']
.",".$barras[5]['total'];

//echo "<br>".$total_horas;


?>

<script>

	
		var pieData = [
				{
					value: <?php echo $torta1[0]['total']?>,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "<?php echo $torta_1_0?>"
				},
				{
					value: <?php echo $torta1[1]['total']?>,
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "<?php echo $torta_1_1?>"
				},
				{
					value: <?php echo $torta1[2]['total']?>,
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "<?php echo $torta_1_2?>"
				},
				{
					value: <?php echo $torta1[3]['total']?>,
					color: "#949FB1",
					highlight: "#A8B3C5",
					label: "<?php echo $torta_1_3?>"
				},
				{
					value: <?php echo $torta1[4]['total']?>,
					color: "#4D5360",
					highlight: "#616774",
					label: "<?php echo $torta_1_4?>"
				},
				{
					value: <?php echo $torta1[5]['total']?>,
					color: "#0000FF",
					highlight: "#2E2EFE",
					label: "<?php echo $torta_1_5?>"
				},
				{
					value: <?php echo $torta1[6]['total']?>,
					color: "#088A29",
					highlight: "#04B431",
					label: "<?php echo $torta_1_6?>"
				}

			];
			
			var pieData3 = [
				{
					value: <?php echo $torta2[0]['total']?>,
					color:"#F7464A",
					highlight: "#FF5A5E",
					label: "<?php echo $torta_2_0?>"
				},
				{
					value: <?php echo $torta2[1]['total']?>,
					color: "#46BFBD",
					highlight: "#5AD3D1",
					label: "<?php echo $torta_2_1?>"
				},
				{
					value: <?php echo $torta2[2]['total']?>,
					color: "#FDB45C",
					highlight: "#FFC870",
					label: "<?php echo $torta_2_2?>"
				},
				{
					value: <?php echo $torta2[3]['total']?>,
					color: "#0000FF",
					highlight: "#2E2EFE",
					label: "<?php echo $torta_2_3?>"
				},
				{
					value: <?php echo $torta2[4]['total']?>,
					color: "#088A29",
					highlight: "#04B431",
					label: "<?php echo $torta_2_4?>"
				}

			];
			
			var barChartData = {
		labels : <?php echo $etiquetas_horas ?>
		datasets : [
			{
				label: "Incidentes por hora",
            			fillColor: "rgba(151,187,205,0.5)",
            			strokeColor: "rgba(151,187,205,0.8)",
           			highlightFill: "rgba(151,187,205,0.75)",
            			highlightStroke: "rgba(151,187,205,1)",
			        data: [<?php echo $total_horas ?>]
			}
		]

	}

			window.onload = function(){
				
				var ctx = document.getElementById("chart-area").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData);
    			
    
				
				var ctx = document.getElementById("chart-area-1").getContext("2d");
					window.myBar = new Chart(ctx).Bar(barChartData, {
					responsive : true
				});
				
				var ctx = document.getElementById("chart-area-2").getContext("2d");
				window.myPie = new Chart(ctx).Pie(pieData3);
				
				/*var ctx = document.getElementById("plot-area").getContext("2d");
				window.myLine = new Chart(ctx).LineAlt(lineChartData, {
					responsive: true,
					scaleLabel: "<%=value%> C",
					multiTooltipTemplate: "<%= datasetLabel %> , <%= value %> C"
				});*/
				
				
			};



	</script>



<?php
			//$profile_start = Rendimiento::get_microtime();
			// code to be profiled comes here
	?>	

<!-- about-us -->
<div id="about-us" class="about-us">
	<div class="container">
		<div class="about-info">
			<h2>MAPA EN VIVO</h2>
			<h3>
			<?php 			
				
				$m_vial = '';
				if ($zonaVial != ''){
					$m_vial = "para la Zona Vial <b> $zonaVial </b>";
				}


				echo "<br>La información mostrada en el mapa corresponde al período: $fecha_mem_1 al $fecha_mem_2 $m_vial ";				
			?>
			</h3>
			
				<br>
				<br>		
			<table align="center">
			<tr>
			<td>  <img src="../images/map-marker-red.png" width="55" height="50"></td>
			<td>Zonas viales con alta ocurrencia de incidentes &nbsp;&nbsp; </td>	
				
			
			<td>  <img src="../images/map-marker-amarillo.png" width="55" height="50"></td>
			<td>Zonas viales con moderada ocurrencia de incidentes &nbsp;&nbsp;</td>
			
			
			<td>  <img src="../images/map-marker-azul.png" width="55" height="50"></td>
			<td>Zonas viales con baja ocurrencia de incidentes &nbsp;&nbsp; </td>
			
			
			</tr>
			</table>
			<br>			
		</div>
		<div class="about-grids">	
		
		
		

		<!-- inicio de mapa -->
		
		<div style="border-radius: 10px; border: 5px solid;" id="map"></div>
      	
      <script>
    
    var marcadorAlto = [
       <?php echo $marcadorAlto ?>
    ];
    
     var marcadorMedio = [
       <?php echo $marcadorMedio ?>
    ];
    
    var marcadorBajo = [
       <?php echo $marcadorBajo ?>
    ];
    
   
    
   // map = new OpenLayers.Map("map");
   epsg4326 =  new OpenLayers.Projection("EPSG:4326"); //WGS 1984 projection
     
    map = new OpenLayers.Map({
                  div: "map",
                  displayProjection: epsg4326
            } );
    
    map.addLayer(new OpenLayers.Layer.OSM());
    
   
    projectTo = map.getProjectionObject(); //The map projection (Spherical Mercator)
   
    var lonLat = new OpenLayers.LonLat(-66.96381,10.38298).transform(epsg4326, projectTo);          
    var zoom=12;
    map.setCenter (lonLat, zoom);

    var vectorLayer = new OpenLayers.Layer.Vector("Overlay");
    
    // Define an array. This could be done in a seperate js file.
    // This tidy formatted section could even be generated by a server-side script (jsonp)


    /*var markers = [
       [ -67.03739, 10.35372 ],
       [ -67.02992, 10.36175 ],
       [ -66.99567, 10.35803 ]
    ];*/
    
    

    //Loop through the markers array
    //    alert("Total de puntos graficados: "+marcadorAlto.length);
    
    //rojos
    
    var inicial = <?php echo $fecha_inicial_val ?>;
    var final = <?php echo $fecha_final_val ?>;
    
    for (var i=0; i<marcadorAlto.length; i++) {
      
       var lon = marcadorAlto[i][0];
       var lat = marcadorAlto[i][1];
       var lugar = marcadorAlto[i][2];
       var nu_incidentes = marcadorAlto[i][3];      
       var ambito = marcadorAlto[i][4];
       
       var param = "lat="+lat+"&lon="+lon+"&i="+inicial+"&f="+final;
       
        var feature = new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Point( lon, lat ).transform(epsg4326, projectTo),
                {description: "<img src='../images/ovi-texto.png' width='250' heigth='109' > <br><br><b>Contexto Vial:</b> "+ambito+"<br><b>Zona vial:</b> "+lugar+".<br> <b>Incidentes registrados:</b> " + nu_incidentes + "<br> <a target='_blank' href='tuits.php?" + param + "' >Ver tuits reportados</a>"} ,
                {externalGraphic: '../images/map-marker-red.png', graphicHeight: 50, graphicWidth: 55, graphicXOffset:-12, graphicYOffset:-25}                
            );   
            
                      
        vectorLayer.addFeatures(feature);
    }
    
   
    
      // amarllos      
      for (var i=0; i<marcadorMedio.length; i++) {
      
       var lon = marcadorMedio[i][0];
       var lat = marcadorMedio[i][1];
       var lugar = marcadorMedio[i][2];
       var nu_incidentes = marcadorMedio[i][3];
       var ambito = marcadorMedio[i][4];
       
       var param = "lat="+lat+"&lon="+lon+"&i="+inicial+"&f="+final;
       
        var feature = new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Point( lon, lat ).transform(epsg4326, projectTo),
                {description: "<img src='../images/ovi-texto.png' width='250' heigth='109' > <br><br><b>Contexto Vial:</b> "+ambito+"<br><b>Zona vial:</b> "+lugar+".<br> <b>Incidentes registrados:</b> " + nu_incidentes + "<br> <a target='_blank' href='tuits.php?" + param + "' >Ver tuits reportados</a>"} ,
                {externalGraphic: '../images/map-marker-amarillo.png', graphicHeight: 40, graphicWidth: 55, graphicXOffset:-12, graphicYOffset:-25  }
            );   
            
                      
        vectorLayer.addFeatures(feature);
    }
    
     // verdes      
      for (var i=0; i<marcadorBajo.length; i++) {
      
       var lon = marcadorBajo[i][0];
       var lat = marcadorBajo[i][1];
       var lugar = marcadorBajo[i][2];
       var nu_incidentes = marcadorBajo[i][3];
       var ambito = marcadorBajo[i][4];
       
       var param = "lat="+lat+"&lon="+lon+"&i="+inicial+"&f="+final;
       
        var feature = new OpenLayers.Feature.Vector(
                new OpenLayers.Geometry.Point( lon, lat ).transform(epsg4326, projectTo),
                {description: "<img src='../images/ovi-texto.png' width='250' heigth='109' > <br><br><b>Contexto Vial:</b> "+ambito+"<br><b>Zona vial:</b> "+lugar+".<br> <b>Incidentes registrados:</b> " + nu_incidentes + "<br> <a target='_blank' href='tuits.php?" + param + "' >Ver tuits reportados</a>"} ,
                {externalGraphic: '../images/map-marker-azul.png', graphicHeight: 30, graphicWidth: 35, graphicXOffset:-12, graphicYOffset:-25  }
            );   
            
                      
        vectorLayer.addFeatures(feature);
    }
    
    
                  
   
    map.addLayer(vectorLayer);
    
    //Add a selector control to the vectorLayer with popup functions
    var controls = {
      selector: new OpenLayers.Control.SelectFeature(vectorLayer, { /*hover: true,*/ onSelect: createPopup, onUnselect: destroyPopup })
    };

    
    
    function createPopup(feature) {
         
          document.getElementById("map").style.cursor = "pointer";
         
          feature.popup = new OpenLayers.Popup.FramedCloud("pop",
          feature.geometry.getBounds().getCenterLonLat(),
          null,
          '<div class="markerContent">'+feature.attributes.description+'</div>',
          null,
          true,
          function() { controls['selector'].unselectAll(); }
      );
      //feature.popup.closeOnMove = true;
      map.addPopup(feature.popup);
    }

    function destroyPopup(feature) {
      document.getElementById("map").style.cursor = "auto";
      feature.popup.destroy();
      feature.popup = null;
    }
    
    map.addControl(controls['selector']);
    controls['selector'].activate();
    
    //Add the MousePosition control to show coordinates
    map.addControl(new OpenLayers.Control.MousePosition());
    
    
    
  </script>
  
  <!-- fin de mapa -->
    
    	  	
      	
		</div>
	</div>
</div>
<!-- //about-us -->

<!-- ZONAS-VIALES -->
<div id="contact-us" class="contact-us">
	<div class="container">
		<div class="contact-info">
		<br>
		<br>
			<h2>BÚSQUEDA DE ZONAS VIALES </h2>
			<p>Seleccione la zona vial de su interés para ver los incidentes viales reportados por la red de usuarios de <b>OVI</b> en Twitter.</p>
		</div>
		<div class="contact-grids">
			<div class="col-md-6 contact-left">
				<h2>ZONAS VIALES</h2>
				<div class="border"></div>
				<p>Una zona vial, está determinada por una zona geográfica ubicada en una autopista, carretera o avenida. Estas zonas viales, son identificadas por <b>OVI</b> a través del análisis de la red social Twitter.</p>
				
			</div>
			<div class="contact-right">
				<form action="inicio.php" method="POST">
					 
					 <select name="zonaVial" id="zonaVial" required>
					 <option value="" disabled selected>Zona Vial</option>  		
					 <option value="0" <?php if ($zonaVial == '') echo 'selected'; ?> >Todas las Zonas Viales</option>  		
					 <?php 
					 	$vias = $web->listarVias();
					 for ($i=0;$i<count($vias);$i++) {

					 	if ($zonaVial == $vias[$i]['tx_siglas']) $selected = 'selected';
					 	else $selected = '';


					 	echo "<option value='".$vias[$i]['tx_siglas']."' $selected > ".$vias[$i]['tx_nombre']." </option>";
					 } ?>			 
 					
 					 </select> 

					
					<input type="submit" value="BUSCAR">
					<br>

				</form>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div> 



<!-- ZONAS-VIALES -->
<!--<div id="contact-us" class="contact-us">
	<div class="container">
		<div class="contact-info">
		<br>
		<br>
			<h2>BÚSQUEDA DE ZONAS VIALES </h2>
			<p>Ingrese la fecha de inicio y la fecha fin, para que <b>OVI</b> le muestre los resultados acorde a la información vial de su interés.</p>
		</div>
		<div class="contact-grids">
			<div class="col-md-6 contact-left">
				<h2>ZONAS VIALES</h2>
				<div class="border"></div>
				<p>Una zona vial, está determinada por una zona geográfica ubicada en una autopista, carretera o avenida. Estas zonas viales, son identificadas por <b>OVI</b> a través del análisis de la red social Twitter.</p>
				
			</div>
			<div class="col-md-6 contact-right">
				<form action="inicio.php" method="POST">
					<input name="datetimepicker1" id="datetimepicker1" type="text" placeholder="Fecha inicio..." value="<?php //if(isset($_POST['datetimepicker1'])) echo $fecha_mem_1 ?>" required>
					<input name="datetimepicker2" id="datetimepicker2" type="text" placeholder="Fecha fin..."  value="<?php //if(isset($_POST['datetimepicker2'])) echo $fecha_mem_2 ?>" required>					
					<input type="submit" value="BUSCAR">
					
				</form>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div> 

<script>
    
  jQuery('#datetimepicker1').datetimepicker(
  {
 	lang:'es', 	
 	timepicker:false,
 	format:'d.m.Y'
  });
	
  jQuery('#datetimepicker2').datetimepicker(
  {
 	lang:'es', 	
 	timepicker:false,
 	format:'d.m.Y'
  });
	
  </script> 

  -->

<!-- //contact-us -->




<!-- portfolio -->
<div id="portfolio" class="portfolio">
	<div class="container">
		<div class="portfolio-info">
			<h2>¿QUÉ ES OVI?</h2>
			<p >El <b>Observatorio Vial Inteligente (OVI)</b> es un proyecto de investigación e innovación, desarrollado con Software Libre, por el equipo de investigación sociotecnológico "Antonio José de Sucre". OVI busca identificar las zonas de mayor siniestralidad y accidentalidad de las vías de mayor circulación en la República Bolivariana de Venezuela. En su versión Beta, se analiza las autopistas de la Gran Caracas a través de la etiquetas viales de la red social Twitter. Para esto, es necesario aplicar técnicas informáticas para la detección y clasificación de estos incidentes de tránsito.		
			<br>
			<br>			
			<br></p>


<div id="section3">
        <div id="s3group">
          <div id="s3icono1">
              <img width="188" height="188" src="../images/red-social.png">
              <p>&nbsp;</p>
              <h2> Redes sociales</h2>
              <p align="center" > Integración con el <b>API</b> de la</p>
              <p align="center" > red social Twitter y el correo electrónico </p>
              <p align="center" > para notificar los incidentes viales.</p>
            
            </div>
          <div id="s3icono2">
              <img width="188" height="188" src="../images/ia.png">
              <p>&nbsp;</p>
              <h2> Inteligencia Artificial</h2>
              <p align="center" > Uso de Multi-Agentes inteligentes </p>
              <p align="center" > y Minería de Datos para el </p>
              <p align="center" > procesamiento de la información vial.</p>
              
          </div> 
          <div id="s3icono3"><img width="188" height="188" src="../images/datos-abiertos.png">
            	<p>&nbsp;</p>
              <h2> Datos Abiertos</h2>
              <p align="center" > Inspirados en los valores  </p>
              <p align="center" > del Software Libre y </p>
               <p align="center" > el Conocimiento Libre.</p>
              
            </div>
      </div>
   </div>        
</div> 
        
      			
			</div> 
				
					
		</div>
		
	</div>





<!-- //portfolio -->
  
   
  
<!-- contact-us -->
<div id="contacto" class="contact-us">
	<div class="container">
		<div class="contact-info">
		<br>
		<br>
			<h2>SUSCRÍBETE</h2>
			<p>Al suscribirte a <b>OVI</b>, podrás recibir notificaciones por Twitter y/o correo electrónico sobre lo que acontece en las principales vías del país, adaptado a tus preferencias y necesidades. ¡Hazlo ahora! es muy fácil y rápido, además es gratis.</p>
		</div>
		<div class="contact-grids">
			<div class="col-md-6 contact-left">
				<h2>INFORMACIÓN</h2>
				<div class="border"></div>
				<p>Solo indicanos tus datos personales, tu correo electrónico y sobre que quieres estar informado y <b>OVI</b> se encargará del resto. </p>
				
			</div>
			<div class="col-md-6 contact-right">
				<form name="form-contacto" id="form-contacto" action="" method="POST">
					<input name="nombreApellido" id="nombreApellido" type="text" placeholder="Nombres y apellidos..." required>
					<input name="correoElectronico" id="correoElectronico" type="email" placeholder="Correo electrónico..." required>
					<input name="telefono" id="telefono" type="text" maxlength="14" placeholder="Teléfono" required>
					 <select name="preferencias[]" id="preferencias" class="preferencias" multiple="true" required>					
					 <?php 
					 	
					 for ($i=0;$i<count($vias);$i++) {

					 	echo "<option value='".$vias[$i]['tx_siglas']."'> ".$vias[$i]['tx_nombre']." </option>";
					 } ?>	
 					 </select> 	
 					
 					 <select name="preferenciasHora" id="preferenciasHora" required>
					  <option value="" disabled selected>Opciones de notificación</option>  					
 					 <option value="d_1_5_x_h_5_6_7_17_18_19">De lunes a viernes en horas pico (Entre 5am - 7am y 5pm - 7pm )</option>	
 					 <option value="d_1_5_x_h_24">De lunes a viernes todo el día</option>	
 					  <option value="d_1_7_x_h_24">De lunes a domingo todo el día</option>	
 					 </select> 	
 					  <select name="sexo" id="sexo" required>
					  <option value="" disabled selected>Sexo</option>  					
 					 <option value="M">Masculino</option>	
 					 <option value="F">Femenino</option>	 					  					
 					 </select> 	
 					 <input name="datepicker" id="datepicker" type="text" placeholder="Fecha de Nacimiento" required>
 					<br> 					
					<label>Por favor confirme que usted no es un robot <font size="1" ><a href="http://es.wikipedia.org/wiki/Captcha">¿Porqué?</a></font></label>
					<br>
					<!-- <div class="g-recaptcha" data-sitekey="6Lce1iETAAAAAAIPZXk-UwlRjkCP65lBCDqN9lGZ"></div> -->
					<div id="recaptcha1"></div>
					<!-- <script src='https://www.google.com/recaptcha/api.js?hl=es'></script> -->
 						
					<input type="submit" value="SUSCRIBIRSE">
						
				</form>

			</div>				
			<div id="respuesta" class="clearfix"></div>
			<br>			
		</div>
	</div>
</div>


<!-- Colabora -->
<!-- <div id="inicio" class="inicio">
	<div class="container">
		<div class="contact-info">		
		<br>		
			<h2> COLABORA </h2>			 

			<table width="100%">
			<tr>
			<td width="20%"><img width="188" height="188" src="../images/donate-icon.png"></td>
			<td width="2%"> &nbsp;</td>
			<td valign="top" width="78%"><h5> Somos una organización sin fines de lucro, por lo cual aceptamos cualquier ayuda que nos puedan brindar, por ejemplo comparte este sitio web por redes sociales o coméntaselo a un amigo para que más personas nos conozcan o si lo prefieres haz un aporte económico, todo es bien recibido.</h5></td>
			</tr>	
		</table>		 
		<br>
		<center>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="LNXZCXK73FEEG">
				<input type="image" src="https://www.paypalobjects.com/es_ES/ES/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal. La forma rápida y segura de pagar en Internet.">
				<img alt="" border="0" src="https://www.paypalobjects.com/es_XC/i/scr/pixel.gif" width="1" height="1">
			</form>
		</center>
	
	<div class="clearfix"></div>
		</div>
	</div>
</div>

<!-- Contactenos -->
<div id="contactenos" class="portfolio">
	<div class="container">
		<div class="portfolio-info">		
			<h2> CONTÁCTENOS </h2>
			
		<br>
		<p>.</p>
		</div>
		<div class="contact-grids">
			<div class="col-md-6 contact-left">
				<h2>INFORMACIÓN</h2>
				<div class="border"></div>
				<p>Nos apasiona el análisis de datos en redes sociales para describir fenómenos sociales. Si desea contactarnos para preguntas y propuestas sobre el <b>OVI</b>, escribenos por esta vía</p>
				
			</div>
			<div class="col-md-6 contact-right">
				<form name="form-contactenos" id="form-contactenos" action="" method="POST">
					 <input name="nombreCompleto" id="asunto" type="text" placeholder="Nombres y Apellido" required >								
					 <input name="correoCompleto" id="asunto" type="email" placeholder="Correo electrónico" required >	
					 <input name="asuntoCompleto" id="asunto" type="text" placeholder="Asunto" required >	
					 <input name="mensajeCompleto" id="asunto" type="text" placeholder="Mensaje" style="width: 520px; height: 100px"  required >	
					<br> 					
					<label>Por favor confirme que usted no es un robot <font size="1" ><a href="http://es.wikipedia.org/wiki/Captcha">¿Porqué?</a></font></label>
					<br>
					<!-- <div class="g-recaptcha" data-sitekey="6Lce1iETAAAAAAIPZXk-UwlRjkCP65lBCDqN9lGZ"></div> -->
					<div id="recaptcha2"></div>
					<!-- <script src='https://www.google.com/recaptcha/api.js?hl=es'></script> -->
					<input type="submit" value="ENVIAR">		
			     	</form>	
				
			</div>		
		</div>
		<div id="respuesta_contactenos" class="clearfix"></div>
		
	</div>
</div>



<!-- Publicaciones -->
<div id="inicio" class="contact-us">
	<div class="container">
		<div class="contact-info">
		<br>		
			<h2> DOCUMENTOS DE INTERÉS </h2>
			<p >
			<ul>
				<li><b>1.- Concisa 2015: Observatorio Vial Inteligente (OVI),</b> Análisis de las redes sociales con técnicas de minería de datos para aumentar la seguridad vial en Venezuela. <a target="_blank" href="../../doc/ovni-concisa.html">[Descargar]</a></li> 
				
			</ul>
			<ul>
				<li><b>2.- Jornastec 2016: Observatorio Vial Inteligente (OVI),</b> Una visión tecnológica de la seguridad vial. <a target="_blank" href="../../doc/ovi-jornastec-2016-inteligencia-artificial-seguridad-vial.pdf">[Descargar]</a></li> 
				
			</ul>
			<ul>
				<li><b>3.- Libro: Seguridad Vial en Venezuela</b> por Elio Rafael Aguilera.<a target="_blank" href="../../doc/Seguridad_Vial_Prof_Elio_Aguilera.pdf">[Descargar]</a></li> 
				
			</ul>
			<ul>
				<li><b>4.- Arquitectura de software del OVI</b> por el equipo de investigación "Antonio José de Sucre".<a target="_blank" href="../../doc/arquitectura_software_ovi_resumen.pdf">[Descargar]</a></li> 
				
			</ul>
			
			</p>
		</div>
	
	<div class="clearfix"></div>
		
	</div>
</div>
<script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
<script>
      var recaptcha1;
      var recaptcha2;
      var myCallBack = function() {
        //Render the recaptcha1 on the element with ID "recaptcha1"
        recaptcha1 = grecaptcha.render('recaptcha1', {
          'sitekey' : '6Lce1iETAAAAAAIPZXk-UwlRjkCP65lBCDqN9lGZ', //Replace this with your Site key
          'theme' : 'light'
        });
        
        //Render the recaptcha2 on the element with ID "recaptcha2"
        recaptcha2 = grecaptcha.render('recaptcha2', {
          'sitekey' : '6Lce1iETAAAAAAIPZXk-UwlRjkCP65lBCDqN9lGZ', //Replace this with your Site key
          'theme' : 'light'
        });
      };
</script>


<script>
$(document).ready(function(){

     jQuery('#datepicker').datetimepicker(
  {
 	lang:'es', 	
 	timepicker:false,
 	format:'Y-m-d'
  });
   
   
	
  $.fn.select2.defaults.set('language', 'es');
  //Select2
  $(".preferencias").select2({
    maximumSelectionLength: 3,
    placeholder: "Zona vial de interés"
  })




	$('#telefono', '#form-contacto')

	.keydown(function (e) {
		var key = e.charCode || e.keyCode || 0;
		$phone = $(this);

		// Auto-format- do not expose the mask as the user begins to type
		if (key !== 8 && key !== 9) {
			if ($phone.val().length === 5) {
				$phone.val($phone.val() + ')');
			}
			if ($phone.val().length === 5) {
				$phone.val($phone.val() + ' ');
			}			
			if ($phone.val().length === 9) {
				$phone.val($phone.val() + '-');
			}
		}

		// Allow numeric (and tab, backspace, delete) keys only
		return (key == 8 || 
				key == 9 ||
				key == 46 ||
				(key >= 48 && key <= 57) ||
				(key >= 96 && key <= 105));	
	})
	
	.bind('focus click', function () {
		$phone = $(this);
		
		if ($phone.val().length === 0) {
			$phone.val('(');
		}
		else {
			var val = $phone.val();
			$phone.val('').val(val); // Ensure cursor remains at the end
		}
	})
	
	.blur(function () {
		$phone = $(this);
		
		if ($phone.val() === '(') {
			$phone.val('');
		}
	});



      $('#form-contactenos').submit(function(){
     
        // show that something is loading
        $('#respuesta_contactenos').html("Enviando correo electrónico al <b>OVI.</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            url: '../../src/control_contactenos.php?ac=nuevo',
            data: $(this).serialize()
        })
        .done(function(data){
             
            // show the response
            $('#respuesta_contactenos').html(data);
             
        })
        .fail(function() {
         
            // just in case posting your form failed
            alert( "Por favor intentelo más tarde." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
    
    


    $('#form-contacto').submit(function(){
     
        // show that something is loading
        $('#respuesta').html("Enviando solicitud de suscripción a <b>OVI.</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            url: '../../src/control_suscriptores.php?ac=nuevo',
            data: $(this).serialize()
        })
        .done(function(data){
             
            // show the response
            $('#respuesta').html(data);
             
        })
        .fail(function() {
         
            // just in case posting your form failed
            alert( "Por favor intentelo más tarde." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
    
    
});
</script>


<!-- //contact-us -->

<br>
<br>

<!-- incluir piepagina -->
<?php
include_once("piepagina.php");
?>


</body>
</html>


