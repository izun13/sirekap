<?php
include "../../includes/parser-php-version.php";
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

/** PhpSpreadsheet */
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


$sheet->setTitle('DATA PERMOHONAN');
$sheet->setCellValue('A1', 'REKAPITULASI PERMOHONAN PERIZINAN REKLAME');
$sheet->setCellValue('A2', 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP) KOTA MAGELANG');
$sheet->setCellValue('A3', "Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2));

/*$sheet->getStyle('A1:A3')->getFont()->setName('Arial Narrow');
$sheet->getStyle('A1:A3')->getFont()->setSize(14);
$sheet->getStyle('A1:A3')->getFont()->setBold(true);*/

//set table header
$header = array('No.','Nomor Permohonan','Jenis Izin','Jenis Permohonan','Nama Pemohon','No. Identitas','Telp./HP. Pemohon','Tanggal Penetapan',
'Nomor Izin','Jenis Reklame','Isi Reklame','Ukuran Reklame','Lokasi Reklame','Titik Koordinat');//'Nama Perusahaan',
$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$sheet->setCellValue($col.'5',$header[$y]);
$col++;
}

/*$sheet->mergeCells('A1:'.$cols.'1');
$sheet->mergeCells('A2:'.$cols.'2');
$sheet->mergeCells('A3:'.$cols.'3');
$sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial Narrow');
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setSize(11);
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$sheet->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/

	$i = 1;
	$j = 6;
	$jumlah = 0;
	$jml_data = 0;
	
	$tabel = "SELECT*FROM view_permohonan_izin WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND tgl_pengajuan >= '$search1' AND tgl_pengajuan <= '$search2'";
	if($search0 == "Pengesahan") $tabel = "SELECT*FROM view_permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";
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
			$query_teknis = mysql_query("SELECT*FROM data_teknis_reklame WHERE permohonan_izin_id = '$r[id]'");
			while ($r_teknis= mysql_fetch_array($query_teknis)){
				$value = array ($i,$r['no_permohonan'],$r['jenis_izin'],$r['jenis_permohonan'],$r['nama'],$r['tipe_identitas']."-".$r['no_identitas'],$contact,$tgl2,
				$r['no_izin'],$r_teknis['jenis_reklame'],$r_teknis['isi_reklame'],$r_teknis['ukuran'],$r_teknis['lokasi_pasang'],$r_teknis['titik_koordinat']);//$r['nama_perusahaan'],
			}
			
			$col = "A";
			for($y=0;$y< count($header);$y++){
				$cols = $col;
				$sheet->setCellValue($col.$j,$value[$y]);
				$col++;
			}			
			
			//$jumlah += $hari_kerja;
			//$jml_data = $i;
			$i++;	
			$j++;
		}
			//$rata = number_format($jumlah/$jml_data);
			//$sheet->setCellValue('B'.$j,"Rata-rata Lama Proses"); 
			//$sheet->setCellValue('M'.$j,$rata);
			
/*$sheet->getStyle('A6:'.$cols.$j)->getFont()->setName('Arial Narrow');
$sheet->getStyle('A6:'.$cols.$j)->getFont()->setSize(11);
//$sheet->getStyle('A5:I'.$j)->getFont()->setBold(true);
$sheet->getStyle('A6:A'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;*/
$writer = new Xlsx($spreadsheet);
$writer->save('Permohonan Izin Reklame.xlsx');
echo "<script>window.location = 'Permohonan Izin Reklame.xlsx'</script>";
?>
