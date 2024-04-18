<body>
<?php
include"../../includes/parser-php-version.php";
//require_once "../../includes/tanggal.php";

// Koneksi ke database dan tampilkan datanya
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();

$search = $_GET["send"];

	$tabel = "SELECT SUM(tambah_investasi) AS nilai,sektor FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND tambah_investasi != '0' AND 
			tgl_verifikasi LIKE '%$search%' GROUP BY sektor_id ORDER BY nilai DESC";
	
	$response = array();
	$response["data"] = array();
	$i = 1;
	$query = mysql_query($tabel);
	while ($r= mysql_fetch_array($query)){
		
		$h['sektor'] = $r['sektor'];
		$h['nilai'] = $r['nilai'];
		array_push($response["data"], $h);
		
		$i++;
	}
	
	echo json_encode($response);	
?>
</body>
