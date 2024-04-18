<?php
error_reporting(1);
// Include file class.ezpdf dalam folder fungsiPDF
require_once "../../ezpdf/class.ezpdf.php";
require_once "../../includes/tanggal.php";
// Koneksi ke database dan tampilkan datanya
require_once "../../includes/koneksi.php";
$id = $_GET["id"];
	
    $pdf = new Cezpdf('FOLIO','potrait');

    // Set margin dan font
    $pdf->ezSetCmMargins(1,1,1.5,1);
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
	$pdf->ezText("<b>PEMERINTAH KOTA MAGELANG</b>",14,array('left'=>55,'justification'=>'center'));
	$pdf->ezText("<b>DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP)</b>",16,array('left'=>55,'justification'=>'center'));
	$pdf->ezSetDy(-4,'makeSpace');
	$pdf->ezText("Jl. Veteran No.7 Kota Magelang 56117 Telp.(0293) 314663",12,array('left'=>55,'justification'=>'center'));
	// Garis
    $pdf->line(40, 834, 570, 834);
    $pdf->line(40, 832, 570, 832);
        
	
	
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
	$pdf->ezText("<b><u>RINCIAN PERMOHONAN PERIZINAN</u></b>",14,array('justification'=>'center'));
	$pdf->ezSetDy(-10,'makeSpace');
    
	$tabel = "SELECT*FROM view_permohonan_izin WHERE id = '$id' ";	
	$query = mysql_query($tabel);
	$r = mysql_fetch_array($query);
	
	$hari_kerja= 0;
	$tgl1 = "";
	$tgl2 = "";
	$tgl3 = "";
	$tgl4 = "";
	
	if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
	if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
	$r_tgl = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
	if($r_tgl['tgl_penyerahan']) $tgl3 = tgl1($r_tgl['tgl_penyerahan']);
	
	$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '2' "));
	$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '8' "));
	
	$tgl_awal = $tglawal['end_date'];
	//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
	$tgl_akhir = $tglakhir['end_date'];
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
	}
	
	
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
	
	$header = array("Tanggal Pengajuan","Nomor Permohonan","Jenis Izin","Jenis Permohonan","Nama Pemohon",
	"No. Identitas","Telp./HP. Pemohon","Tanggal Terima Berkas","Nomor Izin","Tanggal Penetapan","Lama Proses","Tanggal Penyerahan","Lokasi Izin");
	for($y=0;$y<count($header);$y++){
	$tabels[$y][0]= $header[$y];
	$tabels[$y][1]= ":";
	}

	$data = array($tgl1,$r['no_permohonan'],$r['jenis_izin'],$r['jenis_permohonan'],$r['nama'],$r['tipe_identitas']."-".$r['no_identitas'],$contact,
	$tgl4,$r['no_izin'],$tgl2,$hari_kerja,$tgl3,TRIM($r['lokasi_izin']));
	for($y=0;$y<count($header);$y++){
	$tabels[$y][2]= $data[$y];
	}
	
	$pdf->ezTable($tabels,'','',
				array(	'shaded'=>0,'showLines'=>0,'showHeadings'=>0,'fontSize'=>10,'titleFontSize'=>10,'xPos'=>0,'xOrientation'=>'right','rowGap'=>2,
						'cols'=>array('0'=>array ( 'width'=>120, 'justification'=>'left'),
										'1'=>array ( 'width'=>15, 'justification'=>'center'),
										'2'=>array ( 'width'=>300, 'justification'=>'left')
						)
				) );
				
	
	$pdf->ezSetDy(-10,'makeSpace');	
	$libur_nasional=mysql_fetch_array(mysql_query("SELECT tgl FROM libur_nasional"));
	$query = mysql_query("SELECT*FROM proses_permohonan WHERE permohonan_izin_id = '$id' order by id asc");
	$i = 1;
	$tgl_awal_ttl = null;
	$tgl_akhir_ttl = null;
	while ($r = mysql_fetch_array($query)){
		$hari_kerja = 0;
		$tgl1 = "";
		$tgl2 = "";
		
		$tgl_awal = $r['start_date'];
		//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
		$tgl_akhir = $r['end_date'];
		//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");
			
		if($tgl_awal != null)$tgl1 = tgl1($tgl_awal);
		if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
		if(($tgl_awal != null) and ($tgl_akhir != null)){
			$awal=strtotime($tgl_awal);
			$akhir=strtotime($tgl_akhir);
				
			for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
				$i_date=date("Y-m-d",$x);
				if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
					$hari_kerja++;
				}
			}
		}
		
		if($r["jenis_proses_id"] == 2)$tgl_awal_ttl = $r['end_date'];
		if($r["jenis_proses_id"] == 8)$tgl_akhir_ttl = $r['end_date'];
			
		$tabels2[$i]['<b>No.</b>']= $i;
		$tabels2[$i]['<b>Nama Proses</b>']= $r['nama_proses'];
		$tabels2[$i]['<b>Status</b>']= $r['status']; 
		//$tabels2[$i]['<b>Id Jenis Proses</b>']= $r['jenis_proses_id']; 
		$tabels2[$i]['<b>Tanggal Mulai</b>']= $tgl1;     
		$tabels2[$i]['<b>Tanggal Selesai</b>']= $tgl2;
		$tabels2[$i]['<b>Lama Proses</b>']= $hari_kerja." hari";
		$i++;
	}
		
		$hari_kerja2 = 0;
		if(($tgl_awal_ttl != null) and ($tgl_akhir_ttl != null)){
			$awal=strtotime($tgl_awal_ttl);
			$akhir=strtotime($tgl_akhir_ttl);
				
			for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
				$i_date=date("Y-m-d",$x);
				if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
					$hari_kerja2++;
				}
			}
		}
		
		$tabels2[$i]['<b>Nama Proses</b>']= "<b>Total</b> \n(Lama Proses dari Menerima & Memeriksa Berkas s/d Penetapan Izin)";
		$tabels2[$i]['<b>Lama Proses</b>']= "<b>".$hari_kerja2." hari </b>";
		
	$pdf->ezTable($tabels2,'', '', 
				array(	'shaded'=>1,'showLines'=>1,'showHeadings'=>1,'fontSize'=>10,'titleFontSize'=>10,'xPos'=>50,'xOrientation'=>'right','rowGap'=>5,
						'cols'=>array('<b>No.</b>'=>array ( 'width'=>30, 'justification'=>'center'),
										'<b>Nama Proses</b>'=>array ( 'width'=>150, 'justification'=>'left'),
										'<b>Status</b>'=>array ( 'width'=>75, 'justification'=>'center'),
										'<b>Tanggal Mulai</b>'=>array ( 'width'=>60, 'justification'=>'center'),
										'<b>Tanggal Selesai</b>'=>array ( 'width'=>60, 'justification'=>'center'),
										'<b>Lama Proses</b>'=>array ( 'width'=>60, 'justification'=>'center')
						)
				) );
		
    //$lurah = mysql_fetch_array(mysql_query("SELECT*FROM konfigurasi"));
	
	//$pdf->ezSetDy(-20,'makeSpace');
	//$pdf->ezText('KEPALA DINAS',12,array('left'=>300,'justification'=>'center'));
	//$pdf->ezSetDy(-50,'makeSpace');
	//$pdf->ezText("<b>M. ABDUL AZIZ, SH.</b>",12,array('left'=>300,'justification'=>'center'));
	//$pdf->ezText("NIP. 19690724 199803 1 006",12,array('left'=>300,'justification'=>'center'));
		
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