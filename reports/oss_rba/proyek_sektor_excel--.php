<?php
include "../../includes/parser-php-version.php";
//require_once "../../includes/koneksi.php";
require_once "../../includes/tanggal.php";
require_once "../../includes/rupiah.php";

require_once "../../includes/konfigurasi_db.php";
koneksi2_buka();	
	
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

//if($_GET["search"])$searching = explode(";",$_GET["search"]);
$search1 = $_POST["search1"]; 
$search2 = $_POST["search2"];
		
//$header = array("No.","ID Proyek","Nama Perusahaan","NIB","Status","Jenis Perusahaan","Resiko","Skala Usaha","Alamat Usaha","No. Telepon","KBLI","Judul KBLI","Sektor",
//"Jumlah Investasi","Mesin Peralatan","Mesin Peralatan Impor","Pembelian Pematangan Tanah","Bangunan Gedung","Modal Kerja","Lain-lain","Tenaga Kerja","Status Perusahaan");
$header = array("No.","NIB","Nama Perusahaan","Judul KBLI","Sektor","Kelompok Sektor","Realisasi Investasi","TKI","TKA");

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
$objPHPExcel->getActiveSheet()->setCellValue('A1', "");
$objPHPExcel->getActiveSheet()->setCellValue('A2', "DATA REALISASI PENANAMAN MODAL/INVESTASI");
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Bulan : ".bulantahun($search2));

$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setCellValue('B6',"PENANAM MODAL/INVESTOR BARU"); 

	$tabel = "SELECT id,nib,nama_perusahaan,judul_kbli,sektor,kelompok_sektor,tambah_investasi,tki FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND status_perusahaan = 'Baru' AND tambah_investasi != '0' AND 
			tgl_input >= '$search1' AND tgl_input <= '$search2' ORDER BY kelompok_sektor asc,nama_perusahaan asc";	
	$query = mysql_query($tabel);
	
	$i = 1;
	$j = 7;
	$jml = 0;
	$tki = 0;
	$jml_nib = 0;
	$nib_old = "";
	while ($r= mysql_fetch_array($query)){
		$col = "A";
		for($y = 0; $y < mysql_num_fields($query); $y++){
			$name = mysql_field_name($query, $y);
			$cols = $col;
			if($name=="id") $objPHPExcel->getActiveSheet()->setCellValue($col.$j,$i);
			elseif($name=="nib") {
				$objPHPExcel->getActiveSheet()->setCellValue($col.$j,$r[$name]);
				if($r[$name] != $nib_old) $jml_nib++;
				$nib_old = $r[$name];
			}
			else $objPHPExcel->getActiveSheet()->setCellValue($col.$j,$r[$name]);
			
			$col++;
		}	

		$jml += $r["tambah_investasi"];
		$tki += $r["tki"];
		$i++;
		$j++;
	}
	
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,"JUMLAH"); 
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j,$jml);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j,$tki);
	$j++;
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,"JUMLAH PERUSAHAAN"); 
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j,$jml_nib);
	$j+=2;
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,"PENAMBAHAN INVESTASI"); 
	$j++;

	$tabel = "SELECT id,nib,nama_perusahaan,judul_kbli,sektor,kelompok_sektor,tambah_investasi,tki,status_kbli FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND status_perusahaan = 'Lama' AND tambah_investasi != '0' AND 
			tgl_input >= '$search1' AND tgl_input <= '$search2' ORDER BY status_kbli asc,nama_perusahaan asc";	
	$query = mysql_query($tabel);
	
	$i = 1;
	$jml = 0;
	$tki = 0;
	$jml_nib = 0;
	$nib_old = "";
	$kbli_new = 0;
	while ($r= mysql_fetch_array($query)){
		
		if(($r["status_kbli"] == "Penambahan") AND ($kbli_new == 0)){
			$objPHPExcel->getActiveSheet()->setCellValue("B".$j,"PENAMBAHAN KBLI");
			$j++;
			$kbli_new = 1;
		}

		$col = "A";
		for($y = 0; $y < mysql_num_fields($query); $y++){
			$name = mysql_field_name($query, $y);
			$cols = $col;
			if($name!="status_kbli") {
				if($name=="id") $objPHPExcel->getActiveSheet()->setCellValue($col.$j,$i);
				elseif($name=="nib") {
					$objPHPExcel->getActiveSheet()->setCellValue($col.$j,$r[$name]);
					if($r[$name] != $nib_old) $jml_nib++;
					$nib_old = $r[$name];
				}
				else $objPHPExcel->getActiveSheet()->setCellValue($col.$j,$r[$name]);
			}
			
			$col++;
		}		

		$jml += $r["tambah_investasi"];
		$tki += $r["tki"];
		$i++;
		$j++;
	}
	
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,"JUMLAH"); 
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j,$jml);
	$objPHPExcel->getActiveSheet()->setCellValue('G'.$j,$tki);
	$j++;
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,"JUMLAH PERUSAHAAN"); 
	$objPHPExcel->getActiveSheet()->setCellValue('F'.$j,$jml_nib);
	
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
header('Content-Disposition: attachment;filename="Laporan Proyek.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
