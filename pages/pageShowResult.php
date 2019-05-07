<?php
require_once '..\vendor\autoload.php';

$mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8', 
        'format' => [190, 236], 
        'orientation' => 'L'
]);



include('..\class\classDisplayResult.php');
include('..\class\classProduct.php');
include('..\class\classDB.php');
include('..\class\classXML.php');

require '..\class\classCreateExcel.php';


  $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
  $db = new dbConnection($xml->getConnectionArray());

  $product = new Product($_POST['year'],$_POST['month']);
  
  $index = $db->getMaxIndex();
  echo $index;
 
  for($i=0; $i<$index;$i++){
    $product -> setConnectionString($db->getDbConnection($i));
    $product -> setData();
  }
  
  //print_r($product->returnOrderArray());
  
  $display = new Display($product->returnOrderArray(), $product->returnTaricArray(), $product->returnShopNameArray());
  $show = $display -> displayData();

  
  
$raportTitle = 'Commodity codes raport from '.date ('F',  mktime(0, 0, 0, $_POST['month'], 1, date ('Y', time()))).' '.$_POST['year'];

$xls = new Create_Excel();
$xls->arrayPass($product->returnOrderArray(), $product->returnTaricArray(), $product->returnShopNameArray());
$sheet = $xls->getActiveSheet();
$sheet->mergeCells('A1:E1');
$sheet->setCellValue('A1', $raportTitle);
$sheet->getStyle("A1")->getFont()->setSize(16);
$xls->setFontStyle('A1','bold');
$xls->setAlligment('A1','center');

//building header
$xls->setColumnSize('A',20);
$xls->setColumnSize('C',20);
$xls->setColumnSize('D',15);
$xls->setColumnSize('E',20);
//$xls->getActiveSheet()->getStyle('A2:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$xls->setAlligment('A3:E3','center');
$xls->setFontStyle('A3:E3','bold');

$sheet->setCellValue('A3', 'Supplier name');
$sheet->setCellValue('B3', 'Order No');
$sheet->setCellValue('C3', 'Invoice No');
$sheet->setCellValue('D3', 'Invoice Value');
$sheet->setCellValue('E3', 'Comodity Code');

$htmlTable = $xls->suppAdd($sheet);

$tableHeader = "<thead>
    <tr>
      <th>Supplier<br/>name</th>
      <th>Order No</th>
      <th>Invoice No</th>
      <th>Invoice<br/>Value</th>
      <th>Comodity<br/>Code</th>
    </tr>
  </thead>";

$sheet->freezePane('A4');

$sheet->setCellValue('D2', 'Total');
$xls->setAlligment('D2','right');

$totalInvoicesValue = $xls->getSumTotal();

$sheet->setCellValue('E2', $totalInvoicesValue);
$xls->setAlligment('E2','right');
$xls->setFontStyle('E2','bold');

$xls->getEuro('E2');

if($_POST['month']<10){
    $month = "0".$_POST['month'];
    }else{
      $month = $_POST['month'];
    };

$fileName = 'com_code_raport_'.$_POST['year'].'_'.$month;

$xls->saveToXls('../export/'.$fileName.'.xlsx');



$totalHtml = "<h5>Total Invoice Value: &euro;".$totalInvoicesValue."</h5>";

$html = "<C><H3>".$raportTitle."</H3></C>".$totalHtml."<TABLE>".$tableHeader.$htmlTable."</TABLE>";
$mpdf->WriteHTML($html);
$mpdf->Output("../export/".$fileName.".pdf");


$directory = explode("\\",dirname(dirname(__FILE__)));
//print_r($directory);
$pathToFileXlsx = dirname(pathinfo(__FILE__)['dirname']).'\\export\\'.$fileName.'.xlsx';
$pathToFilePdf = dirname(pathinfo(__FILE__)['dirname']).'\\export\\'.$fileName.'.pdf';

$links = "";

if(file_exists($pathToFileXlsx)){
  $links .= "<a href = '/".$directory[count($directory)-1]."/export/".$fileName.".xlsx'>Download ".$fileName.".xlsx </a><br/>";
}
if(file_exists($pathToFilePdf)){
  $links .= "<a href = '/".$directory[count($directory)-1]."/export/".$fileName.".pdf'>Download ".$fileName.".pdf </a><br/>";
}
echo $links."<br/>".$show;
//echo "<a href = '../export/".$fileName.".pdf'>".$fileName."</a>";

  ?>