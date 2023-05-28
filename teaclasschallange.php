<?php
ini_set('display_errors','1');
error_reporting(E_ALL);



require_once 'PHPExcel/Classes/PHPExcel.php';
$inputFile = 'tasks_statement_file/template.xlsx'; // 输入文件路径

// 创建一个新的 PHPExcel 实例
$objPHPExcel = PHPExcel_IOFactory::load($inputFile);

// 读取数据

// 处理数据...

// 创建新的 PHPExcel 实例
$objPHPExcel = new PHPExcel();



// 保存文件
$outputFile = 'tasks_statement_file/output.xlsx';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($outputFile);

// 提供文件下载
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . basename($outputFile) . '"');
header('Cache-Control: max-age=0');
readfile($outputFile);
exit;


























?>
require_once 'PHPExcel/Classes/PHPExcel.php';
// 创建一个新的Excel对象
$objPHPExcel = new PHPExcel();

// 设置文档属性
$objPHPExcel->getProperties()->setCreator('Your Name')
                             ->setLastModifiedBy('Your Name')
                             ->setTitle('报表')
                             ->setSubject('报表示例')
                             ->setDescription('这是一个报表示例')
                             ->setKeywords('报表 PHPExcel 示例')
                             ->setCategory('报表');

// 创建一个工作表
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();

// 设置列标题
$sheet->setCellValue('A1', '食物');
$sheet->setCellValue('B1', '热量');
$sheet->setCellValue('C1', '营养成分');

// 设置列宽度
$sheet->getColumnDimension('A')->setWidth(20);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(30);

// 设置标题样式
$titleStyle = array(
    'font' => array(
        'bold' => true,
        'size' => 14,
    ),
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('argb' => 'FFFF00'),
    ),
);
$sheet->getStyle('A1:C1')->applyFromArray($titleStyle);

// 添加数据
$data = array(
    array('苹果', 52, '维生素C, 膳食纤维'),
    array('香蕉', 96, '维生素B6, 钾'),
    array('胡萝卜', 41, '维生素A, 纤维素'),
    array('鸡胸肉', 165, '蛋白质, 烟酸'),
);

$row = 2; // 数据开始的行数
foreach ($data as $item) {
    $sheet->setCellValue('A'.$row, $item[0]);
    $sheet->setCellValue('B'.$row, $item[1]);
    $sheet->setCellValue('C'.$row, $item[2]);
    $row++;
}

// 设置自动筛选
$lastColumn = $sheet->getHighestColumn();
$lastRow = $sheet->getHighestRow();
$sheet->setAutoFilter('A1:'.$lastColumn.$lastRow);

// 保存Excel文件
$filename = 'report.xlsx';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filename);

echo '报表文件已生成：'.$filename;
exit;











//todo:抓老師的名單













// 創建一個新的 PHPExcel 物件
$excel = new PHPExcel();
$n=10;

for($i =0;$i<$n;$i++){
	// 設定標題和內容
	if($i !=0){
		$excel->createSheet();
	}	
	$excel->setActiveSheetIndex($i);
	$excel->getActiveSheet()->setTitle('分頁'.($i+1));
	
	
	
	
	
	
	$excel->getActiveSheet()->setCellValue('A1', '挑戰賽分流網址：');
	
	
	// 設定要合併的儲存格範圍
	$mergeRange = 'B1:I1';
	
	// 設定合併儲存格的內容
	$excel->getActiveSheet()->setCellValue('B1', 'https://chippy.csie.ntnu.edu.tw/???');
	$excel->getActiveSheet()->mergeCells($mergeRange);
	$excel->getActiveSheet()->getStyle($mergeRange)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	//$excel->getActiveSheet()->getStyle($mergeRange)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	
	
	
	
	//
	$excel->getActiveSheet()->setCellValue('A2', '開課教師編號');
	$excel->getActiveSheet()->setCellValue('B2', '班級名稱');
	$excel->getActiveSheet()->setCellValue('C2', '班級名稱');
	$mergeRange = 'D2:G2';
		// 設定合併儲存格的內容
	$excel->getActiveSheet()->setCellValueExplicit('D2', "學生性別\n請勾選該帳號之學生性別", PHPExcel_Cell_DataType::TYPE_STRING);
	$excel->getActiveSheet()->mergeCells($mergeRange);
	$excel->getActiveSheet()->getStyle($mergeRange)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$excel->getActiveSheet()->getStyle($mergeRange)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	//
	//
	$excel->getActiveSheet()->setCellValue('H2', '班級類型');
	$excel->getActiveSheet()->setCellValue('I2', '備註');
	//
	//
	//
	//
	//
	//
	//
	//$excel->getActiveSheet()->setCellValue('A3', '-');
	//$excel->getActiveSheet()->setCellValue('B3', '-');
	//$excel->getActiveSheet()->setCellValue('C3', '-');
	//$excel->getActiveSheet()->setCellValue('D3', '全部男生');
	//
	//$checkboxCell = 'E3';
	//
	//// 創建一個新的表單控件
	//$checkbox = new PHPExcel_Formula_FormulaRecord();
	//$checkbox->setFormula('HYPERLINK("#' . $checkboxCell . '","[X]")');
	//$excel->getActiveSheet()->getCell($checkboxCell)->setValue($checkbox);
	//
	//
	//
	////$excel->getActiveSheet()->setCellValue('E3', '');
	//$excel->getActiveSheet()->setCellValue('F3', '全部女生');
	//	$checkboxCell = 'G3';
	//
	//// 創建一個新的表單控件
	//$checkbox = new PHPExcel_Formula_FormulaRecord();
	//$checkbox->setFormula('HYPERLINK("#' . $checkboxCell . '","[X]")');
	//$excel->getActiveSheet()->getCell($checkboxCell)->setValue($checkbox);
	//
	//
	//
	//// 設定下拉選單的選項列表
	//$options = array('普通班', '數理資優班', '人文資優班', '體育班', '其他（請於備註欄註明）');
	//
	//// 設定下拉選單所在的儲存格
	//$dropdownCell = 'H3';
	//
	//// 設定下拉選單的選項
	//$validation = $excel->getActiveSheet()->getCell($dropdownCell)->getDataValidation();
	//$validation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
	//$validation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
	//$validation->setAllowBlank(false);
	//$validation->setShowInputMessage(true);
	//$validation->setShowErrorMessage(true);
	//$validation->setShowDropDown(true);
	//$validation->setErrorTitle('輸入錯誤');
	//$validation->setError('您輸入的值不在下拉選單中。');
	//$validation->setPromptTitle('選擇');
	//$validation->setPrompt('請從下拉選單中選擇一個選項。');
	//$validation->setFormula1('"' . implode(',', $options) . '"');
	
	
	
	$excel->getActiveSheet()->setCellValue('H3', '班級名稱');
	
	
	$sheet = $excel->getActiveSheet();
	$cellIterator = $sheet->getRowIterator();
	foreach ($cellIterator as $row) {
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(true);
		foreach ($cellIterator as $cell) {
			if (!is_null($cell->getValue())) {
				$columnIndex = $cell->getColumn();
				$sheet->getColumnDimension($columnIndex)->setAutoSize(true);
			}
		}
	}
	
	
	
	
	
}

$filename = 'example.xlsx';
$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

// 設定下載的 headers
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');
// 寫入檔案
$writer->save('php://output');
exit;

?>