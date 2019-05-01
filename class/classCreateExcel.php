<?php
require '..\excel\vendor\autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Create_Excel extends Spreadsheet{
    
    private $header = null;
    private $orderArray;
    private $taricArray;
    private $shopNameArray = null;
    private $sumAll = 0;
    
   
    
    public function arrayPass($orderArr,$taricArr,$shopNameArr){
        $this->orderArray = $orderArr;
        $this->taricArray = $taricArr;
        $this->shopNameArray = $shopNameArr;
    }
    
    public function saveToXls($path){
        $writer = new Xlsx($this);
        $writer->save($path);

    //    $spreadsheet->disconnectWorksheets();
      //  unset($spreadsheet);
    }
    
    
    public function suppAdd($sheet){
        $html = "";
        $cellNo = 5;
        $cellStart = 5;
        
            foreach ($this->orderArray as $key => $value){
                for($s = 0; $s < count($this->shopNameArray);$s++){
                    if(array_key_exists($this->shopNameArray[$s], $this->orderArray[$key])){
                        for($x = 0; $x < count($this->orderArray[$key][$this->shopNameArray[$s]]); $x++){
                            $shopName = $this->shopNameArray[$s];
                            $order = $this->orderArray[$key][$this->shopNameArray[$s]][$x]["orderNo"];
                            $invoice = $this->orderArray[$key][$this->shopNameArray[$s]][$x]["invoiceRef"];
                        
                            for($counter = 0 ; $counter < count($this->taricArray[$shopName][$order]);$counter++){
                                $this->getActiveSheet()->setCellValue('A'.$cellNo, $key);
                                $this->getActiveSheet()->setCellValue('B'.$cellNo, $order);
                                $this->getActiveSheet()->setCellValue('C'.$cellNo, $invoice);
                                $this->getActiveSheet()->getStyle('C'.$cellNo)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                                
                                $this->setAlligment('C'.$cellNo,'left');
                                $value = $this->taricArray[$shopName][$order][$counter]["value"];
                                
                                $this->getActiveSheet()->setCellValue('D'.$cellNo, $value);
                                $this->setSumTotal($value);
                               
                                $this->getEuro('D'.$cellNo);
                                
                                $this->getActiveSheet()->getStyle('E'.$cellNo)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                               
                                $this->setAlligment('E'.$cellNo,'center');
                                $this->getActiveSheet()->setCellValue('E'.$cellNo, $this->taricArray[$shopName][$order][$counter]["taric"]);
                                $html .= "<TR><TD>".$key."</TD><TD>".$order."</TD><TD>".$invoice."</TD><TD>&euro;".$value."</TD><TD>".$this->taricArray[$shopName][$order][$counter]["taric"]."</TD></TR>";
                                $cellNo++;
                             }
                         }
                    }
                }
            }
            
            return $html;
    }
    
    public function setColumnSize($column,$size){
        $this->getActiveSheet()->getColumnDimension($column)->setWidth($size);
    }
    
    private function setSumTotal($value){
        $this->sumAll += $value;
    }
    
    public function getSumTotal(){
        return $this->sumAll;
    }
    
    public function getEuro($cell){
        $this->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE_1);
    }
    
    public function setAlligment($cell,$allig){
        $alligment = null;
        switch ($allig){
            case 'center':
                $alligment = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER;
                break;
            case 'right':
                $alligment = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT;
                break;
            case 'left':
                $alligment = \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT;
                break;            
        }
        $this->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal($alligment);
    }
    
    public function setFontStyle($cell,$fontType){
        switch($fontType){
            case 'italic':
                    $this->getActiveSheet()->getStyle($cell)->getFont()->setItalic(true);
                break;
            case 'bold':
                    $this->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
                break;
            case 'normal':
                    $this->getActiveSheet()->getStyle($cell)->getFont()->setItalic(false);
                    $this->getActiveSheet()->getStyle($cell)->getFont()->setBold(false);
                break;
        }
        
    }
}
?>