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
//$search4 = $search[4];

//$tanggal1 = date('Y-m-d',strtotime($search1));
//$tanggal2 = date('Y-m-d',strtotime('2022-04-30'));

if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
$tgl_now = tgl2(date("Y-m-d"));

//$jns_izin = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE id='$search3'"));

$pdf = new PDF_MC_Table('l','mm','folio');
// membuat halaman baru
$pdf->AddPage();
$pdf->SetMargins(20,20,20);
// setting jenis font yang akan digunakan
$pdf->SetFont('times','B',14);
// mencetak string 
		//put logo
$pdf->Image('../../images/logo.jpg',20,10,20);
$pdf->Cell(306,6,'PEMERINTAH KOTA MAGELANG',0,1,'C');
$pdf->Cell(306,6,'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU (DPMPTSP)',0,1,'C');
$pdf->SetFont('times','B',12);
$pdf->Cell(306,6,'Jl. Veteran No.7, Magelang, Magelang Tengah, Kota Magelang, Jawa Tengah 56117',0,1,'C');
$pdf->Cell(306,6,'Telp. (0293) 314663 http://dpmptsp.magelangkota.go.id',0,1,'C');
$pdf->Cell(0, 1, " ", "B");
$pdf->Ln(5);
$pdf->Cell(306,6,"DAFTAR PENERBITAN IZIN MAKAM",0,1,'C');
$pdf->Cell(306,6,"BULAN ".STRTOUPPER(bulantahun($search2)),0,1,'C');

// Memberikan space kebawah agar tidak terlalu rapat
$pdf->Ln(5);


//make new object
//set width for each column (6 columns)
$pdf->SetWidths(Array(10,40,40,20,20,70,50,20,20));
//set line height. This is the height of each lines, not rows.
$pdf->SetLineHeight(5);

// Tabel
					
$pdf->SetFont('times','B',10);
$pdf->SetAligns(Array('C','C','C','C','C','C','C','C','C'));
$pdf->Row(Array(
	'No.',
	'Nama Pemohon',
	'Nama Jenazah',
	'Jenis Kelamin',
	'Agama',
	'Alamat',
	'Jenis Makam',
	'Blok',
	'Tanggal Penerbitan Izin'
));
					
$pdf->SetFont('times','',10);
$pdf->SetAligns(Array('C','L','L','C','C','L','L','C','C'));
	
    // Query untuk merelasikan tabel
    
	$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
	);  
		
	//$sumber = "https://dlh.magelangkota.go.id/simpel/get-json.php?s=2";
	$sumber = "https://dlh.magelangkota.go.id/simpel/get-json.php?p=tte";
	
	$sumber .= "&a=$search1&z=$search2";
	//echo $sumber;
	$konten = file_get_contents($sumber, false, stream_context_create($arrContextOptions));//
	$data = json_decode($konten, true);
	$i = 1;
		foreach ($data as $key=>$r) {
							
			//$tgl1 = "";
			//$tgl2 = "";
			//$tgl3 = "";
			//$tgl4 = "";
			$link = "";
			
			//if($r['daftar']) $tgl1 = tgl1($r['daftar']);
			//if($r['lahir']) $tgl2 = tgl1($r['lahir']);
			//if($r['wafat']) $tgl3 = tgl1($r['wafat']);
			//if($r['tte']) $tgl4 = tgl1($r['tte']);
			$alamat = TRIM($r["alamat"])." ".$r["rt"]." ".$r["rw"]." ".$r["desa"]." ".$r["kec"]." ".$r["kota"];
			
			$tampil = 1;
			if($search3 != ""){
				$tampil = 0;
				if(stristr($r['jasa'],$search3)) $tampil = 1;
			}
			if($tampil){								
					$pdf->Row(Array(
						$i,
						$r['pemohon'],
						$r['nama'],
						$r['gender'],
						$r['agama'],
						$alamat,
						$r['jasa'],
						$r['blok'],
						$r['tte']
					));
	
					$i++;
				
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

$pdf->Output('Rekap Izin Makam.pdf','D');
?>