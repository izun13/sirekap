<?php
include"../../includes/parser-php-version.php";
error_reporting(1);
require('../../fpdf17/pdf_mc_table.php');
require_once "../../includes/tanggal.php";
// Koneksi ke database dan tampilkan datanya
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();

$id = $_GET["id"];

	$pdf = new PDF_MC_Table('p','mm','folio');
	// membuat halaman baru
	$pdf->AddPage();
	$pdf->SetMargins(15,10);
	// setting jenis font yang akan digunakan
	$pdf->SetFont('times','B',13);
	// mencetak string 
			//put logo
	$pdf->Image('../../images/logo.jpg',15,10,20);
	$pdf->Cell(210,6,'PEMERINTAH KOTA MAGELANG',0,1,'C');
	$pdf->Cell(210,6,'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU',0,1,'C');
	$pdf->SetFont('times','',12);
	$pdf->Cell(210,6,'Jl. Veteran No.7 Magelang, Magelang Tengah, Kota Magelang, Jawa Tengah 56117',0,1,'C');
	$pdf->Cell(210,6,'Telp. (0293) 314663 http://dpmptsp.magelangkota.go.id',0,1,'C');
	$pdf->Cell(0, 1, " ", "B");
	$pdf->Ln(5);
	$pdf->SetFont('times','B',12);
	$pdf->Cell(210,6,"RINCIAN PENERBITAN PERIZINAN",0,1,'C');
	// Memberikan space kebawah agar tidak terlalu rapat
	$pdf->Ln(5);
	
	$pdf->SetFont('times','',10);

	// libur nasional
	$z=0;
	$query_libur = mysql_query("SELECT tgl FROM libur_nasional");
	while ($r_libur= mysql_fetch_array($query_libur)){
		$libur_nasional[$z] = $r_libur['tgl'];
		$z++;
	}
	
	$tabel = "SELECT*FROM permohonan_izin_penetapan WHERE id = '$id' ";	
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
	//$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '40' "));
	//$tgl_akhir = $tglakhir['end_date'];
	
	$tgl_awal = $tglawal['end_date'];
	//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
	$tgl_akhir = $r['end_date'];
	//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");
	
	if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
	if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
	
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
	
	
	$hari_kerja = $hari_kerja-1;
	if($hari_kerja == 0) $hari_kerja = 1;
			
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
	"No. Identitas","Telp./HP. Pemohon","Tanggal Terima Berkas","Nomor Izin","Tanggal Pengesahan","Lama Proses","Tanggal Penyerahan","Lokasi Izin");
	
	$data = array($tgl1,$r['no_permohonan'],$r['jenis_izin'],$r['jenis_permohonan'],$r['nama'],$r['tipe_identitas']."-".$r['no_identitas'],$contact,
	$tgl4,$r['no_izin'],$tgl2,$hari_kerja.' hari',$tgl3,TRIM($r['lokasi_izin']));
	
	for($y=0;$y<count($header);$y++){
		$pdf->Cell(60,6,$header[$y],0,0,'L');
		$pdf->Cell(5,6,":",0,0,'L');
		$pdf->Cell(100,6,$data[$y],0,1,'L');
	}
	
	$pdf->Ln(5);
	//set width for each column (6 columns)
	$pdf->SetWidths(Array(10,60,30,30,30,30));
	$pdf->SetAligns(Array('C','L','C','C','C','C'));
	//set line height. This is the height of each lines, not rows.
	$pdf->SetLineHeight(5);
	$pdf->SetFont('times','B',10);
	
	$pdf->Cell(10,6,'No.',1,0,'C');
	$pdf->Cell(60,6,'Nama Proses',1,0,'L');
	$pdf->Cell(30,6,'Status',1,0,'C');
	$pdf->Cell(30,6,'Tanggal Mulai',1,0,'C');
	$pdf->Cell(30,6,'Tanggal Selesai',1,0,'C');
	$pdf->Cell(30,6,'Lama Proses',1,1,'C');
	
	$pdf->SetFont('times','',10);
	
	$query = mysql_query("SELECT*FROM proses_permohonan WHERE permohonan_izin_id = '$id' order by id asc");
	$i = 1;
	$tgl_awal_ttl = null;
	$tgl_akhir_ttl = null;
	while ($r = mysql_fetch_array($query)){
		$hari_kerja = 0;
		$tgl1 = "";
		$tgl2 = "";
		$hari = "";
		$jam = "";
		$menit = "";
		$detik = "";
		
		$tgl_awal = $r['start_date'];
		//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
		$tgl_akhir = $r['end_date'];
		if($r["jenis_proses_id"] == 40)$tgl_akhir = $r['end_date'];
		//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");
			
		if($tgl_awal != null)$tgl1 = tgl1($tgl_awal);
		if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
		if(($tgl_awal != null) and ($tgl_akhir != null)){
			$awal=strtotime($tgl_awal);
			$akhir=strtotime($tgl_akhir);
			$tgl_awal2 = date('Y-m-d', strtotime($tgl_awal));
			$tgl_akhir2 = date('Y-m-d', strtotime($tgl_akhir));
			$awal2=strtotime($tgl_awal2);
			$akhir2=strtotime($tgl_akhir2);
				
			for ($x=$awal2; $x <= $akhir2; $x += (60 * 60 * 24)) {
				$i_date=date("Y-m-d",$x);
				if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
					$hari_kerja++;
				}
			}
			if($hari_kerja)$hari = $hari_kerja." hari";
			
			if($tgl1 == $tgl2){
				$diff  = $akhir - $awal;
				$jam   = floor($diff / (60 * 60));
				$menit = $diff - ( $jam * (60 * 60) );
				$menit = floor( $menit / 60 );
				$detik = $diff % 60;
				$hari = $jam." jam";
				if($jam == 0)$hari = $menit." menit";
				if($menit == 0)$hari = $detik." detik";
			}
		}
		
		if($r["jenis_proses_id"] == 2)$tgl_awal_ttl = $r['end_date'];
		if($r["jenis_proses_id"] == 40)$tgl_akhir_ttl = $r['end_date'];
		
		$pdf->Row(Array(
						$i,
						$r['nama_proses'],
						$r['status'],
						$tgl1,
						$tgl2,
						$hari,
					));
		
		$i++;
	}
		
		$hari_kerja = 0;
		if(($tgl_awal_ttl != null) and ($tgl_akhir_ttl != null)){
			$tgl_awal = date('Y-m-d', strtotime($tgl_awal_ttl));
			$tgl_akhir = date('Y-m-d', strtotime($tgl_akhir_ttl));
			$awal=strtotime($tgl_awal);
			$akhir=strtotime($tgl_akhir);
				
			for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
				$i_date=date("Y-m-d",$x);
				if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
					$hari_kerja++;
				}
			}
		}
	

	$hari_kerja = $hari_kerja-1;
	if($hari_kerja == 0) $hari_kerja = 1;
			
	$pdf->SetWidths(Array(160,30));
	$pdf->SetAligns(Array('C','C'));	
	$pdf->SetLineHeight(6);	
	$pdf->SetFont('times','B',10);
	$pdf->Row(Array(
						'TOTAL (Lama Proses dari Tanda Terima Berkas s/d Penetapan Izin)',
						$hari_kerja.' hari'
					));

$pdf->Output('Rincian Penerbitan izin.pdf','D');
?>