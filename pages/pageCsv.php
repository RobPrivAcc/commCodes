<?php
include('..\class\classDisplayResult.php');
include('..\class\classProduct.php');
include('..\class\classDB.php');
include('..\class\classXML.php');    

  $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
  $db = new dbConnection($xml->getConnectionArray());

  //$product = new Product($_POST['year'],$_POST['month']);
  $product = new Product('2019','01');
  
  $index = $db->getMaxIndex();
  echo $index.'<br/><br/>';
 
 
    $product -> setConnectionString($db->getDbConnection(1));
   $t = $product -> supp();

//    $rr = $product->getSup();
  
  echo '<pre>',print_r($t),'</pre>';
  
  //$string = 'Bird Sand (tetra pack) 2kg';
  //echo $product->getWeight($string);