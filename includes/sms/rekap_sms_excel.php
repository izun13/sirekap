<?php
mysql_connect("172.17.20.7","smsgateway","sms432432432");
$con=mysql_select_db("gammu");

//include"../koneksi.php";
include"../tanggal.php";
$date = date("Y-m-d");
$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
$tgl_now = tgl2(date("Y-m-d"));

error_reporting(E_ALL);

/** PHPExcel */
require_once '../../Classes/PHPExcel.php';

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("")
                             ->setLastModifiedBy("")
                             ->setTitle("Office 2007 XLSX Test Document")
                             ->setSubject("Office 2007 XLSX Test Document")
                             ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                             ->setKeywords("office 2007 openxml php")
                             ->setCategory("Test result file");


// Create the worksheet
$objPHPExcel->setActiveSheetIndex(0);

// Add an image to the worksheet
/*$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Media Kreatif Indonesia');
$objDrawing->setDescription('Logo Media Kreatif');
$objDrawing->setPath('../img/logo.jpg');
$objDrawing->setCoordinates('B2');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/

$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:J2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:J3');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setTitle('DATA WNI');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'REKAPITULASI PERMOHONAN PERIZINAN');
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP) KOTA MAGELANG');
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Tanggal Laporan : '.$tgl_now);

//set table header
$objPHPExcel->getActiveSheet()->setCellValue('A5','No.');           
$objPHPExcel->getActiveSheet()->setCellValue('B5','Tanggal');           
$objPHPExcel->getActiveSheet()->setCellValue('C5','Nomor SMSC');         
$objPHPExcel->getActiveSheet()->setCellValue('D5','Nomor Tujuan');
$objPHPExcel->getActiveSheet()->setCellValue('E5','Isi SMS'); 
$objPHPExcel->getActiveSheet()->setCellValue('F5','Status');

$objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('A5:J5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$i = 1;
	$j = 6;	
	$tabel = "select * from sentitems order by ID asc";
	
	if(($search1 != "") and ($search2 != "")){		
		$tabel = "select * from sentitems WHERE SendingDateTime >= '$search1' and SendingDateTime <= '$search2' order by ID asc";
	}
	if(($search3 != "")){		
		$tabel = "select * from sentitems WHERE Status LIKE '$search3' order by ID asc";
	}
	if(($search1 != "") and ($search2 != "") and ($search3 != "")){		
		$tabel = "select * from sentitems WHERE SendingDateTime >= '$search1' and SendingDateTime <= '$search2' and Status = '$search3' order by ID asc";
	}
		
	$query = mysql_query($tabel);
		while ($r= mysql_fetch_array($query)){
			$tgl1 = "";
			if($r['SendingDateTime']) $tgl1 = tgl2($r['SendingDateTime']);
			if($r['DestinationNumber']) $telp = preg_replace("/[^0-9]/", "", $r['DestinationNumber']);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$j,$i);           
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,$tgl1);           
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$j,$r['SMSCNumber']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$j,$telp); 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$j,$r['TextDecoded']); 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$j,$r['Status']);
			
			$i++;	
			$j++;
		}
		
$objPHPExcel->getActiveSheet()->getStyle('A6:F'.$j)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A6:F'.$j)->getFont()->setSize(10);
//$objPHPExcel->getActiveSheet()->getStyle('A5:I'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A6:A'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sharedStyle1 = new PHPExcel_Style();
 
$sharedStyle1->applyFromArray(
 array('borders' => array(
 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
 'right' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
 'left' => array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
 ),
 ));
 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Rekap Pengiriman SMS.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
