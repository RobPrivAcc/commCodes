<?php
include('..\class\classDisplayResult.php');
include('..\class\classProduct.php');
include('..\class\classDB.php');
include('..\class\classXML.php');

  $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
  $db = new dbConnection($xml->getConnectionArray());

  $product = new Product($_POST['year'],$_POST['month']);
  $t ='maxi adult (1.5) liver';
  //echo $t.'<br/>';
  //echo $product->getWeight($t);
  //echo $product->getWeight($t)[0].'<br/>';
  //echo $product->getWeight($t)[1].'<br/>';
  //echo $product->getWeight($t)[2].'<br/>';
  
  $index = $db->getMaxIndex();
  echo $index."<br/><br/>";
 
  for($i=0; $i<$index;$i++){
    $product -> setConnectionString($db->getDbConnection($i));
    $product -> setDataCSV();
  }
  
  $array = $product -> returnTaricArrayCSV();
  //var_dump($array);
  $header = '';
  $header = "Commodity code,Country of Consignment,Country of Origin,Mode of Transport,Nature of Transaction,Invoice Value Euro,Net Mass\r\n";
  
  foreach($array as $key => $value){
    $tot = 0;
    for($i=0; $i < count($value);$i++){
      $tot = $tot + $value[$i]['value'];
    }
    $header .= $key.",".$value[0]['supp']['address'].",".$value[0]['supp']['address'].",".$value[0]['supp']['transport'].",1,".$tot.",1\r\n";
    //$header .= $key.",GB,GB,1,1,".array_sum($value).",1<br/>";

  }
////  $this->supplierInfo = array('address' => $row["Address"], 'transport' => $row["UserDefinedField2"]);
  //echo $header;
  $directory = explode("\\",dirname(dirname(__FILE__)));
//print_r($directory);

$pathToFileCSV = dirname(pathinfo(__FILE__)['dirname']).'\\export\\'.$_POST['year'].'-'.$_POST['month'].'.csv';
$myfile = fopen($pathToFileCSV, "w") or die("Unable to open file!");

fwrite($myfile, $header);
fclose($myfile);
  
//  var_dump($array);
?>