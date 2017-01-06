<?php
class Login {
    
    function loginHTTPS($url,$user,$password){
        
        $ch = curl_init();

  curl_setopt_array($ch, array(
  CURLOPT_URL =>$url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
   CURLOPT_TIMEOUT => 40,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
     "authorization: Basic bGNhcnF1ZXo6TiN5eEJlbjgx",
    "cache-control: no-cache"
    
    
  ),
));

                                  //ejectuar funciones cURL

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
            
             $archivo=fopen("log.txt",'a+');
 	    fwrite($archivo,"que dice el preregistro\n");
  	    fwrite($archivo,"que dice el preregistro respuesta: ".$result."\n");
    


        curl_close($ch);
        
        return $result;
    }
    
}

?>
