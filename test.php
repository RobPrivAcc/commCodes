<?php
  
class Taric{
    public $taricCode = array();
    public $country = 0;
    public $code = 0;
    public $weight = 0;
    public $value = 0;
    public $transport = 0;
    
    public function setCountry($var){
        $this->country = $var;
        return $this;
    }
    
    public function setCode($var){
        $this->code = $var;
        return $this;
    }
    
    public function setWeight($var){
        $this->weight = $var;
        return $this;
    }
    
    public function setValue($var){
        $this->value = $var;
        return $this;
    }

    public function setTransport($var){
        $this->transport = $var;
        return $this;
    }    
   
   public function setArray(){
        //$this->taricCode[$this->country] = array($this->code => array('weight' => $this->weight,
        //                                                                      'value' => $this->value));
        if (!isset($this->taricCode[$this->country]))
            {
                //echo 'before country: <pre>',print_r($this->taricCode),'</pre>';
                 $this->taricCode[$this->country] = array ();
                 $this->taricCode[$this->country]['transport'] = $this->transport;
                 //echo 'After country: <pre>',print_r($this->taricCode),'</pre>';
            }
            
        if (!isset($this->taricCode[$this->country]['code'][$this->code])){
            //echo 'before taric: <pre>',print_r($this->taricCode),'</pre>';
            $this->taricCode[$this->country]['code'][$this->code] = array ('weight' =>0,
                                                                        'value' => 0);
            //echo 'After taric: <pre>',print_r($this->taricCode),'</pre>';
        }
          
        $this->taricCode[$this->country]['code'][$this->code]['weight'] += $this->weight;
        $this->taricCode[$this->country]['code'][$this->code]['value'] += $this->value;
   }
   
   public function getArray(){
    return $this->taricCode;
   }
   

}


$t = new Taric;
$t->setCountry('GB')->setCode('111')->setWeight('15')->setValue('10')->setTransport('1')->setArray();
$t->setCountry('GB')->setCode('111')->setWeight('10')->setValue('10')->setTransport('1')->setArray();
$t->setCountry('XI')->setCode('151')->setWeight('30')->setValue('150')->setTransport('1')->setArray();
$t->setCountry('XI')->setCode('151')->setWeight('7')->setValue('150')->setTransport('1')->setArray();
$t->setCountry('XI')->setCode('001')->setWeight('20')->setValue('20')->setTransport('1')->setArray();
$t->setCountry('XI')->setCode('001')->setWeight('5')->setValue('5')->setTransport('1')->setArray();
//$tArray[$key]['code'][$code]['weight'] + $data['weight'];
           // $value = $tArray[$key]['code'][$code]['total'] + $data['totalValue'];
          

echo '<pre>',print_r($t),'</pre>';