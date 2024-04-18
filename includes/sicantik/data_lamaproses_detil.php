<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"inputField1",
			dateFormat:"%Y-%m-%d"
		});
		new JsDatePick({
			useMode:2,
			target:"inputField2",
			dateFormat:"%Y-%m-%d"
		});
	};
</script>
<body>
<?php
$recpage = 20;
$to_page = "sicantik2";
	
	// libur nasional
	$z=0;
	$query_libur = mysql_query("SELECT tgl FROM libur_nasional");
	while ($r_libur= mysql_fetch_array($query_libur)){
		$libur_nasional[$z] = $r_libur['tgl'];
		$z++;
	}
		
	$tabel = "SELECT*FROM permohonan_izin_penetapan WHERE id = '$id' ";	
	$query = mysql_query($tabel);
	$r = mysql_fetch_array($query);
	
	$hari_kerja= 0;
	$tgl1 = "";
	$tgl2 = "";
	$tgl3 = "";
	$tgl4 = "";
	
	if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
	if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
	$r_tgl = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
	if($r_tgl['tgl_penyerahan']) $tgl3 = tgl1($r_tgl['tgl_penyerahan']);
	
	$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '2' "));
	//$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '40' "));
	//$tgl_akhir = $tglakhir['end_date'];
	
	$tgl_awal = $tglawal['end_date'];
	//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
	$tgl_akhir = $r['tgl_signed_report'];
	//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");
	
	if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
	if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
	
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
	
	$hari_kerja = $hari_kerja-1;
	if($hari_kerja == 0) $hari_kerja = 1;
			
	$tlp = null;
	$hp = null;
	if(($r['no_tlp'] != "") and ($r['no_tlp'] != "-"))$tlp = $r['no_tlp'];
	if(($r['no_hp'] != "") and ($r['no_hp'] != "-"))$hp = $r['no_hp'];
	$contact = "";
	if($tlp != null) $contact = $tlp;
	if($hp != null) $contact = $hp;
	if(($tlp != null) and ($hp != null)) $contact = $tlp." / ".$hp;
	if(($tlp != null) and ($hp != null) and ($tlp == $hp)) $contact = $tlp;
?>
<div class="judul">Detil Perizinan Reklame Sicantik</div>

<form action='<?php echo "?send=$to_page/$id/$starting/$search";?>' method='post' autocomplete="off"> 
<table class="tabelbox3">
<tr><td><b>Tanggal Pengajuan</b></td><td>:</td><td><?php echo $tgl1; ?></td></tr>
<tr><td><b>Nomor Permohonan</b></td><td>:</td><td><?php echo $r['no_permohonan']; ?></td></tr>
<tr><td><b>Jenis Izin</b></td><td>:</td><td><?php echo $r['jenis_izin']; ?></td></tr>
<tr><td><b>Jenis Permohonan</b></td><td>:</td><td><?php echo $r['jenis_permohonan']; ?></td></tr>
<tr><td><b>Nama Pemohon</b></td><td>:</td><td><?php echo $r['nama']; ?></td></tr>
<tr><td><b>No. Identitas</b></td><td>:</td><td><?php echo $r['tipe_identitas']."-".$r['no_identitas']; ?></td></tr>
<tr><td><b>Telp./HP. Pemohon</b></td><td>:</td><td><?php echo $contact; ?></td></tr>
<tr><td><b>Tanggal Terima Berkas</b></td><td>:</td><td><?php echo $tgl4; ?></td></tr>
<tr><td><b>Nomor Izin</b></td><td>:</td><td><?php echo $r['no_izin']; ?></td></tr>
<tr><td><b>Tanggal Penetapan</b></td><td>:</td><td><?php echo $tgl2; ?></td></tr>
<tr><td><b>Lama Proses</b></td><td>:</td><td><?php echo $hari_kerja; ?></td></tr>
<tr><td><b>Tanggal Penyerahan</b></td><td>:</td><td><?php echo $tgl3; ?></td></tr>
<tr><td><b>Lokasi Izin</b></td><td>:</td><td><?php echo TRIM($r['lokasi_izin']); ?></td></tr>

<tr><td colspan="3" align="right"><input type='submit' name='tombol' value='Kembali'></td></tr>
</table>
</form>
<?php
echo"&nbsp; <a href='reports/sicantik/rincian_lamaproses_pdf.php?id=$id'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
?>
<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th width="60"><b>ID Jenis Proses</b></th>
			<th><b>Nama Proses</b></th>
			<th><b>Diproses Oleh</b></th>
			<th><b>Tgl Diubah</b></th>
			<th><b>Status</b></th>	
			<th><b>Tanggal Mulai</b></th>	
			<th><b>Tanggal Selesai</b></th>
			<th><b>Lama Proses</b></th>
		</tr>
		<?php

	// Nampilin Data
	$query = mysql_query("SELECT*FROM proses_permohonan WHERE permohonan_izin_id = '$id' order by id asc");
	$i = 1;
	$tgl_awal_ttl = null;
	$tgl_akhir_ttl = null;
	while ($r = mysql_fetch_array($query)){
		$hari_kerja = 0;
		$tgl1 = "";
		$tgl2 = "";
		$hari = "";
		$jam = "";
		$menit = "";
		$detik = "";
		
		$tgl_awal = $r['start_date'];
		//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
		$tgl_akhir = $r['end_date'];
		if($r["jenis_proses_id"] == 40)$tgl_akhir = $r['tgl_signed_report'];
		//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");
			
		if($tgl_awal != null)$tgl1 = tgl1($tgl_awal);
		if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
		if(($tgl_awal != null) and ($tgl_akhir != null)){
			$awal=strtotime($tgl_awal);
			$akhir=strtotime($tgl_akhir);
			$tgl_awal2 = date('Y-m-d', strtotime($tgl_awal));
			$tgl_akhir2 = date('Y-m-d', strtotime($tgl_akhir));
			$awal2=strtotime($tgl_awal2);
			$akhir2=strtotime($tgl_akhir2);
				
			for ($x=$awal2; $x <= $akhir2; $x += (60 * 60 * 24)) {
				$i_date=date("Y-m-d",$x);
				if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
					$hari_kerja++;
				}
			}			
			if($hari_kerja)$hari = $hari_kerja." hari";
			
			if($tgl1 == $tgl2){
				$diff  = $akhir - $awal;
				$jam   = floor($diff / (60 * 60));
				$menit = $diff - ( $jam * (60 * 60) );
				$menit = floor( $menit / 60 );
				$detik = $diff % 60;
				$hari = $jam." jam";
				if($jam == 0)$hari = $menit." menit";
				if($menit == 0)$hari = $detik." detik";
			}
		}
		
		if($r["jenis_proses_id"] == 2)$tgl_awal_ttl = $r['end_date'];
		if($r["jenis_proses_id"] == 40)$tgl_akhir_ttl = $r['tgl_signed_report'];
			
		if($i % 2==0){
			echo "<tr class='cyan'>";
		}else{
			echo "<tr>";
		}
		
		$pegawai = mysql_fetch_array(mysql_query("SELECT jabatan FROM pegawai WHERE username = '$r[diproses_oleh]'"));
		if((preg_match("/bpkad/i",$pegawai['jabatan'])) or (preg_match("/dishub/i",$pegawai['jabatan'])) or (preg_match("/dkk/i",$pegawai['jabatan'])) or (preg_match("/disperindag/i",$pegawai['jabatan'])) or
			(preg_match("/kesehatan/i",$pegawai['jabatan'])) ) echo "<tr class='current'>";
			
		echo "<td align='center'>$i</td>";
		echo "<td align='left'>$r[jenis_proses_id]</td>";
		echo "<td align='left'>$r[nama_proses]</td>";
		echo "<td align='left'>$r[diproses_oleh]</td>";
		echo "<td align='left'>$r[tgl_diubah]</td>";
		echo "<td align='left'>$r[status]</td>";
		echo "<td align='left'>$tgl1</td>";
		echo "<td align='left'>$tgl2</td>";
		echo "<td align='left'>$hari</td>";
		echo "</tr>";
		$i++;	
	}
		
	$hari_kerja = 0;
	if(($tgl_awal_ttl != null) and ($tgl_akhir_ttl != null)){
		$tgl_awal = date('Y-m-d', strtotime($tgl_awal_ttl));
		$tgl_akhir = date('Y-m-d', strtotime($tgl_akhir_ttl));
		$awal=strtotime($tgl_awal);
		$akhir=strtotime($tgl_akhir);
				
		for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
			$i_date=date("Y-m-d",$x);
			if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
			$hari_kerja++;
			}
		}
	}
	
	$hari_kerja = $hari_kerja-1;
	if($hari_kerja == 0) $hari_kerja = 1;	
	
	echo "<tr>";
	echo "<th colspan='8' align='center'><b>TOTAL</b> \n(Lama Proses dari Menerima & Memeriksa Berkas s/d Penetapan Izin)</th>";
	echo "<th align='left'><b>$hari_kerja hari</b></th>";
?>
</table>
</body>
