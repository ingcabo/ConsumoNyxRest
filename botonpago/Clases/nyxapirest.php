<?php
class rest{
    
    function api($url,$json){
        
        $ch = curl_init();

  curl_setopt_array($ch, array(
  CURLOPT_FRESH_CONNECT  => 1,
  CURLOPT_URL =>$url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
 // CURLOPT_SSL_VERIFYHOST=>false,
 // CURLOPT_SSL_VERIFYPEER=>false,
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_NOSIGNAL=> 1,
  CURLOPT_TIMEOUT => 200,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_HTTPHEADER => array(
    "status:".$json,
     "content-type: application/json",
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
  	    fwrite($archivo,"respuesta ingreso xml en nyx: ".$result."\n");
    


        curl_close($ch);
        
        return $result;
    }
    
}

?>