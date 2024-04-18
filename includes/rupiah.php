<?php 
//membuat format rupiah dengan PHP
function rupiah($angka){
	
	//$hasil_rupiah = "Rp. " . number_format($angka,2,',','.');
	$hasil_rupiah = number_format($angka,0,',','.');
	return $hasil_rupiah;
}
?>