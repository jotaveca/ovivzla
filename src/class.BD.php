<?php
class BD {
	
	private $conexionBD = 0;
	
	//Usuario full jotave_obs_vial:jotave_obs_vial
       public function __construct() {
      		$this->conexionBD = new PDO("mysql:host=localhost;port=xxx;dbname=yyyyy","zzzzz","xyzxyz", array( PDO::ATTR_PERSISTENT => true));
		
       }
   
       public function __destruct() {
       		$this->conexionBD = null;
        }
        
       /**
       *
       * Obtener conexion de BD
       */
       public function obtenerConexion(){
        
           $this->conexionBD = new PDO("mysql:host=localhost;port=5432;dbname=oviorgve_ovi","oviorgve_ovi","~}l=p#zzzz", array( PDO::ATTR_PERSISTENT => true));	
           return $this->conexionBD;        
        
        }
        
        /**
       *
       * Obtener conexion Segura de BD
       */
       public function obtenerConexionSegura(){
        
        	$this->conexionBD = new PDO("mysql:host=localhost;port=xxxx;dbname=yyyy","zzzz","xyzxyz", array( PDO::ATTR_PERSISTENT => true)); 
        	return $this->conexionBD;        
        
        }

        public function utf8_converter($array)
	    {
	        array_walk_recursive($array, function(&$item, $key){
	            if(!mb_detect_encoding($item, 'utf-8', true)){
	                    $item = utf8_encode($item);
	            }
	        });
	     
	        return $array;
            
	    }
   
	
	/**
	 * 
	 * Registrar visitas web
	 *
	 */
	 public function registrarVisitasWeb ($ip, $entorno){
	 
	  	$fecha = date("Y-m-d");
		$sql = $this->crearSelect("tr004_visitas","*","ip = '$ip' AND fecha = '$fecha' ","");
		$resultado = $this->conexionBD->query($sql);
   		
   		//echo $sql.$navegador;
   
  	 	if ($resultado->fetchColumn() > 0){
  	 		 //echo "Ya existe";
  	 	}else{
  	 		 //echo "No existe";
  	 		$sql = "INSERT INTO tr004_visitas (ip,fecha,visitas,entorno) VALUES (:ip,:fecha,:visitas,:entorno)";
  	 		$stmt = $this->conexionBD->prepare($sql); 				 
			$stmt->execute(array(':ip'=>$ip,':fecha'=>$fecha,':visitas'=> 1,':entorno'=>$entorno));
  	 	}
 
	 }
	 
	 /**
	 * 
	 * Ver total de visitas web
	 *
	 */
	 public function verTotalVisitas(){
	 
		return $total = current($this->conexionBD->query("SELECT SUM(visitas) FROM tr004_visitas")->fetch());
	 
	 }
	
	
	
	
	/**
	*
	* Ejecutar una consulta de forma segura
	*
	*/
	public function listarRegistrosSeguro($sql,$parametros){
	
	$stmt = $this->conexionBD->prepare($sql);
	
	foreach($parametros as $clave=>$valor){
	
		$param[":$clave"] = $valor;		
	
	}
	//echo "$sql <br>";
	//var_dump($param);
	
	if ($stmt->execute($param)) {          
         
			
				 //echo "<br>execute".$stmt->rowCount();
				if ($row = $stmt->fetchAll()) {
					//echo "<br>  fetchall -";				
					$boReturn = $row;
					
				} else {
					//echo "<br>No FecthAll";
					$boReturn = false;
				}
			} else {
				
					//echo "<br>No execute";
					$boReturn = false;
			}
		return $boReturn;
			
	//$hStmt=$oDB->prepare("select name, age from users where userid=:userid");
	//$hStmt->execute(array(':userid',$nUserID));
	
	}
	
	
	/**
	 * 
	 * Execute a query
	 * @param String $query
	 * @return array asociativo con el resultado de la consulta.
	 *
	 */
	 public function listarRegistros($query) {
		
		//$this->conectar();
		//$row = array();
		$boReturn = false;		
				
			$stmt = $this->conexionBD->prepare($query);
			//echo $query."<br>";				
			//print_r($this->conexionBD->errorInfo() );
			
			if ($stmt->execute()) {				
			
				//echo "<br>execute".$stmt->rowCount();
				if ($row = $stmt->fetchAll()) {
					//echo "<br>  fetchall -";				
					$boReturn = $row;
					
				} else {
					//echo "<br>No FecthAll";
					$boReturn = false;
				}
			} else {
				
					//echo "<br>No execute";
					$boReturn = false;
			}
			return $boReturn;		
		
	}
		
	/**
	*$valor=
	* Funcion para la creacion de consultas de seleccion sobre tablas
	* @param $table tabla.
	* @param $columns columnas a seleccionar.
	* @param $where condicion where.
	* @param $order campo de ordenamiento.
	* @return string sentencia SELECT creada.
	*
	*/
	public function crearSelect($table, $columns, $where='', $order='')
	{
		$tmp = "SELECT $columns FROM $table";
		if($where!=''){
			$tmp.=" WHERE $where";
		}
		if($order!=''){
			$tmp.=" ORDER BY $order";
		}
		//echo "<br> BD".$tmp;
		return $tmp;
    	}
    	
    	/**
    	* Método para iniciar medicion de rendimiento de BD
    	*
    	*/     	
    	public function medirRendimientoQuery(){
    		//echo "<br>Iniciando analisis<br>";
    		$sql = "set profiling = 1";
    		$stmt = $this->conexionBD->prepare($sql);
		$stmt->execute();
    	
    	}
    	
    	/**
    	* Método para mostrar medicion de rendimiento de BD
    	*
    	*/     	
    	public function mostrarRendimientoQuery(){
	    	$sql = "show profiles";
    		$stmt = $this->conexionBD->prepare($sql);
		$stmt->execute();
		$row = $stmt->fetchAll();
		//echo "<br>Mostrando analisis<br>";
		//var_dump($row);
		
		foreach($row as $fila)		
		{
    			echo $fila['Query_ID'].' - '.round($fila['Duration'],4) * 1000 .' ms - '.$fila['Query'].'<br />';
		}	
		
		
    	
    	}
        /*
         * Ejecuta un procedimiento almacenado
         * 
         */
        public function ejecutarProcedimiento($nombre_proc, $paramentros=NULL){
            if($paramentros==NULL){
                $stmt = $this->conexionBD->prepare($nombre_proc);
                $resul = $stmt->execute();
                return $resul;
            }
            else{
                return NULL;
            }
        }
    	
    	    	
		
}// fin clase

//$a = new BD();
//$a->buscar_usuarios();
//echo $a->agregarTweet(rand(),rand(),"Prueba - 1","Domingo 2014", 66, 88, 123456  );
//$id_tweet,$id_str,$text,$created_at,$retweet_count,$favorite_count,$user_id
//echo $a->agregarUsuarioTweet(rand(),"Coco","COquito",rand(), "Caracas", 5000, "Descripcion larga"  );
//$user_id, $name, $screen_name, $id_str, $location, $followers_count, $description

?>