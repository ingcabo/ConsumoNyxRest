<?php
/**
 * Description of RedireccionFormulario
 *
 * @author arlopez
 */
class RedireccionFormulario {
    
    public function redireccionar($control){
        
        $archivo=fopen("log.txt",'a+');
        fwrite($archivo,"RedireccionFormulario: Redireccionando\n");
        fwrite($archivo,"Control: $control \n");
        
        $url="https://200.71.151.226:8443/payment/action/paymentgatewayuniversal-data?control=$control";
        $url="https://200.71.151.226:8443/payment/login.htm";
        
        header("Location: $url");
        
    }
    
}

?>
