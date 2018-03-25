<?php



include_once("../../src/class.ControlWeb.php");
$web = new ControlWeb();

$ciudad_ltq = "Los Teques, VE";
$clima = $web->obtenerClimaDetalleCiudad(date('Y-m-d'),$ciudad_ltq );
echo "Los Teques, VE<br>".$clima["etiqueta"]."<br>".$clima["valores"] ;
$etiqueta_ltq = $clima["etiqueta"];
$valores_ltq = $clima["valores"];

$ciudad_ccs = "Caracas, VE";
$clima = $web->obtenerClimaDetalleCiudad(date('Y-m-d'),$ciudad_ccs);
echo "<br>Caracas, VE<br>".$clima["etiqueta"]."<br>".$clima["valores"] ;
$etiqueta_ccs = $clima["etiqueta"];
$valores_ccs = $clima["valores"];



?>

<!doctype html>
<html>
	<head>
		<title>Line Chart</title>
		<script src="../js/chart-js/Chart.min.js"></script>
	</head>
	<body>
		<div style="width:50%">
			<div>
				<canvas id="canvas" height="450" width="600"></canvas>
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
		
		/*Chart.types.Line.extend({
		    name: "LineAlt",
		    draw: function () {
		        Chart.types.Line.prototype.draw.apply(this, arguments);
		
		        var ctx = this.chart.ctx;
		        ctx.save();
		        // text alignment and color
		        ctx.textAlign = "center";
		        ctx.textBaseline = "bottom";
		        ctx.fillStyle = this.options.scaleFontColor;
		        // position
		        var x = this.scale.xScalePaddingLeft * 0.4;
		        var y = this.chart.height / 2;
		        // change origin
		        ctx.translate(x, y)
		        // rotate text
		        ctx.rotate(-90 * Math.PI / 180);
		        ctx.fillText(this.datasets[0].label, 0, 0);
		        ctx.restore();
		    }
		});*/
		
		var lineChartData = {
			/*labels : ["22","23","24","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21"],*/
			labels : [<?php echo $etiqueta_ltq ?>],
			datasets : [
				{
					label: "<?php echo "$ciudad_ltq" ?>",
					fillColor : "rgba(220,220,220,0.2)",
					strokeColor : "rgba(220,220,220,1)",
					pointColor : "rgba(220,220,220,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(220,220,220,1)",
					/*data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]*/
					data: [<?php echo $valores_ltq ?>]
				},
				{
					label: "<?php echo "$ciudad_ccs" ?>",
					fillColor : "rgba(151,187,205,0.2)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					pointHighlightFill : "#fff",
					pointHighlightStroke : "rgba(151,187,205,1)",
					data : [<?php echo $valores_ccs ?>]
				}
			]

		}

	window.onload = function(){
	
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myLine = new Chart(ctx).LineAlt(lineChartData, {
			responsive: true,
			scaleLabel: "<%=value%> &#176; C",
			multiTooltipTemplate: "<%= datasetLabel %> - <%= value %> C"
		});
	}


	</script>
	</body>
</html>