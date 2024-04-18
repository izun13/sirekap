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
$opd_id = $_GET["search4"];
$search5 = $_GET['search5'];
if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
//$tgl_now = tgl2(date("Y-m-d"));

$jns_izin = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE id='$search3'"));

if (($search3 == "") and ($opd_id != 1) and ($opd_id != 0)){ 
	$query_jns=mysql_query("SELECT*FROM jenis_izin WHERE opd_id ='$opd_id' ORDER BY jenis_izin asc");
	$x = 0;
	while($r_jns=mysql_fetch_array($query_jns)){
		$jenis_izin[$x] = $r_jns["id"];
		$x++;
	}
}
	
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
	if($search3)$pdf->ezText("<b><u>DAFTAR ".STRTOUPPER($jns_izin['jenis_izin'])."</u></b>",14,array('justification'=>'center'));
	else $pdf->ezText("<b>DAFTAR PERMOHONAN PERIZINAN</b>",14,array('justification'=>'center'));
	//$pdf->ezSetDy(-5,'makeSpace');
	//$pdf->ezText("<b>BULAN ".STRTOUPPER(bulantahun($search2))."</b>",14,array('justification'=>'center'));
	$pdf->ezSetDy(-10,'makeSpace');
	if($search1 != NULL or $search2 != NULL)$pdf->ezText("Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2),11,array('justification'=>'left'));
	$pdf->ezSetDy(-10,'makeSpace');
    
	$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
	);  
	
	$sumber = "https://sicantik.go.id/api/TemplateData/keluaran/36461.json";
	$sumber .= "?key1=$search1&key2=$search2";
	//echo $sumber;
	$konten = file_get_contents($sumber, false, stream_context_create($arrContextOptions));
	$data = json_decode($konten, true);
	$i = 1;
		foreach ($data["data"]["data"] as $key=>$r) {
			$tgl1 = "";
			$tgl2 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			//if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			//if(( $r['tgl_signed_report'] != null ) and ($r['tgl_signed_report'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_signed_report']);
			
			/*$masaberlaku = $r["masa_berlaku"];
			if($masaberlaku == null) $masaberlaku = $r["tgl_akhir_str"];
			if($masaberlaku == null) $masaberlaku = $r["tgl_surat_pengantar"];
			if($masaberlaku == null) $masaberlaku = $r["tgl_permohonan_perusahaan"];
			if($masaberlaku == null) $masaberlaku = $r["tanggal_jatuh_tempo"];
			$masaberlaku = tgl1($masaberlaku);*/
			
			$lokasi = TRIM($r["lokasi_izin"]);
			
			$tlp = "";
			$hp = "";		
			if(($r['no_tlp'] != "") and ($r['no_tlp'] != "-") and ($r['no_tlp'] != "0"))$tlp = $r['no_tlp'];
			if(($r['no_hp'] != "") and ($r['no_hp'] != "-") and ($r['no_hp'] != "0"))$hp = $r['no_hp'];
			$contact = $tlp;
			if($hp != "") $contact = $hp;
			if(($tlp != "") and ($hp != "")) $contact = $tlp." / ".$hp;
			if($tlp == $hp) $contact = $tlp;
			
			$tampil = 1;
			if(($search3 != "") and ($search3 == $r['jenis_izin_id'])) $tampil = 1;
			if(($search3 != "") and ($search3 != $r['jenis_izin_id'])) $tampil = 0;
			if (($search3 == "") and ($opd_id != 1) and ($opd_id != 0)){ 
				if(in_array($r['jenis_izin_id'],$jenis_izin)) $tampil = 1;
				else $tampil = 0;
			}
			
			if($tampil){	
				$tampil2 = 1;
				if ($search5){
					if($search5 == $r['nama_proses']) $tampil2 = 1;
					else $tampil2 = 0;
				}
				
				if($tampil2){
					$tabels[$i]['<b>No.</b>']= $i;
					$tabels[$i]['<b>Nomor Permohonan</b>']= $r['no_permohonan'];
					$tabels[$i]['<b>Nama Pemohon</b>']= $r['nama'];
					$tabels[$i]['<b>Jenis Izin</b>']= $r['jenis_izin'];
					$tabels[$i]['<b>Jenis Permohonan</b>']= $r['jenis_permohonan'];
					$tabels[$i]['<b>Telp./HP. Pemohon</b>']= $contact; 
					$tabels[$i]['<b>Lokasi Izin</b>']= TRIM($r['lokasi_izin']);
					$tabels[$i]['<b>Status Proses Terakhir</b>']= $r['nama_proses'];
					//$tabels[$i]['<b>Nomor Izin</b>']= $r['no_izin'];
					//$tabels[$i]['<b>Tanggal Penetapan</b>']= $tgl2;
					//$tabels[$i]['<b>Akhir Masa Berlaku</b>']= $masaberlaku;
					$i++;
				}
			}
		}
		
	$pdf->ezTable($tabels,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>1,'fontSize'=>11,'titleFontSize'=>11,'xPos'=>0,'xOrientation'=>'right','rowGap'=>5,
						'cols'=>array('<b>No.</b>'=>array ( 'width'=>50, 'justification'=>'center'),
										'<b>Nomor Permohonan</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Nama Pemohon</b>'=>array ( 'width'=>150, 'justification'=>'left'),
										'<b>Jenis Izin</b>'=>array ( 'width'=>100, 'justification'=>'left'),
										'<b>Jenis Permohonan</b>'=>array ( 'width'=>80, 'justification'=>'left'),
										'<b>Telp./HP. Pemohon</b>'=>array ( 'width'=>100, 'justification'=>'left'),
										'<b>Lokasi Izin</b>'=>array ( 'width'=>200, 'justification'=>'full'),//'full'
										'<b>Status Proses Terakhir</b>'=>array ( 'width'=>100, 'justification'=>'left')
										//'<b>Nomor Izin</b>'=>array ( 'width'=>100, 'justification'=>'left'),
										//'<b>Tanggal Penetapan</b>'=>array ( 'width'=>70, 'justification'=>'center'),
										//'<b>Akhir Masa Berlaku</b>'=>array ( 'width'=>70, 'justification'=>'center')
						)
				) );
		
    //$lurah = mysql_fetch_array(mysql_query("SELECT*FROM konfigurasi"));
	
	$pdf->ezSetDy(-20,'makeSpace');
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
    //$pdf->ezStream();
	$pdf->ezStream(array('Content-Disposition'=>'Rekap Permohonan Izin.pdf'));

?>