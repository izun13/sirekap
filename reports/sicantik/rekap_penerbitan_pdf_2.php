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
$opd_id = $search0;

$tanggal1 = date('Y-m-d',strtotime($search1));
$tanggal2 = date('Y-m-d',strtotime('2022-04-30'));

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
if($search3)$pdf->Cell(306,6,"DAFTAR ".STRTOUPPER($jns_izin['jenis_izin']),0,1,'C');
else $pdf->Cell(306,6,"DAFTAR PENERBITAN PERIZINAN",0,1,'C');
if($search1 != NULL or $search2 != NULL)$pdf->Cell(306,6,"Dari Tanggal : ".tgl1($search1)." s/d : ".tgl1($search2),0,1,'C');

// Memberikan space kebawah agar tidak terlalu rapat
$pdf->Ln(5);


//make new object
//set width for each column (6 columns)
$pdf->SetWidths(Array(10,50,50,30,40,70,40,20));
//set line height. This is the height of each lines, not rows.
$pdf->SetLineHeight(5);
				
// Tabel
//Title					
$pdf->SetFont('times','B',10);
$pdf->SetAligns(Array('C','C','C','C','C','C','C','C'));
$pdf->Row(Array(	'No.',
					'Nama Pemohon',
					'Jenis Izin',
					'Jenis Permohonan',
					'Telp/Hp.',
					'Lokasi Izin',
					'Nomor Izin',
					'Tanggal Penetapan',
					//'Akhir Masa Berlaku'
				));
//Isi Tabel					
$pdf->SetAligns(Array('C','L','L','C','L','L','L','C'));
$pdf->SetFont('times','',10);   

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
				if ($search4 == 1){
					if($r['no_izin'] != null) $tampil2 = 1;
					else $tampil2 = 0;
				}
				if ($search4 == 2){
					if($r['no_izin'] == null) $tampil2 = 1;
					else $tampil2 = 0;
				}
				
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
						$r['jenis_izin'],
						$r['jenis_permohonan'],
						$contact,
						TRIM($r['lokasi_izin']),
						$r['no_izin'],
						$tgl2,
						//$masaberlaku,
					));
					
					$i++;
				}
			}
		}
		
	

$pdf->Output('Rekap Penerbitan izin.pdf','D');
?>