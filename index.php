<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('class\classDisplayResult.php');
include('class\classProduct.php');
include('class\classDB.php');
include("class/classXML.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Comodity codes report</title>

   <!-- <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">-->
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    

    <link href="css/toogle.css" rel="stylesheet">
    <link href="css/myCSS.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script  type='text/javascript' src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script type='text/javascript' src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body>
    <?php
      $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    ?>
    <div class="container-fluid" id="mainContainer">
        
        <div class="row">
            <div class="col-xs-12 col-s-12 col-12">
                <div></div>
            </div>
        </div>
        
        <div class="row">
            <div class="form-group col-lg-3">
                <SELECT id='yearList' class='form-control'>
                  <option value="<?php echo date ('Y', time()); ?>"><?php echo date ('Y', time()); ?></option>
                  <option value="<?php echo date ('Y', time())-1; ?>"><?php echo date ('Y', time())-1; ?></option>
                </select>
            </div>
            
            <div class="form-group col-lg-3">
                <SELECT id='monthList' class='form-control'>
                  
                  <?php
                  $month = date ('n',time());
                    for($i=1; $i <= $month ;$i++){
                      ?>
                      <option value="<?php echo $i;?>"><?php echo date ('F',  mktime(0, 0, 0, $i, 1, date ('Y', time()))); ?></option>
                      <?php
                    }
                  ?>
                </select>
            </div>
            
            <div class="form-group col-lg-3">
                <button class="btn btn-primary" id="searchBtn">Create report</button>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-s-12 col-12">
                <div id = "result"></div>
            </div>
        </div>
    </div>
   
    <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->

    
    <script type='text/javascript' src="js/bootstrap.min.js"></script>
    
    <!-- Confirm window -->
    <link rel="stylesheet" href="css/jquery-confirm.min.css">
    <script type='text/javascript' src="js/jquery-confirm.min.js"></script>
    

    <!-- toogle button -->
    <link href="css/bootstrap-toggle.css" rel="stylesheet">
    <script type='text/javascript' src="js/bootstrap-toggle.js"></script>
    
    <!--  Custom scripts  -->
      <!--  Date scripts  -->
        <script type='text/javascript' src="js/myJs/date.js"></script>
      
    
  <script>
    $( document ).ready(function() {
      $.get( "https://www.robertkocjan.com/petRepublic/ip/ipGetArray.php", function(i) {
        console.log(i);
        var configArray = i;
      
          $.get( "getIpFromServer.php", { ipArray: configArray }, function(data) {
            console.log(data);
          });
      });
    });
    
    $( "#searchBtn" ).click(function () {
                
      var spinner = '<Div class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></DIV>';
      $('#result').html(spinner);
                
      $.post( "pages/pageCsv.php", { year: $( "#yearList" ).val(), month: $( "#monthList" ).val() })
        .done(function( data ) {
           $("#result").html(data);
        });
    });
          
       
  </script>
    
 <!-- echo '<input type="hidden" id="orderArray" value="'.$product->returnOrderArray.'">';
    echo '<input type="hidden" id="taricArray" value="'.$product->returnTaricArray.'">';
    echo '<input type="hidden" id="shopName" value="'.$product->returnShopNameArray.'">';
  -->
  </body>
</html>