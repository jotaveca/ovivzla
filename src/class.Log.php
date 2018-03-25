<?php
Class Log {
  
  
  const GENERAL_LOG_DIR = '/home/oviorgve/public_html/log/ovi_log.log';  
   
   
   function __construct() {		
		
	
		
     }
   
   /**
   *
   * Registra los errores en el log
   */
    public function general($msg)
    {
    	$date = date('d.m.Y h:i:s');
    	$log = $msg." | Fecha:  ".$date."\n";
    	//echo $log;
    	error_log($log,3,self::GENERAL_LOG_DIR);
    }
    
    
    

} //fin clase

//$log = new Log();
//$msg = "ERROR | ".__CLASS__." | ".__METHOD__." | ".__FUNCTION__." | ";
//$msg = "ERROR | ";
//$log->general($msg);

//echo $log->registrarLogBd("212313123,53453534534,6346464645645,8768678678768768,63463464645645645,",9,"nuevo");
?>