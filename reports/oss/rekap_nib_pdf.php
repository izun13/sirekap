<?php
error_reporting(1);
// Include file class.ezpdf dalam folder fungsiPDF
require_once "../../ezpdf/class.ezpdf.php";
require_once "../../includes/tanggal.php";
// Koneksi ke database dan tampilkan datanya
require_once "../../includes/koneksi.php";

if($_GET["search"])$searching = explode(";",$_GET["search"]);

	
    $pdf = new Cezpdf('FOLIO','landscape');

    // Set margin dan font
    $pdf->ezSetCmMargins(1.25,1,2,1);
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
	
	
	$pdf->ezimage($img ,12,45,'none',0);
	$pdf->ezText("<b>PEMERINTAH KOTA MAGELANG</b>",14,array('justification'=>'center'));
	$pdf->ezText("<b>DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP)</b>",16,array('justification'=>'center'));
	$pdf->ezSetDy(-4,'makeSpace');
	$pdf->ezText("Jl. Veteran No.7 Kota Magelang 56117 Telp.(0293) 314663",12,array('justification'=>'center'));
	// Garis
    $pdf->line(50, 520, 910, 520);
    $pdf->line(50, 518, 910, 518);
        
	
	
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
	
	$pdf->ezSetDy(-20,'makeSpace');
	$pdf->ezText("<b><u>REKAPITULASI NIB</u></b>",14,array('justification'=>'center'));
	//$pdf->ezSetDy(-5,'makeSpace');
	//$pdf->ezText("<b>BULAN ".STRTOUPPER(bulantahun($search2))."</b>",14,array('justification'=>'center'));
	//if($search1 != NULL or $search2 != NULL)$pdf->ezText("Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2),11,array('justification'=>'left'));
	$pdf->ezSetDy(-20,'makeSpace');
    
	$tabel = "SELECT*FROM oss_nib WHERE id IS NOT NULL";
	
	for ($x=0;$x<count($searching)-1;$x++){
						
		if($searching[$x]){
			$searching2 = explode(":",$searching[$x]);
			$kol = $searching2[0];
			$sim = $searching2[1];
			$val = $searching2[2];
			$val = str_replace("_"," ",$val);
		}
				
		if($kol) {
			if(($sim == "LIKE") or ($sim == "NOT LIKE"))$tabel2 .= " AND ".$kol." ".$sim." '%".$val."%'";
			else $tabel2 .= " AND ".$kol." ".$sim." '".$val."'";
		}
	}
	
	$tabel .= "ORDER BY id asc";
	
	
	$query = mysql_query($tabel);
	
		$i = 1;
		while ($r = mysql_fetch_array($query)){
			$tabels[$i]['<b>No.</b>']= $i;
			$tabels[$i]['<b>Nama Perusahaan</b>']= $r['nama_perusahaan'];
			$tabels[$i]['<b>Jenis</b>']= $r['jenis_perusahaan'];
			$tabels[$i]['<b>Status PM</b>']= $r['status_pm'];
			$tabels[$i]['<b>NIB</b>']= $r['nib'];
			$tabels[$i]['<b>Tanggal NIB</b>']= $r['tanggal_nib'];
			$tabels[$i]['<b>Status NIB</b>']= $r['status_nib'];
			$tabels[$i]['<b>Alamat</b>']= $r['alamat_perusahaan'];
			$tabels[$i]['<b>No. Telpon</b>']= $r['no_telp'];
			$tabels[$i]['<b>Email</b>']= $r['email_perusahaan'];
			$i++;
			
		}
		
	$pdf->ezTable($tabels,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>1,'fontSize'=>11,'titleFontSize'=>11,'xPos'=>0,'xOrientation'=>'right','rowGap'=>5,
						'cols'=>array('<b>No.</b>'=>array ( 'width'=>40, 'justification'=>'center'),
										'<b>Nama Perusahaan</b>'=>array ( 'width'=>120, 'justification'=>'left'),
										'<b>Jenis</b>'=>array ( 'width'=>50, 'justification'=>'center'),
										'<b>Status PM</b>'=>array ( 'width'=>70, 'justification'=>'center'),
										'<b>NIB</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Tanggal NIB</b>'=>array ( 'width'=>70, 'justification'=>'center'),
										'<b>Status NIB</b>'=>array ( 'width'=>70, 'justification'=>'center'),
										'<b>Alamat</b>'=>array ( 'width'=>180, 'justification'=>'full'),//'full'
										'<b>No. Telpon</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Email</b>'=>array ( 'width'=>100, 'justification'=>'left')
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