<?php
include"../../includes/parser-php-version.php";
require_once "../../includes/tanggal.php";

// Koneksi ke database dan tampilkan datanya
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();

$search = $_GET["send"];
		
		$response = array();
		$response["data"] = array();

		$jum = 0;
		$j = 1;
		for($x=0;$x<12;$x++){			
			$bulan = $search."-".$j;
			if(strlen($j)==1)$bulan = $search."-0".$j;
			$query = "SELECT*FROM permohonan_izin_penetapan WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND tgl_penetapan LIKE '%$bulan%'";
			
			$jum = mysql_num_rows(mysql_query($query));
			$jumlah[$x] = $jum ;
			
			
			$h['bulan'] = $NAMA_BULAN[$j]." ".$search;
			$h['jumlah_izin'] = $jum;
			array_push($response["data"], $h);
			
			$j++;
		}
		
			
		echo json_encode($response);
			
?>