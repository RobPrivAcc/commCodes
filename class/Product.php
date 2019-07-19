<?php
class Product{
    private $Name = null;
    private $Cost = null;
    private $Retail = null;
    private $Qty = null;
    private $Weight = null;
    private $Taric = null;
    
    public function setName($var){
        $this->Name = $var;
        
    }
    
    public function getName(){
        return $this->Name;
    }
    
    public function setCost($var){
        $this->Cost = $var;
    }
    
    public function getCost(){
        return $this->Cost;
    }
    
    public function setRetail($var){
        $this->Retail = $var;
    }
    
    public function getRetail(){
        return $this->Retail;
    }
    
    public function setQty($var){
        $this->Qty = $var;
    }
    
    public function getQty(){
        return $this->Qty;
    }
    
    private function setWeight(){
        $pattern = '/\s(\(?[0-9]\.?[0-9]+(kg|g)\)?)/';
        
        if(preg_match($pattern , $this->Name, $array1) == 1){       
                $weight = str_replace($array1[2],'',$array1[1]);
                $weight = str_replace('(','',$weight);
                $weight = str_replace(')','',$weight);
            if($array1[2] == 'g'){
                $weight = $weight/1000;
            }else{
                $weight = 1;
            }
            $this->Weight = $weight;
        }
    }
    
    public function getWeight(){
        $this->setWeight();
        return $this->Weight;
    }
    
}
?>