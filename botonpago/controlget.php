<?php
 
 include_once './Clases/email.php';
 // header("Location: https://200.71.151.226:8443/payment");
 //  die();
 
 
     $factura= 123456;
     $monto   =340000;
     $afiliacion =20150311;
     $url = "https://200.71.151.226:8443/payment/action/paymentgatewayuniversal-".
          "prereg?cod_afiliacion=".$afiliacion."&factura=".$factura."&monto=".$monto;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "8443",
   CURLOPT_FRESH_CONNECT  => 1,
  CURLOPT_URL =>$url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_SSL_VERIFYHOST=>false,
  CURLOPT_SSL_VERIFYPEER=>false,
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_NOSIGNAL=> 1,
  CURLOPT_TIMEOUT => 200,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: Basic dW5ldGFjaGlyYToxMjNUYWNoaXJhKg==",
    "cache-control: no-cache"
    
  ),
));


 $email = new email();

  $enviamail=$email->sendemail("ingcabo@gmail.com","ddddddd","dfsfsdfsdf");



$response = curl_exec($curl);
$err = curl_error($curl);

 $archivo=fopen("ejemplo.txt",'a+');

  fwrite($archivo,"que dice el preregistro respuesta: ".$response."\n");
    fwrite($archivo,"que dice el preregistro error: ".$err."\n");

 echo $err;

curl_close($curl);

if ($err) {
  RETURN "cURL Error #:" . $err;
} else {
 echo "numero de control: ". $response;

  RETURN $response;
}



?>