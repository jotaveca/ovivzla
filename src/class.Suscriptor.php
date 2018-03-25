<?php
class Suscriptor{

	private $nombreApellido;
	private $correoElectronico;
	private $preferenciasHora;
  	private $telefono;
  	private $activo;
  	private $fecha_nac;
  	private $sexo;
	private $preferencias; //array 
	private $bd;
  	private $log; 
	
	function __construct() {
	
		require_once('class.BD.php');
    include_once("class.Log.php");
		
		$this->bd = new BD();
    $this->log = new Log(); 
      	
   	}
   	
   	public function __set($property, $value) {
        	if (property_exists($this, $property)) {
	            $this->$property = $value;
        	}
    	}
    	
    	public function __get($property) {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
   	 }
   	

   	function __destruct() {
       
   	}

      
    public function eliminarSuscriptor($id_suscriptor){
    
        $sql = "DELETE FROM tr005_suscriptores WHERE id_suscriptor = :id_suscriptor"; 
        $sql2 = "DELETE FROM tr005_suscriptores WHERE id_suscriptor = $id_suscriptor"; 
        $con = $this->bd->obtenerConexionSegura();                        
        $stmt = $con->prepare($sql);      

        $fecha = date("Y-m-d H:i:s"); 
        $msg = "[Suscriptor eliminarSuscriptor()] | $fecha | $sql2 ";
        $this->log->general($msg);    
                         
        $salida = $stmt->execute(array(':id_suscriptor'=>$id_suscriptor));
        
        return $salida;
    

    }

      /**
      * Total de suscriptores
      *
      */
      public function total_suscriptores(){        
      
        $con = $this->bd->obtenerConexion();    
        return $count = current($con->query("SELECT count(distinct(tx_correo_electronico)) FROM tr005_suscriptores")->fetch());
      
      }
   	
   	public function obtenerSuscriptores(){
   	
   		$sql  = "SELECT * FROM tr005_suscriptores";		
   		$suscriptores=  $this->bd->listarRegistros($sql);
		   return $this->bd->utf8_converter($suscriptores);
   	
   	}
   	
   	public function obtenerSuscriptoresActivos(){
   	
   		$sql  = "SELECT * FROM tr005_suscriptores WHERE activo = 1";
		//echo $sql;
   		$tweet=  $this->bd->listarRegistros($sql);
		return $tweet;
   	
   	}

    public function obtenerSuscriptoresXID($id_suscriptor){
    
      $sql  = "SELECT * FROM tr005_suscriptores WHERE id_suscriptor = $id_suscriptor";
      //echo $sql;
      $r =  $this->bd->listarRegistros($sql);
      return $r;
    
    }

     public function obtenerSuscriptoresXCorreo($correo){
    
      $correo = trim($correo);
      $sql  = "SELECT * FROM tr005_suscriptores WHERE tx_correo_electronico  = '".$correo."'";
      //echo $sql;
      $r =  $this->bd->listarRegistros($sql);
      return $r;
    
    }
   	
   	public function obtenerSuscriptoresActivosAreaInteres($tx_preferencia){
   	
   		$sql  = "SELECT * FROM tr005_suscriptores WHERE activo = 1 AND tx_preferencia = '$tx_preferencia'";
      //$sql2  = "SELECT * FROM tr005_suscriptores WHERE activo = 1 AND tx_preferencia = '$tx_preferencia'";
		  //echo $sql;

      $fecha = date("Y-m-d H:i:s"); 
      $msg = "[Suscriptor obtenerSuscriptoresActivosAreaInteres()] | $fecha | $sql ";
      $this->log->general($msg);  


   		$tweet=  $this->bd->listarRegistros($sql);
		return $tweet;
   	
   	}
   	
   	public function deshabilitarSuscriptor($tx_correo_electronico){
   	
   		$sql = "UPDATE tr005_suscriptores SET activo = 0 WHERE tx_correo_electronico = :tx_correo_electronico";	
		//echo $sql ;
		$con = $this->bd->obtenerConexion();						
		$stmt = $con->prepare($sql);		
						 
		$salida = $stmt->execute(array(':tx_correo_electronico'=>$tx_correo_electronico));
		
		return $salida;
   	
   	}

    public function modificarSuscriptor($id_suscriptor){
    
      $sql = "UPDATE tr005_suscriptores SET tx_nombre_apellido = :tx_nombre_apellido, tx_correo_electronico = :tx_correo_electronico, tx_preferencia = :tx_preferencia, tx_preferencia_hora = :tx_preferencia_hora, tx_telefono = :tx_telefono, activo = :activo WHERE id_suscriptor = :id_suscriptor"; 
   
    $con = $this->bd->obtenerConexion();            
    $stmt = $con->prepare($sql); 


    $nombreApellido =  $this->__get("nombreApellido");
    $correoElectronico = $this->__get("correoElectronico");
    $preferencias = $this->__get("preferencias");
    $preferenciasHora = $this->__get("preferenciasHora");
    $tx_telefono = $this->__get("telefono");
    $activo = $this->__get("activo");   

     $sql2 = "UPDATE tr005_suscriptores SET tx_nombre_apellido = '$nombreApellido', tx_correo_electronico = '$correoElectronico', tx_preferencia = '$preferencias', tx_preferencia_hora = '$preferenciasHora', tx_telefono = '$tx_telefono', activo =  $activo WHERE id_suscriptor = $id_suscriptor"; 
    //echo $sql2 ;
             
    
    $salida = $stmt->execute(array(':tx_nombre_apellido'=>$nombreApellido,':tx_correo_electronico'=>$correoElectronico,':tx_preferencia'=>$preferencias,':tx_preferencia_hora'=>$preferenciasHora,':tx_telefono'=>$tx_telefono,':activo'=>$activo, ':id_suscriptor'=>$id_suscriptor));  
    
    return $salida;
    
    }
   	
   	public function agregarSuscriptor(){
   	
   		$sql = "INSERT INTO tr005_suscriptores (tx_nombre_apellido,tx_correo_electronico, sexo, fecha_nac, tx_preferencia, tx_preferencia_hora, fecha_suscripcion, tx_telefono, activo) VALUES (:tx_nombre_apellido,:tx_correo_electronico, :sexo, :fecha_nac,:tx_preferencia, :tx_preferencia_hora, :fecha_suscripcion, :tx_telefono, :activo)";
   		
   		$con = $this->bd->obtenerConexion();						
		  $stmt = $con->prepare($sql); 
		
		  $nombreApellido =  $this->__get("nombreApellido");
		  $correoElectronico = $this->__get("correoElectronico");
		  $preferencias = $this->__get("preferencias");
		  $preferenciasHora = $this->__get("preferenciasHora");
      		  $tx_telefono = $this->__get("telefono");
      		  $activo = $this->__get("activo");
      		  
      		  $sexo 	= $this->__get("sexo");
      		  $fecha_nac 	=	$this->__get("fecha_nac");
      
      		$fechaSuscripcion = date("Y-m-d");   
		
		
	  for($i=0;$i<count($preferencias);$i++){

          //echo "P ".$preferencias[$i];

          $stmt->execute(array(':tx_nombre_apellido'=>$nombreApellido,':tx_correo_electronico'=>$correoElectronico,':sexo'=>$sexo,':fecha_nac'=>$fecha_nac,':tx_preferencia'=>$preferencias[$i],':tx_preferencia_hora'=>$preferenciasHora,':fecha_suscripcion'=>$fechaSuscripcion,':tx_telefono'=>$tx_telefono,':activo'=>$activo));   
      }     			   
		
		  return $lastId = $con->lastInsertId();
		
   	}
        /*
         * Actualiza la cantidad de tuits de interes que ha realizado cada usuario
         * 
         */
        public function actualizarCantTuitsInteres(){
            $sql  = "CALL sp_actualizar_cant_tuits()";
            $resul= $this->bd->ejecutarProcedimiento($sql);
            return $resul;
            
        }

}

/*$s = new Suscriptor();
$s->__set("nombreApellido","Juan Cisneros");
$s->__set("correoElectronico","juanv.cisneros@gmail.com");
$s->__set("preferencias","#PNM");
//echo  $s->__get("nombreApellido");


echo $s->agregarSuscriptor();*/

?>