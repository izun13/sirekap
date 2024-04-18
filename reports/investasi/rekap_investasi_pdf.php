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
	
    $pdf = new Cezpdf('FOLIO','landscape');

    // Set margin dan font
    $pdf->ezSetCmMargins(1.15,1,2,1);
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
    $pdf->line(50, 520, 885, 520);
    $pdf->line(50, 518, 885, 518);
        
	
	
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
	$pdf->ezText("<b><u>REKAPITULASI REALISASI INVESTASI</u></b>",14,array('justification'=>'center'));
	$pdf->ezSetDy(-5,'makeSpace');
	$pdf->ezText("<b>BULAN ".STRTOUPPER($search4)." ".$search3."</b>",14,array('justification'=>'center'));
	//if($search1 != NULL or $search2 != NULL)$pdf->ezText("Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2),11,array('justification'=>'left'));
	$pdf->ezSetDy(-10,'makeSpace');
    
	$tabel = "SELECT*FROM realisasi_investasi WHERE id IS NOT NULL";
	if(($search1 != "") and ($search2 != ""))$tabel .= " AND $search1 LIKE '%$search2%'";
	if(($search3 != ""))$tabel .= " AND tahun LIKE '%$search3%'";
	if(($search4 != ""))$tabel .= " AND bulan LIKE '%$search4%'";
	
	$tabel .= " ORDER BY id asc";
	
	
	$query = mysql_query($tabel);
	
		$i = 1;
		$jum = 0;
		while ($r = mysql_fetch_array($query)){
			//tahun,bulan,nama_perusahaan,bidang_usaha,jenis_modal,nilai_investasi,jumlah_tk,no_izin,kegiatan_usaha,no_telepon	
			//$header = array("No.","Tahun","Bulan","Nama Perusahaan","Bidang Usaha","Jenis Modal","Nilai Investasi","Jumlah TK","Nomor Izin","Kegiatan Usaha","Nomor Telepon");

			$tabels[$i]['<b>No.</b>']= $i;
			$tabels[$i]['<b>Tahun</b>']= $r['tahun'];
			$tabels[$i]['<b>Bulan</b>']= $r['bulan']; 
			$tabels[$i]['<b>Nama Perusahaan</b>']= STRTOUPPER($r['nama_perusahaan']);
			$tabels[$i]['<b>Bidang Usaha</b>']= $r['bidang_usaha'];
			$tabels[$i]['<b>Jenis Modal</b>']= $r['jenis_modal'];
			$tabels[$i]['<b>Nilai Investasi</b>']= number_format($r['nilai_investasi'],0,",",".");
			$tabels[$i]['<b>Jumlah TK</b>']= $r['jumlah_tk'];
			$tabels[$i]['<b>Nomor Izin</b>']= $r['no_izin'];
			$tabels[$i]['<b>Kegiatan Usaha</b>']= $r['kegiatan_usaha'];
			$tabels[$i]['<b>Nomor Telepon</b>']= $r['no_telepon'];
			$i++;
			$jum += $r['nilai_investasi'];
			
		}
			$tabels[$i]['<b>Jenis Modal</b>']= "Total";
			$tabels[$i]['<b>Nilai Investasi</b>']= number_format($jum,0,",",".");
		
	$pdf->ezTable($tabels,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>1,'fontSize'=>11,'titleFontSize'=>11,'xPos'=>0,'xOrientation'=>'right','rowGap'=>5,
						'cols'=>array('<b>No.</b>'=>array ( 'width'=>40, 'justification'=>'center'),//rata kanan kiri -> 'full'
										'<b>Tahun</b>'=>array ( 'width'=>50, 'justification'=>'center'),
										'<b>Bulan</b>'=>array ( 'width'=>60, 'justification'=>'left'),
										'<b>Nama Perusahaan</b>'=>array ( 'width'=>120, 'justification'=>'left'),
										'<b>Bidang Usaha</b>'=>array ( 'width'=>100, 'justification'=>'left'),
										'<b>Jenis Modal</b>'=>array ( 'width'=>50, 'justification'=>'left'),
										'<b>Nilai Investasi</b>'=>array ( 'width'=>80, 'justification'=>'right'),
										'<b>Nomor Izin</b>'=>array ( 'width'=>75, 'justification'=>'left'),
										'<b>Jumlah TK</b>'=>array ( 'width'=>75, 'justification'=>'center'),
										'<b>Kegiatan Usaha</b>'=>array ( 'width'=>120, 'justification'=>'left'),
										'<b>Nomor Telepon</b>'=>array ( 'width'=>75, 'justification'=>'left')
						)
				) );
	
	$tabels2[0][0] = "<b>TOTAL</b>";
	$tabels2[0][1] = "<b>".number_format($jum,0,",",".")."</b>";
	$tabels2[0][2] = "";
	$pdf->ezTable($tabels2,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>0,'fontSize'=>11,'titleFontSize'=>11,'xPos'=>0,'xOrientation'=>'right','rowGap'=>5,
						'cols'=>array(0=>array ( 'width'=>420, 'justification'=>'center'),//rata kanan kiri -> 'full'
										1=>array ( 'width'=>80, 'justification'=>'right'),
										2=>array ( 'width'=>345, 'justification'=>'left')
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