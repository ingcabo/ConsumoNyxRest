<?php

include_once './Clases/loginnyx.php';
//include_once './Clases/Login.php';
include_once './RedireccionFormulario.php';
include './constantes.php';
include_once './Clases/email.php';



   
             
/*parametro para guardar la compra en nyx paso 1*/
$compra = unserialize(base64_decode($_POST['urlrcv']));
//print_r($compra);
$nombre = $_POST['nombres'];
$email = $_POST['email'];
$clienteemail=$_POST['email'];
$telefono = $_POST['telefono'];
$identidad = $_POST['identidad'];
$datos_env = $_POST['datos_env'];
$monto_total = $_POST['monto_total'];
$emailDestino="";

/*parametro para guardar la compra en nyx paso 1*/


/*creamos una array llamado lote*/
$lote = array(

  'nombres' => $nombre,
  'email'   => $email,
  'telefono'=> $telefono,
  'doc_identidad' => $identidad,
  'datos_env'  => $datos_env,
  'monto_total' =>$monto_total
);
/*creamos una array llamado lote*/

/*ajustamos a formato json los array lote y compra detalle*/
$compra = json_encode($compra);
$lote = json_encode($lote);
/*ajustamos a formato json los array lote y compra detalle*/

 







 function lote($compra,$lote){

//http://www.nyxvzla.com
$url_lote = 'http://127.0.0.1/NyxapiRest/index.php/Myrest/lote';
$curl_lote = curl_init();

curl_setopt_array($curl_lote, array(
  CURLOPT_URL => $url_lote,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "compra:".$compra,
    "lote:".$lote
    
  ),
));


      $archivo=fopen("log.txt",'a+');
      fwrite($archivo,"insertar compra\n");
      fwrite($archivo,"lote:".$lote."\n");
      fwrite($archivo,"detalle compra:\n");
      fwrite($archivo,$compra."\n");
  
$response_lote = curl_exec($curl_lote);
$err_lote = curl_error($curl_lote);

//$response_lote  = json_decode($response_lote);
$response_lote  = json_decode($response_lote);

curl_close($curl_lote);

    if ($err_lote) {
      fwrite($archivo,"Respuesta numero lote Error:".$err_lote."\n");
      
      RETURN "cURL Error #:" . $err_lote;
      
    } else {
       
     
       
       
      
     	 $email = new email();
     	 global $emailDestino;
     	 global $lote;
     	 global $compra;
     	 global $clienteemail;
     	 $emailDestino= $clienteemail;
     	 $idlote= $response_lote->id_lote;
     	 
     	fwrite($archivo,"enviar email a:".$emailDestino."\n"); 
     	fwrite($archivo,"Respuesta numero lote:".$idlote."\n");
     	

     	
      $partsup = $email->superior($idlote,'','Pendiente');	
      $partinf = $email->inferior($lote,$compra,'');
      $menssage = $partsup.$partinf;      
      
      	  
 	 $enviamail=$email->sendemail($emailDestino, $menssage,"1/3 Asignacion numero de factura");

       
      
      RETURN $response_lote->id_lote;

    }





}
//https://e-payment.megasoft.com.ve/

function micontrol($nlote,$monto_total){

     $factura = $nlote;
     $monto   = $monto_total;
     $monto   = $monto."00";
     $url = "https://e-payment.megasoft.com.ve/payment/action/paymentgatewayuniversal-".
          "prereg?cod_afiliacion=".CODAFILIACION."&factura=".$factura."&monto=".$monto;
     

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

$response = curl_exec($curl);
$err = curl_error($curl);

 $archivo=fopen("log.txt",'a+');
 fwrite($archivo,"que dice el preregistro\n");
  fwrite($archivo,"que dice el preregistro respuesta: ".$response."\n");
    fwrite($archivo,"que dice el preregistro error: ".$err."\n");


curl_close($curl);

if ($err) {
  RETURN "cURL Error #:" . $err;
} else {
  RETURN $response;
}




  }
//https://e-payment.megasoft.com.ve/
//https://200.71.151.226:8443

function control_mega($nlote,$monto_total){

     $factura = $nlote;
     $monto   = $monto_total;
     $monto   = $monto."00";
     $codigoafi = 20160705;


      $url = "https://e-payment.megasoft.com.ve/payment/action/paymentgatewayuniversal-".
          "prereg?cod_afiliacion=".$codigoafi."&factura=".$factura."&monto=".$monto;



   //   fwrite($archivo,"$url\n");

      $control = new Login();
      $archivo=fopen("log.txt",'a+');
      fwrite($archivo,"PreRegistro\n");
      fwrite($archivo,"$url\n");
      fwrite($archivo,"Obteniendo Login\n");
      fwrite($archivo,"Datos a enviar:\n");
      fwrite($archivo,"".$url.",".USERNAME.",".PASSWORD."\n");


     $numeroControl=$control->loginHTTPS($url,USERNAME,PASSWORD);
     
      fwrite($archivo,"Numero de control:".$numeroControl."\n");
      fwrite($archivo,"correo control:".$emailDestino."\n");
      
      global $emailDestino;
      global $lote;
      global $compra;
      $email = new email();
      $partsup = $email->superior($factura,$numeroControl,'Pendiente');
     	
      $partinf = $email->inferior($lote,$compra,$numeroControl);
      $menssage = $partsup.$partinf; 
      
       
      $enviamail=$email->sendemail($emailDestino,$menssage,"2/3 asignacion numero de factura y numero de control");
      
      RETURN $numeroControl;
}




  function update_lote_control($factura,$numeroControl){


      $url_nix = "http://127.0.0.1/NyxapiRest/index.php/Myrest/control";
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url_nix,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_NOSIGNAL=> 1,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "Control: ".$numeroControl,
          "Lote: ".$factura
         
        ),
      ));


          $archivo=fopen("log.txt",'a+');
          fwrite($archivo,"Update numero de control a factura Nyx\n");
          fwrite($archivo,"Numero de Control:".$numeroControl."\n");
          fwrite($archivo,"Numero de lote:".$factura."\n");


            $response = curl_exec($curl);

            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
              echo "cURL Error #:" . $err;
              fwrite($archivo,"respuesta :".$err."\n");
              return $err;
            } else {
           
            
             $response = json_decode($response);  

             return $response->bandera;
            }



}


 $archivo=fopen("log.txt",'a+');
 fwrite($archivo,"******INICIO******\n");


$nlote =  lote($compra,$lote); 


if (is_numeric($nlote)){


//$numeroControl = control_mega($nlote,$monto_total);
//$numeroControl = micontrol($nlote,$monto_total);
$numeroControl=653623;
}else{
  

 $valor= "en estos momentos no podemos procesar su compra llegamos a uno";
 $url="/nix/site/wp-content/plugins/woocommerce/templates/cart/botonpago/error.php?text=$valor";      
  header("Location: $url");
}




/*
if (is_numeric($numeroControl)){



$update_nyx = update_lote_control($nlote,$numeroControl);
//echo $update_nyx;

}else{

  $update_nyx = 4;
  $valor= "en estos momentos no podemos procesar su compra 2";
  $url="/site/wp-content/plugins/woocommerce/templates/cart/botonpago/error.php?text=$valor";      
  header("Location: $url");
}



if ($update_nyx==0){

  $archivo=fopen("log.txt",'a+');
  fwrite($archivo,"redireccionando formulario a formulario de pago Megasog\n");
  fwrite($archivo,"redireccionando numero de control:".$numeroControl."\n");

//RedireccionFormulario::redireccionar($numeroControl);
   $archivo=fopen("log.txt",'a+');
   fwrite($archivo,"RedireccionFormulario: Redireccionando\n");
   fwrite($archivo,"Control: $control \n");
   fwrite($archivo,"update: $update_nyx \n");
   header("Location: https://e-payment.megasoft.com.ve/payment/action/paymentgatewayuniversal-data?control=".$numeroControl);
   die();

}else{
 
  $archivo=fopen("log.txt",'a+');
  fwrite($archivo,"redireccionando formulario a formulario de pago Megasog\n");
  fwrite($archivo,"No Podemos redireccionando numero de control:".$numeroControl."\n");
  fwrite($archivo,"Razon : aparentemente no se actualizo el numero de control en conjunto con el numero de lote en NYX\n");
  
  $valor= "en estos momentos no podemos procesar su compra 3";
  $url="/nix/site/wp-content/plugins/woocommerce/templates/cart/botonpago/error.php?text=$valor";      
  header("Location: $url");
}

fwrite($archivo,"******FIN******\n");
fwrite($archivo,"\n");
fwrite($archivo,"\n");




















//$numeroControl = "error -conexion 129";









//fwrite($archivo,"Control: $numeroControl \n");

//fwrite($archivo,"Respuesta ingreso de numero de control en Nyx: ".$response->mensaje." \n");

//fwrite($archivo,"Factura: $factura \n");




 


//echo 'hola mundo'.$factura .'xxxxxxxxx'.$monto;

       
              
            //  RedireccionFormulario::redireccionar($numeroControl);
              
              //  echo "aca si vamos a la compra";
               

*/







?>
