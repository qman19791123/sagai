<?php

//qmancms
include ('qmancms/plus/Excel/PHPExcel.php');
include ('qmancms/plus/Excel/PHPExcel/Writer/Excel2007.php');
$name =date('Y-m-d');
error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
$objPHPExcel = new PHPExcel();


foreach ($data as $k => $v) {
    $num = $k + 1;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $num, $v[0])
            ->setCellValue('B' . $num, $v[1])
            ->setCellValue('C' . $num, $v[2]);
}
$objPHPExcel->getActiveSheet()->setTitle('User');
$objPHPExcel->setActiveSheetIndex(0);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="' . $name . '.xls"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

