<?php
include('..\class\classDisplayResult.php');
include('..\class\classProduct.php');
include('..\class\classDB.php');
include('..\class\classXML.php');    

    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    $db = new dbConnection($xml->getConnectionArray());

    $product = new Product($_POST['year'],$_POST['month']);
  
    $index = $db->getMaxIndex();
    echo $index.'<br/><br/>';
    $i=0;
    while($i < $index){
        $product -> setConnectionString($db->getDbConnection($i));
        $t[] = $product -> supp();
        $i++;
    }

    //echo '<pre>',print_r($t),'</pre>';
  $taricArray = array();
  
    foreach($t as $i => $shop){
        //echo '<pre>',print_r($shop),'</pre>';
        
        foreach($shop as $key=>$value){
            foreach($value['codes'] as $taricCode => $v){
                
                //echo $key,' ',$value['address'],'  ',$k,'  ',$v['weight'],'  ',$v['totalValue'],'<br/>';
                $taricArray = add($taricArray,$value['address'],$taricCode,$value['transport'],$v['weight'],$v['totalValue']);
            }
          
        }
        //echo '<br/><br/><br/>';
    }
    
 //   echo '<pre>',print_r($taricArray),'</pre>';
    
function add($array,$key,$taricCode,$transport,$weight,$value){
    $tmpArray = $array;
    
    
    
    if(!isset($tmpArray[$key][$taricCode]['weight'])){
        $w = $weight;    
    }else{
        $w = $tmpArray[$key][$taricCode]['weight']+$weight;    
    }
    
    if(!isset($tmpArray[$key][$taricCode]['value'])){
        $val = $value;
    }else{
        $val = $tmpArray[$key][$taricCode]['value']+$value;
    }
    
    $tmpArray[$key][$taricCode] = array('transport' => $transport, 'weight' => $w, 'value' => $val);
    
    
    
    
    return $tmpArray;
}  
//echo '<pre>',print_r($t),'</pre>';
  
  $line = "";
  
  foreach($taricArray as $country => $data){
    foreach($data as $code => $values){
        $line .= $code.",".$country.",".$country.",".$values["transport"].",1,".$values["weight"].",".$values["value"]."\r\n";
    
       //echo '<pre>',print_r($country),'</pre>';
    }
  }
  
  $header = "Commodity code,Country of Consignment,Country of Origin,Mode of Transport,Nature of Transaction,Invoice Value Euro,Net Mass\r\n";


  $csv = $header.$line;
  
$filename = $_POST['year'].'-'.$_POST['month'].'.csv';

$directory = explode("\\",dirname(dirname(__FILE__)));
//print_r($directory);
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

  //$string = 'Bird Sand (tetra pack) 2kg';
  //echo $product->getWeight($string);
