<?php
require_once "../../includes/koneksi.php";
require_once "../../includes/tanggal.php";

$date = date("Y-m-d");
$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
$search4 = $_GET["search4"];
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
$objPHPExcel->getActiveSheet()->setCellValue('A5','No.');     
$objPHPExcel->getActiveSheet()->setCellValue('B5','Tanggal Pengajuan');      
$objPHPExcel->getActiveSheet()->setCellValue('C5','Nomor Permohonan');           
$objPHPExcel->getActiveSheet()->setCellValue('D5','Jenis Izin');         
$objPHPExcel->getActiveSheet()->setCellValue('E5','Jenis Permohonan');
$objPHPExcel->getActiveSheet()->setCellValue('F5','Nama Pemohon'); 
$objPHPExcel->getActiveSheet()->setCellValue('G5','No. Identitas'); 
$objPHPExcel->getActiveSheet()->setCellValue('H5','Telp./HP. Pemohon'); 
$objPHPExcel->getActiveSheet()->setCellValue('I5','Nama Perusahaan'); 
$objPHPExcel->getActiveSheet()->setCellValue('J5','Tanggal Terima Berkas'); 
$objPHPExcel->getActiveSheet()->setCellValue('K5','Nomor Izin'); 
$objPHPExcel->getActiveSheet()->setCellValue('L5','Tanggal Penetapan'); 
$objPHPExcel->getActiveSheet()->setCellValue('M5','Lama Proses');
$objPHPExcel->getActiveSheet()->setCellValue('N5','Tanggal Penyerahan');
$objPHPExcel->getActiveSheet()->setCellValue('O5','Lokasi Izin');

$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getFont()->setSize(10);
$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$i = 1;
	$j = 6;
	$jumlah = 0;
	$jml_data = 0;
	
	$tabel = "SELECT*FROM view_permohonan_izin WHERE no_permohonan NOT LIKE '%EXP%' ";	
	if(($search1 != "") and ($search2 != "")) $tabel .= "and tgl_penetapan >= '$search1' and tgl_penetapan <= '$search2' ";
	if($search3 != "") $tabel .= "and jenis_izin = '$search3' ";
	if($search4 == "Sudah") $tabel .= "and no_izin != '' ";
	if($search4 == "Belum") $tabel .= "and no_izin = '' ";
	
	$tabel .= "ORDER BY no_izin asc";
		
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
					
			$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '2' "));
			$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '8' "));
			
			$tgl_awal = $tglawal['end_date'];
			//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
			$tgl_akhir = $tglakhir['end_date'];
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
			}
			
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
						
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$j,$i);   
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,$tgl1);         
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$j,$r['no_permohonan']);           
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$j,$r['jenis_izin']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$j,$r['jenis_permohonan']); 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$j,$r['nama']); 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$j,$r['tipe_identitas']."-".$r['no_identitas']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$j,$contact); 
			//$objPHPExcel->getActiveSheet()->setCellValue('I'.$j,$r['nama_perusahaan']); 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$j,$tgl4); 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$j,$r['no_izin']); 
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$j,$tgl2); 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$j,$hari_kerja); 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$j,$tgl3); 
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$j,trim($r['lokasi_izin'])); 
			
			$jumlah += $hari_kerja;
			$jml_data = $i;
			$i++;	
			$j++;
		}
			$rata = number_format($jumlah/$jml_data);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$j,"Rata-rata Lama Proses"); 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$j,$rata);
			
$objPHPExcel->getActiveSheet()->getStyle('A6:O'.$j)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A6:O'.$j)->getFont()->setSize(10);
//$objPHPExcel->getActiveSheet()->getStyle('A5:I'.$j)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A6:O'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
