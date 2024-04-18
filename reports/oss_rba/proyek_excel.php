<?php
include "../../includes/parser-php-version.php";
require_once "../../includes/tanggal.php";
require_once "../../includes/rupiah.php";
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();	
		
error_reporting(E_ALL);

//$search1 = $_POST["search1"]; 
//$search2 = $_POST["search2"];
$send = $_GET["send"];

/** PhpSpreadsheet */
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// sheet peratama
$sheet->setTitle('Sheet 1');

$sheet->setCellValue('A1', "");
$sheet->setCellValue('A2', "DATA REALISASI PENANAMAN MODAL/INVESTASI");
$sheet->setCellValue('A3', "Bulan : ".bulantahun($send));

		
//$header = array("No.","ID Proyek","Nama Perusahaan","NIB","Status","Jenis Perusahaan","Resiko","Skala Usaha","Alamat Usaha","No. Telepon","KBLI","Judul KBLI","Sektor",
//"Jumlah Investasi","Mesin Peralatan","Mesin Peralatan Impor","Pembelian Pematangan Tanah","Bangunan Gedung","Modal Kerja","Lain-lain","Tenaga Kerja","Status Perusahaan");
$header = array("No.","ID Proyek","NIB","Nama Perusahaan","KBLI","Judul KBLI","Sektor","Grup Sektor","Realisasi Investasi","TKI");

$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$sheet->setCellValue($col.'5',$header[$y]);
$col++;
}

/*
$sheet->getStyle('A1:A3')->getFont()->setName('Arial');
$sheet->getStyle('A1:A3')->getFont()->setSize(12);
$sheet->getStyle('A1:A3')->getFont()->setBold(true);

$sheet->mergeCells('A1:'.$cols.'1');
$sheet->mergeCells('A2:'.$cols.'2');
$sheet->mergeCells('A3:'.$cols.'3');
$sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->setTitle('REKAP');
$sheet->setCellValue('A1', "");
$sheet->setCellValue('A2', "DATA REALISASI PENANAMAN MODAL/INVESTASI");
$sheet->setCellValue('A3', "Bulan : ".bulantahun($search2));

$sheet->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$sheet->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
*/

$total = 0;
$total_tki = 0;
$total_nib = 0;
//INVESTOR BARU
$sheet->setCellValue('B6',"PENANAM MODAL/INVESTOR BARU"); 
$stat_pm = array("PMDN","PMA","Bukan PMA/PMDN");
$i = 1;
$j = 7;

for($x=0;$x<count($stat_pm);$x++){
$sheet->setCellValue('B'.$j,$stat_pm[$x]); 
$j++;

	$tabel = "SELECT id,id_proyek,nib,nama_perusahaan,kbli,judul_kbli,sektor,kelompok_sektor,tambah_investasi,tki FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND 
			status_perusahaan = 'Baru' AND tambah_investasi != '0' AND tgl_verifikasi LIKE '%$send%' AND uraian_status_penanaman_modal = '$stat_pm[$x]' 
			ORDER BY nama_perusahaan asc";	
	$query = mysql_query($tabel);
	
	$jml = 0;
	$tki = 0;
	while ($r= mysql_fetch_array($query)){
		$col = "A";
		for($y = 0; $y < mysql_num_fields($query); $y++){
			$name = mysql_field_name($query, $y);
			$cols = $col;
			if($name=="id") $sheet->setCellValue($col.$j,$i);
			else $sheet->setCellValue($col.$j,$r[$name]);
			
			$col++;
		}	

		$jml += $r["tambah_investasi"];
		$tki += $r["tki"];
		$i++;
		$j++;
	}
	
	$sheet->setCellValue('B'.$j,"JUMLAH"); 
	$sheet->setCellValue('I'.$j,$jml);
	$sheet->setCellValue('J'.$j,$tki);
	$j++;
	$jum_nib = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT(nib)) AS jum FROM view_proyek WHERE status_perusahaan = 'Baru' AND uraian_status_penanaman_modal = '$stat_pm[$x]' AND verifikasi = 'Terverifikasi' AND tambah_investasi != '0' AND tgl_verifikasi LIKE '%$send%'"));
	$sheet->setCellValue('B'.$j,"JUMLAH PERUSAHAAN"); 
	$sheet->setCellValue('I'.$j,$jum_nib["jum"]);
	$j++;
	
	$total += $jml;
	$total_tki += $tki;
}


//INVESTOR LAMA	
	$j++;
	$sheet->setCellValue('B'.$j,"PENAMBAHAN INVESTASI"); 
	$j++;

for($x=0;$x<count($stat_pm);$x++){
	$sheet->setCellValue('B'.$j,$stat_pm[$x]); 
	$j++;
	
	$tabel = "SELECT id,id_proyek,nib,nama_perusahaan,kbli,judul_kbli,sektor,kelompok_sektor,tambah_investasi,tki,status_kbli FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND 
			status_perusahaan = 'Lama' AND tambah_investasi != '0' AND tgl_verifikasi LIKE '%$send%' AND uraian_status_penanaman_modal = '$stat_pm[$x]' 
			ORDER BY status_kbli DESC,nama_perusahaan ASC";	
	$query = mysql_query($tabel);
	
	$i = 1;
	$jml = 0;
	$tki = 0;
	$kbli_new = 0;
	while ($r= mysql_fetch_array($query)){
		
		if(($r["status_kbli"] == "Baru") AND ($kbli_new == 0)){
			$sheet->setCellValue("B".$j,"PENAMBAHAN KBLI");
			$j++;
			$kbli_new = 1;
		}

		$col = "A";
		for($y = 0; $y < mysql_num_fields($query); $y++){
			$name = mysql_field_name($query, $y);
			$cols = $col;
			if($name!="status_kbli") {
				if($name=="id") $sheet->setCellValue($col.$j,$i);
				else $sheet->setCellValue($col.$j,$r[$name]);
			}
			
			$col++;
		}		

		$jml += $r["tambah_investasi"];
		$tki += $r["tki"];
		$i++;
		$j++;
	}
	
	$sheet->setCellValue('B'.$j,"JUMLAH"); 
	$sheet->setCellValue('I'.$j,$jml);
	$sheet->setCellValue('J'.$j,$tki);
	$j++;
	$jum_nib = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT(nib)) AS jum FROM view_proyek WHERE status_perusahaan = 'Lama' AND uraian_status_penanaman_modal = '$stat_pm[$x]' AND verifikasi = 'Terverifikasi' AND tambah_investasi != '0' AND tgl_verifikasi LIKE '%$send%'"));
	$sheet->setCellValue('B'.$j,"JUMLAH PERUSAHAAN"); 
	$sheet->setCellValue('I'.$j,$jum_nib["jum"]);
	$j++;
	
	$total += $jml;
	$total_tki += $tki;
}
	
	$j++;
	$sheet->setCellValue('B'.$j,"TOTAL"); 
	$sheet->setCellValue('I'.$j,$total);
	$sheet->setCellValue('J'.$j,$total_tki);
	$j++;
	
	$jum_nib = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT(nib)) AS jum FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND tambah_investasi != '0' AND tgl_verifikasi LIKE '%$send%'"));		
	$sheet->setCellValue('B'.$j,"TOTAL PERUSAHAAN"); 
	$sheet->setCellValue('I'.$j,$jum_nib["jum"]);

/*$sheet->getStyle('A6:'.$cols.$j)->getFont()->setName('Arial');
$sheet->getStyle('A6:'.$cols.$j)->getFont()->setSize(10);
//$sheet->getStyle('A5:'.$j)->getFont()->setBold(true);
$sheet->getStyle('A6:A'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
exit;*/


$writer = new Xlsx($spreadsheet);
$writer->save('Realisasi Investasi.xlsx');
echo "<script>window.location = 'Realisasi Investasi.xlsx'</script>";
?>
