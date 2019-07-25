<?php
include('..\class\classDisplayResult.php');
include('..\class\classProduct.php');
include('..\class\classDB.php');
include('..\class\classXML.php');    

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
    
    for($i=0; $i<count($t); $i++){
      foreach($t[$i] as $key => $value){
       
        $tArray[$key] = array('transport' => $value['transport']);
        
        $stat = array('weight'=> 0,
                       'total' => 0);
        
       // echo $key.'  '.$value['transport'].'<br/>';
       $taric = array();
       //echo '<pre>',print_r($value['codes']),'</br>';
       
        foreach($value['codes'] as $code => $data){
          //$code = substr($code, 0, -2);
          if(!isset($tArray[$key]['code'][$code]['weight'])){
            $tArray[$key]['code'][$code]['weight'] = 0;
            $tArray[$key]['code'][$code]['total'] = 0;
          }else{
            $tArray[$key]['code'][$code]['weight'] += $data['weight'];
           $tArray[$key]['code'][$code]['total'] += $data['totalValue'];
          }
          
          if($code == '0208909800'){
            echo '<pre>',print_r($tArray[$key]['code'][$code]),'</pre>';
            echo 'Weight: ',$data['weight'],' Total: ',$data['totalValue'],'<br/>';
          }
          //$stat['weight'] += $data['weight'];
          //$stat['total'] += $data['totalValue'];
          //
          // $taric[$code] = $stat;
           //echo $key,' ',$code,'<br/>';
        //echo '<pre>',print_r($data),'</pre>';   
          $tArray[$key]['code'][$code]['weight'] += $data['weight'];
          $tArray[$key]['code'][$code]['total'] += $data['totalValue'];
         
          $line .= $code.",".$key.",".$key.",".$value['transport'].",1,".$data['weight'].",".$data['totalValue']."\r\n";
        }
       // $tArray[$key]['code'] = $taric;
        //echo '<pre>',print_r($value),'</pre>';
      }
    }
    
echo '<pre>',print_r($tArray),'</pre>';

 
    



//  
  $header = "Commodity code,Country of Consignment,Country of Origin,Mode of Transport,Nature of Transaction,Net Mass,Invoice Value Euro\r\n";
//$header = "Commodity code,Country of Consignment,Country of Origin,Nature of Transaction,Invoice Value Euro,Net Mass\r\n";
//
//
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
