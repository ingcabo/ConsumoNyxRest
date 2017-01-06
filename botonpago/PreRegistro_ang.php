<?php

include_once './Clases/Login.php';
include_once './RedireccionFormulario.php';
include './constantes.php';

$archivo=fopen("log.txt",'a+');
fwrite($archivo,"PreRegistro\n");


$factura = $_GET['factura'];
$monto   = $_GET['monto'];
/*se deben enviar dos ceros decimales*/
$monto   = $monto."00";

$url_nix = "http://www.nyxvzla.com/nixapiRest/index.php/Myrest/control";

$url = "https://200.71.151.226:8443/payment/action/paymentgatewayuniversal-".
    "prereg?cod_afiliacion=".CODAFILIACION."&factura=".$factura."&monto=".$monto;





fwrite($archivo,"$url\n");

$control = new Login();

fwrite($archivo,"Obteniendo Login\n");
fwrite($archivo,"Datos a enviar:\n");
fwrite($archivo,"".$url.",".USERNAME.",".PASSWORD."\n");

$numeroControl=$control->loginHTTPS($url,USERNAME,PASSWORD);





$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url_nix,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "control: ".$numeroControl,
    "lote: ".$factura
   
  ),
));




if (is_numeric($numeroControl)){


            //$response = array();
            $response = curl_exec($curl);

            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
          
             $response = json_decode($response);        

            }


//$response = json_decode($response,true);


fwrite($archivo,"Control: $numeroControl \n");

fwrite($archivo,"Respuesta ingreso de numero de control en Nyx: ".$response->mensaje." \n");

fwrite($archivo,"Control: $numeroControl \n");







//echo 'hola mundo'.$factura .'xxxxxxxxx'.$monto;

              if ($response->bandera ==0){
              
              RedireccionFormulario::redireccionar($numeroControl);
              
              //  echo "aca si vamos a la compra";
               

              }else{

              echo $response->mensaje;
             
             
              }





}else{



 echo "No podemos procesar su compra en estos momentos";
 echo "<br> <br>";
 echo $numeroControl;





}




?>
