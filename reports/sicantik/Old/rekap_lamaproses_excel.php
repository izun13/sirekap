<?php
include"../../includes/parser-php-version.php";
require_once "../../includes/tanggal.php";
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();

$date = date("Y-m-d");
$search0 = $_GET["search0"];
$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
$search4 = $_GET["search4"];
$search5 = $_GET["search5"];
$search6 = $_GET["search6"];
$search7 = $_GET["search7"];
if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
$tgl_now = tgl2(date("Y-m-d"));

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

$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setSize(12);
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->mergeCells('A1:O1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:O2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:O3');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->setTitle('DATA PERMOHONAN');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'REKAPITULASI PERMOHONAN PERIZINAN');
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP) KOTA MAGELANG');
$objPHPExcel->getActiveSheet()->setCellValue('A3', 'Tanggal Laporan : '.$tgl_now);

//set table header
$header = array("No.","Tanggal Pengajuan","Nomor Permohonan","Jenis Izin","Jenis Permohonan","Nama Pemohon","No. Identitas","Telp./HP. Pemohon","Email","Lokasi","Nomor Izin","Tanggal Terima Berkas","Tanggal Pengesahan","Lama Proses");

$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$objPHPExcel->getActiveSheet()->setCellValue($col.'5',$header[$y]);
$col++;
}

$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	// libur nasional
	$z=0;
	$query_libur = mysql_query("SELECT tgl FROM libur_nasional");
	while ($r_libur= mysql_fetch_array($query_libur)){
		$libur_nasional[$z] = $r_libur['tgl'];
		$z++;
	}
	
	$i = 1;
	$j = 6;
	$jumlah = 0;
	$jml_data = 0;
	
	//$tabel = "SELECT*FROM view_permohonan_izin WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
	//if(($search0 == "Pengajuan") and($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_pengajuan >= '$search1' AND tgl_pengajuan <= '$search2'";
	//if(($search0 == "Penetapan") and($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
	//if(($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
	$tabel = "SELECT*FROM view_permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
	if(($search1 != "") and ($search2 != "")) $tabel .= " AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";
	if($search3 != "") $tabel .= " AND jenis_izin = '$search3'";
	if($search6 != "") $tabel .= " AND nama LIKE '%$search6%'";
	if($search7 != "") $tabel .= " AND jenis_izin LIKE '%$search7%'";
	
	$tabel .= " ORDER BY no_permohonan ASC";
	
	$query = mysql_query($tabel);
		while ($r= mysql_fetch_array($query)){
			$id = $r["id"];
			
			$hari_kerja= 0;
			$tgl_awal = null;
			$tgl_akhir = null;			
			$tgl1 = "";
			$tgl2 = "";
			$tgl3 = "";
			$tgl4 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			//if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			$r_tgl = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
			if($r_tgl['tgl_penyerahan']) $tgl3 = tgl1($r_tgl['tgl_penyerahan']);
			$lokasi = TRIM($r["lokasi_izin"]);
			
			$tgl_akhir = $r['end_date'];
			//tgl cetak tanda terima berkas
			$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '33' "));
			$tgl_awal = $tglawal['end_date'];
			
			//tgl ttd izin
			//$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '40' "));
			//tgl penetapan
			//if(empty($tglakhir))$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '8' "));
						
			//tgl rekomendasi kesehatan
			$tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '7' "));
			//tgl rekomendasi diperindag
			if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '176' "));
			//tgl rekomendasi bpkad
			if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '108' "));
			
			if(!empty($tgl_rekomendasi))$tgl_akhir = $tgl_rekomendasi['start_date'];
			
			if($search4 == 0)$tgl_akhir = $r['end_date'];
						
			if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
			if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
			//jumlah hari kerja
			if(($tgl_awal != null) and ($tgl_akhir != null)){
				$tgl_awal = date('Y-m-d', strtotime($tgl_awal));
				$tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
				$awal=strtotime($tgl_awal);
				$akhir=strtotime($tgl_akhir);
				
				for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
					$i_date=date("Y-m-d",$x);
					if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
						$hari_kerja++;
					}
				}
			}
			
			if((!empty($tgl_rekomendasi)) and ($search4 == 1)){
				//tgl Cetak Rekomendasi dkk dan disperindag
				$tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '35' "));
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '7' "));
				//tgl Verifikasi status bayar bpkad
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '226' "));
				
				if(!empty($tgl_cetakrekomendasi))$tgl_awal = $tgl_cetakrekomendasi['end_date'];
				if( date('Y-m-d', strtotime($tgl_awal)) ==  $tgl_akhir) $tgl_awal = date('Y-m-d', strtotime('+1 days', strtotime($tgl_awal))); 
				
				$tgl_akhir = $r['end_date'];
				if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
				
				//jumlah hari kerja
				if(($tgl_awal != null) and ($tgl_akhir != null)){
					$tgl_awal = date('Y-m-d', strtotime($tgl_awal));
					$tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
					$awal=strtotime($tgl_awal);
					$akhir=strtotime($tgl_akhir);
					
					for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
						$i_date=date("Y-m-d",$x);
						if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
							$hari_kerja++;
						}
					}
				}
			}
						
			$tlp = "";
			$hp = "";		
			if(($r['no_tlp'] != "") and ($r['no_tlp'] != "-") and ($r['no_tlp'] != "0"))$tlp = $r['no_tlp'];
			if(($r['no_hp'] != "") and ($r['no_hp'] != "-") and ($r['no_hp'] != "0"))$hp = $r['no_hp'];
			$contact = $tlp;
			if($hp != "") $contact = "'".$hp;
			if(($tlp != "") and ($hp != "")) $contact = "'".$tlp." / ".$hp;
			if($tlp == $hp) $contact = "'".$tlp;
			
			/*$r_nama = explode(",",$r['nama']);
			for ($j=0; $j<count($r_nama); $j++) {
				if($j==0)$nama = strtoupper($r_nama[$j]).",";
				else $nama .= $r_nama[$j].",";
			}
			$nama = substr($nama, 0, -1);*/
			
			//$tampil = 0;
			//if(($search4 == 0) or ($search4 == "")) $tampil = 1;
			//if(($search4 == 1) and ($tgl_akhir != null) and ($tgl_akhir <= $search2)) $tampil = 1;
			//if(($search4 == 2) and ($tgl_akhir > $search2)) $tampil = 1;
			//if(($search4 == 2) and ($tgl_akhir == null)) $tampil = 1;
							
			//if($tampil == 1){
				
				$tampil2 = 1;
				if(($search5 == 5) and ($hari_kerja > $search5)) $tampil2 = 0;
				if(($search5 == 6) and ($hari_kerja < $search5)) $tampil2 = 0;
				
				if($tampil2 == 1){
						
					/*$r_nama = explode(",",$r['nama']);
					for ($j=0; $j<count($r_nama); $j++) {
						if($j==0)$nama = strtoupper($r_nama[$j]).",";
						else $nama .= $r_nama[$j].",";
					}
					$nama = substr($nama, 0, -1);*/
							
					$identitas = $r['tipe_identitas']."-".$r['no_identitas'];
					$value = array ($i,$tgl1,$r['no_permohonan'],$r['jenis_izin'],$r['jenis_permohonan'],$r['nama'],$identitas,$contact,$r['email'],trim($r['lokasi_izin']),$r['no_izin'],$tgl4,$tgl2,$hari_kerja);
					$col = "A";
					for($y=0;$y< count($header);$y++){
						$cols = $col;
						$objPHPExcel->getActiveSheet()->setCellValue($col.$j,$value[$y]);
						$col++;
					}						
					$jumlah += $hari_kerja;
					$jml_data = $i;
					$i++;	
					$j++;
				}
			//}
		}
			$rata = number_format($jumlah/$jml_data);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,"Rata-rata Lama Proses"); 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$j,$rata);
			
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
