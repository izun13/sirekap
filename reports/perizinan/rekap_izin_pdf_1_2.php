<?php
error_reporting(1);
// Include file class.ezpdf dalam folder fungsiPDF
require_once "../../ezpdf/class.ezpdf.php";
require_once "../../includes/tanggal.php";
// Koneksi ke database dan tampilkan datanya
require_once "../../includes/koneksi.php";

$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
$search4 = $_GET["search4"];
$opd_id = $_GET['search5'];
if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
//$tgl_now = tgl2(date("Y-m-d"));
	
    $pdf = new Cezpdf('FOLIO','landscape');

    // Set margin dan font
    $pdf->ezSetCmMargins(1.25,1,1.5,1);
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
    $pdf->line(50, 520, 890, 520);
    $pdf->line(50, 518, 890, 518);
        
	
	
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
	$pdf->ezText("<b><u>DAFTAR PERMOHONAN IZIN</u></b>",14,array('justification'=>'center'));
	//$pdf->ezSetDy(-5,'makeSpace');
	//$pdf->ezText("<b>BULAN ".STRTOUPPER(bulantahun($search2))."</b>",14,array('justification'=>'center'));
	if($search1 != NULL or $search2 != NULL)$pdf->ezText("Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2),11,array('justification'=>'left'));
	$pdf->ezSetDy(-10,'makeSpace');
    
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
	
		$i = 1;
		while ($r = mysql_fetch_array($query)){
			$hari_kerja= 0;
			$tgl1 = "";
			$tgl2 = "";
			$tgl3 = "";
			$tgl4 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			//$r_tgl = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
			//if($r_tgl['tgl_penyerahan']) $tgl3 = tgl1($r_tgl['tgl_penyerahan']);
					
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
			
			/*$r_nama = explode(",",$r['nama']);
			for ($j=0; $j<count($r_nama); $j++) {
				if($j==0)$nama = strtoupper($r_nama[$j]).",";
				else $nama .= $r_nama[$j].",";
			}
			$nama = substr($nama, 0, -1);*/
			
			$tabels[$i]['<b>No.</b>']= $i;
			$tabels[$i]['<b>Nama Pemohon</b>']= $r['nama'];
			$tabels[$i]['<b>Jenis Izin</b>']= $r['jenis_izin'];
			$tabels[$i]['<b>Jenis Permohonan</b>']= $r['jenis_permohonan'];
			$tabels[$i]['<b>Telp./HP. Pemohon</b>']= $contact; 
			$tabels[$i]['<b>Lokasi Izin</b>']= TRIM($r['lokasi_izin']);
			$tabels[$i]['<b>Nomor Izin</b>']= $r['no_izin'];
			$tabels[$i]['<b>Tanggal Penetapan</b>']= $tgl2;
			$tabels[$i]['<b>Akhir Masa Berlaku</b>']= $masaberlaku;
			$i++;
			
		}
		
	$pdf->ezTable($tabels,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>1,'fontSize'=>11,'titleFontSize'=>11,'xPos'=>0,'xOrientation'=>'right','rowGap'=>5,
						'cols'=>array('<b>No.</b>'=>array ( 'width'=>50, 'justification'=>'center'),
										'<b>Nama Pemohon</b>'=>array ( 'width'=>110, 'justification'=>'left'),
										'<b>Jenis Izin</b>'=>array ( 'width'=>100, 'justification'=>'left'),
										'<b>Jenis Permohonan</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Telp./HP. Pemohon</b>'=>array ( 'width'=>85, 'justification'=>'left'),
										'<b>Lokasi Izin</b>'=>array ( 'width'=>200, 'justification'=>'full'),//'full'
										'<b>Nomor Izin</b>'=>array ( 'width'=>100, 'justification'=>'left'),
										'<b>Tanggal Penetapan</b>'=>array ( 'width'=>70, 'justification'=>'center'),
										'<b>Akhir Masa Berlaku</b>'=>array ( 'width'=>70, 'justification'=>'center')
						)
				) );
		
    //$lurah = mysql_fetch_array(mysql_query("SELECT*FROM konfigurasi"));
	
	//$pdf->ezSetDy(-20,'makeSpace');
	//$pdf->ezText('Plt. Kabid. Penyelenggaraan Perizinan dan',12,array('left'=>500,'justification'=>'center'));
	//$pdf->ezText('Non Perizinan',12,array('left'=>500,'justification'=>'center'));
	//$pdf->ezText('Sekretaris',12,array('left'=>500,'justification'=>'center'));
	//$pdf->ezSetDy(-60,'makeSpace');
	//$pdf->ezText("<u><b>SRI ASIH WIDIYASTUTI, S.H, M.H</b></u>",12,array('left'=>500,'justification'=>'center'));
	//$pdf->ezText("NIP. 19661107 199703 2 003",12,array('left'=>500,'justification'=>'center'));
	
	//$pdf->ezimage('../../images/ttd.png',30,220,'none',570);
	
	//$pdf->ezSetDy(-15,'makeSpace');
	//if($surat['tembusan']){
	//$pdf->ezText("Tembusan :",12,array('justification'=>'left'));
	//$pdf->ezText($surat['tembusan'],12,array('justification'=>'left'));
	//}
	
	//if($surat['gambar'])$pdf->ezimage('../../'.$surat['gambar'],5,200,'none','left');
	
    // Penomoran halaman
    //$pdf->ezStartPageNumbers(320, 15, 8);
    $pdf->ezStream();

?>