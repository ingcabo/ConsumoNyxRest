<?php

include_once './Clases/Login.php';
include_once './RedireccionFormulario.php';
include './constantes.php';


   
             
/*parametro para guardar la compra en nyx paso 1*/
$compra = unserialize(base64_decode($_POST['urlrcv']));
$nombre = $_POST['nombres'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$identidad = $_POST['identidad'];
$datos_env = $_POST['datos_env'];
$monto_total = $_POST['monto_total'];

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

           //http://nyxvzla.com/NyxapiRest/index.php/myrest/lote
$url_lote = 'http://www.nyxvzla.com/NyxapiRest/index.php/Myrest/lote';
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
    "lote:".$lote,
    "content-type: application/json"
    
  ),
));


      $archivo=fopen("log.txt",'a+');
      fwrite($archivo,"insertar compra\n");
      fwrite($archivo,"lote:".$lote."\n");
      fwrite($archivo,"detalle compra:\n");
      fwrite($archivo,$compra."\n");
  
$response_lote = curl_exec($curl_lote);
$err_lote = curl_error($curl_lote);
 fwrite($archivo,"Respuesta numero lote:".$err_lote."\n");
fwrite($archivo,"Respuesta numero lote:".$response_lote."\n");
$response_lote  = json_decode($response_lote);

curl_close($curl_lote);

    if ($err_lote) {
      fwrite($archivo,"Respuesta numero lote:".$err_lote."\n");
      RETURN "cURL Error #:" . $err_lote;
      
    } else {
       fwrite($archivo,"Respuesta numero lote:".$response_lote->id_lote."\n");
      RETURN $response_lote->id_lote;

    }





}


function control_mega($nlote,$monto_total){

     $factura = $nlote;
     $monto   = $monto_total;
     $monto   = $monto."00";


      $url = "https://200.71.151.226:8443/payment/action/paymentgatewayuniversal-".
          "prereg?cod_afiliacion=".CODAFILIACION."&factura=".$factura."&monto=".$monto;



   //   fwrite($archivo,"$url\n");

      $control = new Login();
      $archivo=fopen("log.txt",'a+');
      fwrite($archivo,"PreRegistro\n");
      fwrite($archivo,"$url\n");
      fwrite($archivo,"Obteniendo Login\n");
      fwrite($archivo,"Datos a enviar:\n");
      fwrite($archivo,"".$url.",".USERNAME.",".PASSWORD."\n");


      $numeroControl=$control->loginHTTPS($url,USERNAME,PASSWORD);
      
     // $numeroControl = 12354999;
      fwrite($archivo,"Numero de control:".$numeroControl."\n");
      RETURN $numeroControl;
}




  function update_lote_control($factura,$numeroControl){


      $url_nix = "http://127.0.0.1/nixapiRest/index.php/Myrest/control";
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
           
             fwrite($archivo,"respuesta :".$response."\n");
             $response = json_decode($response);  

             return $response->bandera;
            }



}


 $archivo=fopen("log.txt",'a+');
 fwrite($archivo,"******INICIO******\n");


$nlote =  lote($compra,$lote); 


if (is_numeric($nlote)){

//$numeroControl = 12545;
$numeroControl = control_mega($nlote,$monto_total);
//$numeroControl = "error -conexion 129";
}else{

  echo "no podemos gestionar su compra";
  echo "<br><br><br>"; 
}




if (is_numeric($numeroControl)){

$update_nyx = update_lote_control($nlote,$numeroControl);
//echo $update_nyx;

}else{

  $update_nyx = 4;
  echo "el numero de control no es un numero valido";
  echo "<br><br><br>"; 
}



if ($update_nyx==0){

  $archivo=fopen("log.txt",'a+');
  fwrite($archivo,"redireccionando formulario a formulario de pago Megasog\n");
  fwrite($archivo,"redireccionando numero de control:".$numeroControl."\n");

//RedireccionFormulario::redireccionar($numeroControl);

}else{
 
  $archivo=fopen("log.txt",'a+');
  fwrite($archivo,"redireccionando formulario a formulario de pago Megasog\n");
  fwrite($archivo,"No Podemos redireccionando numero de control:".$numeroControl."\n");
  fwrite($archivo,"Razon : aparentemente no se actualizo el numero de control en conjunto con el numero de lote en NYX\n");
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
               









?>
