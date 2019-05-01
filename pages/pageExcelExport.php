<?php

include('..\class\classProduct.php');
require '..\excel\vendor\autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//$orderArray = json_decode($_POST['orderArray']);
/*$taricArray = $_POST['taricArray'];
$shopNameArray = $_POST['shopNameArray'];*/

$product = json_decode($_POST['orderArray']);

print_r($product);

/*
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');

$writer = new Xlsx($spreadsheet);
$writer->save('../export/hello world.xlsx');*/
//echo "done";
?>