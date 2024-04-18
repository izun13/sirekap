<?php
error_reporting(E_ALL);
include "../../includes/parser-php-version.php";
require_once "../../includes/tanggal.php";
require_once "../../includes/rupiah.php";
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();

$search = explode(";",$_GET["search"]);
$search0 = $search[0];
$search1 = $search[1];
$search2 = $search[2];
$search3 = $search[3];
$search4 = $search[4];

if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
//$tgl_now = tgl2(date("Y-m-d"));

if($search3 == ""){
	$query_jns=mysql_query("SELECT*FROM jenis_izin WHERE opd_id = '3'");
	$x = 0;
	while($r_jns=mysql_fetch_array($query_jns)){
		$jenis_izin[$x] = $r_jns["id"];
		$x++;
	}
}

/** PhpSpreadsheet */
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Add an image to the worksheet
/*$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Media Kreatif Indonesia');
$objDrawing->setDescription('Logo Media Kreatif');
$objDrawing->setPath('../img/logo.jpg');
$objDrawing->setCoordinates('B2');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/
$sheet->setTitle('DATA PERMOHONAN');
$sheet->setCellValue('A1', 'DAFTAR PENERBITAN PERIZINAN REKLAME');
$sheet->setCellValue('A2', 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP) KOTA MAGELANG');
$sheet->setCellValue('A3', 'Dari Tanggal : '.tgl2($search1).' s/d '.tgl2($search2));

$header = array("No.","No. Permohonan","Nama Pemohon","Nama Perusahaan","Jenis Izin","Jenis Permohonan","Telp./HP. Pemohon","Jenis Reklame","Isi Reklame","Ukuran Reklame","Lokasi Reklame","Nomor Izin","Tanggal Penetapan","Akhir Masa Berlaku","Titik Koordinat");

$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$sheet->setCellValue($col.'5',$header[$y]);
$col++;
}

	$i = 1;
	$j = 6;	
	
	$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
	);  
	
	//$sumber = "https://sicantik.go.id/api/TemplateData/keluaran/27646.json";
	$sumber = "https://sicantik.go.id/api/TemplateData/keluaran/37133.json";
	$sumber .= "?key1='$search1'&key2='$search2'";
	//echo $sumber;
	$konten = file_get_contents($sumber, false, stream_context_create($arrContextOptions));
	$data = json_decode($konten, true);
	
		foreach ($data["data"]["data"] as $key=>$r) {
			$tgl1 = "";
			$tgl2 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			//if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			if(( $r['end_date'] != null ) and ($r['end_date'] != "0000-00-00")) $tgl2 = tgl1($r['end_date']);
			
			$masaberlaku = $r["tgl_permohonan_perusahaan"];			
			if($masaberlaku) $masaberlaku = tgl1($masaberlaku);
			
			$lokasi = TRIM($r["lokasi_izin"]);
			
			$tlp = "";
			$hp = "";		
			if(($r['no_tlp'] != "") and ($r['no_tlp'] != "-") and ($r['no_tlp'] != "0"))$tlp = $r['no_tlp'];
			if(($r['no_hp'] != "") and ($r['no_hp'] != "-") and ($r['no_hp'] != "0"))$hp = $r['no_hp'];
			$contact = $tlp;
			if($hp != "") $contact = $hp;
			if(($tlp != "") and ($hp != "")) $contact = $tlp." / ".$hp;
			if($tlp == $hp) $contact = $tlp;
			
			$usaha = "";
			$usaha = $r['nama_perusahaan'];
			//if($usaha == "") $usaha = $r['irt'];
			
			$tampil = 1;		
			if ($search3){ 
				if($search3 == $r['jenis_izin_id']) $tampil = 1;
				else $tampil = 0;
			}else{
				if(in_array($r['jenis_izin_id'],$jenis_izin)) $tampil = 1;
				else $tampil = 0;
			}
			
			if($tampil){	
				
				$tampil2 = 1;
				if ($search4 != ""){
					if(preg_match("/{$search4}/i", $r['nama']))$tampil2 = 1;
					else $tampil2 = 0;
				}
								
				if($tampil2){	
					$value = array ($i,$r['no_permohonan'],$r['nama'],$usaha,$r['jenis_izin'],$r['jenis_permohonan'],$contact,$r['jenis_reklame'],$r['isi_reklame'],$r['ukuran'],trim($r['lokasi_pasang']),$r['no_izin'],$tgl2,$masaberlaku,$r['titik_koordinat']);
					$col = "A";
					for($y=0;$y< count($header);$y++){
						$cols = $col;
						$sheet->setCellValue($col.$j,$value[$y]);
						$col++;
					}			
					$i++;	
					$j++;
				}
			}
		}
		

	$writer = new Xlsx($spreadsheet);
	$writer->save('Rekap Izin Reklame.xlsx');
	echo "<script>window.location = 'Rekap Izin Reklame.xlsx'</script>";
?>
