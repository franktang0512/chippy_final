<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

require_once 'PHPExcel/Classes/PHPExcel.php';




// 設定樣本檔案的路徑
$templateFile = 'challengeforeachteacher/temp.xlsx';
// 讀取樣本檔案
$objPHPExcel = PHPExcel_IOFactory::load($templateFile);

// 取得樣本分頁的索引
$templateSheetIndex = 0;

// 複製樣本分頁
for ($i = 0; $i < 5; $i++) {
    $clonedSheet = clone $objPHPExcel->getSheet($templateSheetIndex);
    $clonedSheet->setTitle('挑戰班級名稱');
    $objPHPExcel->addSheet($clonedSheet);

    // 在複製的分頁的 A1 儲存格填入「你好」
    $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($clonedSheet));
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '你好');
	//
	
	
	
	
	
	
}
$objPHPExcel->removeSheetByIndex(0);
// 儲存 Excel 檔案
$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$writer->save('challengeforeachteacher/temp老師lala.xlsx');



echo "Excel 檔案已修改成功！";










?>
