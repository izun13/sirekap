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
$pdf->Cell(306,6,"LAMA PROSES PENERBITAN IZIN MAKAM",0,1,'C');
$pdf->Cell(306,6,"BULAN ".STRTOUPPER(bulantahun($search2)),0,1,'C');

// Memberikan space kebawah agar tidak terlalu rapat
$pdf->Ln(5);


//make new object
//set width for each column (6 columns)
$pdf->SetWidths(Array(10,40,40,50,50,20,20,20,20,20));
//set line height. This is the height of each lines, not rows.
$pdf->SetLineHeight(5);

// Tabel
					
$pdf->SetFont('times','B',10);
$pdf->SetAligns(Array('C','C','C','C','C','C','C','C','C','C'));
$pdf->Row(Array(
	'No.',
	'Nama Pemohon',
	'Nama Jenazah',
	'Alamat',
	'Jenis Makam',
	'Blok',
	'Tanggal Daftar',
	'Tanggal Konfirmasi',
	'Tanggal Penerbitan Izin',
	'Lama Proses'
));
					
$pdf->SetFont('times','',10);
$pdf->SetAligns(Array('C','L','L','L','L','C','C','C','C','C'));
	
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
		
		$tahun = date('Y',strtotime($search2));
		$z=0;
		$query_libur = mysql_query("SELECT tgl FROM libur_nasional WHERE tgl LIKE '%$tahun%'");
		while ($r_libur= mysql_fetch_array($query_libur)){
			$libur_nasional[$z] = $r_libur['tgl'];
			$z++;
			//echo $r_libur['tgl'].",";
		}
		
		$jum_hari_kerja = 0;
		foreach ($data as $key=>$r) {
			
			$hari_kerja= 0;				
			$alamat = TRIM($r["alamat"])." ".$r["rt"]." ".$r["rw"]." ".$r["desa"]." ".$r["kec"]." ".$r["kota"];
			
			//$tampil = 0;
			//$tanggal1 = date('Y-m-d',strtotime($r['tte']));
			//if(($tanggal1 >= $search1) and ($tanggal1 <= $search2)) $tampil = 1;
			//if($tampil){

				$tgl_awal = $r['konfirm'];
				$tgl_akhir = $r['tte'];			
				//jumlah hari kerja
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
				
				$tampil2 = 0;
				if($search3 == 1){
					if ($hari_kerja <= 3) $tampil2 = 1;				
				}elseif($search3 == 2){					
					if ($hari_kerja > 3) $tampil2 = 1;	
				}else $tampil2 = 1;	
				
				if($tampil2){
					$pdf->Row(Array(
						$i,
						$r['pemohon'],
						$r['nama'],
						$alamat,
						$r['jasa'],
						$r['blok'],
						$r['daftar'],
						$r['konfirm'],
						$r['tte'],
						$hari_kerja
					));
					$jum_hari_kerja += $hari_kerja;
					$i++;
				}
			//}
		}
	
		$rata_hari_kerja = $jum_hari_kerja/($i-1);
		$rata_hari_kerja = number_format($rata_hari_kerja,2,',','.');
		
		$pdf->SetWidths(Array(270,20));
		$pdf->SetLineHeight(5);
		$pdf->SetFont('times','B',10);
		$pdf->SetAligns(Array('C','C'));
		$pdf->Row(Array(
			'RATA-RATA',
			$rata_hari_kerja
		));
	
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

$pdf->Output('Rekap Lama Proses Izin Makam.pdf','D');
?>