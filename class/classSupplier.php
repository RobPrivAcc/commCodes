<?php
    class Supplier{
        
        private $pdo = null;
        private $arraySupplierList = array();
       
        function __construct($server,$user,$pass){
            if(isset($server) || isset($user) || isset($pass)){
                $this->pdo = new PDO($server,$user,$pass);
                
                //$this->pdo = new PDO("sqlsrv:server=DESKTOP-TACKN94\SQLEXPRESS2016;Database=petshoptest","stocktake","stocktake");
            }
                
        }        
        /**
         *
         *
         *  getting supplier array
         *
         **/
        
        function setSupplierList(){
            $arraySuppliers = array();  // empty array to store suppliers
            
            $query = "SELECT [Supplier], UserDefinedField1 FROM [Suppliers] ORDER BY [Supplier] ASC"; // getting all suppliers from DB
            $sql = $this->pdo->prepare($query);
            $sql->execute();
                
            while($row = $sql->fetch()){
              $arraySuppliers[] = array("leadTime" => $row['UserDefinedField1'], "supplier" =>$row['Supplier']);
            }
            
            $this->arraySupplierList = $arraySuppliers;
        }
        
        
        function getSupplierList(){
            $this->setSupplierList();
            
            if(isset($this->arraySupplierList)){
                return $this->arraySupplierList;
            }else{
                return "null";
            }
        }
        
    }
?>