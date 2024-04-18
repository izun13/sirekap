<?php
include "../../includes/parser-php-version.php";
require_once "../../includes/tanggal.php";
require_once "../../includes/rupiah.php";
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();	
		
error_reporting(E_ALL);

/** PhpSpreadsheet */
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// sheet peratama
$sheet->setTitle('Sheet 1');

$search = explode(";",$_GET["send"]);
$search1 = $search[0]; 
$search2 = $search[1]; 
$search3 = $search[2]; 
$search4 = $search[3];
		
$sheet->setCellValue('A1', "DAFTAR PERSETUJUAN BANGUNAN GEDUNG (PBG)");
$sheet->setCellValue('A2', "DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU KOTA MAGELANG");
$sheet->setCellValue('A3', "Tanggal Terbit : ".tgl2($search1)." s/d ".tgl2($search2));
		
	$tabel = "SELECT*FROM tb_pbg WHERE id IS NOT NULL";		

	$query = mysql_query($tabel);
	$col = "A";		
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);
		$title = str_replace("_"," ",$name);
		$title = ucwords($title);
		$sheet->setCellValue($col.'5',$title);
		$col++;
	}
	
	if(($search1 != "") and ($search2 != ""))$tabel .= " AND date(tgl_terbit) >= '$search1' AND date(tgl_terbit) <= '$search2'";
	if(($search3 != "") and ($search4 != ""))$tabel .= " AND $search3 LIKE '%$search4%'";
		
	$query = mysql_query($tabel);
	
	$i = 1;
	$j = 6;
	while ($r= mysql_fetch_array($query)){
		$col = "A";
		for($y = 0; $y < mysql_num_fields($query); $y++){
			$name = mysql_field_name($query, $y);
			$cols = $col;
			if($name=="id") $sheet->setCellValue($col.$j,$i);
			//elseif($name=="jumlah_investasi")$sheet->setCellValue($col.$j,rupiah($r[$name]));
			//elseif(($name=="nib") or ($name=="nomor_telp")) $sheet->setCellValue($col.$j,"_".($r[$name]));
			elseif($name=="tgl_terbit") $sheet->setCellValue($col.$j,tgl1($r[$name]));
			else $sheet->setCellValue($col.$j,$r[$name]);				
			$col++;
		}			
		$i++;
		$j++;
	}

$writer = new Xlsx($spreadsheet);
$writer->save('Data PBG.xlsx');
echo "<script>window.location = 'Data PBG.xlsx'</script>";
?>
