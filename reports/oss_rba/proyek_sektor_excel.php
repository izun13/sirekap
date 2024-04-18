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
$header = array("No.","ID Proyek","NIB","Nama Perusahaan","KBLI","Judul KBLI","Sektor","Realisasi Investasi","TKI");

$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$sheet->setCellValue($col.'5',$header[$y]);
$col++;
}

$tabel_kel = "SELECT*FROM kelompok_sektor ORDER BY id asc";	
$query_kel = mysql_query($tabel_kel);
while ($r_kel= mysql_fetch_array($query_kel)){
	$cols = $col;
	$sheet->setCellValue($col.'5',$r_kel['kelompok_sektor']);
	$sheet->setCellValue($col.'6',"Nilai Investasi");
	$col++;
	$sheet->setCellValue($col.'6',"TKI");
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

$total = 0; $total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $total5 = 0; $total6 = 0; $total7 = 0; $total8 = 0; $total9 = 0;
$total_tki = 0; $total_tki1 = 0; $total_tki2 = 0; $total_tki3 = 0; $total_tki4 = 0; $total_tki5 = 0; $total_tki6 = 0; $total_tki7 = 0; $total_tki8 = 0; $total_tki9 = 0;
//Investasi Baru
	$sheet->setCellValue('B7',"PENANAM MODAL/INVESTOR BARU"); 

	$tabel = "SELECT id,id_proyek,nib,nama_perusahaan,kbli,judul_kbli,sektor,tambah_investasi,tki,kelompok_sektor_id FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND 
			status_perusahaan = 'Baru' AND tambah_investasi != '0' AND tgl_verifikasi LIKE '%$send%' ORDER BY nama_perusahaan asc";	
	$query = mysql_query($tabel);
	
	$i = 1;
	$j = 8;
	$jml = 0;
	$tki = 0;
	while ($r= mysql_fetch_array($query)){
		$col = "A";
		for($y = 0; $y < mysql_num_fields($query); $y++){
			$name = mysql_field_name($query, $y);
			$cols = $col;
			if($name!="kelompok_sektor_id") {
				if($name=="id") $sheet->setCellValue($col.$j,$i);
				else $sheet->setCellValue($col.$j,$r[$name]);
			}else{
				if($r[$name] == 1){
					$sheet->setCellValue("J".$j,$r['tambah_investasi']);
					$sheet->setCellValue("K".$j,$r['tki']);
					$total1 = $r['tambah_investasi'];
				}
				if($r[$name] == 2){
					$sheet->setCellValue("L".$j,$r['tambah_investasi']);
					$sheet->setCellValue("M".$j,$r['tki']);
					$total2 = $r['tambah_investasi'];
				}
				if($r[$name] == 3){
					$sheet->setCellValue("N".$j,$r['tambah_investasi']);
					$sheet->setCellValue("O".$j,$r['tki']);
					$total3 = $r['tambah_investasi'];
				}
				if($r[$name] == 4){
					$sheet->setCellValue("P".$j,$r['tambah_investasi']);
					$sheet->setCellValue("Q".$j,$r['tki']);
					$total4 = $r['tambah_investasi'];
				}
				if($r[$name] == 5){
					$sheet->setCellValue("R".$j,$r['tambah_investasi']);
					$sheet->setCellValue("S".$j,$r['tki']);
					$total5 = $r['tambah_investasi'];
				}
				if($r[$name] == 6){
					$sheet->setCellValue("T".$j,$r['tambah_investasi']);
					$sheet->setCellValue("U".$j,$r['tki']);
					$total6 = $r['tambah_investasi'];
				}
				if($r[$name] == 7){
					$sheet->setCellValue("V".$j,$r['tambah_investasi']);
					$sheet->setCellValue("W".$j,$r['tki']);
					$total7 = $r['tambah_investasi'];
				}
				if($r[$name] == 8){
					$sheet->setCellValue("X".$j,$r['tambah_investasi']);
					$sheet->setCellValue("Y".$j,$r['tki']);
					$total8 = $r['tambah_investasi'];
				}
				if($r[$name] == 9){
					$sheet->setCellValue("Z".$j,$r['tambah_investasi']);
					$sheet->setCellValue("AA".$j,$r['tki']);
					$total9 = $r['tambah_investasi'];
				}
			}
			
			$col++;
		}	

		$jml += $r["tambah_investasi"];
		$tki += $r["tki"];
		$i++;
		$j++;
	}
	
	$sheet->setCellValue('B'.$j,"JUMLAH"); 
	$sheet->setCellValue('H'.$j,$jml);
	$sheet->setCellValue('I'.$j,$tki);
	$j++;
	$jum_nib = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT(nib)) AS jum FROM view_proyek WHERE status_perusahaan = 'Baru' AND verifikasi = 'Terverifikasi' AND tambah_investasi != '0' AND tgl_verifikasi LIKE '%$send%'"));
	$sheet->setCellValue('B'.$j,"JUMLAH PERUSAHAAN"); 
	$sheet->setCellValue('H'.$j,$jum_nib["jum"]);
	
//Jumlah Tiap Sektor
	$colss = "J";
	$jj = $j-1;
	$tabel_kel = "SELECT*FROM kelompok_sektor ORDER BY id asc";	
	$query_kel = mysql_query($tabel_kel);
	while ($r_kel= mysql_fetch_array($query_kel)){
		$tabel = "SELECT nib,tambah_investasi,tki,kelompok_sektor_id FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND status_perusahaan = 'Baru' AND tambah_investasi != '0' AND 
			tgl_verifikasi LIKE '%$send%' AND kelompok_sektor_id = '$r_kel[id]' ORDER BY nama_perusahaan asc";	
		$query = mysql_query($tabel);
		
		$jml = 0;
		$tki = 0;
		$jml_nib = 0;
		$nib_old = "";
		while ($r= mysql_fetch_array($query)){
			if($r["nib"] != $nib_old) $jml_nib++;
			$nib_old = $r["nib"];			
			$jml += $r["tambah_investasi"];
			$tki += $r["tki"];
			
		}
		
		$sheet->setCellValue($colss.$jj,$jml);
		$sheet->setCellValue($colss.$j,$jml_nib);
		$colss ++;
		$sheet->setCellValue($colss.$jj,$tki); 
		$colss ++;	
	}
	
//Penambahan Investasi
	$j+=2;
	$sheet->setCellValue('B'.$j,"PENAMBAHAN INVESTASI"); 
	$j++;

	$tabel = "SELECT id,id_proyek,nib,nama_perusahaan,kbli,judul_kbli,sektor,tambah_investasi,tki,status_kbli,kelompok_sektor_id FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND 
			status_perusahaan = 'Lama' AND tambah_investasi != '0' AND tgl_verifikasi LIKE '%$send%' ORDER BY status_kbli DESC,nama_perusahaan ASC";	
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
				
				if($name!="kelompok_sektor_id") {
					if($name=="id") $sheet->setCellValue($col.$j,$i);
					else $sheet->setCellValue($col.$j,$r[$name]);
				}else{
					if($r[$name] == 1){
						$sheet->setCellValue("J".$j,$r['tambah_investasi']);
						$sheet->setCellValue("K".$j,$r['tki']);
					}
					if($r[$name] == 2){
						$sheet->setCellValue("L".$j,$r['tambah_investasi']);
						$sheet->setCellValue("M".$j,$r['tki']);
					}
					if($r[$name] == 3){
						$sheet->setCellValue("N".$j,$r['tambah_investasi']);
						$sheet->setCellValue("O".$j,$r['tki']);
					}
					if($r[$name] == 4){
						$sheet->setCellValue("P".$j,$r['tambah_investasi']);
						$sheet->setCellValue("Q".$j,$r['tki']);
					}
					if($r[$name] == 5){
						$sheet->setCellValue("R".$j,$r['tambah_investasi']);
						$sheet->setCellValue("S".$j,$r['tki']);
					}
					if($r[$name] == 6){
						$sheet->setCellValue("T".$j,$r['tambah_investasi']);
						$sheet->setCellValue("U".$j,$r['tki']);
					}
					if($r[$name] == 7){
						$sheet->setCellValue("V".$j,$r['tambah_investasi']);
						$sheet->setCellValue("W".$j,$r['tki']);
					}
					if($r[$name] == 8){
						$sheet->setCellValue("X".$j,$r['tambah_investasi']);
						$sheet->setCellValue("Y".$j,$r['tki']);
					}
					if($r[$name] == 9){
						$sheet->setCellValue("Z".$j,$r['tambah_investasi']);
						$sheet->setCellValue("AA".$j,$r['tki']);
					}
				}
			}
			
			$col++;
		}		

		$jml += $r["tambah_investasi"];
		$tki += $r["tki"];
		$i++;
		$j++;
	}
	
	$sheet->setCellValue('B'.$j,"JUMLAH"); 
	$sheet->setCellValue('H'.$j,$jml);
	$sheet->setCellValue('I'.$j,$tki);
	$j++;
	$jum_nib = mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT(nib)) AS jum FROM view_proyek WHERE status_perusahaan = 'Lama' AND verifikasi = 'Terverifikasi' AND tambah_investasi != '0' AND tgl_verifikasi LIKE '%$send%'"));
	$sheet->setCellValue('B'.$j,"JUMLAH PERUSAHAAN"); 
	$sheet->setCellValue('H'.$j,$jum_nib{"jum"});

//Jumlah Tiap Sektor
	$colss = "J";
	$jj = $j-1;
	$tabel_kel = "SELECT*FROM kelompok_sektor ORDER BY id asc";	
	$query_kel = mysql_query($tabel_kel);
	while ($r_kel= mysql_fetch_array($query_kel)){
		$tabel = "SELECT nib,tambah_investasi,tki,status_kbli,kelompok_sektor_id FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND status_perusahaan = 'Lama' AND tambah_investasi != '0' AND 
			tgl_verifikasi LIKE '%$send%' AND kelompok_sektor_id = '$r_kel[id]' ORDER BY status_kbli DESC,nama_perusahaan ASC";
		$query = mysql_query($tabel);
		
		$jml = 0;
		$tki = 0;
		$jml_nib = 0;
		$nib_old = "";
		while ($r= mysql_fetch_array($query)){
			if($r["nib"] != $nib_old) $jml_nib++;
			$nib_old = $r["nib"];			
			$jml += $r["tambah_investasi"];
			$tki += $r["tki"];
			
		}
		
		$sheet->setCellValue($colss.$jj,$jml);
		$sheet->setCellValue($colss.$j,$jml_nib);
		$colss ++;
		$sheet->setCellValue($colss.$jj,$tki); 
		$colss ++;	
	}
	
	
$writer = new Xlsx($spreadsheet);
$writer->save('Realisasi Per Sektor.xlsx');
echo "<script>window.location = 'Realisasi Per Sektor.xlsx'</script>";
?>
