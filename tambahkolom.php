<?php
mysql_connect("localhost","root","");
$con=mysql_select_db("db_perizinan");

//$kolom = array("id_proyek","nib","npwp_perusahaan","nama_perusahaan","uraian_status_penanaman_modal","uraian_jenis_perusahaan","uraian_risiko_proyek","uraian_skala_usaha","alamat_usaha","kecamatan_usaha","kbli","judul_kbli");
$kolom = array("id_proyek","nib","npwp_perusahaan","nama_perusahaan","uraian_status_penanaman_modal","uraian_jenis_perusahaan","uraian_risiko_proyek","uraian_skala_usaha","alamat_usaha","kecamatan_usaha","kelurahan_usaha",
"longitude","latitude","kbli","judul_kbli","kl_sektor_pembina","nama_user","nomor_identitas_user","email","nomor_telp","jumlah_investasi","jumlah_investasi","mesin_peralatan","mesin_peralatan_impor","pembelian_pematangan_tanah",
"bangunan_gedung","modal_kerja","lain_lain","jumlah_investasi","tki");

for ($x=0;$x<count($kolom);$x++) {
	$col = strtolower($kolom[$x]);
	//$field = "ALTER TABLE oss_rba_proyek ADD $col VARCHAR (20)";
	//$hasil=mysql_query($field);
}

if($hasil) echo "Tambah kolom berhasil !"; 
else echo "Tambah kolom gagal :".mysql_error();
?>