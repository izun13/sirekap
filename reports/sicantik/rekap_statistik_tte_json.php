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
				
		$tabel = "SELECT count(jenis_izin) as jumlah,jenis_izin FROM permohonan_izin_penetapan WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND status_penetapan != 'ditolak'";	
		$tabel .= " AND date(tgl_penetapan) >= '$search1' AND date(tgl_penetapan) <= '$search2'";
		
		$tabel .= " GROUP BY jenis_izin ORDER BY jumlah desc";
		// Nampilin Data
		$query = mysql_query($tabel);
  
		while ($r= mysql_fetch_array($query)){
						
			$h['jenis_izin'] = $r['jenis_izin'];
			$h['jumlah_izin'] = $r['jumlah'];
			array_push($response["data"], $h);
			
		}
			
			echo json_encode($response);
			
?>