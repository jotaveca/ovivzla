<?php

//die();

include_once("class.BD.php");
include_once("class.Cadena.php");
include_once("class.Memoria.php");
include_once("class.Twitter.php");
include_once("class.Log.php");


class AgenteRevisor{

	private $twitter;
	private $bd;
	private $memoria;
	private $cadena;
	private $palabras_interes;
	private $lugares_interes;
	private $caracteres_interes;
	private $log;
        private $ambito;
        private $via_ejec;
        
        private $tuits_guardados;
        private $tuits_normalizados;
        private $tuits_procesados;
        private $tam_estudio;
        
        private $patrones;
	private $cant_patrones;
        private $patrones_formateados;
        private $depuracion;
        /*Atributos de corrida del agente*/
        private $horaInicio;        
        private $tiempoInicio;
        private $horaFin;
        private $tiempoFin;
        /*Atributos de corrida de los métodos*/
        private $horaInicio_metd;
        private $horaFin_metd;
        private $tiempoInicio_metd;
        private $tiempoFin_metd;
        
        private $id_corrida_agt;
        private $aleatorio;
                
	/*function __construct(){
            return;
        }
          */      
        function __construct() {
                $this->bd = new BD();
      		$this->memoria = new Memoria();
      		$this->twitter = new Twitter();
      		$this->cadena = new Cadena();
   	}
   	
         public function inicializarCorrida($aletorio,$via_ejec) {	        
                     		
      		$this->aleatorio = $aletorio;
                $this->via_ejec = $via_ejec;
      		$this->log = new Log();	
            	$fecha = date("Y-m-d H:i:s");	    
	    	$msg = "[AGENTE REVISOR INICIADO] | $via_ejec | $fecha ";
	    	$this->log->general($msg);                    
                
                
                include_once("../config/palabras_interes.php");
		$this->palabras_interes = $palabras_interes;
		$this->lugares_interes = $lugares_interes;
		$this->caracteres_interes = $caracteres_interes;
                //Para usar los tuits sintéticos descomentar     
                //$this->tuits_guardados = $tuits_guardados;             
                
                
                $this->patrones = array ( 
                            "via @",
                            "vía @",        
                            "(via @",
                            "(vía @",            
                            "retweeted ",
                            "rt @",
                            "rt ",
                            "reporta @",                            
                            "informa @"                            
                          );
                $this->depuracion = 0;
                
                $this->cant_patrones = sizeof($this->patrones);                
                
   	}
        
        
   	/**
   	* Metodo usado para actualizar al finalizar el dia los tuits registrados a "1" o "0" no procesados
   	*
   	*/
   	public function actualizarMensajesAnalizados() {
		
		$this->memoria->actualizarUltimosMensajes("1");
	}
   	/*
         * Se emplea para aplicar los metodos de revisión disponibles
         * 
         */
        public function aplicarMetodosRevision(){
            $this->id_corrida_agt   = $this->estadisticasAgente(1);
            echo "<br><h1>Corrida: $this->id_corrida_agt </h1>";
            //echo "<br><h1> aplicarComparacionSubCadena() </h1>";
            //$this->aplicarComparacionSubCadena($aleatorio);
            //echo "<br><h1> compararDiferenciaArreglos() </h1>";
            //$this->aplicarDiferenciaArreglos($aleatorio,2);
            echo "<br><h1> aplicarComparacionLugarEquivalente() </h1>";
            $this->aplicarComparacionLugarEquivalente($this->aleatorio,3,$this->ambito);
            $this->estadisticasAgente(0);
        }
        /*
         * Consulta la memoria del Agente
         * param 1=PorRevisar 0=Revisada -1=Todas
         */
        private function recordarEstadoVias($param){
            $sql = "SELECT * FROM tr016_memoria_agt_revisor WHERE in_por_revisar=$param";
            $result = $this->bd->listarRegistros($sql);
            //var_dump($result);                       
            
            return $result;
        }
        /*
         * Indica que una vía fue revisada por el agente
         */
        private function guardarRevisionVia(){
                        
            echo "<br>Guardando por revisar a $this->ambito";
            $sql = "UPDATE tr016_memoria_agt_revisor SET in_por_revisar=0, in_reportado_inc=0, fe_ult_corrida=:fe_ult_corrida WHERE tx_ambito=:tx_ambito";
            $parametros = array (":tx_ambito" => $this->ambito,
                                ':fe_ult_corrida'=>$this->horaInicio->format("Y-m-d H:i:s")
                          );
            $con = $this->bd->obtenerConexion();						
            $stmt = $con->prepare($sql);
            $stmt->execute($parametros);        
            return $con->lastInsertId();
            
            
        }
        /*
         * Selecciona una vía que esté en estado PorRevisasr
         */
        private function seleccionarViaRevisar(){
            $porRevisar = 1;
            $vias_candidatas = $this->recordarEstadoVias($porRevisar);
            //Sino hay vías por revisar entonces se inicializan nuevamente
            if ($vias_candidatas==null){
               $this->inicializarRevisionVias();
               return null;
            }
            $vias_priorizadas = array();
            
            /*Revisando si hay alguna prioridad*/
            for($i=0;$i<sizeof($vias_candidatas);$i++){
               if($vias_candidatas[$i]['in_reportado_inc']==1) {
                   array_push($vias_priorizadas, $vias_candidatas[$i]);
               }
            }                  
            //Si hay alguna prioridad
            if (sizeof($vias_priorizadas)>0){
                $vias_candidatas = $vias_priorizadas;
            }
            
            $cant_vias = sizeof($vias_candidatas);
            $indiceViaAleatoria = mt_rand(0, $cant_vias-1);
            $viaAleatoria = $vias_candidatas[$indiceViaAleatoria]['tx_ambito'];       
            
            echo $viaAleatoria;
            $this->ambito = $viaAleatoria;
            /*var_dump($vias_candidatas);
            die();*/
            return $viaAleatoria;
            
        }
        /*
         * Ejecuta los métodos de revisión para un ámbito en particular
         */
        public function revisarVia(){
            $this->ambito = $this->seleccionarViaRevisar();
            if ($this->ambito==null){
                return null;
            }
            $this->aplicarMetodosRevision();
            $this->guardarRevisionVia();
            return;
        }
        /*
         * Coloca a todas las vías Revisada a PorRevisar
         */
        private function inicializarRevisionVias(){
            $sql  = "UPDATE tr016_memoria_agt_revisor SET in_por_revisar=1"; 
            $con = $this->bd->obtenerConexion();						
            $stmt = $con->prepare($sql);
            $stmt->execute();        
            return $con->lastInsertId();
            
        }
        /*
         * Informa a la memoria del Agente Revisor que se ha extraido datos sobre una vía (ámbito)
         */
        public function reportarIncidenteEnVia($ambito){
            $horaReporte   = new DateTime("now");            
            $sql = "UPDATE tr016_memoria_agt_revisor SET in_reportado_inc=1, fe_reporte_inc=:fe_reporte_inc WHERE tx_ambito=:tx_ambito";
            $parametros = array (":tx_ambito" => $ambito,
                                ':fe_reporte_inc'=>$horaReporte->format("Y-m-d H:i:s")
                          );
            $con = $this->bd->obtenerConexion();						
            $stmt = $con->prepare($sql);
            $stmt->execute($parametros);        
            return $con->lastInsertId();
        }

        /*
         * Metodo para recuperar los tuits de interés desde la BD 
         * @param $cantTuits=50 cantidad de tuits a recuperar
         * @param $aleatorio=0 indica que son los últimos tuits
         * @param $referenciado=1 indica que están geograficamente referenciados
         * return array $this->tuits_guardados
         */
        private function inicializarTuits($cantTuits=50, $aleatorio=0, $referenciado=1,$ambito="#PNM"){
            
                       
            $total_tuits    =0;
            $inicio         =0;
            $campos         = " id,id_tweet,text,date,user_id,km_aprox,clase_incidente ";
            $where          = " WHERE tx_ambito ='$ambito' ";
            $order_by       = " ORDER BY date ASC ";
            
            /*Referente a la Geolocalización*/
            /* Referencia indica si busco tuits que tengan una localización establecida
             * o si busco aquellos que no han podido ser ubicados
             */
            if ($referenciado==1){
                $total_tuits = $this->twitter->total_tuits_interes_ambito($ambito);                
            }  else {
                $total_tuits = $this->twitter->total_tuits_interes_sin_localizacion();// revisar el ambito
                //$where = " WHERE lugar = '0' and tx_ambito ='$ambito' ";                
                $where = $where." and lugar = '0' ";                
            }                    
            
            /*Referente al aspecto aleatorio*/
            if($aleatorio==0){  // Caso No Aleatorio                          
                $inicio = $total_tuits - $cantTuits;
            }  else { // Caso Aleatorio     		
		$inicio = mt_rand(1, $total_tuits);
                if ($inicio == $total_tuits) {$inicio = 1;} //en el caso que se asigne el total de tuits al limite inicial	
            }
            
            $sql  = "SELECT $campos FROM tr002_tweet_interes $where $order_by limit $inicio, $cantTuits";           
            //Para usar los tuits sintéticos comentar 
            //include_once("../config/palabras_interes.php");
            //$this->tuits_guardados = $tuits_guardados;             
            $this->tuits_guardados      = $this->bd->listarRegistros($sql);                        
            
            $this->tam_estudio = sizeof($this->tuits_guardados);
            $this->formatearPatrones();
            
            echo "<br>Inicializando arreglo de tuits";
            echo "<br>Cantidad de tuits:".$this->tam_estudio;
            echo "<br>Fecha primer tuit:".$this->tuits_guardados[0]["date"];
            echo "<br>Fecha último tuit:".$this->tuits_guardados[$cantTuits-1]["date"];
            
            return $this->tuits_guardados;
        }
        /*
         *Obtiene la parte inicial del arreglo de las menciones, no incluye el @ 
         * Se emplea para identificar las palabras clave
         */
        private function formatearPatrones(){
            $this->patrones_formateados = $this->patrones;
            for($i=0;$i<$this->cant_patrones;$i++){
                $cadena = $this->patrones_formateados[$i];
                $pos_arroba = strpos($cadena, "@");
                if($pos_arroba!== FALSE){
                    $tam_cadena = strlen($cadena);
                    $cadena = substr($cadena, 0, $tam_cadena -($tam_cadena-$pos_arroba));
                }
                $cadena = trim($cadena);
                $this->patrones_formateados[$i] = $cadena;
            }
            //var_dump($this->patrones_formateados);
        }


        /*
     * Formatea los tuits en un nuevo arreglo (minusculas, menciones, usuarios
     * rutas)
     */
        private function formatearTuits(){
            echo "<br>Formateo del arreglo de tuits a analizar";
            //echo "<br>Tamaño del arreglo de tuits: ". $this->tam_estudio;
            $this->normalizar_tuits();
            $this->quitar_menciones_tuits();
            $this->quitar_usuarios_tuits();
            $this->quitar_rutas_tuits();
            $this->quitar_etiquetas_tuits();
        }
        
     /*
     * Recorre todo el arreglo de tuits, los hace minusculas, quita acentos,
     * elimina los espacios en blanco al inicio y al final
     */
    private function normalizar_tuits(){
        echo "<br><br><b>1. Haciendo minusculas todos los tuits</b>";
        $this->tuits_normalizados   = $this->tuits_guardados;
        for($i=0;$i<$this->tam_estudio;$i++){
            $this->tuits_normalizados[$i]["text"] = $this->cadena->formatearCadena($this->tuits_guardados[$i]["text"]);
        }
        //var_dump($this->tuits_procesados);
    }
    
    /*
     * Recorre todo el arreglo de tuits y elimina las menciones (via, rt...)
     */
    private function quitar_menciones_tuits(){
        echo "<br><br><b>2. Quitando menciones todos los tuits</b>";
        $this->tuits_procesados = $this->tuits_normalizados;
        for($i=0;$i<$this->tam_estudio;$i++){
            if($this->depuracion) {echo "<br>Tuit: ".$i;}
            $resul = $this->tieneMenciones($this->tuits_normalizados[$i]["text"]);
            
            if( $resul == TRUE){
                $this->tuits_procesados[$i]["text"] = $this->eliminarMencion($this->tuits_normalizados[$i]["text"]);
            }
        }
    }
    /*
     * Recorre todo el arreglo de tuits y elimina los usuarios @
     */
    private function quitar_usuarios_tuits(){
        echo "<br><br><b>3. Eliminando usuarios de todos los tuits</b>";
        for($i=0;$i<$this->tam_estudio;$i++){
            if($this->depuracion) {echo "<br>Tuit: ".$i;}
            $resul = $this->tieneUsuarios($this->tuits_procesados[$i]["text"]);
            
            if( $resul == TRUE){
            
                $this->tuits_procesados[$i]["text"] = $this->eliminarUsuario($this->tuits_procesados[$i]["text"]);
            }
        }
    }
    /*
     * Recorre todo el arreglo de tuits y elimina las rutas http
     */
    private function quitar_rutas_tuits(){
        echo "<br><br><b>4. Eliminando rutas de todos los tuits</b>";
        for($i=0;$i<$this->tam_estudio;$i++){
            if($this->depuracion) {echo "<br>Tuit: ".$i;}
            $resul = $this->tieneRutas($this->tuits_procesados[$i]["text"]);
            
            if( $resul == true){
            
                $this->tuits_procesados[$i]["text"] = $this->eliminarRuta($this->tuits_procesados[$i]["text"]);
            }
        }
    }
    /*
     * Recorre todo el arreglo de tuits y elimina las etiquetas #
     */
    private function quitar_etiquetas_tuits(){
        echo "<br><br><b>5. Eliminando etiquetas de todos los tuits</b>";
        for($i=0;$i<$this->tam_estudio;$i++){
            if($this->depuracion) {echo "<br>Tuit: ".$i;}
            $resul = $this->tieneEtiquetas($this->tuits_procesados[$i]["text"]);
            
            if( $resul == true){
            
                $this->tuits_procesados[$i]["text"] = $this->eliminarEtiqueta($this->tuits_procesados[$i]["text"]);
            }
        }
    }
    
    /*
     * Estadisticas del Agente
     */
    private function estadisticasAgente($inicio){
        $sql;
        $parametros;        
        //Caso inicialización
        if($inicio==1){
            $this->horaInicio   = new DateTime("now");   
            $this->tiempoInicio = microtime(true);
            $sql = "INSERT INTO tr009_estadisticas_corridas_agt_revisor (fe_corrida, in_aleatorio, tx_ambito, tx_via_ejecucion) VALUES (:fe_corrida, :in_aleatorio, :tx_ambito, :tx_via_ejecucion)";
            $parametros = array(                                            
                            ':fe_corrida'=>$this->horaInicio->format("Y-m-d H:i:s"),
                            ':in_aleatorio'=>$this->aleatorio,                                
                            ':tx_ambito'=>$this->ambito,
                            ':tx_via_ejecucion'=>$this->via_ejec
                               );
            }        
        else{//Caso finalización
            $this->horaFin     = new DateTime("now");
            $this->tiempoFin   = microtime(true);        
            $microsegundos = $this->tiempoFin - $this->tiempoInicio;
            $sql = "UPDATE tr009_estadisticas_corridas_agt_revisor SET ca_segs_ejec=:ca_segs_ejec WHERE id_corrida=:id_corrida";	
            $parametros = array(                                            
                            ':ca_segs_ejec'=>$microsegundos,
                            ':id_corrida'=>$this->id_corrida_agt
                               );
            
            echo "<h1>Estadísticas del Agente Revisor</h1>";
            echo "<br>Fecha inicio: ".$this->horaInicio->format('d/m/Y H:i:s');
            echo "<br>Fecha fin: ".$this->horaFin->format('d/m/Y H:i:s');        
            echo "<br>Tiempo de ejecución: ".$microsegundos." segundos";
            echo "<br><h1>Fin de Estadísticas</h1><br>";
            
            }
        $con = $this->bd->obtenerConexion();						
        $stmt = $con->prepare($sql);
        $stmt->execute($parametros);        
        return $con->lastInsertId();
    }
    /*
     * Estadisticas de Metodo
     */
    private function estadisticasMetodo($metodo,$param,$aleatorio,$arreglo_tuits){
        $this->horaFin_metd     = new DateTime("now");
        $this->tiempoFin_metd   = microtime(true);        
        $microsegundos = $this->tiempoFin_metd - $this->tiempoInicio_metd;
        $tam = sizeof($arreglo_tuits);
        echo "<h1>Estadísticas Método</h1>";
        echo "<br>Método: ".$metodo;
        echo "<br>Fecha inicio: ".$this->horaInicio_metd->format('d/m/Y H:i:s');
        echo "<br>Fecha fin: ".$this->horaFin_metd->format('d/m/Y H:i:s');        
        echo "<br>Tiempo de ejecución: ".$microsegundos." segundos";
        echo "<br>Cantidad de tuits eliminados: ".$tam;
        echo "<br>Tuits eliminados:";
         for ($i=0;$i<$tam ; $i++){            
            echo "<br>$i: ".$arreglo_tuits[$i]["text"]." -- ID tuit: ".$arreglo_tuits[$i]["id_tweet"];                        
        }
        
        $sql = "INSERT INTO tr010_estadisticas_corridas_metodos_agt_revisor (tx_metodo,tx_param, fe_corrida,ca_segs_ejec,ca_tuit_eliminados,id_corrida,in_aleatorio) VALUES (:tx_metodo,:tx_param,:fe_corrida,:ca_segs_ejec,:ca_tuit_eliminados,:id_corrida,:in_aleatorio)";	
			
			
        $con = $this->bd->obtenerConexion();						
        $stmt = $con->prepare($sql);

        $parametros = array(':tx_metodo'=>$metodo,                                            
                            ':fe_corrida'=>$this->horaInicio_metd->format("Y-m-d H:i:s"),                                                                                        
                            ':ca_segs_ejec'=>$microsegundos,
                            ':ca_tuit_eliminados'=>$tam,
                            ':tx_param'=>$param,
                            ':id_corrida'=>$this->id_corrida_agt,
                            ':in_aleatorio'=>$aleatorio                                
                            );

        $stmt->execute($parametros);
        echo "<br><h1>Fin de Estadísticas</h1><br>";
        return $lastId = $con->lastInsertId();
        
    }
    
    /*
     * Recorre todo el arreglo de tuits y elimina las rutas http
     */
    private function aplicarComparacionSubCadena($aleatorio){
        $this->horaInicio_metd   = new DateTime("now");
        $this->tiempoInicio_metd = microtime(true);
        /*****************************************************************/
        /********Se establecen los tuits a utilizar en el método**********/
        /*****************************************************************/
        if ($aleatorio==0){
                    $this->inicializarTuits(50, 0, 1);
                }else{
                    $this->inicializarTuits(60, 1, 1);
        }
        $this->formatearTuits();
        
        
        echo "<br><br><b>Aplicando la comparacion de SubCadena a :".sizeof($this->tuits_procesados)."</b>";
        
        $resultado = array();
        for($i=0; $i< sizeof($this->tuits_procesados);$i++){
            if(1) {echo "<br>Tuit: ".$i;}            
            $cant_palabras=7;
            $inicio = 3;
            $arreglo = $this->compararSubCadena($this->tuits_procesados[$i],$cant_palabras,$inicio);
            if($arreglo!=NULL){
                array_push($resultado, $arreglo[0]);
            }
            //var_dump($arreglo);
            
        }       
        
        $this->eliminarArregloTweetInteres($resultado);
        $param = "cant_palabras=".$cant_palabras.",inicio=".$inicio;
        $id_corrida_met = $this->estadisticasMetodo("aplicarComparacionSubCadena()",$param,$aleatorio, $resultado);
        $this->agregarHistoricoTweetInteresEliminados($resultado, $this->id_corrida_agt,$id_corrida_met);
        //var_dump($resultado);
        
        
        
        
    }
    
    /*
     * Elias C: 
     * Este método permite hacer una comparación por tres criterios
     * Criterio 1: lugar donde ocurre el incidente
     * Criterio 2: rango de horas para realizar la comparación
     * Criterio 3: tipo de incidente
     * @param  $param cantidad de horas para buscar
     * @param  $aleatorio indica si buscará tuits aleatorios en la BD
     */
    private function aplicarComparacionLugarEquivalente($aleatorio,$param,$ambito="#PNM"){
        $this->horaInicio_metd   = new DateTime("now");
        $this->tiempoInicio_metd = microtime(true);
        echo "<br>**************************************************************************";
        echo "<br>** Método: aplicarComparacionLugarEquivalente (aleatorio=$aleatorio, param=$param)*****";
        echo "<br>*************************************************************************<br>";
        /*****************************************************************/
        /********Se establecen los tuits a utilizar en el método**********/
        /*****************************************************************/
        // Se emplea el atributo global $this->tuits_guardados
        if ($aleatorio==0){
                    $this->inicializarTuits(50, 0, 1,$ambito);
        }else{
                    $this->inicializarTuits(60, 1, 1,$ambito);
        }       
        
        $tot = sizeof($this->tuits_guardados);
        $retorno = array();
        for($i=0;$i<$tot;$i++){
            
            if ($this->tuits_guardados[$i] != NULL &&
                    $this->tuits_guardados[$i]['km_aprox']!=NULL &&
                    $this->tuits_guardados[$i]['km_aprox']!='99') // km_aprox']!='99' es un lugar destinado como errado
                {
                $km_aprox           = $this->tuits_guardados[$i]['km_aprox'];
                $clase_incidente    = $this->tuits_guardados[$i]['clase_incidente'];
                $fecha_str          = $this->tuits_guardados[$i]['date'];
                $fecha              = new DateTime($fecha_str);
                $id_tuit = $this->tuits_guardados[$i]['id_tweet'];
                echo "<br><b>Iteración $i</b>";
                echo "<br><h1>Tuit en estuddio: ".$this->tuits_guardados[$i]["text"]. "- ID Tuit: ".$id_tuit."- Fecha: ".$fecha_str." Km_aprox $km_aprox</h1>";
                

                for($j=$i+1;$j<$tot;$j++){
                    echo "<br><b>Comparación $i-$j</b>";
                    echo "<br>Tuit: ".$this->tuits_guardados[$j]["text"].
                            "- ID Tuit: ".$this->tuits_guardados[$j]['id_tweet'].
                            "- Fecha: ".$this->tuits_guardados[$j]['date'].
                            " Km_aprox ".$this->tuits_guardados[$j]['km_aprox'];
                    
                    $fecha_comp_str = $this->tuits_guardados[$j]['date'];
                    $fecha_comp     = new DateTime($fecha_comp_str);
                    $hora           = date_diff($fecha, $fecha_comp, true)->format('%h');
                    $dia            = date_diff($fecha, $fecha_comp, true)->format('%d');
                    $mes            = date_diff($fecha, $fecha_comp, true)->format('%m');
                    $anio           = date_diff($fecha, $fecha_comp, true)->format('%y');
                    echo "<br>Rango de años:". $anio." años";
                    echo "<br>Rango de meses:". $mes." meses";
                    echo "<br>Rango de días:". $dia." días";
                    echo "<br>Rango de horas:". $hora." horas";
                    /*************************************************/
                    /* Se compara que sea el mismo km aproximado y
                     * la misma clase de incidente y
                     * que no tenga mas cierta (param) cantidad de horas de transcurrido
                     */
                    if($km_aprox == $this->tuits_guardados[$j]['km_aprox'] &&
                            $clase_incidente == $this->tuits_guardados[$j]['clase_incidente'] &&
                            $dia ==0 &&
                            $mes == 0 &&
                            $anio == 0 &&
                            $hora <= $param  ){
                                               
                        echo "<br><b>ENCONTRADo lugar equivalente y la misma clase de incidente: ".$this->tuits_guardados[$j]["km_aprox"]."</b>";            
                        $result=$this->eliminarTweetInteres($this->tuits_guardados[$j]["id_tweet"],$this->tuits_guardados[$j]["text"]);
                        //if($result==TRUE){	    
                            $valores = array(   "id_tweet" => $this->tuits_guardados[$j]["id_tweet"],
                                                    "text" => $this->tuits_guardados[$j]["text"],
                                                     "date" => $this->tuits_guardados[$j]["date"],
                                                     "user_id" => $this->tuits_guardados[$j]["user_id"],
                                                     "id_tweet_eliminador" => $id_tuit
                                                     );                 
                            array_push($retorno, $valores);
                            echo "<br>Eliminando posicion: ".$j. "-";                                    
                            unset($this->tuits_guardados[$j]);                   
                        //}

                    }
                }
            }else{
                echo "<br><b>Tuit eliminado anteriomente: ".$i. "</b>";                                    
            }
            
            
            
        }
        
        $parametros = "param=".$param.",via_ejecucion=".$this->via_ejec.",ambito=".$this->ambito;
        $id_corrida_met = $this->estadisticasMetodo("aplicarComparacionLugarEquivalente()",$parametros,$aleatorio, $retorno);                
        $this->agregarHistoricoTweetInteresEliminados($retorno, $this->id_corrida_agt,$id_corrida_met);
        return $retorno;
        
    }
    /*
     * Indica la cantidad de elementos que tiene un tuit verificando los espa-
     * cios en blanco de la cadena
     */     
    
    /*private function cantElementos($tuit){
        //$elementos = array();
        $elementos = explode(" ", $tuit);
        $cant = sizeof($elementos);
        echo "<br>Cantidad de elementos: ".$cant;
        return $cant;
    }*/
    
     /*
     * Revisa un tuit e indica si tiene una mención 'via @' 'retweeted' 'rt'
     * 
     */
    public function tieneMenciones($tuit){
                
        $tieneMension = FALSE;
        for($i=0;$i<$this->cant_patrones;$i++){
            $result = strpos($tuit, $this->patrones[$i]);
            if ($result!== FALSE){
                $tieneMension=TRUE;
                break;
            }
        }        
        
        if($tieneMension==TRUE){
            if($this->depuracion) {echo "<br> Tiene mención: ".$tuit;}            
        }
        else{
            if($this->depuracion) {echo "<br> No tiene mención: ".$tuit;}
        }       
        return $tieneMension;
    }
    /*
     * Remueve las menciones en un tuit particular
     */
    private function eliminarMencion($tuit){
        $indices = $this->UbicacionMencion($tuit);
        $menciones = $indices[0];
        $cant_menciones = sizeof($menciones);
        $usuarios = $indices[1];
        $cant_usuarios = sizeof($usuarios);
        $arreglo_tuit = explode(" ", $tuit);
        
        
        if($cant_menciones!=$cant_usuarios){
            if($this->depuracion) {echo "<br>Hay una diferencia entre cantidad de menciones y usuarios";}
        }       
        
        for($i=0;$i<$cant_menciones;$i++){
            $indice_inicio = key($menciones);
            $indice_fin = key($usuarios);
            if ($indice_fin==null){
                break;
            }
            
            if($indice_inicio>$indice_fin){
                $aux = $indice_inicio;
                $indice_inicio = $indice_fin;
                $indice_fin = $aux;
            }
            
            for($j=$indice_inicio;$j<=$indice_fin;$j++){
                unset($arreglo_tuit[$j]);
            }
            
            next($menciones);
            next($usuarios);
        }
        $nuevo_tuit = implode(" ", $arreglo_tuit);
        if($this->depuracion) {echo "<br>Nuevo Tuit: ".$nuevo_tuit;}
        return $nuevo_tuit;        
    }
    
    /*
     * Ubica las menciones en tuits particular
     * retorna una arreglo de dos posiciones
     * posicion 1: arreglo con los índices donde inicia la(s) mencion(es)
     * posicion 2: arreglo con los índices donde finalizan la(s) mencion(es)
     */
    private function UbicacionMencion($tuit){
                
        //$patrones_formateados = $this->patrones;   
        
                
       
        $patrones_usuario = array ("@");
        
        $arreglo_tuit = explode(" ", $tuit);
        $cant_elem = sizeof($arreglo_tuit);
        $menciones = array_intersect($arreglo_tuit,$this->patrones_formateados);
        //print_r($menciones);
        $usuarios_menciones = array();
        $i=0;
        foreach ($arreglo_tuit as $t){
            $tiene_arroba = strpos($t, "@");            
            if($tiene_arroba!==FALSE){                                
                $usuarios_menciones[$i]=$t;
            }
            $i++;
        }        
        
        //print_r($usuarios_menciones);
        
        $result = array($menciones, $usuarios_menciones);
        
        return $result;
        
         
    }
    
    /*
     * Indica si un tuit particular tiene al menos un usuario @
     * 
     */
    private function tieneUsuarios($tuit){
        $bandera = -1;
        $bandera = substr_count($tuit,"@");
        if ($bandera!=0){
            if($this->depuracion) {echo "<br>Tiene usuarios ";}
            return TRUE;
        }
        else{
            if($this->depuracion) {echo "<br>No tiene usuario ";}
            return FALSE;
        }
    }
    /*
     * Elimina los usuarios de un tuit particular @
     * 
     */
    private function eliminarUsuario($tuit){
        $arreglo_tuit = explode(" ", $tuit);
        $cant_elem = sizeof($arreglo_tuit);        
        for ($j=0;$j<$cant_elem;$j++){
            $tiene_arroba = strpos($arreglo_tuit[$j], "@");            
            if($tiene_arroba!==FALSE){                                
                unset($arreglo_tuit[$j]);
            }            
        }  
        $nuevo_tuit = implode(" ", $arreglo_tuit);
        if($this->depuracion) {
            echo "<br>Tuit con usuario: ".$tuit;
            echo "<br>Tuit sin usuario: ".$nuevo_tuit;
        }
        return $nuevo_tuit;
    }
    /*
     * Elimina las rutas de un tuit particular http
     * 
     */
    private function eliminarRuta($tuit){
        $arreglo_tuit = explode(" ", $tuit);
        $cant_elem = sizeof($arreglo_tuit);        
        for ($j=0;$j<$cant_elem;$j++){
            $tiene_ruta = strpos($arreglo_tuit[$j], "http");            
            if($tiene_ruta!==FALSE){                                
                unset($arreglo_tuit[$j]);
            }            
        }  
        $nuevo_tuit = implode(" ", $arreglo_tuit);
        if($this->depuracion) {
            echo "<br>Tuit con ruta: ".$tuit;
            echo "<br>Tuit sin ruta: ".$nuevo_tuit;
        }
        return $nuevo_tuit;
    }
    
    /*
     * Elimina las rutas de un tuit particular http
     * 
     */
    private function eliminarEtiqueta($tuit){
        $arreglo_tuit = explode(" ", $tuit);
        $cant_elem = sizeof($arreglo_tuit);        
        for ($j=0;$j<$cant_elem;$j++){
            $tiene_etiqueta = strpos($arreglo_tuit[$j], "#");            
            if($tiene_etiqueta!==FALSE){                                
                unset($arreglo_tuit[$j]);
            }            
        }  
        $nuevo_tuit = implode(" ", $arreglo_tuit);
        if($this->depuracion) {
            echo "<br>Tuit con etiqueta: ".$tuit;
            echo "<br>Tuit sin etiqueta: ".$nuevo_tuit;
        }
        return $nuevo_tuit;
    }
     /*
     * Indica si un tuit particular tiene ruta http
     * 
     */
    private function tieneRutas($tuit){
        
        $bandera = substr_count($tuit,"http");
        if ($bandera!=0){
            if ($this->depuracion) {echo "<br>Tiene ruta ";}
            return TRUE;
        }
        else{
            if ($this->depuracion) {echo "<br>No tiene ruta ";}
            return FALSE;
        }
    }
    
    /*
     * Indica si un tuit particular tiene al menos una Etiqueta #
     * 
     */
    private function tieneEtiquetas($tuit){
        
        $bandera = substr_count($tuit,"#");
        if ($bandera!=0){
            if($this->depuracion) {echo "<br>Tiene etiqueta ";}
            return TRUE;
        }
        else{
            if($this->depuracion) {echo "<br>No tiene etiqueta ";}
            return FALSE;
        }
    }
    
    /*
     * Método para comparar un tuit particular contra el arreglo de tuits general
     * @param tuit = tuit a buscar
     * @param cant_palabras = cantidad de palabras a considerar
     * @param inicio = indice de inicio dentro del arreglo (offset)
     * @return un arreglo con los {id_tweet, text} que coinciden con tuit
     */
    private function compararSubCadena($tuit,$cant_palabras=7,$inicio = 3){
        
        echo "<br>******************************************************************";
        echo "<br>** Método: compararSubCadena(Cant_palabras=$cant_palabras,Inicio=$inicio****";
        echo "<br>******************************************************************<br>";
        if($tuit!=NULL){
            $tuit_estudio = $tuit;
            $elementos_estudio = explode(" ", $tuit_estudio["text"]);        
            $cant_palabras_estudio = sizeof($elementos_estudio);
            if ($cant_palabras_estudio< ($cant_palabras+$inicio) ){
                
                if($cant_palabras_estudio < $cant_palabras){
                    $cant_palabras = $cant_palabras_estudio-1;
                }
                    
                $inicio=0;
                echo "<br><b>Ajustando parametros: cantidad de palabras:".$cant_palabras." - inicio: ".$inicio."</b>";
            }

            /*********Definiendo la subcadena de busqueda*****************/        
            $contador_palabras = 0; //contador hasta llegar a la cantidad de palabras        
            $sub_cadena = "";
            while ($contador_palabras<$cant_palabras){
                $sub_cadena = $sub_cadena ." ". $elementos_estudio[$inicio];
                $inicio++;
                $contador_palabras++;
            }
            echo "<br><b>Tuit: ".$tuit_estudio["text"]. "- ID Tuit: ".$tuit_estudio["id_tweet"]."- Fecha: ".$tuit_estudio["date"]."</b>";
            
            
            echo "<br><b>Tamaño del arreglo: ".sizeof($this->tuits_procesados)."</b>";
            echo "<br>Subcadena de comparacion: ".$sub_cadena;
            /*********Iterando la subcadena dentro del arreglo de estudio*********/
            $k=0; //Cantidad de coincidencias
            $retorno = array();
            for($j=0;$j< sizeof($this->tuits_procesados);$j++){

                if ($this->tuits_procesados[$j]!=NULL){                
                    echo "<br>Tuit: ".$j;
                    echo "<br>Tuit a comparar: ".$this->tuits_procesados[$j]["text"].
                                                "- ID Tuit: ".$this->tuits_procesados[$j]["id_tweet"].
                                                "- Fecha : ".$this->tuits_procesados[$j]["date"];            
                    //$this->cantElementos($this->tuits_procesados[$j][text]);            

                    $result = strpos($this->tuits_procesados[$j]["text"],$sub_cadena);

                    if($result !=false && 
                       $tuit_estudio["id_tweet"]!= $this->tuits_procesados[$j]["id_tweet"]){

                        echo "<br><b>ENCONTRADA subcadena: ".$sub_cadena. "</b> en: ".$this->tuits_guardados[$j]["text"];            
                        $k++;                        
                        $valores = array(   "id_tweet" => $this->tuits_guardados[$j]["id_tweet"],
                                            "text" => $this->tuits_guardados[$j]["text"],
                                             "date" => $this->tuits_guardados[$j]["date"],
                                             "user_id" => $this->tuits_guardados[$j]["user_id"],
                                             "id_tweet_eliminador" => $tuit_estudio["id_tweet"]
                                             );                 
                        array_push($retorno, $valores);
                        echo "<br><b>Eliminando posicion: ".$j. "</b>";            
                        unset($this->tuits_procesados[$j]);
                        unset($this->tuits_guardados[$j]);
                    }
                }

            }
            echo "<br><br><b>Cantidad de coincidencias: ".$k."</b><br>";
            //var_dump($retorno);
            return $retorno;
        }else{
            echo "<br><br><b>Tuit nulo, siguiente comparacion...</b><br>";
            return NULL;
        }
            
    }
    
    
    
   	 /**
   	 *
   	 * Encontrar las palabras claves dentro de un tuit relacionadas a su ubicacion geografica
   	 */
   	 public function extraerUbicacionTweet($tweet){ 
  
  
  		$encontrado = false;
  		//var_dump($this->lugares_interes);
  		foreach ($this->lugares_interes as $clave=>$valor) {
    
     		//echo "<br>"."id= ".$tweet."palabra= ".$clave;
    		if (strpos($tweet, $clave) !== false) {    		
     		//if (strripos($this->tweet, $clave) !== false) {    		
        		 //echo "<br> $clave";
        			 $encontrado = $valor;       
         		//break;      
   		 }
   		}//fin foreach
  
   		return $encontrado; 
  

	}	
   	 
   	 
   	/**
   	*
   	* Reclasificar o relocalizar los tuits existenes en la base de datos que no tienen ubicación 
   	*/
   	public function relocalizarTweet(){
   	
   		/*$total_tuits = $this->twitter->total_tuits_interes_sin_localizacion();
   		
		$limit_i = rand(1, $total_tuits);
		if ($limit_i == $total_tuits) $limit_i = 1; //en el caso que se asigne el total de tuits al limite inicial		 	
		$sql  = "SELECT * FROM tr002_tweet_interes WHERE lugar = '0' limit $limit_i,30 ";
		echo $sql;
   		$tweet_bd =  $this->bd->listarRegistros($sql);
		$tot = count($tweet_bd);*/
                $this->inicializarTuits(50, 1, 0);
                $this->normalizar_tuits();
                $tweet_bd = $this->tuits_normalizados;
		$tot = $this->tam_estudio;//count($tweet_bd);
            
		
                //$ids_tweets = array();	
		echo "<br><b> Relocalizar Tuits </b><br>";
		
		for($i=0; $i < $tot; $i++)
		{	
			$tweet = $tweet_bd[$i];
			
			$text = $tweet['text'];//$this->cadena->formatearCadena($tweet['text']);
			$idTweet = $tweet['id_tweet'];
			
			$lugar = $this->extraerUbicacionTweet($text);
			echo "<br>".$idTweet." - ".$text;
			//echo "<br>";
			if(is_array($lugar)){
				echo "<br /><b> TGeo añadido </b><br />";
				$this->twitter->modificarGeoTweetInteres($idTweet,$lugar["lat"],$lugar["lon"],$lugar["etiqueta"],$lugar["clase_lugar"]);
					
			}
			
 		}
 		
 	} 
   	 
   	/**
   	*
   	* Revisa los tweets repetidos en BD y los elimina para evitar errores en las estadisticas
   	*/
   	private function aplicarDiferenciaArreglos($aleatorio=0,$param){
   		$this->horaInicio_metd = new DateTime("now");
                $this->tiempoInicio_metd = microtime(true);	 	
		echo "<br>**************************************************************************";
                echo "<br>** Método: compararDiferenciaArreglos (aleatorio=$aleatorio, param=$param)*****";
                echo "<br>*************************************************************************<br>";
                if ($aleatorio==0){
                    $this->inicializarTuits(50, 0, 1);
                }else{
                    $this->inicializarTuits(60, 1, 1);
                }
                    
                $this->normalizar_tuits();
                $tweet_bd = $this->tuits_normalizados;
		$tot = $this->tam_estudio;//count($tweet_bd);
		
		
                $retorno_final = array();
		for($i=0; $i < $tot; $i++)
		{	
			$retorno = array();	
			for($j=$i+1;$j<$tot;$j++){
 				
                            if ($tweet_bd[$i]!=NULL && $tweet_bd[$j]!=NULL){
                                //Tuit que podría ser eliminado
 				$tweet      = $tweet_bd[$i]; 				
                                $id_tweet   = $tweet["id_tweet"];	
 				$text       = $tweet['text'];
                                $date       = $tweet["date"];	
                                //Tuit que está iterando
                                $tweet_1    = $tweet_bd[$j]; 	
                                $text_1     = $tweet_1['text'];
                                $id_tweet_1 = $tweet_1['id_tweet'];
                                $user_id_1  = $tweet_1['user_id'];
                                $date_1     = $tweet_1["date"];
                                
                                echo "<br><br>Comparacion $i-$j:<br>T1: ".$text." - ID Tuit: ".$id_tweet;
 				echo "<br>T2: ".$text_1." - ID Tuit: ".$id_tweet_1;
 				echo "<br>id tuits:  $id_tweet ";
 				echo "<br> fecha: $date  ";
 				//Separar texto por espacios a un arreglo
 				$bolsaPalabras  = preg_split('/\s+/', $text); 			
 				$bolsaPalabras_1= preg_split('/\s+/', $text_1); 			
 			
 				echo "<br>Caracteres especiales T1";
 				$bolsaPalabras = $this->cadena->extraerCaracteresEspeciales($this->caracteres_interes,$bolsaPalabras);
 				echo "<br>Caracteres especiales T2";
 				$bolsaPalabras_1 = $this->cadena->extraerCaracteresEspeciales($this->caracteres_interes,$bolsaPalabras_1);
 				
 				echo "<br>Cadenas resultantes: ";
 				$t1 = implode(" ", $bolsaPalabras);
 				echo "<br>T1 resultantes: $t1";
 				$t2 = implode(" ", $bolsaPalabras_1);
 				echo "<br>T2 resultantes: $t2";				
 				 			
 				$resultado = array_diff($bolsaPalabras, $bolsaPalabras_1);
 				echo "<br>Resultado de la difrencia de los arreglos 1 y 2: ".count($resultado)."<br>";
                                var_dump($resultado);
 				//echo "<br> Tot: ".count($resultado)."<br>";
                                $no_coincidentes = count($resultado);
 				if( $no_coincidentes<=$param ){ // son arreglos con a lo sumo 2 palabras diferentes
 				
                                        $result = $this->eliminarTweetInteres($id_tweet_1,$text_1);
                                        
                                        //if($result==TRUE){						
                                            
                                            echo "<br><b>Guardando en arreglo el tuit eliminado</b>";                                            
                                             $valores = array(  "id_tweet"          => $id_tweet_1,
                                                                "text"              => $text_1,
                                                                "date"              =>$date_1,
                                                                "user_id"           => $user_id_1,
                                                                "id_tweet_eliminador" => $id_tweet
                                             ); 
                                            array_push($retorno, $valores);
                                            unset($tweet_bd[$j]);						
					//}
                                        
                                        
 				}		
                        //echo "<br> Arreglo en iteración:$i-$j";
                        //var_dump($retorno);
                            }
                            else{
                                echo "<br><br><b>Comparación $i-$j: Tuit nulo, siguiente comparación...</b><br>"; 
                                echo "T1:".$tweet_bd[$i]."<br>";
                                echo "T2:".$tweet_bd[$j]."<br>";
                            }
 			}
                        //echo "<br> Arreglo en iteración:".$i;
                        //var_dump($retorno);
                        if($retorno!=NULL){
                            array_push($retorno_final, $retorno[0]);
                        }
                        
 		}
		
		//echo "<br>Estado final del arreglo";
                //var_dump($retorno_final);
                $parametros = "param=".$param;
                $id_corrida_met = $this->estadisticasMetodo("compararDiferenciaArreglos()",$parametros,$aleatorio, $retorno_final);                
                $this->agregarHistoricoTweetInteresEliminados($retorno_final, $this->id_corrida_agt,$id_corrida_met);
                return $retorno_final;
	}
   	
   	/*
         * Elimina un tuit de interés de la base de datos
         * @param id_tuit
         * return resultado de la operación
         */
        private function eliminarTweetInteres($id_tweet,$text=""){
            $result = $this->twitter->eliminarTweetInteres($id_tweet);
            
            //$result = TRUE;
            if($result == TRUE){
                echo "<br><b>Tuits eliminado = $id_tweet </b>";                
                require_once('class.Log.php');
                $log = new Log();	
                $msg = "[AGENTE REVISOR] | TE = $id_tweet - $text ";
                $log->general($msg);
            }            
            return $result;
        }
        
        /*
         * Guarda un tuit de interés eliminado con fines históricos
         * @param arreglo tuit
         * @param id_corrida
         * return resultado de la operación
         */
        private function agregarHistoricoTweetInteresEliminados($tuits,$id_corrida,$id_corrida_met){
            if(1){// para depurar
            $tam = sizeof($tuits);
            $result = '';
            for($i=0;$i<$tam;$i++){
                echo "<br>Agregando tuit eliminado en tabla histórica";
                $id_tweet           = $tuits[$i]["id_tweet"];
                $text               = $tuits[$i]["text"];
                $date               = $tuits[$i]["date"];
                $user_id            = $tuits[$i]["user_id"];
                $id_tuit_eliminador = $tuits[$i]["id_tweet_eliminador"];
                $result     = $this->twitter->agregarHistoricoTweetInteresEliminados($id_tweet, $text, $date, $user_id, $id_corrida,$id_tuit_eliminador,$id_corrida_met);
                if($result == TRUE){
                    echo "<br><b>Tuits guardado en histórico = $id_tweet </b>";                
                    require_once('class.Log.php');
                    $log = new Log();	
                    //TGH = Tuit Guardado Histórico
                    $msg = "[AGENTE REVISOR] | TGH = $id_tweet - $text ";
                    $log->general($msg);
                }
            }
            return $result;
            }
            
        }
        
        /*
         * Elimina tuits de interés de la base de datos
         * @param arreglo con tuits
         * return resultado de la operación
         */
        private function eliminarArregloTweetInteres($tuits){
            
            $cant = sizeof($tuits);
            for ($i=0;$i<$cant;$i++){
                $this->eliminarTweetInteres($tuits[$i]["id_tweet"],$tuits[$i]["text"] );
                
            }
            
        }
        /*
         * Retorna el id de la corrida del agente
         * 
         */
        public function getCorrida(){
            return $this->id_corrida_agt;
        }


        
   	/**
   	*
   	* Revisa los tweets repetidos en BD y los elimina para evitar errores en las estadisticas
   	*/
   	public function revisarTweetRepetidosAleatorios(){
   	
   		return NULL;
               /* $this->inicializarTuits(50, 1, 1);
                $this->normalizar_tuits();
                $tweet_bd = $this->tuits_normalizados;
		$tot = $this->tam_estudio;//count($tweet_bd);
                
                $ids_tweets = array();				
		
		for($i=0; $i < $tot; $i++)
		{	
				
			for($j=$i+1;$j<$tot;$j++){
 				
 				$tweet = $tweet_bd[$i];
 				$tweet_1 = $tweet_bd[$j]; 	
 				$id_tweet = $tweet["id_tweet"];	
 				$date = $tweet["date"];	
 				//quitar espacios, acentos y mayusculas	
				echo "<br><br>Comparacion:<br>T1: ".$text = $tweet['text'];//$this->cadena->formatearCadena($tweet['text']);
 				echo "<br>T2: ".$text_1 = $tweet_1['text'];//$this->cadena->formatearCadena($tweet_1['text']);
 				echo "<br> $id_tweet ";
 				echo "<br> $date  ";
 				//Separar texto por espacios a un arreglo
 				$bolsaPalabras = preg_split('/\s+/', $text); 			
 				$bolsaPalabras_1 = preg_split('/\s+/', $text_1); 			
 			
 				echo "<br>Caracteres especiales T1";
 				$bolsaPalabras = $this->cadena->extraerCaracteresEspeciales($this->caracteres_interes,$bolsaPalabras);
 				echo "<br>Caracteres especiales T2";
 				$bolsaPalabras_1 = $this->cadena->extraerCaracteresEspeciales($this->caracteres_interes,$bolsaPalabras_1);
 			
 				$resultado = array_diff($bolsaPalabras, $bolsaPalabras_1);
 				echo "<br>";var_dump($resultado);
 				//echo "<br> Tot: ".count($resultado)."<br>";
 				$no_coincidentes = count($resultado);
 				if( $no_coincidentes== 0 || $no_coincidentes==1 || $no_coincidentes==2 ){ // son arreglos con las mismas palabras
 					
                                    $result = $this->eliminarTweetInteres($id_tweet,$text);
                                    if($result == TRUE){	                                    						
					array_push($ids_tweets, $id_tweet);
                                    }
 				}
 			
			
		
 			}
 		}
		echo "<br>";
                $this->horaFin = new DateTime("now");
                $interval = date_diff($this->horaFin,$this->horaInicio);
                echo "<br><b>Tiempo de ejecución:".$interval->format('%s segundos')."</b>";
		return $ids_tweets;
                */
   	}
   	
   	
   	
}//fin clase

//$a = new AgenteRevisor();
//$a->relocalizarTweet();



?>   	
