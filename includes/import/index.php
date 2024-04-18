<?php
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(0);
require_once '../../Classes/PHPExcel.php';
require_once '../../Classes/PHPExcel/Reader/Excel2007.php';
require_once '../../Classes/PHPExcel/IOFactory.php';

//$objPHPExcel = PHPExcel_IOFactory::load("uploads/" . $file_import);
$objPHPExcel = PHPExcel_IOFactory::load("Listizin.xlsx");
 
 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

$worksheetTitle = $worksheet->getTitle();
 $highestRow = $worksheet->getHighestRow(); // e.g. 10
 $highestColumn = 'P'; //$worksheet->getHighestColumn(); // e.g 'F'
 $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
 
	 for ($row = 2; $row <= $highestRow; ++ $row) {
	 
		 $dataRow = array();
		 for ($col = 0; $col < $highestColumnIndex; ++ $col) {
			 $cell = $worksheet->getCellByColumnAndRow($col, $row);
			 $val = $cell->getValue();
			 echo $val."--";

			 $dataRow[$col] = $val;
		 }
		 echo "<br>";
	} 
}
?>
