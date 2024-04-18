<?php
include "../../includes/parser-php-version.php";
//require_once "../../includes/tanggal.php";
//require_once "../../includes/rupiah.php";
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();	
		
error_reporting(E_ALL);

$search = explode(";",$_GET["send"]);
$search1 = $search[0]; 
$search2 = $search[1]; 

$tabel = "SELECT*FROM oss_rba_nibs WHERE day_of_tanggal_terbit_oss >= '$search1' AND day_of_tanggal_terbit_oss <= '$search2'";
$tabel .= " ORDER BY nib asc";	
$query = mysql_query($tabel);

//data array
$array=array();
while($data=mysql_fetch_assoc($query)) $array[]=$data; 
 
//mengubah data array menjadi json
 echo json_encode($array);
?>
