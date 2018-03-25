<?php

class Cadena{
	
	function __construct() {	
      		
   	}
   	
   	/**
	* Funcion para todo lo relacionado con el formato antes de guardar en BD de los tuits analizados 
	*/	
	public function formatearCadena($textoEntrada){
	
		$textoSalida = trim($textoEntrada);
		$textoSalida = $this->eliminarAcentos($textoSalida);
		$textoSalida = strtolower($textoSalida);
		
		return $textoSalida;
	
	
	}
	
	/**
   	 *
   	 * Encontrar los caracteres especiales dentro de un arreglo de tuit 
   	 */
   	 public function extraerCaracteresEspeciales($caracteres_especiales, $tuit){   
  
  		//var_dump($tuit);
  		//echo "<br>";
  		$var = array();
  		foreach ($caracteres_especiales as $clave=>$valor) {   
  			for ($i=0;$i<count($tuit);$i++) {    
     		
    				//echo "<br>$clave => $tuit[$i]";
    				if (strpos($tuit[$i], $clave) !== false) {  
        				 //$encontrado = $valor; 
        				 echo "<br>Valor: $tuit[$i] ($valor)";        				 
        				 //array_splice($tuit, $i, 1);
        				 array_push($var,$i);
        			}
         		  
   		 	}
   		}//fin foreach
  		/*echo "<br>";
  		print_r($tuit);
  		
  		echo "<br>";
  		print_r($var);
  		*/
  		foreach($var as $key => $value){
  			if(isset($tuit[$value])){
       				unset($tuit[$value]);
  			}
		}
		/*
  		echo "<br>";
  		print_r($tuit);*/
  		
  		$tuit = array_values($tuit);
  		
   		return $tuit;   

	}
	
	
	/**
	* Funcion para eliminar todos los acentos de una cadena
	*/
	public function eliminarAcentos($string){
		
		$string = str_replace(
        		array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
       			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
       			 $string
    		);

    		$string = str_replace(
        		array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        		array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        		$string
    		);

   		 $string = str_replace(
       			 array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
       			 array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
       			 $string
    		);

    		$string = str_replace(
       			 array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        		array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        		$string
    		);

    		$string = str_replace(
       			 array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
       			 array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
      			  $string
    		);
		
	return 	$string;
		
	}
   	
} //fin clase 

/*$caracteres_interes = 		array("#"     	=> "almohadilla",
				"@"    		=> "arroba",
                                "via"           => "via_usuario",
                                "reporte"       => "reporte_usuario",
                                "reporta"       => "retuit_usuario",
                                "rt"       	=> "reporte_usuario",
                                "http"       	=> "url");
                                
                                
$a = new Cadena();
$text = $a->formatearCadena("#enestemomento @paramedicosmtt bdc y @cpnb_ve atienden accidente sin lesionados en la #pnm km2 #precaucion @fmcenter @trafficaracas");
$bolsaPalabras = preg_split('/\s+/', $text);	
$a->extraerCaracteresEspeciales($caracteres_interes,$bolsaPalabras);
*/


?>