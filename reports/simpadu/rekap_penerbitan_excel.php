<?php
require_once "../../includes/parser-php-version.php";
require_once "../../includes/tanggal.php";
require_once "../../includes/konfigurasi_db.php";
koneksi2_buka();
		
error_reporting(E_ALL);

$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
$search4 = $_GET["search4"];
$search5 = $_GET["search5"];
$search6 = $_GET["search6"];
$opd_id = $_GET['opd_id'];
if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
//$tgl_now = tgl2(date("Y-m-d"));

/** PhpSpreadsheet */
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// sheet peratama
$sheet->setTitle('Sheet 1');

$header = array("No.","Nama Pemohon","Badan Usaha","Jenis Izin","Jenis Permohonan","Telepon Pemohon","Alamat","Nomor Izin","Tanggal Penetapan","Akhir Masa Berlaku");
//if($search3 == 33){
	//$header = array("No.","Nama Pemohon","Badan Usaha","Jenis Izin","Jenis Permohonan","Telepon Pemohon","Alamat","Nomor Izin","Tanggal Penetapan","Akhir Masa Berlaku","Luas Tanah","Peruntukan");
//}

$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$sheet->setCellValue($col.'5',$header[$y]);
$col++;
}

if($opd_id) $r_opd = mysql_fetch_array(mysql_query("SELECT*FROM opd WHERE id = '$opd_id'"));
if($search3) $jnsizin = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE jenisizin_id = '$search3'"));

// sheet peratama
$sheet->setTitle('Sheet 1');

$sheet->setCellValue('A1', "");
$sheet->setCellValue('A2', "DAFTAR PERMOHONAN PERIZINAN SIMPADU");
$sheet->setCellValue('A3', "Dari Tanggal : ".tgl2($search1)." s/d ".tgl2($search2));

/*$sheet->getStyle('A1:A3')->getFont()->setName('Arial');
$sheet->getStyle('A1:A3')->getFont()->setSize(11);
$sheet->getStyle('A1:A2')->getFont()->setBold(true);

$sheet->mergeCells('A1:'.$cols.'1');
$sheet->mergeCells('A2:'.$cols.'2');
$sheet->mergeCells('A3:'.$cols.'3');
$sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$sheet->setTitle('DATA PERMOHONAN');
if($search3)$sheet->setCellValue('A1', 'DAFTAR '.STRTOUPPER($jnsizin['jenisizin_name']));
else $sheet->setCellValue('A1', 'DAFTAR PERMOHONAN PERIZINAN');
if($opd_id)$sheet->setCellValue('A2', STRTOUPPER($r_opd['opd']));
$sheet->setCellValue('A3', "Dari Tanggal : ".tgl2($search1)." s/d ".tgl2($search2));

$sheet->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$sheet->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
*/
	$i = 1;
	$j = 6;	
	
	if (($opd_id == 1)or($opd_id == 0)){ 
		$tabel = "SELECT*FROM view_permohonan_izin WHERE permohonan_id IS NOT NULL";	
	}else{
		$tabel = "SELECT*FROM view_permohonan_izin WHERE permohonan_id IS NOT NULL and jenisizin_opd_id ='$opd_id'";
	}
		
	if(($search1 != "") and ($search2 != "")) $tabel .= " and permohonan_tgl_izin >= '$search1' and permohonan_tgl_izin <= '$search2'";
	if($search3 != "") $tabel .= " and jenisizin_id = '$search3'";
	//if($search4 == 1) $tabel .= " and permohonan_nomor_surat != ''";
	//if($search4 == 2) $tabel .= " and permohonan_nomor_surat = ''";
	if($search5 != "") $tabel .= " AND pemohon_nama LIKE '%$search5%'";
	if($search6 != "") $tabel .= " AND jenisizin_name LIKE '%$search6%'";
	
	$tabel .= " ORDER BY permohonan_id asc";
		
	$query = mysql_query($tabel);
		while ($r= mysql_fetch_array($query)){
			$telp = "'".$r['pemohon_telepon'];
			$value = array ($i,$r['pemohon_nama'],$r['permohonan_badan_usaha'],$r['jenisizin_name'],$r['statusizin_name'],$telp,trim($r['pemohon_alamat']),$r['permohonan_nomor_surat'],
							tgl1($r['permohonan_tgl_izin']),tgl1($r['permohonan_tgl_berakhir_izin']));
						
			/*if($search3 == 33){
				$luas_tanah = mysql_fetch_array(mysql_query("SELECT*FROM surat_izin_data WHERE suratizindata_permohonan_id='$r[permohonan_id]' AND suratizindata_suratizin_id='11655'"));
				$luas = "'".$luas_tanah['suratizindata_value'];
				$peruntukan = mysql_fetch_array(mysql_query("SELECT*FROM surat_izin_data WHERE suratizindata_permohonan_id='$r[permohonan_id]' AND suratizindata_suratizin_id='11656'"));
				$value = array ($i,$r['pemohon_nama'],$r['permohonan_badan_usaha'],$r['jenisizin_name'],$r['statusizin_name'],$r['pemohon_telepon'],trim($r['pemohon_alamat']),
								$r['permohonan_nomor_surat'],tgl1($r['permohonan_tgl_izin']),tgl1($r['permohonan_tgl_berakhir_izin']),$luas,$peruntukan['suratizindata_value']);
			}*/
				
			$col = "A";
			for($y=0;$y< count($value);$y++){
				$cols = $col;
				$sheet->setCellValue($col.$j,$value[$y]);
				$col++;
			}	
			
			/*$teknis = mysql_query("SELECT*FROM view_teknis_izin WHERE suratizindata_permohonan_id='$r[permohonan_id]'");
			while ($r_teknis = mysql_fetch_array($teknis)){
				$val_teknis = "";
				$val_teknis = $r_teknis['suratizin_field']." : ".$r_teknis['suratizindata_value'];
				$sheet->setCellValue($col.$j,$val_teknis);
				$col++;
			}*/
			
			$i++;	
			$j++;
		}
		
/*$sheet->getStyle('A6:'.$cols.$j)->getFont()->setName('Arial');
$sheet->getStyle('A6:'.$cols.$j)->getFont()->setSize(10);
//$sheet->getStyle('A5:'.$j)->getFont()->setBold(true);
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

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;*/

$writer = new Xlsx($spreadsheet);
$writer->save('Rekap Izin Simpadu.xlsx');
echo "<script>window.location = 'Rekap Izin Simpadu.xlsx'</script>";
?>
