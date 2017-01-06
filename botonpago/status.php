<?php
   // include_once './Clases/Login.php';
    include_once './Clases/loginnyx.php';
    include_once './Clases/LeerXML.php';
    include_once './Clases/nyxapirest.php';
    include_once './constantes.php';
    include_once './Clases/email.php';
    

    require_once( $_SERVER['DOCUMENT_ROOT'] . '/nix/site/wp-load.php' );
    global $woocommerce;	
    global $wpdb;

    $url_home = $wpdb->get_row("SELECT option_value AS url_home FROM wp_options WHERE option_name = 'home' limit 1");
    //https://e-payment.megasoft.com.ve/
    //https://200.71.151.226:8443
    $archivo=fopen("log.txt",'a+');
    fwrite($archivo,"PaginaWeb: Llegando a nuestro servidor\n");
    $numeroControl= $_GET['control'];
    fwrite($archivo,"Control: $numeroControl\n"); 
    $url="https://e-payment.megasoft.com.ve/payment/action/paymentgatewayuniversal-querystatus?control=$numeroControl";
    fwrite($archivo,"URL de petici¨®n de XML: $url\n");
    
    $control = new Login();
    $status = new rest();
    fwrite($archivo,"Obteniendo Login\n");
    fwrite($archivo,"Datos a enviar:\n");
    fwrite($archivo,"URL:".$url." USERNAME: ".USERNAME." PASSWORD: ".PASSWORD."\n");

    
   // $xml=$control->loginHTTPS($url,USERNAME,PASSWORD);  
  //  $xml =  statusCompra($numeroControl);
   
   
   
    //$rxml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
    $json = json_encode($rxml);

    $json= '{"control":"213599136890","cod_afiliacion":"20160705","factura":"777","monto":"650000","estado":"A","codigo":"00","descripcion":"APROBADO","vtid":"00004002","seqnum":"2","authid":"453589","authname":"P-EComCorpBanca","tarjeta":{},"referencia":"000002","terminal":"00004002","lote":"1","rifbanco":"J-00064359-8","afiliacion":"20160705","voucher":" \n         BOD BANCO UNIVERSAL\n          RIF J-30061946-0\n          RECIBO DE COMPRA\n             MASTERCARD\n\nBEN & CO\nCARACAS\nRIF:J-2016061-6\nAFILIADO:0075235615\nTERMINAL:00004002 LOTE:1\n554395******7160\nFECHA:25\/07\/2016 HORA:18:56:28\nAPROB:453589 REF:000002\nTRACE:3\nCAJA :BENCO02\nSECUENCIA:2\n<UT>      ** DUPLICADO ** <\/UT>\n\n   MONTO      BS. 6.500,00\n\n\nME OBLIGO A PAGAR AL BANCO EMISOR\nDE ESTA TARJETA EL MONTO DE ESTA \nNOTA DE CONSUMO\n\n"}';
    
    
    

        
        fwrite($archivo,"resultado en formato json: ".$json."\n");
        
        fwrite($archivo,"llamando a funcion para ingreso de compra: \n");
       
       $url= "http://www.nyxvzla.com/NyxapiRest/index.php/Myrest/status";
      
       $ws =$status->api($url,$json); 
       
        fwrite($archivo,"respuesta ingreso de pago en nyx: \n");
        fwrite($archivo,"respuesta:".$ws." \n");
       
    
    fclose($archivo);


  
    
    


    
?>

<!DOCTYPE html>
<html lang="en">
  <head><meta http-equiv="Content-Type" content="text/html; charset=big5">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Resultado Pago de Productos</title>

    <!-- Bootstrap -->
      <link rel="stylesheet" type="text/css" href="<?php echo $url_home->url_home; ?>/nix/site/node_modules/bootstrap/dist/css/bootstrap.min.css"/>



  <!-- Custom CSS -->
    <link href="<?php echo $url_home->url_home; ?>/nix/site/node_modules/bootstrap/dist/css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo $url_home->url_home; ?>/nix/site/node_modules/bootstrap/dist/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <script type="text/javascript">
function closeCurrentWindow()
{
   window.close();


}
</script>
   
  </head>
  <body>


<br/>
<br/>
<br/>
<br/>
 <?php $json =  $ws;?>
 <?php $ws= json_decode($ws); 
$pie="Nyx Cosmetic Venezuela";
 $alert= "alert alert-info";
 $estaus = "Pendiente";

 if ($ws->data->estado=="P"){
        $estado = "Pendiente";
        $alert= "alert alert-danger";


 } 
  if ($ws->data->estado=="R"){
  	 $estado = "Rechazado";
        $alert= "alert alert-danger";
 } 
  if ($ws->data->estado=="A"){
        $woocommerce->cart->empty_cart(); 
         $estado = "Aprobado";
        $alert= "alert alert-success";
        $pie= "Nuestros especialistas se comunicaran con usted a traves de los datos suministrados en el formulario de compra";
    
 } 
 $voucher = nl2br($ws->data->voucher,true);

 ?>

<div class="container-fluid">
            <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Resultado Metodo de Pago</h3>
              </div>
              <div class="panel-body">
     <div class="<?php echo $alert; ?>" role="alert"><?php echo $estado; ?></div>
               
            
 
         <div class="row" >

            <div class="col-sm-6 col-md-3">

            Numero de factura: <?php echo   $ws->data->factura; ?>
            <br/>
            <br/>
            Numero de conrtrol: <?php echo   $ws->data->control; ?>
        
         
           </div>
            
            <div class="col-sm-6 col-md-3">
                        

                <?php echo   $voucher; ?>

           </div> 

           <div class="col-sm-6 col-md-3">
                        

                Estatus compra: <?php echo $ws->descripcion; ?>
                <br/>
            <br/>
                Nombre: <?php echo $ws->dato->nombres; ?>
                <br/>
           
            <br/>
                Cedula: <?php echo $ws->dato->doc_identidad; ?>
                <br/>
            <br/>
                Numero telefono:<?php echo $ws->dato->telefono; ?>
                     <br/>
            <br/>
            Email:  <?php echo $ws->dato->email; ?>
                      <br/>
            <br/>
            Direccion: <?php echo $ws->dato->datos_env; ?> 
           </div>       
                
       

              </div>
              <div class="panel-footer"><?php echo $pie; ?></div>
            </div>
</div>            
<br/>

 <form>
<input type="button" class="btn btn-info" value="Regresar al Carrito" onclick="document.location.href='http://WWW.nyxvzla.com/carro/'
     "> 


   
</form>  

<br/>
  
<?php


//print_r($ws);

      global $emailDestino;
      $email = new email(); 
      
      
      $lote = json_encode($ws->dato);
      $compra = json_encode($ws->comprad);
      
     // print_r($compra);
      
      
     // $array = json_decode($json);
      
    
      
      //$compra = $array->comprad;

      
      
      
       
      
      
     // print_r($ws);
     
     
     
      if ($ws->data->estado=="P"){
     
     	$estado= " Transaccion Pendiente ";
     }
     
     if ($ws->data->estado=="R"){
     
     	$estado= " Transaccion Rechazada ";
     }
     
      if ($ws->data->estado=="A"){
     
     	$estado= " Transaccion Aprobada ";
     }
     
     
     $partsup = $email->superior($ws->data->factura,$ws->data->control,$estado);	
      
      $partinf = $email->inferior($lote,$compra,$numeroControl);
      $menssage = $partsup.$partinf; 
      
      $enviamail=$email->sendemail($ws->dato->email,$menssage,'3/3 '.$estado);


 ?>


  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src='<?php echo $url_home->url_home; ?>/nix/site/node_modules/jquery/dist/jquery.min.js'></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src='<?php echo $url_home->url_home; ?>/nix/site/node_modules/bootstrap/dist/js/bootstrap.min.js'></script>

   
    


    <script src='<?php echo $url_home->url_home; ?>/nix/site/node_modules/angular/angular.min.js'></script>
     <script src='<?php echo $url_home->url_home; ?>/nix/site/node_modules/angular-resource/angular-resource.min.js'></script>
    <script src='<?php echo $url_home->url_home; ?>/nix/site/node_modules/app/app.js'></script>
    <script src='<?php echo $url_home->url_home; ?>/nix/site/node_modules/ng-grid/lib/dirPagination.js'></script>
  </body>
</html>