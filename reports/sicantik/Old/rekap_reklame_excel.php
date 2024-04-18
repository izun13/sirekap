<?php
require_once "../../includes/koneksi.php";
require_once "../../includes/tanggal.php";

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


$objPHPExcel->getActiveSheet()->setTitle('DATA PERMOHONAN');
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'REKAPITULASI PERMOHONAN PERIZINAN REKLAME');
$objPHPExcel->getActiveSheet()->setCellValue('A2', 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP) KOTA MAGELANG');
$objPHPExcel->getActiveSheet()->setCellValue('A3', "Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2));

$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setName('Arial Narrow');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getFont()->setBold(true);

//set table header
$header = array('No.','Nomor Permohonan','Jenis Izin','Jenis Permohonan','Nama Pemohon','No. Identitas','Telp./HP. Pemohon','Tanggal Penetapan',
'Nomor Izin','Jenis Reklame','Isi Reklame','Ukuran Reklame','Lokasi Reklame','Titik Koordinat');//'Nama Perusahaan',
$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$objPHPExcel->getActiveSheet()->setCellValue($col.'5',$header[$y]);
$col++;
}

$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$cols.'1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$cols.'2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:'.$cols.'3');
$objPHPExcel->getActiveSheet()->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial Narrow');
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setSize(11);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$i = 1;
	$j = 6;
	$jumlah = 0;
	$jml_data = 0;
	
	$tabel = "SELECT*FROM view_permohonan_izin_reklame WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
	if(($search0 == "Pengajuan") and($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_pengajuan >= '$search1' AND tgl_pengajuan <= '$search2'";
	if(($search0 == "Penetapan") and($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
	if($search3 != "") $tabel .= " AND jenis_izin = '$search3'";
	if($search4 == 1) $tabel .= " AND no_izin != ''";
	if($search4 == 2) $tabel .= " AND no_izin = '' ";
	if($search6 != "") $tabel .= " AND nama LIKE '%$search6%'";
	if($search7 != "") $tabel .= " AND jenis_izin LIKE '%$search7%'";
	
	$tabel .= " ORDER BY no_izin asc";
		
	$query = mysql_query($tabel);
		while ($r= mysql_fetch_array($query)){
			$hari_kerja= 0;
			$tgl_awal = null;
			$tgl_akhir = null;
			$tgl1 = "";
			$tgl2 = "";
			$tgl3 = "";
			$tgl4 = "";
			$tgl5 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			if($r['tgl_rekomendasi']) $tgl5 = tgl1($r['tgl_rekomendasi']);
			if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			//$r_tgl = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
			//if($r_tgl['tgl_penyerahan']) $tgl3 = tgl1($r_tgl['tgl_penyerahan']);
			
			//tgl cetak tanda terima berkas			
			//$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '33' "));
			//tgl penetapan izin
			//$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '8' "));
			
			//$tgl_awal = $tglawal['end_date'];
			//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
			//$tgl_akhir = $tglakhir['end_date'];
			//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");
			
			//if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
			//if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
			/*if(($tgl_awal != null) and ($tgl_akhir != null)){
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
						
			/*$r_nama = explode(",",$r['nama']);
			for ($j=0; $j<count($r_nama); $j++) {
				if($j==0)$nama = strtoupper($r_nama[$j]).",";
				else $nama .= $r_nama[$j].",";
			}
			$nama = substr($nama, 0, -1);*/
						
			$value = array ($i,$r['no_permohonan'],$r['jenis_izin'],$r['jenis_permohonan'],$r['nama'],$r['tipe_identitas']."-".$r['no_identitas'],$contact,$tgl2,
			$r['no_izin'],$r['jenis_reklame'],$r['isi_reklame'],$r['ukuran'],$r['lokasi_pasang'],$r['titik_koordinat']);//$r['nama_perusahaan'],
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
			//$rata = number_format($jumlah/$jml_data);
			//$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,"Rata-rata Lama Proses"); 
			//$objPHPExcel->getActiveSheet()->setCellValue('M'.$j,$rata);
			
$objPHPExcel->getActiveSheet()->getStyle('A6:'.$cols.$j)->getFont()->setName('Arial Narrow');
$objPHPExcel->getActiveSheet()->getStyle('A6:'.$cols.$j)->getFont()->setSize(11);
//$objPHPExcel->getActiveSheet()->getStyle('A5:I'.$j)->getFont()->setBold(true);
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
