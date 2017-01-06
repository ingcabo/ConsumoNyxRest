<?php

include_once './Clases/loginnyx.php';
//include_once './Clases/Login.php';
include_once './RedireccionFormulario.php';
include './constantes.php';
include_once './Clases/email.php';




 $email = new email();
     	
     	 $emailDestino= 'ingcabo@gmail.com';
     	 $control = 434324324;
     	
     	
     	
     	
     	// $partsup = $email->superior('','','Pendiente');
     	
     	// $partinf = $email->inferior('','',$control);
     	 
     	 $menssage = 'esto es un test'; 
     	  
 	 $enviamail=$email->sendemail($emailDestino, $menssage,"mi prueba de correo, no tomar en cuenta");

?>