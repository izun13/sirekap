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
$to_page = "sicantik4";

$tabel = "SELECT*FROM view_permohonan_izin WHERE id = '$id'";
$query = mysql_query($tabel);	
$r= mysql_fetch_array($query);
if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
//if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
$r_tgl = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
if($r_tgl['tgl_penyerahan']) $tgl3 = tgl1($r_tgl['tgl_penyerahan']);
$lokasi = TRIM($r["lokasi_izin"]);

//tgl cetak tanda terima berkas
$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '33' "));
//tgl penetapan izin
$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '40' "));

$tgl_awal = $tglawal['end_date'];
//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
$tgl_akhir = $tglakhir['end_date'];
//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");

if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);


$tlp = "";
$hp = "";		
if(($r['no_tlp'] != "") and ($r['no_tlp'] != "-") and ($r['no_tlp'] != "0"))$tlp = $r['no_tlp'];
if(($r['no_hp'] != "") and ($r['no_hp'] != "-") and ($r['no_hp'] != "0"))$hp = $r['no_hp'];
$contact = $tlp;
if($hp != "") $contact = $hp;
if(($tlp != "") and ($hp != "")) $contact = $tlp." / ".$hp;
if($tlp == $hp) $contact = $tlp;
?>
<div class="judul">Detil Perizinan Reklame Sicantik</div>

<form action='<?php echo "?send=$to_page/$id/$starting/$search";?>' method='post' autocomplete="off"> 
<table class="tabelbox3">
<?php
echo "<tr class='cyan'><td><b>ID</b></td><td>:</td><td>$r[id]</td></tr>";
echo "<tr><td><b>Nomor</b></td><td>:</td><td>$r[no_permohonan]</td></tr>";	
echo "<tr class='cyan'><td><b>Tanggal Pengajuan</b></td><td>:</td><td>$tgl1</td></tr>";	
echo "<tr><td><b>Jenis Izin</b></td><td>:</td><td>$r[jenis_izin]</td></tr>";
echo "<tr class='cyan'><td><b>Jenis Permohonan</b></td><td>:</td><td>$r[jenis_permohonan]</td></tr>";
echo "<tr><td><b>Nama Pemohon</b></td><td>:</td><td>$r[nama]</td></tr>";
echo "<tr class='cyan'><td><b>Nama Perusahaan</b></td><td>:</td><td>$r[nama_perusahaan]</td></tr>";
echo "<tr><td><b>Telp./HP. Pemohon</b></td><td>:</td><td>$contact</td></tr>";
echo "<tr class='cyan'><td><b>Nomor Izin</b></td><td>:</td><td>$r[no_izin]</td></tr>";
echo "<tr><td><b>Tanggal Penetapan/Pengesahan</b></td><td>:</td><td>$tgl2</td></tr>";
echo "<tr class='cyan'><td><b>Tanggal Penyerahan</b></td><td>:</td><td>$tgl3</td></tr>";	
?>
<tr><td colspan="3" align="right"><input type='submit' name='tombol' value='Kembali'></td></tr>
</table>
</form>
<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>Jumlah</b></th>
			<th><b>Ukuran</b></th>	
			<th><b>Jenis Reklame</b></th>	
			<th><b>Isi Reklame</b></th>
			<th><b>Lokasi pasang</b></th>
			<th><b>Titik Koordinat</b></th>
		</tr>
		<?php

		// Nampilin Data
		$query = mysql_query("SELECT*FROM data_teknis_reklame WHERE permohonan_izin_id = '$r[id]'");
		$i = 1;
		while ($r= mysql_fetch_array($query)){
			if($i % 2==0){
					echo "<tr class='cyan'>";
				}else{
					echo "<tr>";
				}
			echo "<td align='center'>$i</td>";
			echo "<td align='center'>$r[jumlah]</td>";
			echo "<td align='left'>$r[ukuran]</td>";
			echo "<td align='left'>$r[jenis_reklame]</td>";
			echo "<td align='left'>$r[isi_reklame]</td>";
			echo "<td align='left'>$r[lokasi_pasang]</td>";
			echo "<td align='left'>$r[titik_koordinat]</td>";
			echo "</tr>";
			$i++;	
		}
		?>
</table>
</body>
