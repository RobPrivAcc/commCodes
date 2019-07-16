<?php
    class Product{
        
        private $pdo = null;
        private $array = array();
        private $arrayTaric = array();
        private $date = null;
        private $dateStart = null;
        private $dateEnd = null;
        private $shopName = null;
        private $shopNameArray = array();
       
        function __construct($year,$month){
            $this->dateStart = date ('Y-m-d', mktime(0, 0, 0, $month, 1, $year));;
            $this->dateEnd = date ('Y-m-d', mktime(0, 0, 0, $month, date ('t', mktime(0, 0, 0, $month, 1, $year)), $year));
        }
        
        public function setConnectionString($connArray){
            //try {
            //    $this->pdo = new PDO($connArray['server'],$connArray['user'],$connArray['password']);
            //    $this->shopName = $connArray['shopName'];
            //    $this->shopNameArray[] = $this->shopName;
            //}catch(PDOException $e) {
            //    echo "Error in: ".$connArray['shopName']." ip: ".$connArray['server'];
            //    exit;
            //}
            //print_r($connArray);
            try{
            //$this->petcoPDO  = new PDO("sqlsrv:Server=86.47.51.83,1317;Database=petshoptest","sa","SMITH09ALPHA"); // charlestown db test
                $this->pdo = new PDO($connArray["server"],$connArray["user"],$connArray["password"]);
            }catch(Exception $e){
                //$this->petcoPDO  = new PDO("sqlsrv:Server=Server=192.168.1.2\SQLEXPRESS;Database=petshoptest","sa","SMITH09ALPHA");
                echo "Using internal IP for: ".$connArray['shopName'];
                $this->pdo = new PDO($connArray["localServer"],$connArray["user"],$connArray["password"]);
            }
                $this->shopName = $connArray['shopName'];
                $this->shopNameArray[] = $this->shopName;
        }
        
        private function setOrdersArray(){
            
            $query ="SELECT Distinct(RepOrderNo), repMain.Supplier as Supp, InvoiceRef FROM repMain
                        LEFT JOIN actionLog ON [Action] = 'Replenishment Order INCREASED #'+cast(reporderno as varchar(255))
                        INNER JOIN [Suppliers] ON [Suppliers].Supplier = repMain.Supplier
                    WHERE DateTime > '$this->dateStart' and DateTime < '$this->dateEnd' and InvoiceRef not like '% > %' and PostCode = 'export' ORDER BY RepOrderNo ASC";
            //echo $query.'<br/>';
            $sql = $this->pdo->prepare($query);
            $sql->execute();
            
            $counter = 0;
                while($row = $sql->fetch()){
                    $counter++;
                   // echo $counter."  -  ".$this->shopName."<br/>";
                    if($counter > 0){
                        $this->array[$row["Supp"]][$this->shopName][] = array("orderNo" => $row["RepOrderNo"],"invoiceRef" => $row["InvoiceRef"],"shopName" =>$this->shopName);
                        $this->setTaricCodes($row["RepOrderNo"]);
                    }
                }
                        //print_r($this->array);    
            return $this->array;
        }
        
        
        
        private function setTaricCodes($order){
            $subQuery = "SELECT [Code] ,sum([TotalAddedQuantity] * [Price]) AS totalLine
                                        FROM [RepSub] 
                                        INNER JOIN [Types] on TypeOfItem = [Type] AND [RepSub].[SubType] = [Types].[SubType]
                                        WHERE OrderNo = '".$order."' group by Code";
                    
            $sqlSub = $this->pdo->prepare($subQuery);
            $sqlSub->execute();
                        
                            
            while($rowSub = $sqlSub->fetch()){
                $this->arrayTaric[$this->shopName][$order][] = array("taric" => $rowSub["Code"],"value" => round($rowSub[1],2),"orderNo" => $order);
            }
            
            return $this->arrayTaric;
        }
        
        public function setData(){
            $this->setOrdersArray();
        }
        
        public function returnOrderArray(){
            return $this->array;
        }
        
        public function returnTaricArray(){
            return $this->arrayTaric;
        }
        
        public function returnShopNameArray(){
            return $this->shopNameArray;
        }
        
        public function retDate(){
            return $this->dateEnd;
        }
    }
?>