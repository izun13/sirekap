<?php
include "../../includes/parser-php-version.php";
require_once "../../includes/tanggal.php";
require_once "../../includes/rupiah.php";
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();	
		
error_reporting(E_ALL);
		
$date = date("Y-m-d");
$search = explode(";",$_GET["send"]);
$search0 = $search[0]; 
$search1 = $search[1]; 
$search2 = $search[2]; 
$search3 = $search[3]; 
$search4 = $search[4];

$tanggal1 = date('Y-m-d',strtotime($search1));
$tanggal2 = date('Y-m-d',strtotime('2022-04-30'));

if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
$tgl_now = tgl2(date("Y-m-d"));

$jns_izin = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE id='$search3'"));

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
$objDrawing->setWorksheet($sheet);*/

$header = array("No.","Nomor Permohonan","Nama Pemohon","Jenis Izin","Nama Usaha","Telp./HP. Pemohon","Lokasi","Nomor Izin","Tanggal Penetapan","Akhir Masa Berlaku");

$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$sheet->setCellValue($col.'5',$header[$y]);
$col++;
}


/*$sheet->getStyle('A1:A3')->getFont()->setName('Arial');
$sheet->getStyle('A1:A3')->getFont()->setSize(12);
$sheet->getStyle('A1:A3')->getFont()->setBold(true);

$sheet->mergeCells('A1:'.$cols.'1');
$sheet->mergeCells('A2:'.$cols.'2');
$sheet->mergeCells('A3:'.$cols.'3');
$sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/

$sheet->setTitle('DATA PERMOHONAN');
$sheet->setCellValue('A1', 'REKAPITULASI PERMOHONAN PERIZINAN');
if($search3)$sheet->setCellValue('A2', STRTOUPPER($jns_izin['jenis_izin']));
else $sheet->setCellValue('A2', 'DAFTAR PERMOHONAN PERIZINAN');
$sheet->setCellValue('A3', 'BULAN : '.STRTOUPPER(bulantahun($search2)));

/*$sheet->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$sheet->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/

	$i = 1;
	$j = 6;	
	
	$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
	);  
		
	//sebelum TTE bulan mei
	//if($tanggal1 <= $tanggal2)$sumber = "https://sicantik.go.id/api/TemplateData/keluaran/27646.json";		
	$sumber = "https://sicantik.go.id/api/TemplateData/keluaran/27646.json";
	//setelah TTE
	//else $sumber = "https://sicantik.go.id/api/TemplateData/keluaran/36014.json";
	
	$sumber .= "?key1='$search1'&key2='$search2'";
	//echo $sumber;
	$konten = file_get_contents($sumber, false, stream_context_create($arrContextOptions));
	$data = json_decode($konten, true);
	
		foreach ($data["data"]["data"] as $key=>$r) {
			$tgl1 = "";
			$tgl2 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			if(( $r['end_date'] != null ) and ($r['end_date'] != "0000-00-00")) $tgl2 = tgl1($r['end_date']);
			
			//$masaberlaku = $r["tgl_akhir_izin"];
			$masaberlaku = $r["masa_berlaku"];
			//if($masaberlaku == null) $masaberlaku = $r["tgl_akhir_str"];
			//if($masaberlaku == null) $masaberlaku = $r["tgl_surat_pengantar"];
			//if($masaberlaku == null) $masaberlaku = $r["tgl_permohonan_perusahaan"];
			//if($masaberlaku == null) $masaberlaku = $r["tanggal_jatuh_tempo"];
			//$masaberlaku = tgl1($masaberlaku);
			
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
			if(($search3 != "") and ($search3 == $r['jenis_izin_id'])) $tampil = 1;
			if(($search3 != "") and ($search3 != $r['jenis_izin_id'])) $tampil = 0;
			
			if($tampil){	
				
				$tampil2 = 1;
				if ($search4 == 1){
					if($r['no_izin'] != null) $tampil2 = 1;
					else $tampil2 = 0;
				}
				if ($search4 == 2){
					if($r['no_izin'] == null) $tampil2 = 1;
					else $tampil2 = 0;
				}
				
				if($tampil2){
					$sumber2 = "https://sicantik.go.id/api/TemplateData/keluaran/39413.json?key=$r[id]";
					//echo $sumber;
					$konten2 = file_get_contents($sumber2, false, stream_context_create($arrContextOptions));
					$data2 = json_decode($konten2, true);
					if($masaberlaku == null)$masaberlaku = $data2["data"]["data"][0]["tgl_akhir_izin"];
					$masaberlaku = tgl1($masaberlaku);
					
					$value = array ($i,$r['no_permohonan'],$r['nama'],$r['jenis_izin'],$usaha,$contact,trim($r['lokasi_izin']),$r['no_izin'],$tgl2,$masaberlaku);
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
		
/*$sheet->getStyle('A6:'.$cols.$j)->getFont()->setName('Arial');
$sheet->getStyle('A6:'.$cols.$j)->getFont()->setSize(10);
//$sheet->getStyle('A5:'.$j)->getFont()->setBold(true);
$sheet->getStyle('A6:A'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/

$writer = new Xlsx($spreadsheet);
$writer->save('Rekap Penerbitan Izin.xlsx');
echo "<script>window.location = 'Rekap Penerbitan Izin.xlsx'</script>";
?>
