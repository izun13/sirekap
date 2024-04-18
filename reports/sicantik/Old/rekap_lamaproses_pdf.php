<?php
include"../../includes/parser-php-version.php";
error_reporting(1);
// Include file class.ezpdf dalam folder fungsiPDF
require_once "../../ezpdf/class.ezpdf.php";
require_once "../../includes/tanggal.php";
// Koneksi ke database dan tampilkan datanya
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();

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
	
    $pdf = new Cezpdf('FOLIO','landscape');

    // Set margin dan font
    $pdf->ezSetCmMargins(1.5,1,1.5,1);
    $pdf->selectFont('../../ezpdf/fonts/Times-Roman.afm');
    //$pdf->openObject();

    // Tampilkan logo
    //$pdf->setStrokeColor(0, 0, 0, 1);
    //$pdf->addJpegFromFile('logo.jpg',20,800,69);

    // Teks di tengah atas untuk judul header
	//addImage(&$img,$x,$y,$w=0,$h=0,$quality=75)
	$img = "../../images/logo.jpg";
	//$pdf->addImage($img,100,100,60,60,100);
	//$pdf->addPngFromFile($img,50,780,60,30);
	//$pdf->ezimage($img ,-45,45,'none',70);
    //$pdf->addText(220, 790, 12,'<b>PEMERINTAH KOTA MAGELANG</b>');
    //$pdf->addText(218, 780, 12,'<b>KECAMATAN MAGELANG UTARA</b>');
    //$pdf->addText(200, 765, 16,'<b>KELURAHAN POTROBANGSAN</b>');
    //$pdf->addText(195, 755, 10,'Jl. Pahlawan No.134 Magelang 56116 Telp. (0293)363512');
	
	
	$pdf->ezimage($img ,12,45,'none',0);
	$pdf->ezText("<b>PEMERINTAH KOTA MAGELANG</b>",14,array('justification'=>'center'));
	$pdf->ezText("<b>DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP)</b>",16,array('justification'=>'center'));
	$pdf->ezSetDy(-4,'makeSpace');
	$pdf->ezText("Jl. Veteran No.7 Kota Magelang 56117 Telp.(0293) 314663",12,array('justification'=>'center'));
	// Garis
    $pdf->line(40, 514, 910, 514);
    $pdf->line(40, 512, 910, 512);
        
	
	
    // Garis bawah untuk footer
    //$pdf->line(10, 50, 590, 50);
    // Teks kiri bawah
    //$pdf->addText(30,34,8,'Dicetak tgl:' . date( 'd-m-Y, H:i:s'));
	
	
	//$pdf->addText(30,34,9,'SIMKEL Versi PHP/MySQL Kelurahan Potrobangsan');
    //$pdf->closeObject();

    // Tampilkan object di semua halaman
    //$pdf->addObject($all, 'all');
	
	//$pdf->ezimage("../../images/kotak.png",-10,380,'none',-5);
	
    // Query untuk merelasikan tabel
	
	$pdf->ezSetDy(-10,'makeSpace');
	$pdf->ezText("<b><u>REKAPITULASI PERMOHONAN PERIZINAN</u></b>",14,array('justification'=>'center'));
	$pdf->ezSetDy(-10,'makeSpace');
	if($search1 != NULL or $search2 != NULL)$pdf->ezText("Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2),11,array('justification'=>'left'));
	$pdf->ezSetDy(-5,'makeSpace');
    
	// libur nasional
	$z=0;
	$query_libur = mysql_query("SELECT tgl FROM libur_nasional");
	while ($r_libur= mysql_fetch_array($query_libur)){
		$libur_nasional[$z] = $r_libur['tgl'];
		$z++;
	}
		
	//$tabel = "SELECT*FROM view_permohonan_izin WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
	//if(($search0 == "Pengajuan") and($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_pengajuan >= '$search1' AND tgl_pengajuan <= '$search2'";
	//if(($search0 == "Penetapan") and($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
	//if(($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
	$tabel = "SELECT*FROM view_permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
	if(($search1 != "") and ($search2 != "")) $tabel .= " AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";
	if($search3 != "") $tabel .= " AND jenis_izin = '$search3'";
	if($search6 != "") $tabel .= " AND nama LIKE '%$search6%'";
	if($search7 != "") $tabel .= " AND jenis_izin LIKE '%$search7%'";
	
	$tabel .= " ORDER BY no_izin asc";
	
	
	$query = mysql_query($tabel);
	
		$i = 1;
		$jumlah = 0;
		$jml_data = 0;
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
			if($hp != "") $contact = $hp;
			if(($tlp != "") and ($hp != "")) $contact = $tlp." / ".$hp;
			if($tlp == $hp) $contact = $tlp;
			
			/*$r_nama = explode(",",$r['nama']);
			for ($j=0; $j<count($r_nama); $j++) {
				if($j==0)$nama = strtoupper($r_nama[$j]).",";
				else $nama .= $r_nama[$j].",";
			}
			$nama = substr($nama, 0, -1);*/
			
			$tampil = 0;
			if(($search4 == 0) or ($search4 == "")) $tampil = 1;
			if(($search4 == 1) and ($tgl_akhir != null) and ($tgl_akhir <= $search2)) $tampil = 1;
			if(($search4 == 2) and ($tgl_akhir > $search2)) $tampil = 1;
			if(($search4 == 2) and ($tgl_akhir == null)) $tampil = 1;
							
			if($tampil == 1){
				
				$tampil2 = 1;
				if(($search5 == 5) and ($hari_kerja > $search5)) $tampil2 = 0;
				if(($search5 == 6) and ($hari_kerja < $search5)) $tampil2 = 0;
				
				if($tampil2 == 1){
					$tabels[$i]['<b>No.</b>']= $i;          
					//$tabels[$i]['<b>Tanggal Pengajuan</b>']= $tgl1;         
					$tabels[$i]['<b>Nomor Permohonan</b>']= $r['no_permohonan']; 
					$tabels[$i]['<b>Jenis Izin</b>']= $r['jenis_izin'];
					$tabels[$i]['<b>Jenis Permohonan</b>']= $r['jenis_permohonan'];
					$tabels[$i]['<b>Nama Pemohon</b>']= $r['nama'];
					$tabels[$i]['<b>No. Identitas</b>']= $r['tipe_identitas']."-".$r['no_identitas'];
					$tabels[$i]['<b>Telp./HP. Pemohon</b>']= $contact;
					$tabels[$i]['<b>Tanggal Terima Berkas</b>']= $tgl4;
					$tabels[$i]['<b>Tanggal Pengesahan</b>']= $tgl2;
					$tabels[$i]['<b>Nomor Izin</b>']= $r['no_izin'];
					$tabels[$i]['<b>Lama Proses</b>']= $hari_kerja." hari";
					//$tabels[$i]['<b>Tanggal Penyerah an</b>']= $tgl3;
					$tabels[$i]['<b>Lokasi Izin</b>']= TRIM($r['lokasi_izin']);
					
					$jumlah += $hari_kerja;
					$jml_data = $i;
					$i++;
				}
			}
		}
		
	$pdf->ezTable($tabels,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>1,'fontSize'=>10,'titleFontSize'=>10,'xPos'=>0,'xOrientation'=>'right','rowGap'=>5,
						'cols'=>array('<b>No.</b>'=>array ( 'width'=>30, 'justification'=>'center'),
										//'<b>Tanggal Pengajuan</b>'=>array ( 'width'=>58, 'justification'=>'center'),
										'<b>Nomor Permohonan</b>'=>array ( 'width'=>58, 'justification'=>'left'),
										'<b>Jenis Izin</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Jenis Permohonan</b>'=>array ( 'width'=>65, 'justification'=>'left'),
										'<b>Nama Pemohon</b>'=>array ( 'width'=>90, 'justification'=>'left'),
										'<b>No. Identitas</b>'=>array ( 'width'=>75, 'justification'=>'left'),
										'<b>Telp./HP. Pemohon</b>'=>array ( 'width'=>75, 'justification'=>'left'),
										'<b>Tanggal Terima Berkas</b>'=>array ( 'width'=>65, 'justification'=>'left'),
										'<b>Tanggal Pengesahan</b>'=>array ( 'width'=>65, 'justification'=>'center'),
										'<b>Nomor Izin</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Lama Proses</b>'=>array ( 'width'=>40, 'justification'=>'center'),
										//'<b>Tanggal Penyerah an</b>'=>array ( 'width'=>58, 'justification'=>'center'),
										'<b>Lokasi Izin</b>'=>array ( 'width'=>150, 'justification'=>'left')//'full'
						)
				) );
				
	$tabels2[0]['label']= "<b>Rata-rata Lama Proses</b>";
	$tabels2[0]['rata-rata']= number_format($jumlah/$jml_data)." hari";
	$tabels2[0]['kosong']= "";
	$pdf->ezTable($tabels2,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>0,'fontSize'=>10,'titleFontSize'=>10,'xPos'=>0,'xOrientation'=>'right','rowGap'=>8,
						'cols'=>array(	'label'=>array ( 'width'=>683, 'justification'=>'center'),
										'rata-rata'=>array ( 'width'=>40, 'justification'=>'center'),
										'kosong'=>array ( 'width'=>150, 'justification'=>'left')
						)
				) );
		
    //$lurah = mysql_fetch_array(mysql_query("SELECT*FROM konfigurasi"));
	
	$pdf->ezSetDy(-20,'makeSpace');
	$pdf->ezText('Plt. KEPALA DINAS',12,array('left'=>500,'justification'=>'center'));
	$pdf->ezSetDy(-50,'makeSpace');
	$pdf->ezText("<b>HAMZAH KHOLIFI, S.Sos.,MSi.</b>",12,array('left'=>500,'justification'=>'center'));
	$pdf->ezText("NIP. 19680530 199001 1 001",12,array('left'=>500,'justification'=>'center'));
		
	//$pdf->ezSetDy(-15,'makeSpace');
	//if($surat['tembusan']){
	//$pdf->ezText("Tembusan :",12,array('justification'=>'left'));
	//$pdf->ezText($surat['tembusan'],12,array('justification'=>'left'));
	//}
	
	//if($surat['gambar'])$pdf->ezimage('../../'.$surat['gambar'],5,200,'none','left');
	
    // Penomoran halaman
    //$pdf->ezStartPageNumbers(320, 15, 8);
    //$pdf->ezStream();
	$pdf->ezStream(array('Content-Disposition'=>'Rekap Proses Permohonan Izin.pdf'));
?>