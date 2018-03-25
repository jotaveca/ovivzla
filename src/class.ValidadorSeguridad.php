<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ValidadorSeguridad{
    
    public function __construct($hash, $entorno) {
        $this->validar($hash, $entorno);
    }    
    
    private function validar($hash, $entorno){        
                               
        if($hash=='$1$Cccp8eeR$oIvQBOlR9pPTQanb2FtZY1'){	
                    return true;                   
        }
        else{               
            die("\nAcción no autorizada: ".$entorno." ". date("d-m-Y H:i:s"));
        }
    }
}