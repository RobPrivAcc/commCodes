<?php
    class Display{
        
        private $array = array();
        private $arrayTaric = array();
        private $shopName = null;
        private $totalValue = 0;
        private $supplierValue = 0;
        private $sectionValue =0;
        private $shopNameArray = array();
        
        function __construct($ordersArray,$tarricArray,$shopNameArray){
            $this->array = $ordersArray;
            $this->arrayTaric = $tarricArray;
            $this->shopNameArray = $shopNameArray;
        }
        
        public function displayData(){
            $display = null;
           // $value = "Total value: ".$this->totalValue."<br/>";
             foreach ($this->array as $key => $value) {
                $display .= "<h3>".$key."</h3><br/>";
                for($s = 0; $s < count($this->shopNameArray);$s++){
                    if(array_key_exists($this->shopNameArray[$s], $this->array[$key])){
                       // echo "--".$this->shopNameArray[$s]." - ".count($this->array[$key][$this->shopNameArray[$s]])."<br/>";
                        
                        for($x = 0; $x < count($this->array[$key][$this->shopNameArray[$s]]); $x++){
                            $order = $this->array[$key][$this->shopNameArray[$s]][$x]["orderNo"];
                            $invoice = $this->array[$key][$this->shopNameArray[$s]][$x]["invoiceRef"];
                            $display .= "<B>".$this->shopNameArray[$s]." - ".$order."  <I>invoice (".$invoice.")</I>";
                           
                            $display .= $this->displayTaric($this->shopNameArray[$s],$order);
                        }
                        
                    }
                   
                }
                 $display .="Supplier total: &euro;".$this->supplierValue."<br/>";
                 $this->supplierValue = 0;
             }
            return "Total value: &euro;".$this->totalValue."<br/>".$display;
        }
        
        private function summAll($cost){
            $this->totalValue = $this->totalValue + $cost;
          
        }
        
        public function displayTaric($shop,$orderNo){
            $show =null;
            $this->sectionValue = 0;
              if(array_key_exists($shop, $this->arrayTaric)){
                if(array_key_exists($orderNo, $this->arrayTaric[$shop])){
                    for($i=0; $i < count($this->arrayTaric[$shop][$orderNo]);$i++){
                        $this->summAll($this->arrayTaric[$shop][$orderNo][$i]["value"]);
                        $show .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$this->arrayTaric[$shop][$orderNo][$i]["taric"]."  -  &euro;".$this->arrayTaric[$shop][$orderNo][$i]["value"]."<br/>";
                        $this->sectionValue += $this->arrayTaric[$shop][$orderNo][$i]["value"];
//                        $show .= $this->arrayTaric[$shop][$orderNo][$i]["value"]."<br/>";
                    }
                    
                }
             }
             $show .= "<br/>";
            $this->supplierValue += $this->sectionValue;
            return " Invoice value: &euro;".$this->sectionValue."</B><br/>".$show;
        }
    }
?>