<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of class
 *
 * @author elias
 */
class Lugar {
    //put your code here
    private $nb_corto;
    private $lat;
    private $long;
    private $nb_largo;
    private $esAlias;
    private $esHoja;
    private $lugarPadre;
    private $lugarSig;
    private $lugarAnt;    
    private $km_rango;
    private $km_aprox;
    private $nivel;
    private $bd;
    private $log;

    function __construct() {
    
        require_once('class.BD.php');        
        include_once("class.Log.php");
        
        $this->bd = new BD();
        $this->log = new Log(); 
        
    }

    function __destruct() {
       
    }

    
    public function eliminarLugarInteres($idLugar){
    
        $sql = "DELETE FROM tr013_lugares_interes WHERE id_lugar = :idLugar"; 
        $sql2 = "DELETE FROM tr013_lugares_interes WHERE id_lugar = $idLugar"; 
        $con = $this->bd->obtenerConexionSegura();                        
        $stmt = $con->prepare($sql);      

        $fecha = date("Y-m-d H:i:s"); 
        $msg = "[LugarInteres eliminarLugarInteres()] | $fecha | $sql2 ";
        $this->log->general($msg);    
                         
        $salida = $stmt->execute(array(':idLugar'=>$idLugar));
        
        return $salida;
    

    }


    public function obtenerLugaresInteres(){
    
        $sql  = "SELECT * FROM tr013_lugares_interes";     
        $lugar =  $this->bd->listarRegistros($sql);
        return $this->bd->utf8_converter($lugar);
    
    }


    public function agregarLugarInteres($tx_nb_lugar, $cod_nb_corto_lugar, $cod_lugar_padre, $tx_lat, $tx_long, $tx_img, $esAlias, $esHoja, $tx_nb_siguiente, $tx_nb_anterior, $tx_km_rango, $tx_km_aprox, $in_nivel,$cod_rutaraiz=''){
    
          $sql = "INSERT INTO tr013_lugares_interes (tx_nb_lugar, cod_nb_corto_lugar, cod_lugar_padre, tx_lat, tx_long, tx_img, esAlias, esHoja, tx_nb_siguiente, tx_nb_anterior, tx_km_rango, tx_km_aprox, in_nivel, cod_rutaraiz) VALUES (:tx_nb_lugar, :cod_nb_corto_lugar, :cod_lugar_padre, :tx_lat, :tx_long, :tx_img, :esAlias, :esHoja, :tx_nb_siguiente, :tx_nb_anterior, :tx_km_rango, :tx_km_aprox, :in_nivel, :cod_rutaraiz)";
          //echo $sql2 = "INSERT INTO tr013_lugares_interes (tx_nb_lugar, cod_nb_corto_lugar, cod_lugar_padre, tx_lat, tx_long, tx_img, esAlias, esHoja, tx_nb_siguiente, tx_nb_anterior, tx_km_rango, tx_km_aprox, in_nivel) VALUES ($tx_nb_lugar, $cod_nb_corto_lugar, $cod_lugar_padre, $tx_lat, $tx_long, $tx_img, $esAlias, $esHoja, $tx_nb_siguiente, $tx_nb_anterior, $tx_km_rango, $tx_km_aprox, $in_nivel)";
        
          $con = $this->bd->obtenerConexion();                        
          $stmt = $con->prepare($sql);   
        

          $stmt->execute(array(':tx_nb_lugar'=>$tx_nb_lugar,':cod_nb_corto_lugar'=>$cod_nb_corto_lugar,':cod_lugar_padre'=>$cod_lugar_padre,':tx_lat'=>$tx_lat,':tx_long'=>$tx_long,':tx_img'=>$tx_img,':esAlias'=>$esAlias,':esHoja'=>$esHoja,':tx_nb_siguiente'=>$tx_nb_siguiente,':tx_nb_anterior'=>$tx_nb_anterior,':tx_km_rango'=>$tx_km_rango,':tx_km_aprox'=>$tx_km_aprox,':in_nivel'=>$in_nivel, ':cod_rutaraiz'=>$cod_rutaraiz));   
                     
        
          return $lastId = $con->lastInsertId();
        
    }
    
}
