<?php
class Login {
    
    function loginHTTPS($url,$user,$password){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //Dirección URL a capturar
        curl_setopt($ch, CURLOPT_USERPWD, $user.':'.$password);//Nombre de usuario 
                                                                   //y contraseña

        curl_setopt($ch, CURLOPT_HTTPAUTH,CURLAUTH_BASIC); //método de autenticación 
                                                           //HTTP

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        
        
       // curl_setopt($ch, CURLOPT_PORT,"8443");
        
        curl_setopt($ch, CURLOPT_FRESH_CONNECT,1);
        
        
         curl_setopt($ch, CURLOPT_MAXREDIRS,10);
        
        
         curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
         
         curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
         
         

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //verificación peer 
                                                         //del certificado

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //devuelve el resultado de la 
                                                       //transferencia como string del 
                                                       //valor de curl_exec() en lugar 
                                                       //de mostrarlo directamente

        curl_setopt($ch, CURLOPT_TIMEOUT, 200); // máximo de segundos permitido para 
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);                                       //ejectuar funciones cURL

        $result = curl_exec($ch);

            if ( curl_errno($ch) ) {
                $result = 'ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
            } else {

                $returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
                switch($returnCode){
                    case 200:
                        break;
                    default:
                        $result = 'HTTP ERROR -> ' . $returnCode;
                        break;
                }
            }

        curl_close($ch);
        
        return $result;
    }
    
}

?>
