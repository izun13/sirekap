<?php
include"../../includes/parser-php-version.php";
require_once "../../jpgraph-4.4.1/src/jpgraph.php";
require_once "../../jpgraph-4.4.1/src/jpgraph_line.php";
require_once "../../jpgraph-4.4.1/src/jpgraph_bar.php";
require_once "../../jpgraph-4.4.1/src/jpgraph_pie.php";
require_once "../../jpgraph-4.4.1/src/jpgraph_pie3d.php";
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
$search5 = $search[5]; 
$search6 = $search[6]; 
$search7 = $search[7];

$tanggal1 = date('Y-m-d',strtotime($search1));
$tanggal2 = date('Y-m-d',strtotime('2022-04-30'));
$tanggal3 = date('Y-m-d',strtotime('2022-09-01'));

$awal_tgl = tgl2($search1);
$akhir_tgl = tgl2($search2);

$z=0;
$query_libur = mysql_query("SELECT tgl FROM libur_nasional");
while ($r_libur= mysql_fetch_array($query_libur)){
	$libur_nasional[$z] = $r_libur['tgl'];
	$z++;
}

if($tanggal1 <= $tanggal2){
	$tabel_jns = "SELECT jenis_izin FROM view_permohonan_izin WHERE del != '1'";	
	$tabel_jns .= " AND date(tgl_penetapan) >= '$search1' AND date(tgl_penetapan) <= '$search2'";
}
else{
	$tabel_jns = "SELECT jenis_izin FROM permohonan_izin_tte WHERE del != '1'";	
	$tabel_jns .= " AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";
}	

$tabel_jns .= " GROUP BY jenis_izin ORDER BY id desc";
 
$datajenis = array();
$data_a = array();
$data_b = array();
$query_jns = mysql_query($tabel_jns);
while ($r_jns= mysql_fetch_array($query_jns)){
	if($tanggal1 <= $tanggal2){
		$tabel = "SELECT*FROM view_permohonan_izin WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
		$tabel .= " AND date(tgl_penetapan) >= '$search1' AND date(tgl_penetapan) <= '$search2'  AND jenis_izin = '$r_jns[jenis_izin]'";
	}
	else{
		$tabel = "SELECT*FROM permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
		$tabel .= " AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'  AND jenis_izin = '$r_jns[jenis_izin]'";
	}	
		$query = mysql_query($tabel);
		
		$jml_a = 0;
		$jml_b = 0;	
		while ($r= mysql_fetch_array($query)){
			$id = $r["id"];
			
			$hari_kerja= 0;
			$tgl_awal = null;
			$tgl_akhir = null;			
			$tgl1 = "";
			$tgl2 = "";
			$tgl3 = "";
			$tgl4 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			//if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			$r_tgl = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
			if($r_tgl['tgl_penyerahan']) $tgl3 = tgl1($r_tgl['tgl_penyerahan']);
			$lokasi = TRIM($r["lokasi_izin"]);
						
			if($tanggal1 <= $tanggal2)$tgl_akhir = $r['tgl_penetapan'];
			else $tgl_akhir = $r['end_date'];
			//tgl cetak tanda terima berkas
			$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '33' "));
			$tgl_awal = $tglawal['end_date'];
			
			//tgl ttd izin
			//$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '40' "));
			//tgl penetapan
			//if(empty($tglakhir))$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '8' "));
			
			/*
			//tgl rekomendasi kesehatan
			$tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '7' "));
			//tgl rekomendasi diperindag
			if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '176' "));
			//tgl rekomendasi bpkad
			if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '108' "));
			if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '192' "));
			
			if(!empty($tgl_rekomendasi))$tgl_akhir = $tgl_rekomendasi['start_date'];
			
			if($search4 == 0)$tgl_akhir = $r['end_date'];
			*/
			
			if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
			if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
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
			
			//echo "awal : $tgl_awal, akhir : $tgl_akhir, hari kerja : $hari_kerja";
			/*
			if((!empty($tgl_rekomendasi)) and ($search4 == 1)){
				//tgl Verifikasi Rekomendasi disperindag
				$tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '165' "));
				//tgl Cetak Rekomendasi dkk dan disperindag
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '35' "));
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '7' "));
				//tgl Verifikasi status bayar bpkad
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '226' "));
				
				if(!empty($tgl_cetakrekomendasi))$tgl_awal = $tgl_cetakrekomendasi['end_date'];
				if( date('Y-m-d', strtotime($tgl_awal)) ==  $tgl_akhir) $tgl_awal = date('Y-m-d', strtotime('+1 days', strtotime($tgl_awal))); 
				
				$tgl_akhir = $r['end_date'];
				if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
				
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
			}
			*/
	
			//if($hari_kerja <= 5) $jml_a++;
			//if($hari_kerja > 5)	$jml_b++;
			
			$r_jns = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE jenis_izin = '$r[jenis_izin]'"));
			$opd_id = $r_jns['opd_id'];
			
			if($tanggal1 >= $tanggal3){
				if (($hari_kerja <= 3) and ($opd_id != 3)) $jml_a++;
				if (($hari_kerja <= 5) and ($opd_id == 3)) $jml_a++;
			}else{
				if ($hari_kerja <= 5) $jml_a++;
			}
			
			if($tanggal1 >= $tanggal3){
				if (($hari_kerja > 3) and ($opd_id != 3)) $jml_b++;
				if (($hari_kerja > 5) and ($opd_id == 3)) $jml_b++;
			}else{
				if ($hari_kerja > 5) $jml_b++;
			}
			
		}
			
    array_unshift($datajenis, $r_jns['jenis_izin']);
    array_unshift($data_a, $jml_a);
    array_unshift($data_b, $jml_b); 
	
	//echo $r["jenis_izin"]." = ".$r["jml"]."<br>";
}

$title = "Grafik Lama Proses Penerbitan Izin Tanggal ".$awal_tgl." s/d ".$akhir_tgl;
// membuat image dengan ukuran 400x200 px
$graph = new Graph(1200,900,"auto");    
$graph->SetScale("textlin");
 
// pada diagram batang ditampilkan value data
$bplot1 = new BarPlot($data_a);
 
// pada diagram batang ditampilkan value data
$bplot2 = new BarPlot($data_b);
 
// mengelompokkan grafik batang berdasarkan pria dan wanita
$gbplot = new GroupBarPlot(array($bplot1,$bplot2));
$graph->Add($gbplot);

// menampilkan diagram batang warna hijau
$bplot1->SetFillColor("green");
// menampilkan diagram batang untuk merah
$bplot2->SetFillColor("red");

//menampilkan value
$bplot1->value->show();
$bplot1->value->SetFont(FF_FONT1);
//$bplot1->value->SetAngle(45);
$bplot1->value->SetColor("black","navy");

$bplot2->value->show();
$bplot2->value->SetFont(FF_FONT1);
//$bplot2->value->SetAngle(45);
$bplot2->value->SetColor("black","navy");

// membuat legend untuk keterangan pria dan wanita
$bplot1->SetLegend("Sesuai SOP");
$bplot2->SetLegend("Melebihi SOP");
$graph->legend->Pos(0.05,0.5,"right","center");
 
// mengatur margin image
//$graph->img->SetMargin(40,110,20,40);
 
// menampilkan title grafik dan nama masing-masing sumbu
$graph->title->Set($title);
$graph->xaxis->title->Set("Jenis Izin");
//$graph->yaxis->title->Set("Jumlah");
 
// menampilkan nama negara ke sumbu x
$graph->xaxis->SetTickLabels($datajenis);
 
// format font title grafik
$graph->title->SetFont(FF_FONT1,FS_BOLD);
 
// menampilkan efek shadow pada image
$graph->SetShadow();
$graph->Set90AndMargin(350,40,50,20);
 
// menampilkan image ke browser
$graph->Stroke();
?>