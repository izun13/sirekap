<?php
error_reporting(1);
// Include file class.ezpdf dalam folder fungsiPDF
include ('../../ezpdf/class.ezpdf.php');
require_once "../tanggal.php";
// Koneksi ke database dan tampilkan datanya
//include"../koneksi.php";
mysql_connect("172.17.20.7","smsgateway","sms432432432");
$con=mysql_select_db("gammu");

$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
$tgl_now = tgl2(date("Y-m-d"));
	
    $pdf = new Cezpdf('FOLIO','potrait');

    // Set margin dan font
    $pdf->ezSetCmMargins(1,1,1.15,1);
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
	$pdf->ezText("<b>PEMERINTAH KOTA MAGELANG</b>",14,array('left'=>55,'justification'=>'center'));
	$pdf->ezText("<b>DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP)</b>",16,array('left'=>55,'justification'=>'center'));
	$pdf->ezSetDy(-4,'makeSpace');
	$pdf->ezText("Jl. Veteran No.7 Kota Magelang 56117 Telp.(0293) 314663",12,array('left'=>55,'justification'=>'center'));
	// Garis
    $pdf->line(30, 834, 585, 834);
    $pdf->line(30, 832, 585, 832);
        
	
	
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
	$pdf->ezText("<b><u>REKAPITULASI PENGIRIMAN SMS</u></b>",14,array('justification'=>'center'));
	$pdf->ezSetDy(-10,'makeSpace');
	if($search1 != NULL or $search2 != NULL)$pdf->ezText("Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2),11,array('justification'=>'left'));
	$pdf->ezSetDy(-5,'makeSpace');
    
	$query = "select * from sentitems order by ID asc";
	
	if(($search1 != "") and ($search2 != "")){		
		$query = "select * from sentitems WHERE SendingDateTime >= '$search1' and SendingDateTime <= '$search2' order by ID asc";
	}
	if(($search3 != "")){		
		$query = "select * from sentitems WHERE Status = '$search3' order by ID asc";
	}
	if(($search1 != "") and ($search2 != "") and ($search3 != "")){		
		$query = "select * from sentitems WHERE SendingDateTime >= '$search1' and SendingDateTime <= '$search2' and Status = '$search3' order by ID asc";
	}
	
	
	$query = mysql_query($query);
	
		$i = 1;
		while ($r = mysql_fetch_array($query)){
			$tgl1 = "";
			if($r['SendingDateTime']) $tgl1 = tgl1($r['SendingDateTime']);
			if($r['DestinationNumber']) $telp = preg_replace("/[^0-9]/", "", $r['DestinationNumber']);
					  
			$tabel[$i]['<b>No.</b>']= $i;          
			$tabel[$i]['<b>Tanggal</b>']= $tgl1;          
			$tabel[$i]['<b>Nomor SMSC</b>']= $r['SMSCNumber'];
			$tabel[$i]['<b>Nomor Tujuan</b>']= $telp;
			$tabel[$i]['<b>Isi SMS</b>']= $r['TextDecoded'];
			$tabel[$i]['<b>Status</b>']= $r['Status'];
			$i++;
			
		}
		
	$pdf->ezTable($tabel,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>1,'fontSize'=>11,'titleFontSize'=>11,'xPos'=>0,'xOrientation'=>'right','rowGap'=>5,
						'cols'=>array('<b>No.</b>'=>array ( 'width'=>30, 'justification'=>'center'),
										'<b>Tanggal</b>'=>array ( 'width'=>70, 'justification'=>'left'),
										'<b>Nomor SMSC</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Nomor Tujuan</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Isi SMS</b>'=>array ( 'width'=>230, 'justification'=>'left'),
										'<b>Status</b>'=>array ( 'width'=>70, 'justification'=>'left')//'full'
						)
				) );
		
    //$lurah = mysql_fetch_array(mysql_query("SELECT*FROM konfigurasi"));
	
	//$pdf->ezSetDy(-15,'makeSpace');
	//$pdf->ezText('KEPALA DINAS',12,array('left'=>300,'justification'=>'center'));
	//$pdf->ezSetDy(-50,'makeSpace');
	//$pdf->ezText("<b>Nama Lengkap</b>",12,array('left'=>300,'justification'=>'center'));
	//$pdf->ezText("NIP. ",12,array('left'=>300,'justification'=>'center'));
		
	//$pdf->ezSetDy(-15,'makeSpace');
	//if($surat['tembusan']){
	//$pdf->ezText("Tembusan :",12,array('justification'=>'left'));
	//$pdf->ezText($surat['tembusan'],12,array('justification'=>'left'));
	//}
	
	//if($surat['gambar'])$pdf->ezimage('../../'.$surat['gambar'],5,200,'none','left');
	
    // Penomoran halaman
    //$pdf->ezStartPageNumbers(320, 15, 8);
    $pdf->ezStream(array('Content-Disposition'=>'rekap-kirim-sms.pdf'));

?>