<?php

    require_once( $_SERVER['DOCUMENT_ROOT'] . '/nix/site/wp-load.php' );

    global $wpdb;

    $url_home = $wpdb->get_row("SELECT option_value AS url_home FROM wp_options WHERE option_name = 'home' limit 1");
    
    

    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Pagina Error</title>

    <!-- Bootstrap -->
      <link rel="stylesheet" type="text/css" href="<?php echo $url_home->url_home; ?>/nix/site/node_modules/bootstrap/dist/css/bootstrap.min.css"/>



  <!-- Custom CSS -->
    <link href="<?php echo $url_home->url_home; ?>/nix/site/node_modules/bootstrap/dist/css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?php echo $url_home->url_home; ?>/nix/site/node_modules/bootstrap/dist/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
$pie="Nyx Venezuela en estos momentos no podemos procesar su compra";
 $alert= "alert alert-info";

 if ($ws->data->estado=="P"){
        $alert= "alert alert-info";


 } 
  if ($ws->data->estado=="R"){
        $alert= "alert alert-danger";
 } 
  if ($ws->data->estado=="A"){
        $alert= "alert alert-success";
        $pie= "Nuestros especialistas se comunicaran con usted a traves de los datos suministrados en el formulario de compra";
    
 } 
 $voucher = nl2br($ws->data->voucher,true);

 ?>

<div class="container-fluid">
            <div class="panel panel-danger">
              <div class="panel-heading">
                <h3 class="panel-title"><?php echo $_GET['text'];?></h3>
              </div>
              <div class="panel-body" align="center">
     <div class="alert alert-info" role="alert"><input type="button" class="btn btn-info" value="Regresar al Carrito" onclick="document.location.href='http://www.nyxvzla.com/carro/'
     ">  </div>
               



            
 
         <div class="row" >

            <div class="col-sm-6 col-md-3">

          
        
         
           </div>
            
            <div class="col-sm-6 col-md-3">
                        

            

           </div> 

           <div class="col-sm-6 col-md-3">
                        

            
           </div>       
                
       

              </div>
              <div class="panel-footer"><?php echo $pie; ?></div>
            </div>
</div>

   
 
<br/>

 

<br/>


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