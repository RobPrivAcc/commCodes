<?php

include('..\class\classProduct.php');
include('..\class\classDB.php');
include('..\class\classXML.php');
include('..\class\classTaric.php');    

    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    $db = new dbConnection($xml->getConnectionArray());

    $product = new Product($_POST['year'],$_POST['month']);
  
    $line = "";
  
    $index = $db->getMaxIndex();

    $i=0;
    while($i < $index){
        $product -> setConnectionString($db->getDbConnection($i));
        $t[] = $product -> supp();
        $i++;
    }
    
    $tArray = array();
    
    $taric = new Taric;
    
    for($i=0; $i<count($t); $i++){
      foreach($t[$i] as $key => $value){
       
        $tArray[$key] = array('transport' => $value['transport']);
        
      
            foreach($value['codes'] as $code => $data){
                $taric->setCountry($key)->setCode($code)->setWeight($data['weight'])->setValue($data['totalValue'])->setQty($data['qty'])->setTransport('1')->setArray();
            }
       }
    }
    
    $tArray = $taric->getArray();
   
   foreach($tArray as $country => $data){
       $transport = $data['transport'];
    
        foreach($data['code'] as $k => $v){
            $weight = $v['weight'];
            if($weight < 1){
                $weight = 1;
            }
            
            $taricCode = $k;
            
            if(strlen($taricCode) == 10){
                $taricCode = substr($taricCode,0,-2);
            }
            
           $line .= $taricCode.",".$country.",".$country.",".$transport.",1,".$weight.",".$v['qty'].",".$v['value']."\r\n";
        }
    }



  $header = "Commodity code,Country of Consignment,Country of Origin,Mode of Transport,Nature of Transaction,Net Mass,Invoice Value Euro\r\n";

  $csv = $line;
  
$filename = $_POST['year'].'-'.$_POST['month'].'.csv';

$directory = explode("\\",dirname(dirname(__FILE__)));

$pathToFileCSV = dirname(pathinfo(__FILE__)['dirname']).'\\export\\'.$filename;
$myfile = fopen($pathToFileCSV, "w") or die("Unable to open file!");
fwrite($myfile, $csv);
fclose($myfile);


$directory = explode("\\",dirname(dirname(__FILE__)));

$pathToFile = dirname(pathinfo(__FILE__)['dirname']).'\\export\\'.$filename;

if (file_exists($pathToFile)){
    echo "Click to download <a href = '/".$directory[count($directory)-1]."/export/".$filename."'>".$filename."</a>";    
}else{
    echo "Ups.. something went wrong and file wasn't created. Contact Robert.";    
}