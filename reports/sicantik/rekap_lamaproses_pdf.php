<?php
include"../../includes/parser-php-version.php";
error_reporting(1);
require('../../fpdf17/pdf_mc_table.php');
require_once "../../includes/tanggal.php";
// Koneksi ke database dan tampilkan datanya
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();

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

if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
$tgl_now = tgl2(date("Y-m-d"));
	 
	$pdf = new PDF_MC_Table('l','mm','folio');
	// membuat halaman baru
	$pdf->AddPage();
	// setting jenis font yang akan digunakan
	$pdf->SetFont('times','B',14);
	// mencetak string 
			//put logo
	$pdf->Image('../../images/logo.jpg',10,10,20);
	$pdf->Cell(306,6,'PEMERINTAH KOTA MAGELANG',0,1,'C');
	$pdf->Cell(306,6,'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP)',0,1,'C');
	$pdf->SetFont('times','B',12);
	$pdf->Cell(306,6,'Jl. Veteran No.7, Magelang, Magelang Tengah, Kota Magelang, Jawa Tengah 56117',0,1,'C');
	$pdf->Cell(306,6,'Telp. (0293) 314663 http://dpmptsp.magelangkota.go.id',0,1,'C');
	$pdf->Cell(0, 1, " ", "B");
	$pdf->Ln(5);
	$pdf->Cell(306,6,"DAFTAR PENERBITAN PERIZINAN",0,1,'C');
	if($search1 != NULL or $search2 != NULL)$pdf->Cell(306,6,"Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2),0,1,'C');
	
	// Memberikan space kebawah agar tidak terlalu rapat
	$pdf->Ln(5);


	//make new object
	//set width for each column (6 columns)
	$pdf->SetWidths(Array(10,25,50,50,25,25,45,20,20,20,20));//310
	//set line height. This is the height of each lines, not rows.
	$pdf->SetLineHeight(5);
					
	// Tabel
	//Title					
	$pdf->SetFont('times','B',10);
	$pdf->SetAligns(Array('C','C','C','C','C','C','C','C','C','C'));
	$pdf->Row(Array(	'No.',
						'Nomor Permohonan',
						'Jenis Izin',
						'Nama Pemohon',
						'Tanggal Diterima',
						'Tanggal Penetapan',
						'Nomor Izin',
						'Lama Waktu',
						'Durasi',
						'Melebihi SOP',
						'Selesai'
					));
	//Isi Tabel					
	$pdf->SetAligns(Array('C','C','L','L','C','C','L','C','C','C','C'));
	$pdf->SetFont('times','',10); 
			
	// libur nasional
	$z=0;
	$query_libur = mysql_query("SELECT tgl FROM libur_nasional");
	while ($r_libur= mysql_fetch_array($query_libur)){
		$libur_nasional[$z] = $r_libur['tgl'];
		$z++;
	}
		
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
	
	$tabel .= " ORDER BY id asc";
	
	
	$query = mysql_query($tabel);
	
		$i = 1;
		$jumlah = 0;
		$jml_data = 0;
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
			if($hp != "") $contact = $hp;
			if(($tlp != "") and ($hp != "")) $contact = $tlp." / ".$hp;
			if($tlp == $hp) $contact = $tlp;
			
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
					$selisih = "";
					$prosentase =  number_format(($waktu_sop/$hari_kerja)*100,2);
					if($hari_kerja <= $waktu_sop) $prosentase = 100;
					else $selisih = abs($waktu_sop-$hari_kerja)." hari";
					
					$pdf->Row(Array(
						$i,
						$r['no_permohonan'],
						$r['jenis_izin'],
						$r['nama'],
						$tgl4,
						$tgl2,
						$r['no_izin'],
						$waktu_sop.' hari',
						$hari_kerja.' hari',
						$selisih,
						$prosentase.' %'
					));
					//$r['jenis_permohonan'],TRIM($r['lokasi_izin']),
								
					$jumlah += $hari_kerja;
					$jml_data = $i;
					$i++;
				}
			//}
		}
	$pdf->SetWidths(Array(250,60));
	$pdf->SetAligns(Array('C','C'));	
	$pdf->SetLineHeight(6);	
	$pdf->SetFont('times','B',10);
	$average =  number_format(($jumlah/$jml_data)*100,2)." hari";
	$pdf->Row(Array(
						'Rata-rata Durasi (Lama Proses)',
						$average
					));
$pdf->Ln(5);
$y=$pdf->GetY();	
if($y > 150) $pdf->SetAutoPageBreak(true,50);	
$pdf->Ln(5);
$pdf->SetFont('times','B',11);
//$pdf->Cell(500,5,'Posisi Y : '.$y,0,1,'C');
$pdf->Cell(500,5,'KEPALA DINAS PENANAMAN MODAL DAN',0,1,'C');
$pdf->Cell(500,5,'PELAYANAN TERPADU SATU PINTU',0,1,'C');
$pdf->Cell(500,5,'KOTA MAGELANG',0,1,'C');
$pdf->Ln(15);
$pdf->Cell(500,5,'KHUDHOIFAH, SH. MM.',0,1,'C');
$pdf->SetFont('times','',11);
$pdf->Cell(500,5,'Pembina Utama Muda',0,1,'C');
$pdf->Cell(500,5,'NIP. 19650827 199003 2 005',0,1,'C');
//$pdf->Image('../../images/ttd.png',10,10,20);	
$pdf->Output('Lama Proses Penerbitan izin.pdf','D');
?>