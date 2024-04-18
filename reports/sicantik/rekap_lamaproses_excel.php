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
$search5 = $search[5]; 
$search6 = $search[6]; 
$search7 = $search[7];

$search3 = str_replace("_"," ",$search3);

$tanggal1 = date('Y-m-d',strtotime($search1));
$tanggal2 = date('Y-m-d',strtotime('2022-04-30'));
$tanggal3 = date('Y-m-d',strtotime('2023-03-31'));

/** PhpSpreadsheet */
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
$tgl_now = tgl2(date("Y-m-d"));

/*$sheet->getStyle('A1:A3')->getFont()->setName('Arial');
$sheet->getStyle('A1:A3')->getFont()->setSize(12);
$sheet->getStyle('A1:A3')->getFont()->setBold(true);

$sheet->mergeCells('A1:O1');
$sheet->mergeCells('A2:O2');
$sheet->mergeCells('A3:O3');
$sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/

$sheet->setTitle('DATA PERMOHONAN');
$sheet->setCellValue('A1', 'REKAPITULASI PERMOHONAN PERIZINAN');
$sheet->setCellValue('A2', 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP) KOTA MAGELANG');
$sheet->setCellValue('A3', 'Tanggal Laporan : '.$tgl_now);

//set table header
//$header = array("No.","Tanggal Pengajuan","Nomor Permohonan","Jenis Izin","Jenis Permohonan","Nama Pemohon","No. Identitas","Telp./HP. Pemohon","Email","Lokasi","Nomor Izin","Tanggal Terima Berkas","Tanggal Pengesahan","Lama Proses");
$header = array('No.','Nomor Permohonan','Jenis Izin','Nama Pemohon','Tanggal Diterima','Tanggal Penetapan','Nomor Izin','Lama Waktu','Durasi','Melebihi SOP','Selesai');

$col = "A";
for($y=0;$y<count($header);$y++){
$cols = $col;
$sheet->setCellValue($col.'5',$header[$y]);
$col++;
}

/*$sheet->getStyle('A5:'.$cols.'5')->getFont()->setName('Arial');
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setSize(10);
$sheet->getStyle('A5:'.$cols.'5')->getFont()->setBold(true);
$sheet->getStyle('A5:'.$cols.'5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/

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
	
	//sampai dengan bulan mei
	//if($tanggal1 <= $tanggal2){
		$tabel = "SELECT*FROM permohonan_izin_penetapan WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
		$tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
	//}else{
		//$tabel = "SELECT*FROM permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND status_penetapan != 'ditolak'";	
		//$tabel .= " AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";
	//}
	//if($search3 != "") $tabel .= " AND jenis_izin = '$search3'";
	//if($search6 != "") $tabel .= " AND nama LIKE '%$search6%'";
	//if($search7 != "") $tabel .= " AND jenis_izin LIKE '%$search7%'";
	
	if($search7 != "") $tabel .= " AND jenis_izin $search3 '%$search7%'";
	
	$tabel .= " ORDER BY no_permohonan ASC";
	
	$query = mysql_query($tabel);
		while ($r= mysql_fetch_array($query)){
			$id = $r["id"];
			$r_jns = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE jenis_izin = '$r[jenis_izin]'"));
			$opd_id = $r_jns['opd_id'];
			
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
						
			//tgl cetak tanda terima berkas
			$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '33' "));
			$tgl_awal = $tglawal['end_date'];
			
			//if($tanggal1 <= $tanggal2)$tgl_akhir = $r['tgl_penetapan'];
			//else $tgl_akhir = $r['end_date'];
			
			$tgl_akhir = $r['tgl_penetapan'];
			
			//tgl ttd izin
			//$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '40' "));
			//tgl penetapan
			//if(empty($tglakhir))$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '8' "));
			
			$tgl_rekomendasi = "";
			//tgl rekomendasi kesehatan
			//$tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '7' "));
			//tgl rekomendasi diperindag
			//if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '176' "));
			//tgl rekomendasi bpkad
			if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '108' "));
			if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '192' "));
			
			if((!empty($tgl_rekomendasi)) and ($opd_id == 3))$tgl_akhir = $tgl_rekomendasi['start_date'];
			
			//if($search4 == 0)$tgl_akhir = $r['end_date'];			
			
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
			
			
			if((!empty($tgl_rekomendasi)) and ($opd_id == 3)){//and ($search4 == 1)
				//tgl Cetak Rekomendasi dkk dan disperindag
				$tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '35' "));
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '7' "));
				//tgl Verifikasi status bayar bpkad
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '226' "));
				
				if(!empty($tgl_cetakrekomendasi))$tgl_awal = $tgl_cetakrekomendasi['end_date'];
				if( date('Y-m-d', strtotime($tgl_awal)) ==  $tgl_akhir) $tgl_awal = date('Y-m-d', strtotime('+1 days', strtotime($tgl_awal))); 
				
				//$tgl_akhir = $r['end_date'];
				$tgl_akhir = $r['tgl_penetapan'];
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
			
			$hari_kerja = $hari_kerja-1;
			if($hari_kerja <= 0) $hari_kerja = 1;
			
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
				
				$tampil2 = 0;
				
				if($tanggal1 <= $tanggal3){
					if ($opd_id == 3)$waktu_sop = 5;
					else $waktu_sop = 3;
				}else{
					$cek_sop = mysql_fetch_array(mysql_query("SELECT waktu_sop FROM jenis_izin WHERE id = '$r[jenis_izin_id]'"));
					$waktu_sop = $cek_sop['waktu_sop'];
				}
				
				if($search5 == 1){
					if($hari_kerja <= $waktu_sop) $tampil2 = 1;			
				}
				elseif($search5 == 2){	
					if($hari_kerja > $waktu_sop) $tampil2 = 1;	
				}
				else $tampil2 = 1;
				
				if($tampil2 == 1){
						
					/*$r_nama = explode(",",$r['nama']);
					for ($j=0; $j<count($r_nama); $j++) {
						if($j==0)$nama = strtoupper($r_nama[$j]).",";
						else $nama .= $r_nama[$j].",";
					}
					$nama = substr($nama, 0, -1);*/
					
					$selisih = "";
					$prosentase =  number_format(($waktu_sop/$hari_kerja)*100,2);
					if($hari_kerja <= $waktu_sop) $prosentase = 100;
					else $selisih = abs($waktu_sop-$hari_kerja)." hari";
					
					$identitas = $r['tipe_identitas']."-".$r['no_identitas'];
					//$value = array ($i,$tgl1,$r['no_permohonan'],$r['jenis_izin'],$r['jenis_permohonan'],$r['nama'],$identitas,$contact,$r['email'],trim($r['lokasi_izin']),$r['no_izin'],$tgl4,$tgl2,$hari_kerja);
					$value = array ($i,$r['no_permohonan'],$r['jenis_izin'],$r['nama'],$tgl4,$tgl2,$r['no_izin'],$waktu_sop.' hari',$hari_kerja.' hari',$selisih,$prosentase.' %');
					$col = "A";
					for($y=0;$y< count($header);$y++){
						$cols = $col;
						$sheet->setCellValue($col.$j,$value[$y]);
						$col++;
					}						
					$jumlah += $hari_kerja;
					$jml_data = $i;
					$i++;	
					$j++;
				}
			//}
		}
			//$rata = number_format($jumlah/$jml_data);
			$rata =  number_format(($jumlah/$jml_data)*100,2);
			$sheet->setCellValue('B'.$j,"Rata-rata Lama Proses"); 
			$sheet->setCellValue('I'.$j,$rata);
			
/*$sheet->getStyle('A6:'.$cols.$j)->getFont()->setName('Arial');
$sheet->getStyle('A6:'.$cols.$j)->getFont()->setSize(10);
//$sheet->getStyle('A5:'.$j)->getFont()->setBold(true);
$sheet->getStyle('A6:A'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);*/

$writer = new Xlsx($spreadsheet);
$writer->save('Rekap Lama Proses Izin.xlsx');
echo "<script>window.location = 'Rekap Lama Proses Izin.xlsx'</script>";
?>
