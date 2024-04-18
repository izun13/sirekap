
<?php
/*require_once "../konfigurasi_db.php";
koneksi2_buka();

	$query=mysql_query("SELECT*FROM jenis_izin WHERE jenisizin_opd_id IS NOT NULL ORDER BY jenisizin_id asc");
	while($r=mysql_fetch_array($query)){
		$cek = mysql_num_rows(mysql_query("SELECT*FROM permohonan WHERE permohonan_jenisizin_id='$r[jenisizin_id]'"));
		if($cek==0){
			$ubah="update jenis_izin set jenisizin_opd_id='NULL' where jenisizin_id='$r[jenisizin_id]'";
			$hasil=mysql_query($ubah);
			if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
			else echo "Update Berhasil !"."<br>";
		}
	}
*/
?>