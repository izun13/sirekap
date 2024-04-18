<?php
require_once "../../includes/tanggal.php";
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();
$libur_nasional=mysql_fetch_array(mysql_query("SELECT tgl FROM libur_nasional"));
koneksi1_tutup();
koneksi2_buka();

$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
$search4 = $_GET["search4"];
$search5 = $_GET["search5"];
$opd_id = $_GET['opd'];
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

$header = array("No.","Nama Pemohon","Jenis Izin","Jenis Permohonan","Telepon Pemohon","Alamat","Nomor Izin","Tanggal Terima Berkas","Tanggal Penetapan","Lama Proses","Tgl Rekomendasi");
if($search3 == 33){
	$header = array("No.","Nama Pemohon","Jenis Izin","Jenis Permohonan","Telepon Pemohon","Alamat","Nomor Izin","Tanggal Terima Berkas","Tanggal Penetapan","Akhir Masa Berlaku","Luas Tanah","Peruntukan","Tgl Rekomendasi");
}
$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$objPHPExcel->getActiveSheet()->setCellValue($col.'5',$header[$y]);
$col++;
}

if($opd_id) $r_opd = mysql_fetch_array(mysql_query("SELECT*FROM opd WHERE id = '$opd_id'"));
if($search3) $jnsizin = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE jenisizin_id = '$search3'"));

$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setSize(11);
$objPHPExcel->getActiveSheet()->getStyle('A1:A2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$cols.'1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$cols.'2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:'.$cols.'3');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setTitle('DATA PERMOHONAN');
if($search3)$objPHPExcel->getActiveSheet()->setCellValue('A1', 'DAFTAR '.STRTOUPPER($jnsizin['jenisizin_name']));
else $objPHPExcel->getActiveSheet()->setCellValue('A1', 'DAFTAR PERMOHONAN PERIZINAN');
if($opd_id)$objPHPExcel->getActiveSheet()->setCellValue('A2', STRTOUPPER($r_opd['opd']));
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Dari Tanggal : ".tgl2($search1)." s/d ".tgl2($search2));

$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$i = 1;
	$j = 6;	
	
	if (($opd_id == 1)or($opd_id == 0)){ 
		$tabel = "SELECT*FROM view_permohonan_izin WHERE permohonan_id IS NOT NULL";	
	}else{
		$tabel = "SELECT*FROM view_permohonan_izin WHERE permohonan_id IS NOT NULL and jenisizin_opd_id ='$opd_id'";
	}
		
	if(($search1 != "") and ($search2 != "")) $tabel .= " and permohonan_tgl_izin >= '$search1' and permohonan_tgl_izin <= '$search2'";
	if($search3 != "") $tabel .= " and jenisizin_id = '$search3'";
	if($search4 == 1) $tabel .= " and permohonan_nomor_surat != ''";
	if($search4 == 2) $tabel .= " and permohonan_nomor_surat = ''";
	
	$tabel .= " ORDER BY permohonan_id asc";
		
	$query = mysql_query($tabel);
		while ($r= mysql_fetch_array($query)){
			$hari_kerja= 0;
			$tgl_awal = null;
			$tgl_akhir = null;
			
			//tgl terima berkas
			$tglawal = mysql_fetch_array(mysql_query("SELECT permproc_date FROM permohonan_process WHERE permproc_permohonan_id = '$r[permohonan_id]' AND permproc_statusproses_id = '51' "));
			//tgl penetapan izin
			//$tglakhir = mysql_fetch_array(mysql_query("SELECT permproc_date FROM permohonan_process WHERE permproc_permohonan_id = '$r[permohonan_id]' AND permproc_statusproses_id = '7' "));
					
			$tgl_awal = $tglawal['permproc_date'];
			//$tgl_akhir = $tglakhir['permproc_date'];
			$tgl_akhir = $r['permohonan_tgl_izin'];
			
			if($tgl_awal != null)$tgl1 = tgl1($tgl_awal);
			if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
			//jumlah hari kerja
			if(($tgl_awal != null) and ($tgl_akhir != null)){
				$awal=strtotime($tgl_awal);
				$akhir=strtotime($tgl_akhir);
				
				for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
					$i_date=date("Y-m-d",$x);
					if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
						$hari_kerja++;
					}
				}
			}
			
			$tampil = 0;
			if(($search5 == 5) and ($hari_kerja <= $search5) and ($hari_kerja != 0)) $tampil = 1;
			if(($search5 == 6) and ($hari_kerja >= $search5)) $tampil = 1;
			if(($search5 == 0) or ($search5 == null)) $tampil = 1;
			
			if($tampil == 1){
			
				$value = array ($i,$r['pemohon_nama'],$r['jenisizin_name'],$r['statusizin_name'],$r['pemohon_telepon'],trim($r['pemohon_alamat']),$r['permohonan_nomor_surat'],$tgl1,tgl1($r['permohonan_tgl_izin']),
								$hari_kerja,tgl1($r['permohonan_tgl_syarat_lengkap']));
				
				if($search3 == 33){
					$luas_tanah = mysql_fetch_array(mysql_query("SELECT*FROM surat_izin_data WHERE suratizindata_permohonan_id='$r[permohonan_id]' AND suratizindata_suratizin_id='11655'"));
					$luas = "'".$luas_tanah['suratizindata_value'];
					$peruntukan = mysql_fetch_array(mysql_query("SELECT*FROM surat_izin_data WHERE suratizindata_permohonan_id='$r[permohonan_id]' AND suratizindata_suratizin_id='11656'"));
					$value = array ($i,$r['pemohon_nama'],$r['jenisizin_name'],$r['statusizin_name'],$r['pemohon_telepon'],trim($r['pemohon_alamat']),
									$r['permohonan_nomor_surat'],$tgl1,tgl1($r['permohonan_tgl_izin']),tgl1($r['permohonan_tgl_berakhir_izin']),$luas,$peruntukan['suratizindata_value'],tgl1($r['permohonan_tgl_syarat_lengkap']));
				}
					
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
