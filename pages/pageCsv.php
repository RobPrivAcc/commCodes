<?php
include('..\class\classDisplayResult.php');
include('..\class\classProduct.php');
include('..\class\classDB.php');
include('..\class\classXML.php');
include('..\class\classTaric.php');    

    $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    $db = new dbConnection($xml->getConnectionArray());

    $product = new Product($_POST['year'],$_POST['month']);
  
  $line = "";
    $index = $db->getMaxIndex();
    //echo $index.'<br/><br/>';
    $i=0;
    while($i < $index-1){
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
            //$line .= $code.",".$key.",".$key.",".$value['transport'].",1,".$data['weight'].",".$data['totalValue']."\r\n";
            
        }
       // $tArray[$key]['code'] = $taric;
        //echo '<pre>',print_r($value),'</pre>';
      }
    }
    $tArray = $taric->getArray();
   // echo '<pre>',print_r($taric->getArray()),'</pre>';
   
   foreach($tArray as $country => $data){
    
    //echo $key."<br/>";
    //echo '<pre>',print_r($data).'</pre>';
    $transport = $data['transport'];
    
        foreach($data['code'] as $k => $v){
            //echo '<pre>',print_r($k),'</pre>';
            $weight = $v['weight'];
            if($weight < 1){
                $weight = 1;
            }
            
            $taricCode = $k;
            
            if(strlen($taricCode) == 10){
                $taricCode = substr($taricCode,0,-2);
            }
            
           $line .= $taricCode.",".$country.",".$country.",".$transport.",1,".$weight.",".$v['qty'].",".$v['value']."\r\n";
            //$line .= $code.",".$key.",".$key.",".$v['transport'].",1,".$v['weight'].",".$v['totalValue']."\r\n";
        }
    
   }
   echo '<br/><br/><br/><br/>-----------------------------------------<br/><br/><br/>';
   echo '<pre>',print_r($tArray),'</pre>';


//echo '<pre>',print_r($tArray),'</pre>';

 
    



//  
  $header = "Commodity code,Country of Consignment,Country of Origin,Mode of Transport,Nature of Transaction,Net Mass,Invoice Value Euro\r\n";
//$header = "Commodity code,Country of Consignment,Country of Origin,Nature of Transaction,Invoice Value Euro,Net Mass\r\n";
//
//
  $csv = $line;
  
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
