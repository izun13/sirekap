<?php
require_once "../../includes/koneksi.php";
require_once "../../includes/tanggal.php";

$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
$search4 = $_GET["search4"];
$opd_id = $_GET['search5'];
if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
//$tgl_now = tgl2(date("Y-m-d"));

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

$objPHPExcel->getActiveSheet()->setTitle('DATA PERMOHONAN');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'DAFTAR PERMOHONAN PERIZINAN');
$objPHPExcel->getActiveSheet()->setCellValue('A2', STRTOUPPER($r_opd['opd']));
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Dari Tanggal : ".tgl2($search1)." s/d ".tgl2($search2));

$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$i = 1;
	$j = 6;	
	
	if (($opd_id == 1)or($opd_id == 0)){ 
		$tabel = "SELECT*FROM view_permohonan_izin WHERE no_permohonan NOT LIKE '%EXP%' ";
	}else{
		$tabel = "SELECT*FROM view_permohonan_izin WHERE no_permohonan NOT LIKE '%EXP%' and opd_id ='$opd_id' ";
	}
	
	if(($search1 != "") and ($search2 != "")) $tabel .= "and tgl_penetapan >= '$search1' and tgl_penetapan <= '$search2' ";
	if($search3 != "") $tabel .= "and jenis_izin = '$search3' ";
	if($search4 == 1) $tabel .= "and no_izin != '' ";
	if($search4 == 2) $tabel .= "and no_izin = '' ";
	
	$tabel .= "ORDER BY id asc";
		
	$query = mysql_query($tabel);
		while ($r= mysql_fetch_array($query)){
			$hari_kerja= 0;
			$tgl1 = "";
			$tgl2 = "";
			$tgl3 = "";
			$tgl4 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			
			$r_tgl = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
			if($r_tgl['tgl_penyerahan']) $tgl3 = tgl1($r_tgl['tgl_penyerahan']);
					
			/*$tglawal = mysql_fetch_array(mysql_query("SELECT tgl_diubah FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '2' "));
			$tglakhir = mysql_fetch_array(mysql_query("SELECT tgl_diubah FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '8' "));
			
			$tgl_awal = $tglawal['tgl_diubah'];
			//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
			$tgl_akhir = $tglakhir['tgl_diubah'];
			//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");
			
			if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
			if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
			if(($tgl_awal != null) and ($tgl_akhir != null)){
				$awal=strtotime($tgl_awal);
				$akhir=strtotime($tgl_akhir);
				$libur_nasional=mysql_fetch_array(mysql_query("SELECT tgl FROM libur_nasional"));
				
				for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
					$i_date=date("Y-m-d",$x);
					if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
						$hari_kerja++;
					}
				}
			}*/
			
			$masaberlaku = $r["masa_berlaku"];
			if($masaberlaku == "0000-00-00"){
				$r_kes = mysql_fetch_array(mysql_query("SELECT tgl_akhir_str,tgl_surat_pengantar FROM data_teknis_kesehatan WHERE permohonan_izin_id = '$r[id]'"));
				$masaberlaku = $r_kes["tgl_akhir_str"];
				if($masaberlaku == "") $masaberlaku = $r_kes["tgl_surat_pengantar"];
			}
			$masaberlaku = tgl1($masaberlaku);
			
			$tlp = null;
			$hp = null;		
			if(($r['no_tlp'] != "") and ($r['no_tlp'] != "-"))$tlp = $r['no_tlp'];
			if(($r['no_hp'] != "") and ($r['no_hp'] != "-"))$hp = $r['no_hp'];
			$contact = "";
			if($tlp != null) $contact = $tlp;
			if($hp != null) $contact = $hp;
			if(($tlp != null) and ($hp != null)) $contact = $tlp." / ".$hp;
			if(($tlp != null) and ($hp != null) and ($tlp == $hp)) $contact = $tlp;
			$contact = "'".$contact;
			
			$usaha = "";
			$usaha = $r['nama_perusahaan'];
			//if($usaha == "") $usaha = $r['irt'];
			
			/*$r_nama = explode(",",$r['nama']);
			for ($j=0; $j<count($r_nama); $j++) {
				if($j==0)$nama = strtoupper($r_nama[$j]).",";
				else $nama .= $r_nama[$j].",";
			}
			$nama = substr($nama, 0, -1);*/
						
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
header('Content-Disposition: attachment;filename="Rekap Permohonan Izin.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>
