<?php
  
    
    //$code['123'] = array('weight'=> 0,
    //                     'total' => 0);
    //$code['456'] = array('weight'=> 0,
    //                     'total' => 3);

$tArray = array();
$tArray['GB'] = array('transport' => 3,
                      'code' => array());

//$tArray['GB']['code'] = $code;

$tArray['PL'] = array('transport' => 1,
                       'code'=>array('weight'=> 0,
                                     'total' => 0)
                      );

    
   $tArray['PL']['code']['weight'] += 2; 
    
    //echo $tArray['address'];
    
    //echo '<pre>',print_r($tArray),'</pre>';
     echo var_dump(isset($tArray['GB']['code']['456']['weight']));
     $tArray['GB']['code']['456']['weight'] += 11;
     
     echo var_dump(isset($tArray['GB']['code']['456']['weight']));
    // echo '<pre>',print_r($tArray),'</pre>';
     $code = '41313';
     $tArray['GB']['code'][$code]['weight'] += 11;
    // echo '<pre>',print_r($tArray),'</pre>';
    //$tArray['PL']['code']['weight'] += 32;
    //$tArray['PL']['code']['total'] += 15;
    
