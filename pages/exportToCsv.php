<?php
include('..\class\classDisplayResult.php');
include('..\class\classProduct.php');
include('..\class\classDB.php');
include('..\class\classXML.php');

  $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
  $db = new dbConnection($xml->getConnectionArray());

  $product = new Product($_POST['year'],$_POST['month']);
  
  $index = $db->getMaxIndex();
  echo $index."<br/><br/>";
 
  for($i=0; $i<$index;$i++){
    $product -> setConnectionString($db->getDbConnection($i));
    $product -> setDataCSV();
  }
  
  $array = $product -> returnTaricArrayCSV();
  
  $header = "Commodity code,Country of Consignment,Country of Origin,Mode of Transport,Nature of Transaction,Invoice Value Euro,Net Mass\r\n";
  
  foreach($array as $key => $value){
    $header .= $key.",GB,GB,1,1,".array_sum($value).",1\r\n";
  }
  
  $directory = explode("\\",dirname(dirname(__FILE__)));
//print_r($directory);

$pathToFileCSV = dirname(pathinfo(__FILE__)['dirname']).'\\export\\'.$_POST['year'].'-'.$_POST['month'].'.csv';
$myfile = fopen($pathToFileCSV, "w") or die("Unable to open file!");

fwrite($myfile, $header);
fclose($myfile);
  
//  var_dump($array);
?>