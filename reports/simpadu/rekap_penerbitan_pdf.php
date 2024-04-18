<?php
include "../../includes/parser-php-version.php";
error_reporting(1);
// Include file class.ezpdf dalam folder fungsiPDF
require_once "../../ezpdf/class.ezpdf.php";
require_once "../../includes/tanggal.php";
// Koneksi ke database dan tampilkan datanya
require_once "../../includes/konfigurasi_db.php";
koneksi2_buka();

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
	if($search3) $jnsizin = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE jenisizin_id = '$search3'"));
	if($search3) $pdf->ezText("<b>Daftar ".$jnsizin['jenisizin_name']."</b>",14,array('justification'=>'center'));
	else $pdf->ezText("<b>Daftar Permohonan Perizinan</b>",14,array('justification'=>'center'));
	//$pdf->ezSetDy(-5,'makeSpace');
	//$pdf->ezText("<b>BULAN ".STRTOUPPER(bulantahun($search2))."</b>",14,array('justification'=>'center'));
	$pdf->ezSetDy(-10,'makeSpace');
	if($search1 != NULL or $search2 != NULL)$pdf->ezText("Dari Tanggal : ".tgl2($search1)." s/d ".tgl2($search2),11,array('justification'=>'center'));
	$pdf->ezSetDy(-10,'makeSpace');
    
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
	
		$i = 1;
		while ($r = mysql_fetch_array($query)){
			
			$tabels[$i]['<b>No.</b>']= $i;
			$tabels[$i]['<b>Nama Pemohon</b>']= $r['pemohon_nama'];
			$tabels[$i]['<b>Badan Usaha</b>']= $r['permohonan_badan_usaha'];
			$tabels[$i]['<b>Jenis Izin</b>']= $r['jenisizin_name'];
			$tabels[$i]['<b>Jenis Permohonan</b>']= $r['statusizin_name'];
			$tabels[$i]['<b>Telpon</b>']= $r['pemohon_telepon']; 
			$tabels[$i]['<b>Alamat</b>']= TRIM($r['pemohon_alamat']);
			$tabels[$i]['<b>Nomor Izin</b>']= $r['permohonan_nomor_surat'];
			$tabels[$i]['<b>Tanggal Penetapan</b>']= tgl1($r['permohonan_tgl_izin']);
			if(!strstr($r['permohonan_tgl_berakhir_izin'],"9999"))$tabels[$i]['<b>Akhir Masa Berlaku</b>']= tgl1($r['permohonan_tgl_berakhir_izin']);
			
			$i++;
			
		}
		
	$pdf->ezTable($tabels,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>1,'fontSize'=>11,'titleFontSize'=>11,'xPos'=>'center','xOrientation'=>'center','rowGap'=>5,
						'cols'=>array('<b>No.</b>'=>array ( 'width'=>40, 'justification'=>'center'),
										'<b>Nama Pemohon</b>'=>array ( 'width'=>110, 'justification'=>'left'),
										'<b>Badan Usaha</b>'=>array ( 'width'=>100, 'justification'=>'left'),
										'<b>Jenis Izin</b>'=>array ( 'width'=>100, 'justification'=>'left'),
										'<b>Jenis Permohonan</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Telpon</b>'=>array ( 'width'=>85, 'justification'=>'left'),
										'<b>Alamat</b>'=>array ( 'width'=>150, 'justification'=>'full'),//'full'
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
	
	$pdf->ezimage('../../images/ttd.png',30,220,'none',570);
	
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