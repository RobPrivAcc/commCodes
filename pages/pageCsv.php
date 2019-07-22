<?php
include('..\class\classDisplayResult.php');
include('..\class\classProduct.php');
include('..\class\classDB.php');
include('..\class\classXML.php');    

  $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
  $db = new dbConnection($xml->getConnectionArray());

  //$product = new Product($_POST['year'],$_POST['month']);
  $product = new Product($_POST['year'],$_POST['month']);
  
  $index = $db->getMaxIndex();
  echo $index.'<br/><br/>';
 
 
    $product -> setConnectionString($db->getDbConnection(1));
   $t = $product -> supp();

//    $rr = $product->getSup();
  
  $header = "Commodity code,Country of Consignment,Country of Origin,Mode of Transport,Nature of Transaction,Invoice Value Euro,Net Mass\r\n";

  
  echo '<pre>',print_r($t),'</pre>';
  $line = "";
 /*
  foreach($t as $key =>$value){
    for($i=0; $i < count($value['codes']); $i++){
        echo $value['codes'][$i][0],' ',$value['address'],' ',$value['address'],' ',$value['transport'],' ',$value['codes'][$i][1],' ',$value['codes'][$i][2],"<br/>";
        $line .= $value['codes'][$i][0].",".$value['address'].",".$value['address'].",".$value['transport'].',1,'.$value['codes'][$i][1].",".$value['codes'][$i][2]."\r\n";
    }
    
  }
  
  $csv = $header.$line;
  
  
$directory = explode("\\",dirname(dirname(__FILE__)));
//print_r($directory);
$pathToFileCSV = dirname(pathinfo(__FILE__)['dirname']).'\\export\\'.$_POST['year'].'-'.$_POST['month'].'.csv';
$myfile = fopen($pathToFileCSV, "w") or die("Unable to open file!");
fwrite($myfile, $csv);
fclose($myfile);
  //$string = 'Bird Sand (tetra pack) 2kg';
  //echo $product->getWeight($string);
  */