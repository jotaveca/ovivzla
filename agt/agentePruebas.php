<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class agentePruebas{
    
    public $tuits_guardados;
    public $tuits_procesados;
    public $tam_estudio;
    public $tuit_estudio;
    public $tuit_comparado;
            
    function __construct() {	
        $this->inicializarTuits();
        $this->formatearTuits();
        
    }
    
    private function inicializarTuits(){
    
        $this->tuits_guardados = array("Retweeted RV 102.1 FM (@rv1021fm): #ReporteVial Colisión múltiple en el Km 4 de la #PNM en dirección a #CCS. NO... https://t.co/LYyrHbQL3K",
                "Retweeted traffic MIRANDA (@trafficMIRANDA): via @rv1021fm: #ReporteVial Colisión múltiple en el Km 4 de la #PNM... https://t.co/fGKBEwVP5h",
                "via @rv1021fm: #ReporteVial Colisión múltiple en el Km 4 de la #PNM en dirección a #CCS. NO HAY PASO. https://t.co/Jn7YKoLgCB #Miranda",
                "@trafficMIRANDA Choque múltiple ocasiónado por camión de asfalto en la curva del hipódromo #PNM https://t.co/ZHo3IcFwLo",
                "#PNM Paramédicos y BDC controlan gran derrame de combustible en accidente del km4. Reporta @JonathanQuantip 5pm https://t.co/wEASZTa1Wd",
                "#PNM 3 lesionados LEVES colisión km4. Paramédicos y BDC en el lugar Reporta @JonathanQuantip 5pm https://t.co/n5mnTB3n90",
                "#PNM No hay paso ni subiendo ni bajando km 4. Colisión múltiple. Reporta @JonathanQuantip 5pm @TrafficEnLaVia https://t.co/yTIytQkxbl",
                "via @Ifsimonbolivar: Fuerte Accidente en la #PNM dirección #ccs a la altura dl #Hipodromo Tomen precauciones! #30Nov #PrioridadTransito",
                "#PNM Colisión múltiple en km 4. 8 vehículos. No hay paso vía ccs.#SISEC #VIAL https://t.co/XjMIiV9O7X",
                "@Traffic_Mix: #Miranda #PNM Fuerte accidente curva del hipódromo https://t.co/F9Lgw3lRRb",
                "Km 4 accidente fortísimo carretera #pnm https://t.co/K7fWoQUHnt ?@LuisCarvalloG https://t.co/4MWjT4DVIn",
                "Km 4 accidente fortísimo carretera #PNM @trafficMIRANDA @traffiCARACAS https://t.co/ePp6xt9pkM",
                "#GpTuTiL RT @Traffic_Mix: #Miranda #PNM Fuerte accidente curva del hipódromo https://t.co/vWVck88oDs https://t.co/oJNLdQFBUc",
                "Via @Traffic_Mix: #Miranda 5:20pm #PNM Fuerte accidente curva del Hipódromo https://t.co/8MgBMzDDhm",
                "via @JVP_Guaicaipuro: Vía @Reporte_RealLTQ Choque múltiple km 4 Tomen sus previsiones. #PNM @trafficVZLA https://t.co/V0GcoXZEsh #Miranda",
                "Fuerte accidente en la panamericana en &tel km4! Tomen vías alternas.#pnm @EUtrafico @FMCENTER https://t.co/PXFEAzNaqC",
                "#PNM 3 lesionados LEVES en colisión km4. Paramédicos y BDC en el lugar. Reporta @JonathanQuantip 5pm @TrafficEnLaVia https://t.co/vKMz9wHAON",
                "via @LuisCarvalloG: Km 4 accidente fortísimo carretera #PNM @trafficMIRANDA https://t.co/hWQK1Hvb61 #Caracas ",
                "(8:17 am) Informa >> @josegpino Camión volcado a la altura del Km 31 en la #PNM vía #LosTeques. https://t.co/ZJpO8tGI7z #Miranda",
                "@victoria1039fm Buenos días, camión volcado a la altura del km 31 #pnm panamericana vía los teques. https://t.co/obQQ8xHTWI ",
                "#ReporteVial1021 Continua la gandola volcada km 30 #PNM sentido #TEJERIAS #LTQ autoridades en el lugar genera retraso en ambos sentidos ",
                "Terios accidentada en #pnm km23 frente a la mitsubishi, canal rápido sentido carrizal. 7.40am @trafficMIRANDA @laregionweb @LaCima96 ",
                "Choque entre automóviles causa tranca vehicular en San Antonio de los Altos. #pnm https://t.co/w6z75t6A9L",
                "Accidente entre carro y moto (creo) en plena entrada de la Macarena Norte #PNM sentido #LTQ 25/11/15 6:10pm",
                "via @ritaalex21282: Accidente entre carro y moto (creo) en plena entrada de la Macarena Norte #PNM sentido #LTQ 6:10pm #Miranda ",
                "Colisión en el Km 10 de la #PNM arroja saldo de dos heridos https://t.co/xqzufYogXO / #Sucesos ",
                " #FueradelaARC #PNM Colision entre Camion y vehiculo liviano genera retraso en el Km 1 a la entrada de la E/S PDV en la Valle Coche",
                "#FueradelaARC #PNM Colisión entre Camión y vehículo liviano genera retraso en el Km 1 a la entrada de la E/S PDV en la Valle Coche.",
                "#FueradelaARC #PNM Colision entre Camion y vehiculo liviano genera retraso en el Km 1 a la entrada de la E/S PDV en la Valle Coche ",
                "#FueradelaARC #PNM Colision entre Camion y vehiculo liviano genera retraso en el Km 1 a la entrada de la E/S PDV en la Valle Coche",
                " via @alvaradoamerico: #PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación 5:20 am",
                "#PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación",
                "#PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación",
                "via @TuGPSRadio: #PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación 5:20 am #Miranda",
                "@TrafficMiranda #PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación @TraficoVC 5:20 am",
                "@TrafficMiranda #PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación @TraficoVC 5:20 am ",
                "via @alvaradoamerico: #PNM km 18 ambulancia accidentada mientras otra la remolca. precaución, luego libre hasta el km 0 4:56 am #Miranda",
                "via @TuGPSRadio: #PNM km 18 ambulancia accidentada mientras otra la remolca. precaución, luego libre hasta el km 0 4:56 am #Miranda",
                "@TrafficMiranda #PNM km 18 ambulancia accidentada mientras otra la remolca. Precaución, luego libre hasta el km 0 4:56 am",
                "@TrafficMiranda #PNM km 18 ambulancia accidentada mientras otra la remolca. Precaución, luego libre hasta el km 0 4:56 am",
                "via @alvaradoamerico: #PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación 5:20 am",
                "#PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación",
                "#PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación",
                "via @TuGPSRadio: #PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación 5:20 am #Miranda",
                "@TrafficMiranda #PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación @TraficoVC 5:20 am",
                "@TrafficMiranda #PNM con retraso para superar camión de agua accidentado en el km 1 #ValleCoche con buena circulación @TraficoVC 5:20 am",
                "via @alvaradoamerico: #PNM Camión cargado con Botellones de agua accidentado en el km 1 genera retraso sentido #Ccs 5:07 am @trafficVZLA",
                "via @TuGPSRadio: #PNM Camión cargado con Botellones de agua accidentado en el km 1 genera retraso sentido #Ccs 5:07 am @trafficVZLA",
                "@TrafficMiranda #PNM Camión cargado con Botellones de agua accidentado en el km 1 genera retraso sentido #Ccs 5:07 am @traficoVC @EUTrafico",
                "@TrafficMiranda #PNM Camión cargado con Botellones de agua accidentado en el km 1 genera retraso sentido #Ccs 5:07 am @traficoVC @EUTrafico",
                "via @SinTantoEstres: #PNM volumen vehicular en los primeros km sentido Los Teques. Vehículo accidentado en el km 6 generando retraso",
                "via @alvaradoamerico: #PNM volumen vehicular en los primeros km sentido Los Teques. Vehículo accidentado en el km 6 generando retraso @_To",
                "@trafficMIRANDA #PNM volumen vehicular en los primeros km sentido Los Teques. Vehículo accidentado en el km 6 generando retraso @traficoVV ",
                " #GpTuTiL via @Jeannette_AS: #PNM carro ford fiesta accidentado antes dl semáforo de la vega precaución #Miranda https://t.co/wSxEuT4p4l",
                "#GpTUtil via @Jeannette_AS: #PNM carro ford fiesta accidentado antes dl semáforo de la vega precaución #Miranda https://t.co/W190oBnmcy  ",
                "via @willroj: #PNM, carro accidentado a la altura dl km 8,no causa mucha congestión, solventado continua con buena circulación @trafficVZLA",
                "Rt #EnLaVia @willroj #PNM carro accidentado a la altura del km 8,no causa mucha congestión, solventado continua con buena circulación 5:43pm",
                "#PNM, carro accidentado a la altura del km 8,no causa mucha congestión, solventado continua con buena circulación @trafficMIRANDA @EUtrafico",   
                "RT @willroj: #PNM Prevenidos Km 26 Motorizado fallecido al estrellarse contra vehículo.",
                "#ReporteVial1021 accidente entre un vehículo y motorizado en el km 26 #PNM #Miranda", 
                "via @TenaShyr: despues de pasar el carro accidentado la #pnm esta libre al menos hasta la casona 7:18 pm #Miranda",
                "via @TenaShyr: la cola en el km 4 de #pnm se debe a un carro accidentado un poco mas adelante, grua ya en el sitio direcc #saa 7:00 pm",
                "#EnLaVia usuarios reportan volcamiento en el km10 de la carretera #PNM #Por Confirmar 7am",
                "@TrafficMiranda #PNM km 10 triple choque. Km 9 camión de agua accidentado ambos accidentes sentido #Ccs generan retraso",
                "via @TuGPSRadio: #PNM km 10 triple choque. Km 9 camión de agua accidentado ambos accidentes sentido #Ccs generan retraso #Miranda",
                "via @alvaradoamerico: #PNM Choque simple 3 vehículos involucrados. Autoridades en el sitio km 10 canal rápido. #Miranda",
                "#ReporteVial1021 vehículo accidentado en el km 9 de la #PNM dirección #CCS",
                "@TrafficMiranda #PNM Choque simple 3 vehículos involucrados. Autoridades en el sitio km 10 canal rápido. ",
                "@trafficMIRANDA @zona925fm #pnm el lamentable accidente de curva del km6 estaba siendo removido a eso de las 6:30 la cola era larg para #LTQ",
                "RT @TioraFM: Reabierto el paso en la #PNM luego de un choque en el km6. 7am",
                "Gracias @TioraFM: Reabierto el paso en la #PNM luego de un choque en el km6. 7am",
                "via @TioraFM: Reabierto el paso en la #PNM luego de un choque en el km6 @_TonyRey 7am #Miranda",
                "via @roxanalaya: #pnm el lamentable accidente de curva dl km6 estaba siendo removido a eso de las 6:30 la cola era larg para #LTQ #Miranda  ",
                "@TrafficMiranda #PNM choque en el #TercerCanal km 6. Precaución! La falta de señalizacion y la imprudencia responsables @TraficoVV",
                "(5:55am) via @rpabon2011: @AleCanizales accidente en la #PNM Km 6 sentido #CCS Los Teques choque de frente en el VAO",
                "via @TuGPSRadio: #PNM choque en el #TercerCanal km 6. precaución! La falta de señalizacion y la imprudencia responsables @_TonyRey",
                "@TrafficMiranda @traficovv Cerrado el Contraflujo de la #PNM por choque múltiple en el km 6. Prudencia y precaución.",
                "#ReporteVial1021 choque #PNM a la altura del km 6, 3er canal #Miranda",
                "RT #EnLaVia @MONIC31 no hay paso en la #PNM sentido #SAA por choque múltiple después de la entrada de la vega 6.15am",
                "RT @trafficMIRANDA #PNM choque en el #TercerCanal km 6. precaución! La falta de señalizacion y la imprudencia responsables",
                "Reporta @AztireyRm: #Miranda choque a la altura del km 6 #PNM no usar el VAO! sentido #CCS https://t.co/okdjjNjrve",
                "@trafficMIRANDA: via @TuGPSRadio: #PNM precaución acaba de ocurrir un accidente km 6#TercerCanal",
                "#PNM. 05:35am.Lento con volumen para pasar el IVIC y el semáforo de la Vega.Accidente aparatoso Km6 con Heridos.",
                "No hay paso en la #pnm sentido #saa por choque múltiple después de la entrada de la vega ?@MONIC31 https://t.co/v6fWStWrub",
                "@ElVacilonfm: Reporta @AztireyRm: #Miranda choque a la altura del km 6 #PNM no usar el VAO! sentido #CCS https://t.co/lq6KJH79xz",
                "#PNM. 05:41am: Detenido desde Pasando Semaforo de La Vega en dirección Caracas. Motivo: Choque Triple,Heridos.",
                "@TrafficMiranda #PNM choque en el #TercerCanal km 6. Precaución! La falta de señalizacion y la imprudencia responsables @TraficoVV",
                "@unaiamenabar choque en el 3° canal bajando la #pnm km 6",
                "(5:57) via @rpabon2011: @AleCanizales accidente en la #PNM Km 6 sentido #CCS Los Teques choque de frente en el VAO",
                "via @TuGPSRadio: @_TonyRey Cerrado el Contraflujo de la #PNM por choque múltiple en el km 6. Prudencia y precaución. #Miranda ",
                "Hay una camioneta accidentada antes de llegar a la urb las salias dirección #SAA #PNM #SISEC #VIAL",
                "no hay paso Hay una camioneta accidentada antes de llegar a la urb las salias dirección #SAA #PNM #SISEC #VIAL",
                "no hay paso no hay paso Hay una camioneta accidentada antes de llegar a la urb las salias dirección #SAA #PNM #SISEC #VIAL",
                "via @alvaradoamerico: #PNM sentido los Teques vehículo accidentado en toda la curva dl km 18. precaución! La zona muy oscura 4:51am @traff",
                "via @TuGPSRadio: @trafficMIRANDA #PNM sentido los Teques vehículo accidentado en toda la curva dl km 18. precaución! La zona muy oscura 4:5",
                "@trafficMIRANDA #PNM sentido los Teques vehículo accidentado en toda la curva del km 18. Precaución! La zona muy oscura 4:51am @trafficARC ",
                "#Miranda 6:50am #PNM Volcamiento en la curva ants d #CasaMía Via>#LTQS >@Giselvic https://t.co/i7YtLzOtwW",
                "Volcamiento en #PNM en la curva antes de casa mía dirección #LTQS. Reporta @Giselvic 6:50am @TrafficEnLaVia https://t.co/edLRwpXkiH" 
         );
        
        $this->tam_estudio = sizeof($this->tuits_guardados);        
        
        
    }
    /*
     * Formatea los tuits en un nuevo arreglo (minusculas, menciones, usuarios
     * rutas)
     */
    private function formatearTuits(){
        echo "<br>Formateo del arreglo de tuits a analizar";
        echo "<br>Tamaño del arreglo de tuits: ". $this->tam_estudio;
        $this->hacer_minuscula_tuits();
        $this->quitar_menciones_tuits();
        $this->quitar_usuarios_tuits();
        $this->quitar_rutas_tuits();
    }

    

    public function infoTuits(){
        
        //$aleatorio = mt_rand(0, $this->tam_estudio);
        //$aleatorio = 96;
        $aleatorio = 0;
        echo "<br><br><br>Tuit en estudio";
        echo "<br> Índice aleatorio: ".$aleatorio;
        $tuit_aletorio_crudo = $this->tuits_guardados[$aleatorio];
        $tuit_aletorio_procesado = $this->tuits_procesados[$aleatorio];
        echo "<br>Tuit en estudio crudo: ".$tuit_aletorio_crudo;
        echo "<br>Tuit en estudio procesado: ".$tuit_aletorio_procesado;
        $this->cantElementos($tuit_aletorio_crudo);
        $this->tieneRutas($tuit_aletorio_crudo);
        $this->tieneMenciones($tuit_aletorio_crudo);
        $this->tieneUsuarios($tuit_aletorio_crudo);
        
        $this->compararSubConjunto($tuit_aletorio_procesado);
        
    }
    /*
     * Indica la cantidad de elementos que tiene un tuit verificando los espa-
     * cios en blanco de la cadena
     */     
    
    private function cantElementos($tuit){
        //$elementos = array();
        $elementos = explode(" ", $tuit);
        $cant = sizeof($elementos);
        echo "<br>Cantidad de elementos: ".$cant;
        return $cant;
    }
    /*
     * Indica si un tuit particular tiene ruta http
     * 
     */
    private function tieneRutas($tuit){
        $bandera = 0;
        $bandera = substr_count($tuit,"http");
        if ($bandera!=0){
            echo "<br>Tiene ruta ";
            return TRUE;
        }
        else{
            echo "<br>No tiene ruta ";
            return FALSE;
        }
    }
    
    /*
     * Convierte a un tuit en minuscula
     * @param type $tuit
     * @return type     */
    private function tuit_minuscula($tuit){
        echo "<br>Tuit minusculas: ".strtolower($tuit);
        return strtolower($tuit);
    }
    
    /*
     * Revisa un tuit e indica si tiene una mención 'via @' 'retweeted' 'rt'
     * 
     */
    public function tieneMenciones($tuit){
        $patron_mension_1 ="via @";
        $patron_mension_2 ="retweeted ";
        $patron_mension_3 ="rt @";
        $patron_mension_4 ="reporta @";
        $patron_mension_5 ="vía @";
        $patron_mension_6 ="informa ";
        $tieneMension = false;
        if ( strpos($tuit,$patron_mension_1) !== FALSE ||
             strpos($tuit,$patron_mension_2) !== FALSE ||
             strpos($tuit,$patron_mension_3) !== FALSE ||
             strpos($tuit,$patron_mension_4) !== FALSE ||
             strpos($tuit,$patron_mension_5) !== FALSE ||
             strpos($tuit,$patron_mension_6) !== FALSE
          ){
            
          $tieneMension=true;
          }
        
        if($tieneMension==true){
            echo "<br> Tiene mención: ".$tuit;            
        }
        else{
            echo "<br> No tiene mención: ".$tuit;
        }       
        return $tieneMension;
    }
    
    /*
     * Recorre todo el arreglo de tuits y los hace minusculas
     */
    private function hacer_minuscula_tuits(){
        echo "<br><br><b>Haciendo minusculas todos los tuits</b>";
        for($i=0;$i<$this->tam_estudio;$i++){
            $this->tuits_procesados[$i] = strtolower($this->tuits_guardados[$i]);
        }
    }
    /*
     * Recorre todo el arreglo de tuits y elimina las menciones (via, rt...)
     */
    private function quitar_menciones_tuits(){
        echo "<br><br><b>Quitando menciones todos los tuits</b>";
        for($i=0;$i<$this->tam_estudio;$i++){
            echo "<br>Tuit: ".$i;
            $resul = $this->tieneMenciones($this->tuits_procesados[$i]);
            
            if( $resul == true){
                $this->tuits_procesados[$i] = $this->eliminarMencion($this->tuits_procesados[$i]);
            }
        }
    }
    /*
     * Recorre todo el arreglo de tuits y elimina los usuarios @
     */
    private function quitar_usuarios_tuits(){
        echo "<br><br><b>Eliminando usuarios de todos los tuits</b>";
        for($i=0;$i<$this->tam_estudio;$i++){
            echo "<br>Tuit: ".$i;
            $resul = $this->tieneUsuarios($this->tuits_procesados[$i]);
            
            if( $resul == true){
            
                $this->tuits_procesados[$i] = $this->eliminarUsuario($this->tuits_procesados[$i]);
            }
        }
    }
    /*
     * Recorre todo el arreglo de tuits y elimina las rutas http
     */
    private function quitar_rutas_tuits(){
        echo "<br><br><b>Eliminando rutas de todos los tuits</b>";
        for($i=0;$i<$this->tam_estudio;$i++){
            echo "<br>Tuit: ".$i;
            $resul = $this->tieneRutas($this->tuits_procesados[$i]);
            
            if( $resul == true){
            
                $this->tuits_procesados[$i] = $this->eliminarRuta($this->tuits_procesados[$i]);
            }
        }
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
            echo "<br>Hay una diferencia entre cantidad de menciones y usuarios";
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
        echo "<br>Nuevo Tuit: ".$nuevo_tuit;
        return $nuevo_tuit;        
    }
    /*
     * Ubica las menciones en tuits particular
     * retorna una arreglo de dos posiciones
     * posicion 1: arreglo con los índices donde inicia la(s) mencion(es)
     * posicion 2: arreglo con los índices donde finalizan la(s) mencion(es)
     */
    private function UbicacionMencion($tuit){
                
        $patrones = array ("via",
                        "retweeted",
                        "rt",
                        "reporta",
                        "vía",
                        "informa");
        
        $patrones_usuario = array ("@");
        
        $arreglo_tuit = explode(" ", $tuit);
        $cant_elem = sizeof($arreglo_tuit);
        $menciones = array_intersect($arreglo_tuit,$patrones);
        print_r($menciones);
        $usuarios_menciones = array();
        $i=0;
        foreach ($arreglo_tuit as $t){
            $tiene_arroba = strpos($t, "@");            
            if($tiene_arroba!==FALSE){                                
                $usuarios_menciones[$i]=$t;
            }
            $i++;
        }        
        
        print_r($usuarios_menciones);
        
        $result = array($menciones, $usuarios_menciones);
        
        return $result;
        
         
    }
    
    public function tieneOtraEtiqueta($tuit){
        return;
    }
    
    /*
     * Indica si un tuit particular tiene al menos un usuario @
     * 
     */
    private function tieneUsuarios($tuit){
        $bandera = 0;
        $bandera = substr_count($tuit,"@");
        if ($bandera!=0){
            echo "<br>Tiene usuarios ";
            return TRUE;
        }
        else{
            echo "<br>No tiene usuario ";
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
        echo "<br>Tuit con usuario: ".$tuit;
        echo "<br>Tuit sin usuario: ".$nuevo_tuit;
        return $nuevo_tuit;
    }
    /*
     * Elimina las rutas de un tuit particular http
     * 
     */
    private function eliminarRuta($tuit){
        $arreglo_tuit = explode(" ", $tuit);
        $cant_elem = sizeof($arreglo_tuit);
        $i=0;
        for ($j=0;$j<$cant_elem;$j++){
            $tiene_ruta = strpos($arreglo_tuit[$j], "http");            
            if($tiene_ruta!==FALSE){                                
                unset($arreglo_tuit[$j]);
            }            
        }  
        $nuevo_tuit = implode(" ", $arreglo_tuit);
        echo "<br>Tuit con ruta: ".$tuit;
        echo "<br>Tuit sin ruta: ".$nuevo_tuit;
        return $nuevo_tuit;
    }
    /*
     * Método para comparar un tuit particular contra el arreglo de tuits general
     * @param tuit = tuit a buscar
     * @param cant_palabras = cantidad de palabras a considerar
     * @param inicio = indice de inicio dentro del arreglo (offset)
     * @return una arreglo con los índices que coinciden con tuit
     */
    private function compararSubConjunto($tuit,$cant_palabras=7,$inicio = 3){
        
        echo "<br>**************************************";
        echo "<br>********COMPARACION*******************";
        echo "<br>**************************************<br>";
        $tuit_estudio = $tuit;
        $elementos_estudio = explode(" ", $tuit_estudio);        
        
        /*********Definiendo la subcadena de busqueda*****************/        
        $contador = 0; //contador hasta llegar a la cantidad de palabras        
        $sub_cadena = "";
        while ($contador!=$cant_palabras){
            $sub_cadena = $sub_cadena ." ". $elementos_estudio[$inicio];
            $inicio++;
            $contador++;
        }
        echo "<br>Subcadena de comparacion: ".$sub_cadena;
        /*********Iterando la subcadena dentro del arreglo de estudio*********/
        $k=0; //Cantidad de coincidencias
        $retorno = array();
        for($j=0;$j<$this->tam_estudio;$j++){
        
            echo "<br>Tuit: ".$j;
            echo "<br>Tuit de referencia: ".$this->tuits_procesados[$j];            
            $this->cantElementos($this->tuits_procesados[$j]);            

            $result = strpos($this->tuits_procesados[$j],$sub_cadena);
            
            if($result !=false){
                echo "<br><b>ENCONTRADA subcadena: ".$sub_cadena. "</b> en: ".$this->tuits_guardados[$j];            
                $k++;
                array_push($retorno, $j);
            }
        
        }
        echo "<br><br><b>Cantidad de coincidencias: ".$k."</b>";
        var_dump($retorno);
        return $retorno;
    }
}

$agtPrueba = new agentePruebas();
$agtPrueba->infoTuits();

