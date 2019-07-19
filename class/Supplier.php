<?php
class Supplier{
    private $Name = null;
    private $CountryOfOrgin = null;
    private $TransportType = null;
    
    public function setName($var){
        $this->Name = $var;
    }
    
    public function getName(){
        return $this->Name;
    }
    
    //[Address], [UserDefinedField2]
}
?>