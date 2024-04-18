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

$tanggal1 = date('Y-m-d',strtotime($search1));
$tanggal2 = date('Y-m-d',strtotime('2022-04-30'));

if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
$tgl_now = tgl2(date("Y-m-d"));

$jns_izin = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE id='$search3'"));

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
if($search3)$pdf->Cell(306,6,STRTOUPPER($jns_izin['jenis_izin']),0,1,'C');
else $pdf->Cell(306,6,"DAFTAR PENERBITAN PERIZINAN",0,1,'C');
$pdf->Cell(306,6,"BULAN ".STRTOUPPER(bulantahun($search2)),0,1,'C');

// Memberikan space kebawah agar tidak terlalu rapat
$pdf->Ln(5);


//make new object
//set width for each column (6 columns)
$pdf->SetWidths(Array(10,70,40,100,50,40));
//set line height. This is the height of each lines, not rows.
$pdf->SetLineHeight(5);

// Tabel
					
$pdf->SetFont('times','B',10);
$pdf->Cell(10,6,'No.',1,0,'C');
$pdf->Cell(70,6,'Nama Pemohon',1,0,'C');
$pdf->Cell(40,6,'Telp/Hp.',1,0,'C');
$pdf->Cell(100,6,'Lokasi Izin',1,0,'C');
$pdf->Cell(50,6,'Nomor Izin',1,0,'C');
$pdf->Cell(40,6,'Tanggal Penerbitan',1,1,'C');
//$pdf->Cell(40,6,'Akhir Masa Berlaku',1,1,'C');

$pdf->SetFont('times','',10);
	
    // Query untuk merelasikan tabel
    
	$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
	);  
		
	//sebelum TTE bulan mei
	//if($tanggal1 <= $tanggal2)$sumber = "https://sicantik.go.id/api/TemplateData/keluaran/27646.json";		
	$sumber = "https://sicantik.go.id/api/TemplateData/keluaran/27646.json";
	//setelah TTE
	//else $sumber = "https://sicantik.go.id/api/TemplateData/keluaran/36014.json";
	
	$sumber .= "?key1='$search1'&key2='$search2'";
	//echo $sumber;
	$konten = file_get_contents($sumber, false, stream_context_create($arrContextOptions));
	$data = json_decode($konten, true);
	$i = 1;
		foreach ($data["data"]["data"] as $key=>$r) {
			$tgl1 = "";
			$tgl2 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			if($r['tgl_penetapan']) $tgl2 = tgl1($r['tgl_penetapan']);
			if(( $r['end_date'] != null ) and ($r['end_date'] != "0000-00-00")) $tgl2 = tgl1($r['end_date']);
			
			//$masaberlaku = $r["tgl_akhir_izin"];
			$masaberlaku = $r["masa_berlaku"];
			//if($masaberlaku == null) $masaberlaku = $r["tgl_akhir_str"];
			//if($masaberlaku == null) $masaberlaku = $r["tgl_surat_pengantar"];
			//if($masaberlaku == null) $masaberlaku = $r["tgl_permohonan_perusahaan"];
			//if($masaberlaku == null) $masaberlaku = $r["tanggal_jatuh_tempo"];
			//$masaberlaku = tgl1($masaberlaku);
			
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
			
			if($tampil){	
				
				$tampil2 = 1;
				if ($search4 == 1){
					if($r['no_izin'] != null) $tampil2 = 1;
					else $tampil2 = 0;
				}
				if ($search4 == 2){
					if($r['no_izin'] == null) $tampil2 = 1;
					else $tampil2 = 0;
				}
				$lokasi = TRIM($r['lokasi_izin']);
				if($tampil2){
					//$sumber2 = "https://sicantik.go.id/api/TemplateData/keluaran/39413.json?key='$r[id]'";
					//echo $sumber;
					//$konten2 = file_get_contents($sumber2, false, stream_context_create($arrContextOptions));
					//$data2 = json_decode($konten2, true);
					//if($masaberlaku == null)$masaberlaku = $data2["data"]["data"][0]["tgl_akhir_izin"];
					//$masaberlaku = tgl1($masaberlaku);
					
					$pdf->Row(Array(
						$i,
						$r['nama'],
						$contact,
						TRIM($r["lokasi_izin"]),
						$r['no_izin'],
						$tgl2,
						//$masaberlaku,
					));
	
					$i++;
				}
			}
		}
	

$pdf->Ln(5);
$y=$pdf->GetY();	
if($y > 160) $pdf->SetAutoPageBreak(true,50);	

$pdf->SetFont('times','B',11);
//$pdf->Cell(500,5,'Posisi Y : '.$y,0,1,'C');
$pdf->Cell(500,5,'Koordinator Pelayanan Perizinan dan',0,1,'C');
$pdf->Cell(500,5,'Non Perizinan',0,1,'C');
$pdf->Ln(15);
$pdf->Cell(500,5,'VIVI ERI SETYOWATI, SE.',0,1,'C');
$pdf->Cell(500,5,'NIP. 19760524 199903 2 004',0,1,'C');
//$pdf->Image('../../images/ttd.png',10,10,20);

$pdf->Output('Rekap Penerbitan izin.pdf','D');
?>