<?php
header("Access-Control-Allow-Origin: *");
    include("class/classXML.php");
    $array = json_decode($_GET['ipArray'], TRUE);

    if(isset($array)){
        $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    
        foreach($array as $key=>$value){
           //var_dump($value);
           //echo '('.$value['storeName'].'),('.$value['ip'].')';
           $xml->saveNodeToFile($value['storeName'],$value['ip']);
           //echo $value['storeName'];
        }
    }else{
        echo "Error. Can't find array";
    }
?>