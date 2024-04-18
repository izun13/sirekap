<?php
include"../../includes/parser-php-version.php";
//require_once "../../includes/tanggal.php";

// Koneksi ke database dan tampilkan datanya
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();

$search = explode(";",$_GET["send"]);
$search1 = $search[0]; 
$search2 = $search[1];
		
		$response = array();
		$response["data"] = array();
				
		$tabel = "SELECT count(nib) AS jumlah,uraian_jenis_perusahaan FROM oss_rba_nibs WHERE date(day_of_tanggal_terbit_oss) >= '$search1' AND date(day_of_tanggal_terbit_oss) <= '$search2'";	
		$tabel .= " GROUP BY uraian_jenis_perusahaan";
		// Nampilin Data
		$query = mysql_query($tabel);
  
		while ($r= mysql_fetch_array($query)){
						
			$h['jenis_perusahaan'] = $r['uraian_jenis_perusahaan'];
			$h['jumlah'] = $r['jumlah'];
			array_push($response["data"], $h);
			
		}
			
			echo json_encode($response);
			
?>