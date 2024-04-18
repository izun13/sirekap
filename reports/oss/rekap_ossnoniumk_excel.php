<?php
require_once "../../includes/koneksi.php";
require_once "../../includes/tanggal.php";
		
if($_GET["search"])$searching = explode(";",$_GET["search"]);
if($_GET["group"])$group = $_GET["group"];

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

$header = array("No.","Nama Perusahaan","NIB","Tanggal NIB","Status PM","KBLI","Kegiatan Usaha","Tanggal Izin","Uraian","Status izin","Investasi","Jumlah Naker","Alamat Usaha");

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

$objPHPExcel->getActiveSheet()->setTitle('REKAP');
$objPHPExcel->getActiveSheet()->setCellValue('A1', "PEMERINTAH KOTA MAGELANG");
$objPHPExcel->getActiveSheet()->setCellValue('A2', "DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP)");
$objPHPExcel->getActiveSheet()->setCellValue('A3', "REKAPITULASI OSS IUMK");

$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$i = 1;
	$j = 6;	
	
	$tabel = "SELECT nama_perusahaan,nib,tanggal_nib,status_pm,kbli,nama_izin,tanggal_izin,uraian,status_izin,investasi,tenaga_kerja,alamat_proyek,lokasi_proyek FROM oss_noniumk WHERE id IS NOT NULL";
	
	for ($x=0;$x<count($searching)-1;$x++){
						
		if($searching[$x]){
			$searching2 = explode(":",$searching[$x]);
			$kol = $searching2[0];
			$sim = $searching2[1];
			$val = $searching2[2];
			$val = str_replace("_"," ",$val);
		}
				
		if($kol) {
			if(($sim == "LIKE") or ($sim == "NOT LIKE"))$tabel .= " AND ".$kol." ".$sim." '%".$val."%'";
			else $tabel .= " AND ".$kol." ".$sim." '".$val."'";
		}
	}
	
	//$tabel .= "ORDER BY id asc";	
	if($group) $tabel .= " GROUP BY nama_perusahaan";
	$query = mysql_query($tabel);
	
	while ($r= mysql_fetch_array($query)){
		$nib = "'".$r['nib'];
		$alamat = $r['alamat_proyek'].", ".$r['lokasi_proyek'];
		//nama_perusahaan,nib,tanggal_nib,status_pm,kbli,nama_izin,tanggal_izin,uraian,investasi,tenaga_kerja
		$value = array ($i,$r['nama_perusahaan'],$nib,tgl1($r['tanggal_nib']),$r['status_pm'],$r['kbli'],$r['nama_izin'],tgl1($r['tanggal_izin']),$r['uraian'],$r['status_izin'],$r['investasi'],$r['tenaga_kerja'],$alamat);
		$col = "A";
		for($y=0;$y< count($header);$y++){
			$cols = $col;
			$objPHPExcel->getActiveSheet()->setCellValue($col.$j,$value[$y]);
			$col++;
		}			
		$i++;
		$j++;
	}
		
$objPHPExcel->getActiveSheet()->getStyle('A6:'.$cols.$j)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A6:'.$cols.$j)->getFont()->setSize(10);
//$objPHPExcel->getActiveSheet()->getStyle('A5:'.$j)->getFont()->setBold(true);
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
header('Content-Disposition: attachment;filename="Rekap Non IUMK.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
