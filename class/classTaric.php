<?php
  
class Taric{
    public $taricCode = array();
    public $country = 0;
    public $code = 0;
    public $weight = 0;
    public $value = 0;
    public $transport = 0;
    public $qty = 0;
    
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

    public function setQty($var){
        $this->qty = $var;
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
                                                                           'value' => 0,
                                                                           'qty' => 0);
            //echo 'After taric: <pre>',print_r($this->taricCode),'</pre>';
        }
          
        $this->taricCode[$this->country]['code'][$this->code]['weight'] += $this->weight;
        $this->taricCode[$this->country]['code'][$this->code]['value'] += $this->value;
        $this->taricCode[$this->country]['code'][$this->code]['qty'] += $this->qty;
   }
   
   public function getArray(){
    return $this->taricCode;
   }
   

}