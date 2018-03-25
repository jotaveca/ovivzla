<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once './class.Lugar.php';
header('Content-type: text/html; charset=utf-8'); 

/**
 * Description of class
 *
 * @author elias
 */
class CargadorLugares {
//put your code here
    
    private $nodos;
    // Arreglo de nodos de nivel 0 a importar
    private $nodosN0;
    // Arreglo de nodos de nivel 1 a importar
    private $nodosN1;
    // Arreglo de nodos de nivel 2 a importar
    private $nodosN2;
    // Arreglo de nodos de nivel 3 a importar
    private $nodosN3;
    
    private $nodos_BD;
    // Arreglo de nodos de nivel 0 a almacenados en BD
    private $nodosN0_BD;
    // Arreglo de nodos de nivel 1 a almacenados en BD
    private $nodosN1_BD;
    // Arreglo de nodos de nivel 2 a almacenados en BD
    private $nodosN2_BD;
    // Arreglo de nodos de nivel 3 a almacenados en BD
    private $nodosN3_BD;
    
    private $estadoBD;
            
     /* 0. Se realiza desde el nivel 0 al 3
     * 1. Se carga el archivo para importarlo
     * 2. Se carga la info de la BD
     * 3. Se cruzan la info del mismo nivel y se obtiene los datos que no están en la BD
     * 4. Se verifica que exista el padre de cada nodo
     * 5. Se inserta el nodo 
    * 
    */  
    function __construct() {	
        echo __CLASS__."<br>";
        
        $this->nodos = array();
        $this->nodosN0 = array();
        $this->nodosN1 = array();
        $this->nodosN2 = array();
        $this->nodosN3 = array();
        
        $this->nodos_BD = array();
        $this->nodosN0_BD = array();
        $this->nodosN1_BD = array();
        $this->nodosN2_BD = array();
        $this->nodosN3_BD = array();
        
        // Lectura de lugares a importar
        echo "LEYENDO DATOS A IMPORTAR <br>";
        $this->nodos        =  $this->leerLugares();
        // Lectura de lugares de la Base de Datos
        echo "EXAMINANDO DATOS A IMPORTAR  <br>";        
        $this->examinarArreglo($this->nodos,1);
        // Se realiza la clasificación de los nodos a importar
        echo "CLASIFICANDO POR NIVEL LOS DATOS A IMPORTAR  <br>";        
        $nodos_imp = $this->clasificarNodosXNivel($this->nodos);
        $this->nodosN0 = $nodos_imp[0];        
        $this->nodosN1 = $nodos_imp[1];        
        $this->nodosN2 = $nodos_imp[2];        
        $this->nodosN3 = $nodos_imp[3];
        
        
        echo "LEYENDO DATOS DE LA BD  <br>";        
        //$this->nodos_BD     =  $this->leerLugares("../cargas/arbol_lugares_bd.csv",1);
        // El segundo parámetro está en 1, por lo tanto busca en la BD
        $this->nodos_BD     =  $this->leerLugares(0,1);
        
        $nodosFiltradosN0;
        $nodosFiltradosN1;
        $nodosFiltradosN2;
        $nodosFiltradosN3;
        if ($this->estadoBD !=0){
            echo "<br>***********************************";
            echo "<br>La base de datos tiene datos";
            echo "<br>***********************************";
            $this->estadoBD = 1;
            echo "EXAMINANDO DATOS DE LA BD  <br>";        
            $this->examinarArreglo($this->nodos_BD,1);        
        // Se realiza la clasificación de los nodos que reposan en la BD
            echo "CLASIFICANDO POR NIVEL LOS DATOS DE LA BD  <br>";        
            $nodos_bd = $this->clasificarNodosXNivel($this->nodos_BD);
            $this->nodosN0_BD = $nodos_bd[0];
            $this->nodosN1_BD = $nodos_bd[1];
            $this->nodosN2_BD = $nodos_bd[2];
            $this->nodosN3_BD = $nodos_bd[3];

            // Se obtiene solo los nodos nuevos que no estan en la BD
            echo "FASE 1: COMPARANDO POR NIVEL LOS DATOS A IMPORTAR Y LOS ALMACENADOS EN LA BD  <br>";        
            $nodosFiltradosN0 = $this->compararArreglos($this->nodosN0, $this->nodosN0_BD);
            $nodosFiltradosN1 = $this->compararArreglos($this->nodosN1, $this->nodosN1_BD);
            $nodosFiltradosN2 = $this->compararArreglos($this->nodosN2, $this->nodosN2_BD);
            //die();
            $nodosFiltradosN3 = $this->compararArreglos($this->nodosN3, $this->nodosN3_BD);
            
        }else{
            echo "<br>***********************************";
            echo "<br>La base de datos está vacía";
            echo "<br>***********************************";            
            $nodosFiltradosN0 = $this->nodosN0;
            $nodosFiltradosN1 = $this->nodosN1;
            $nodosFiltradosN2 = $this->nodosN2;
            $nodosFiltradosN3 = $this->nodosN3;
        }
            
                
        // Se obtiene solo los nodos con padre valido, no se valida el N0
        echo "FASE 2: VALIDANDO LOS PADRES DE LOS DATOS RESULTANTES  <br>";        
        echo "Validando Padres de N1 <br>";
        $nodosValidadosN1 = $this->validarPadres($nodosFiltradosN1,$this->nodos, $this->nodos_BD);
        echo "Validando Padres de N2 <br>";
        $nodosValidadosN2 = $this->validarPadres($nodosFiltradosN2,$this->nodos, $this->nodos_BD);
        echo "Validando Padres de N3 <br>";
        $nodosValidadosN3 = $this->validarPadres($nodosFiltradosN3,$this->nodos, $this->nodos_BD);
        
        // Se guardan los arreglos N0 , N1. N2 y N3 en la BD
        echo "UNIENDO ARREGLOS RESULTANTES  <br>";        
        //$arregloGuardar = $nodosFiltradosN0+$nodosValidadosN1+$nodosValidadosN2+$nodosValidadosN3;
        //$arregloGuardar = array_merge($nodosFiltradosN0,$nodosValidadosN1,$nodosValidadosN2,$nodosValidadosN3);
        
       
        $arregloGuardar = $this->unirArreglos($nodosFiltradosN0, $nodosValidadosN1);
        $arregloGuardar = $this->unirArreglos($arregloGuardar, $nodosValidadosN2);
        $arregloGuardar = $this->unirArreglos($arregloGuardar, $nodosValidadosN3);
        echo "EXAMINANDO ARREGLO RESULTANTE  <br>";        
        $this->examinarArreglo($arregloGuardar,1);
        echo "GUARDANDO ARREGLO RESULTANTE  <br>";        
        $this->guardarNodosBD($arregloGuardar);
        /*$this->guardarNodosBD($nodosValidadosN1);
        $this->guardarNodosBD($nodosValidadosN2);
        $this->guardarNodosBD($nodosValidadosN3);
        */
        
       
        //$this->getNodo(1);
        //$this->getNodo(2);
        //$this->getNodo(3);
        //$this->getNodo(4);
    }
    
    /*
     * Extrae los datos del archivo o BD y los almacena en un arreglo general
     */
    private function leerLugares($ruta="../cargas/arbol_lugares.csv", $buscarEnBD=0){
        echo "<br>".__METHOD__."<br>";
         $nodos = array();
        if($buscarEnBD==0){
            echo "Leyendo valores desde la ruta para importar datos: $ruta <br>";
           
            if (($gestor = fopen($ruta, "r")) !== FALSE) {
                $fila = 0;
                while (($datos = fgetcsv($gestor, 0, ",")) !== FALSE) {                
                    $numero = count($datos);
                    //echo "<p> $numero de campos en la línea $fila: <br /></p>\n";                
                    $fila++;
                    //echo "Leyendo línea $fila <br>";
                    $nodo = array();
                    $nodo["nbCortoLugar"]  = $datos[0];                    
                    $nodo["lat"]           = $datos[1];
                    $nodo["long"]          = $datos[2];
                    $nodo["nbLugar"]       = $datos[3];
                    $nodo["esAlias"]       = $datos[4];
                    $nodo["esHoja"]        = $datos[5];
                    $nodo["Padre"]         = $datos[6];
                    $nodo["Siguiente"]     = $datos[7];
                    $nodo["Anterior"]      = $datos[8];
                    $nodo["Rango"]         = $datos[9];
                    $nodo["kmAprox"]       = $datos[10];
                    $nodo["Nivel"]         = $datos[11];
                    $nodo["RutaRaiz"]      = $datos[12];

                    array_push($nodos, $nodo);                
                }
                fclose($gestor);                
                echo "Cantidad de líneas leidas: $fila  - incluyendo la cabecera<br>";
                // eliminando la cabecera
                array_shift($nodos);
            } 
        }
        else{
             echo "Leyendo valores desde la base de datos<br>";
            $lugar = new Lugar();
            $datos = $lugar->obtenerLugaresInteres();                       
            if ($datos != false){                
                echo "Datos encontrados, leyendo....<br>";
                $i=0;
                foreach($datos as $dato){
                    $nodo = array();
                    $nodo["nbCortoLugar"]  = $dato["cod_nb_corto_lugar"];
                    $nodo["lat"]           = $dato["tx_lat"];
                    $nodo["long"]          = $dato["tx_long"];
                    $nodo["nbLugar"]       = $dato["tx_nb_lugar"];
                    $nodo["esAlias"]       = $dato["esAlias"];
                    $nodo["esHoja"]        = $dato["esHoja"];
                    $nodo["Padre"]         = $dato["cod_lugar_padre"];
                    $nodo["Siguiente"]     = $dato["tx_nb_siguiente"];
                    $nodo["Anterior"]      = $dato["tx_nb_anterior"];
                    $nodo["Rango"]         = $dato["tx_km_rango"];
                    $nodo["kmAprox"]       = $dato["tx_km_aprox"];
                    $nodo["Nivel"]         = $dato["in_nivel"];
                    $nodo["RutaRaiz"]      = $dato["cod_rutaraiz"];
                    array_push($nodos, $nodo);
                    $i++;
                }
                echo "Cantidad de registros leidos: $i <br>";
                $this->estadoBD = 1;
            }
            else{
                echo "La Tabla de Lugares está vacía <br>";
                $this->estadoBD = 0;
            }
        }
        //var_dump($this->nodos);
        return $nodos;
    }
    
    /*
     * Indica la diferencia entre dos arreglos el contenido de $nodosImportar con $nodosBaseDatos
     * Si existe retorna un arreglo con los nombres de ubicaciones que no existe en BD , en caso contrario NULL
     */
    private function compararArreglos($nodosImp,$nodosBD){
      
        echo "<br>".__METHOD__."<br>";
        $nivel = $nodosImp[0]['Nivel'];
        echo "Comparación en Nivel $nivel <br>";
        //echo "Arreglo a importar <br>";
        //$this->examinarArreglo($nodosImp,1);
        $nombImp  = array_column($nodosImp, 'nbCortoLugar');   
        echo "Nombres candidatos a incluir <br>";
        print_r($nombImp);
        //echo "Arreglo de la BD <br>";
        //$this->examinarArreglo($nodosBD,1);
        $nombBD  = array_column($nodosBD,'nbCortoLugar');        
        echo "<br>Nombres en la BD <br>";
        print_r($nombBD);
        //Se obtiene un arreglo con los elementos que no estan en la BD
        //$difBD = array_diff_assoc($nombImp, $nombBD);
        
        //Hay que verificar que los valores de $nombImp que no estén en $nombBD
        //sean incluidos para su inserción
        $difBD = array();
        $j=0;
        for($i=0;$i<sizeof($nombImp);$i++){
            $estaEnBD = in_array($nombImp[$i], $nombBD);
            if ($estaEnBD==false){
              $difBD[$j]  = $nombImp[$i];
              $j++;
            }
        }
        echo "<br>Nombres no presentes en la BD <br>";
        print_r($difBD);
        $cantDif = sizeof($difBD);
        echo "<br>Cantidad de elementos no presentes: $cantDif <br>";
        //$this->examinarArreglo($difBD,1);
        var_dump($difBD);
        echo "Verificando... <br>";
        $cantNodosImp = sizeof($nodosImp);
        /*Elimina elementos que ya se encuentran en la BD  */
        for( $i=0; $i<$cantNodosImp;$i++){
            $result = in_array($nodosImp[$i]['nbCortoLugar'], $difBD);
            if ($result == FALSE)
            {
                $nombre = $nodosImp[$i]['nbCortoLugar'];
                echo "Eliminando $nombre de la importación <br>";
                unset($nodosImp[$i]);
            }
        }
        echo "Elementos candidatos a importar <br>";
        $nodosImpResult = array_values($nodosImp); // para quitar espacios vacios en el arreglo
        $this->examinarArreglo($nodosImpResult, 1);
        
        return $nodosImpResult;    
       
        
    }
    /*
     * Verifica si el padre de un nodo está en un arreglo de nodosPadres
     * retorna TRUE o FALSE
     */
    private function existeNodoPadre($nodo,$nodosPadre){
        echo "<br>".__METHOD__."<br>";
        $nombre = $nodo['nbCortoLugar'];
        $miPadre = $nodo['Padre'];
        //arreglos de padres
        $padres  = array_column($nodosPadre,'nbCortoLugar');        
        $r = in_array($miPadre, $padres);
        $msg = "";
        if ($r){
            $msg = "Si";
        }else{
            $msg = "No";
        }
            
        echo "El nodo $nombre tiene padre:$msg.<br>";
        return $r;
    }
    /*
     * Recibe un arreglo de nodos para validar si cada uno tiene padre
     * Se debe validar que tenga padre al menos en:
     * a) Nodos a importar o,
     * b) Nodos en BD
     * Retorna los nodos validados que tienen padres
     */
    private function validarPadres($nodosHijos,$nodosImportar,$nodosBD){        
        echo "<br>".__METHOD__."<br>";
        //$nivel = $nodos[0]['Nivel'];
        $cantNodos = sizeof($nodosHijos);
        
        switch ($this->estadoBD){
            
        case 1:                    
            for($i=0; $i<$cantNodos;$i++){
                echo "Verificando... <br>";    
                echo "En el arreglo de la importación <br>";
                $resultImp = $this->existeNodoPadre($nodosHijos[$i], $nodosImportar);
                echo "En el arreglo de la BD <br>";
                $resultBD = $this->existeNodoPadre($nodosHijos[$i], $nodosBD);
                /*Elimina elementos que no tengan padre  */
                if ($resultImp == FALSE && $resultBD == FALSE ){
                        //No tiene Padre
                        $nombre = $nodosHijos[$i]['nbCortoLugar'];
                        $nivel = $nodosHijos[$i]['Nivel'];
                        echo "El nodo $nombre en el nivel $nivel no tiene padre, eliminando... <br> ";
                        unset($nodosHijos[$i]);
                }
            }
            break;
            case 0:               
                for($i=0; $i<$cantNodos;$i++){
                echo "Verificando... <br>";    
                echo "En el arreglo de la importación <br>";
                $resultImp = $this->existeNodoPadre($nodosHijos[$i],$nodosImportar);               
                /*Elimina elementos que no tengan padre  */
                if ($resultImp == FALSE ){
                        //No tiene Padre
                        $nombre = $nodosHijos[$i]['nbCortoLugar'];
                        $nivel = $nodosHijos[$i]['Nivel'];
                        echo "El nodo $nombre en el nivel $nivel no tiene padre, eliminando... <br> ";
                        unset($nodosHijos[$i]);
                }
            }
            break;
        
        }
        echo "Elementos con padres validados <br>";
        $nodosResult = array_values($nodosHijos); // para eliminar posiciones vacias del arreglo
        $this->examinarArreglo($nodosResult, 1);
        //var_dump($nodos);
        return $nodosResult;
   }

    
    /*
     * Recibe un arreglo de nodos para almacenarlos en la BD
     * Retorna 
     */
    private function guardarNodosBD($nodos){
         echo "<br>".__METHOD__."<br>";
         //echo "Guardando nodos de nivel:".$nodos[0]["Nivel"];
        $lugar = new Lugar();
        $i = 1;
        //print_r($nodos);
        //die();
        
        if(count($nodos)!=0){
            foreach ($nodos as $nodo){           

                $tx_nb_lugar = $nodo["nbLugar"];

                if(!mb_detect_encoding($tx_nb_lugar, 'utf-8', true)){
                       $tx_nb_lugar = utf8_encode($tx_nb_lugar);
                 }

                $cod_nb_corto_lugar = $nodo["nbCortoLugar"];
                $cod_lugar_padre    = $nodo["Padre"];
                $tx_lat             = $nodo["lat"];
                $tx_long            = $nodo["long"];
                $esAlias            = $nodo["esAlias"];
                $esHoja             = $nodo["esHoja"];
                $tx_nb_siguiente    = $nodo["Siguiente"];
                $tx_nb_anterior     = $nodo["Anterior"];
                $tx_km_rango        = $nodo["Rango"];
                $tx_km_aprox        = $nodo["kmAprox"];
                $in_nivel           = $nodo["Nivel"];
                $ruta_raiz          = $nodo["RutaRaiz"];
                $result = 1;
                $result = $lugar->agregarLugarInteres(
                        $tx_nb_lugar,
                        $cod_nb_corto_lugar,
                        $cod_lugar_padre,
                        $tx_lat,
                        $tx_long,
                        '',
                        $esAlias,
                        $esHoja,
                        $tx_nb_siguiente,
                        $tx_nb_anterior,
                        $tx_km_rango,
                        $tx_km_aprox,
                        $in_nivel,
                        $ruta_raiz
                        );


              echo "<br>Nodo $cod_nb_corto_lugar de nivel $in_nivel -  Resultado de operación $i: ". $result;
              $i++;
            }
        }
        else{
            echo "<br>No hay datos para guardar en la BD";
        }
    }




    /*
     * Clasifica 4 arreglos de nodos según el nivel de cada nodo
     * los niveles posibles son 0,1,2,3
     */
    private function clasificarNodosXNivel($nodos){
        echo "<br>".__METHOD__."<br>";
        $nodosN0 = array();
        $nodosN1 = array();
        $nodosN2 = array();
        $nodosN3 = array();
        $cant_nodos = sizeof($nodos);
        
        for($i=0;$i<$cant_nodos;$i++){
        
            $nivel = $nodos[$i]["Nivel"];

            switch ($nivel):
            case "0":
                array_push($nodosN0, $nodos[$i]);
                break;
            case "1":
                array_push($nodosN1, $nodos[$i]);
                break;
            case "2":
                array_push($nodosN2, $nodos[$i]);
                break;
            case "3":
                array_push($nodosN3, $nodos[$i]);
                break;
            endswitch;        
        }
        
        return array ($nodosN0,$nodosN1,$nodosN2,$nodosN3);
        
    }  
    
    
    /*
     * Muestra información general sobre un arreglo de nodos
     * Si opcion = 1 entonces muestra información detallada
     */
    public function examinarArreglo($nodos, $opcion){
        echo __METHOD__."<br>";       
         $cant_nodos = sizeof($nodos);
        echo "Cantidad de elementos:".   $cant_nodos." <br>";
        //var_dump($nodos);
        
       if($opcion==1){
        
        for($i=0;$i<$cant_nodos;$i++){
        
            $nivel = $nodos[$i]["Nivel"];
            $nombre = $nodos[$i]['nbCortoLugar'];
            $padre = $nodos[$i]['Padre'];            
            $msj = "Nivel $nivel - Nombre: $nombre - Padre: $padre  <br>";
            switch ($nivel):
            case "0":                
                echo "$i ".$msj;
                break;
            case "1":
                echo "$i  -   ".$msj;
                break;
            case "2":
                echo "$i  -  -    ".$msj;
                break;
            case "3":
                echo "$i  -  -  -     ".$msj;
                break;
            endswitch;        
        
         }
       }
        
    }    
    
    /*
     * Une dos arreglos y retorna el Arreglo Base
     */
   private function unirArreglos($arregloBase, $arregloAnexar){
       
       foreach ($arregloAnexar as $nodoAnexar){
           array_push($arregloBase, $nodoAnexar);
       }
       return $arregloBase;
   }


   /*
     * Recupera nodo(s) de la BD a partir del nivel
     * 
     */
    private function getNodosBD($nivel){
        //sentencia de BD
        return null;
    }
    /*
     * Recupera el nodo 0 de la BD
     */
    private function getNodoN0_BD(){
        return $this->getNodosBD(0);
    }
    
    /*
     * Recupera los nodos nivel 1 de la BD
     */
    private function getNodosN1_BD(){
        return $this->getNodosBD(1);
    }
    
    /*
     * Recupera los nodos nivel 2 de la BD
     */
    private function getNodosN2_BD(){
        return $this->getNodosBD(2);
    }
    
    /*
     * Recupera los nodos nivel 3 de la BD
     */
    private function getNodosN3_BD(){
        return $this->getNodosBD(3);
    }
    
    
    
}

//phpinfo();
echo "Corrida ". date("Y-m-d H:i:s")."<br>";	 
$c = new CargadorLugares();

