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
        private $arr = array();
        
       
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
            
            $query ="SELECT Distinct(RepOrderNo), repMain.Supplier as Supp, InvoiceRef,[Address],[UserDefinedField2]
            FROM repMain
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
                        $this->array[$row["Supp"]][$this->shopName][] = array("orderNo" => $row["RepOrderNo"],"invoiceRef" => $row["InvoiceRef"],"shopName" =>$this->shopName, 'address' => $row["Address"], 'travel' => $row["UserDefinedField2"]);
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
        
        //public function supp(){
        //    $sql = "SELECT distinct(([RepMain].[Supplier])) as Suppl
        //            ,[Address]
        //            ,[UserDefinedField2]
        //      FROM [RepMain] 
        //          inner join [Suppliers] on [Suppliers].Supplier = [RepMain].[Supplier]
        //          inner join [RepSub] on [RepOrderNo] = [OrderNo]
        //          iNNER JOIN [Types] on TypeOfItem = [Type] AND [RepSub].[SubType] = [Types].[SubType]
        //      WHERE [StockUpdateDate] > '{$this->dateStart}' and [StockUpdateDate] < '{$this->dateEnd}'
        //      and  PostCode = 'export' and [TotalCheckedQuantity] >0 and InvoiceRef not like '% > %' order by Suppl ASC";
        //    
        //    $sqlSub = $this->pdo->prepare($sql);
        //    $sqlSub->execute();
        //                
        //    $arr = array();
        //    
        //    $weight = 0;
        //    $total = 0;
        //    
        //    $t = array();
        //    
        //    while($r = $sqlSub->fetch()){
        //        //$t[] = $this->makeTarric($r['Suppl'],$r['Code']);
        //        //$this->arr[$r['Suppl']] = array('address' => $r['Address'],
        //        //                                'transport' => $r['UserDefinedField2'],
        //        //                                'taric' => $t);
        //       // echo $r['Suppl'],' ', $r['Address'],' ', $r['UserDefinedField2'],'</br>';
        //        
        //       // echo '<pre>'.print_r($this->makeTarric($r['Suppl'])),'</pre><br/>';
        //       $taric = $this->makeTarric($r['Suppl']);
        //       
        //       
        //       foreach ($taric as $key => $value){
        //        $weight = 0;
        //        $totVal = 0;
        //        for($i=0; $i <count($value); $i++){
        //            $weight = $weight + $value[$i]['weight'];
        //            $totVal = $totVal + $value[$i]['value'];
        //        }
        //        //echo $key,' ',var_dump($value).'<br/>';
        //        
        //        
        //        $ta[] = array($key, $weight, $totVal);
        //       }
        //       $t[$r['Suppl']] = array('address'=>$r['Address'],
        //                                'transport'=>$r['UserDefinedField2'],
        //                                'codes' => $ta);
        //        //$t[] = $this->makeTarric($r['Suppl'],$r['Code']);
        //        //$this->arr[$r['Suppl']] = array('address' => $r['Address'],
        //        //                                'transport' => $r['UserDefinedField2']);
        //    } 
        //     return $t; 
        //}
        
        
    public function supp(){
            $sql = "SELECT distinct(([RepMain].[Supplier])) as Suppl
                    ,[Address]
                    ,[UserDefinedField2]
              FROM [RepMain] 
                  inner join [Suppliers] on [Suppliers].Supplier = [RepMain].[Supplier]
                  inner join [RepSub] on [RepOrderNo] = [OrderNo]
                  iNNER JOIN [Types] on TypeOfItem = [Type] AND [RepSub].[SubType] = [Types].[SubType]
              WHERE [StockUpdateDate] > '{$this->dateStart}' and [StockUpdateDate] < '{$this->dateEnd}'
              and  PostCode = 'export' and [TotalCheckedQuantity] >0 and InvoiceRef not like '% > %' order by Suppl ASC";
            
            $sqlSub = $this->pdo->prepare($sql);
            $sqlSub->execute();
                        
            $arr = array();
            
            $weight = 0;
            $total = 0;
            
            $t = array();
            
            while($r = $sqlSub->fetch()){
                
               $taric = $this->makeTarric($r['Address']);
               
               $ta = array();
               
               foreach ($taric as $key => $value){
                $weight = 0;
                $totVal = 0;
                for($i=0; $i <count($value); $i++){
                    $weight = $weight + $value[$i]['weight'];
                    $totVal = $totVal + $value[$i]['value'];
                }
                //echo $key,' ',var_dump($value).'<br/>';
                
                
                $ta[$key] = array('weight' => $weight, 'totalValue' => $totVal);
               }
               $t[$r['Address']] = array('transport'=>$r['UserDefinedField2'],
                                        'codes' => $ta);
                //$t[] = $this->makeTarric($r['Suppl'],$r['Code']);
                //$this->arr[$r['Suppl']] = array('address' => $r['Address'],
                //                                'transport' => $r['UserDefinedField2']);
            } 
             return $t; 
        }        
        
        public function makeTarric($address){
            $sql = "SELECT Code, (price * [TotalCheckedQuantity]) as total,[TotalCheckedQuantity],[Nameofitem]
                    
              FROM [RepMain] 
                  inner join [Suppliers] on [Suppliers].Supplier = [RepMain].[Supplier]
                  inner join [RepSub] on [RepOrderNo] = [OrderNo]
                  iNNER JOIN [Types] on TypeOfItem = [Type] AND [RepSub].[SubType] = [Types].[SubType]
              WHERE [StockUpdateDate] > '{$this->dateStart}' 
					and [StockUpdateDate] < '{$this->dateEnd}'
					and  PostCode = 'export' and InvoiceRef not like '% > %' and [TotalCheckedQuantity] >0
                    
					and [Address] = '{$address}'
					
					order by Code ASC";
            $sqlSub = $this->pdo->prepare($sql);
            $sqlSub->execute();
            $total = 0;
            $weight = 0;
            $t = array();
            
            while($r = $sqlSub->fetch()){
                $weight = $this->getWeight($r['Nameofitem']) * $r['TotalCheckedQuantity'];
                $total = $r['total'];
                $t[$r['Code']][] = array('weight' => $weight,
                                    'value' => $total);
            }
            
            
           
            return $t;
            //$t[$code] = array('weight' => $weight,
            //                    'value' => $total);
            
            //return $t;
            
            
            
        }
        
        public function getSup(){
            return $this->arr;
        }
        
        public function getWeight($string){
            $pattern = '/\s(\(?[0-9]*\.?[0-9]+(kg|g)\)?)/';
            
           if(preg_match($pattern , $string, $array1) == 1){
            
            $weight = str_replace($array1[2],'',$array1[1]);
            $weight = str_replace('(','',$weight);
            $weight = str_replace(')','',$weight);
            if($array1[2] == 'g'){
                $weight = $weight/1000;
            }
           }else{
            $weight = 1;
           }

            return $weight;
        }
        
        public function setData(){
            $this->setOrdersArray();
        }
        //
        public function returnOrderArray(){
            return $this->array;
        }
        //
        public function returnTaricArray(){
            return $this->arrayTaric;
        }
        //
        public function returnShopNameArray(){
            return $this->shopNameArray;
        }
        
        public function retDate(){
            return $this->dateEnd;
        }
    }
?>