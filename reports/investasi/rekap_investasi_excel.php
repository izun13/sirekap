<?php
require_once "../../includes/koneksi.php";
require_once "../../includes/tanggal.php";
//$date = date("Y-m-d");
$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
$search4 = $_GET["search4"];

error_reporting(E_ALL);

/** PHPExcel */
require_once "../../Classes/PHPExcel.php";

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

$header = array("No.","Tahun","Bulan","Nama Perusahaan","Bidang Usaha","Jenis Modal","Nilai Investasi","Jumlah TK","Nomor Izin","Kegiatan Usaha","Nomor Telepon");

$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$objPHPExcel->getActiveSheet()->setCellValue($col.'5',$header[$y]);
$col++;
}


$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$cols.'1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$cols.'2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:'.$cols.'3');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setTitle('DATA');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'REKAPITULASI REALISASI INVESTASI');
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU KOTA MAGELANG');
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'BULAN : '.STRTOUPPER($search4).' '.$search3);

$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$i = 1;
	$j = 6;
	$jum = 0;
	
	$tabel = "SELECT*FROM realisasi_investasi WHERE id IS NOT NULL";
	if(($search1 != "") and ($search2 != ""))$tabel .= " AND $search1 LIKE '%$search2%'";
	if(($search3 != ""))$tabel .= " AND tahun LIKE '%$search3%'";
	if(($search4 != ""))$tabel .= " AND bulan LIKE '%$search4%'";
	
	$tabel .= " ORDER BY id asc";
		
	$query = mysql_query($tabel);
		while ($r= mysql_fetch_array($query)){
			////tahun,bulan,nama_perusahaan,bidang_usaha,jenis_modal,nilai_investasi,jumlah_tk,no_izin,kegiatan_usaha,no_telepon			
			$value = array ($i,$r['tahun'],$r['bulan'],$r['nama_perusahaan'],$r['bidang_usaha'],$r['jenis_modal'],$r['nilai_investasi'],$r['jumlah_tk'],$r['no_izin'],$r['kegiatan_usaha'],$r['no_telepon']);
			$col = "A";
			for($y=0;$y< count($header);$y++){
				$cols = $col;
				$objPHPExcel->getActiveSheet()->setCellValue($col.$j,$value[$y]);
				$col++;
			}			
			$i++;	
			$j++;
			$jum += $r['nilai_investasi'];
		}
		
$objPHPExcel->getActiveSheet()->getStyle('A6:'.$cols.$j)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A6:'.$cols.$j)->getFont()->setSize(10);
//$objPHPExcel->getActiveSheet()->getStyle('A5:'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A6:A'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('A'.$j,"TOTAL");
$objPHPExcel->getActiveSheet()->mergeCells('A'.$j.':'.'F'.$j);
$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':'.'F'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('G'.$j,$jum);
$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':'.$col.$j)->getFont()->setBold(true);

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
header('Content-Disposition: attachment;filename="Rekap Relaisasi Investasi.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
