<?php
error_reporting(E_ALL);
require_once "../../includes/koneksi.php";
require_once "../../includes/tanggal.php";

$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
$search4 = $_GET["search4"];
$opd_id = $_GET['search5'];
if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
//$tgl_now = tgl2(date("Y-m-d"));

$jns_izin = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE id='$search3'"));

if (($search3 == "") and ($opd_id != 1) and ($opd_id != 0)){ 
	$query_jns=mysql_query("SELECT*FROM jenis_izin WHERE opd_id ='$opd_id' ORDER BY jenis_izin asc");
	$x = 0;
	while($r_jns=mysql_fetch_array($query_jns)){
		$jenis_izin[$x] = $r_jns["id"];
		$x++;
	}
}

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

$header = array("No.","Nama Pemohon","Nama Perusahaan","Jenis Izin","Jenis Permohonan","Telp./HP. Pemohon","Lokasi","Nomor Izin","Tanggal Penetapan","Akhir Masa Berlaku");

$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$objPHPExcel->getActiveSheet()->setCellValue($col.'5',$header[$y]);
$col++;
}

$r_opd = mysql_fetch_array(mysql_query("SELECT*FROM opd WHERE id = '$opd_id'"));

$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$cols.'1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$cols.'2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:'.$cols.'3');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setTitle('DATA PERIZINAN');
if($search3)$objPHPExcel->getActiveSheet()->setCellValue('A1', STRTOUPPER($jns_izin['jenis_izin']));
else $objPHPExcel->getActiveSheet()->setCellValue('A1', 'DAFTAR PENERBITAN PERIZINAN');
$objPHPExcel->getActiveSheet()->setCellValue('A2', STRTOUPPER($r_opd['opd']));
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Dari Tanggal : ".tgl2($search1)." s/d ".tgl2($search2));

$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$i = 1;
	$j = 6;	
	
	$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
	);  
	
	//$sumber = "https://ws.sicantik.go.id/api/TemplateData/keluaran/27646.json";
	$sumber = "https://ws.sicantik.go.id/api/TemplateData/keluaran/36014.json";
	$sumber .= "?key1='$search1'&key2='$search2'";
	//echo $sumber;
	$konten = file_get_contents($sumber, false, stream_context_create($arrContextOptions));
	$data = json_decode($konten, true);
	
		foreach ($data["data"]["data"] as $key=>$r) {
			$tgl1 = "";
			$tgl2 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			//if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			if(( $r['tgl_signed_report'] != null ) and ($r['tgl_signed_report'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_signed_report']);
			
			$masaberlaku = $r["masa_berlaku"];
			if($masaberlaku == null) $masaberlaku = $r["tgl_akhir_str"];
			if($masaberlaku == null) $masaberlaku = $r["tgl_surat_pengantar"];
			if($masaberlaku == null) $masaberlaku = $r["tgl_permohonan_perusahaan"];
			if($masaberlaku == null) $masaberlaku = $r["tanggal_jatuh_tempo"];
			$masaberlaku = tgl1($masaberlaku);
			
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
			if (($search3 == "") and ($opd_id != 1) and ($opd_id != 0)){ 
				if(in_array($r['jenis_izin_id'],$jenis_izin)) $tampil = 1;
				else $tampil = 0;
			}
			
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
					$value = array ($i,$r['nama'],$usaha,$r['jenis_izin'],$r['jenis_permohonan'],$contact,trim($r['lokasi_izin']),$r['no_izin'],$tgl2,$masaberlaku);
					$col = "A";
					for($y=0;$y< count($header);$y++){
						$cols = $col;
						$objPHPExcel->getActiveSheet()->setCellValue($col.$j,$value[$y]);
						$col++;
					}			
					$i++;	
					$j++;
				}
			}
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
header('Content-Disposition: attachment;filename="Rekap Penerbitan Izin.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
