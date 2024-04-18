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
koneksi1_tutup();
koneksi2_buka();

//$recpage = 20;
$to_page = "simpadu1";

/*$tabel = "SELECT*FROM view_permohonan_izin WHERE id = '$id'";
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
if($tlp == $hp) $contact = $tlp;*/
?>
<div class="judul">Detil Data Teknis Perizinan</div>

<form action='<?php echo "?send=$to_page/$id/$starting/$search";?>' method='post' autocomplete="off"> 
<table class="tabelbox3">
<?php
/*echo "<tr class='cyan'><td><b>ID</b></td><td>:</td><td>$r[id]</td></tr>";
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
*/
?>
<tr><td colspan="3" align="right"><input type='submit' name='tombol' value='Kembali'></td></tr>
</table>
</form>
<table class="tabelbox1" width="800">
		<?php
		$query = mysql_query("SELECT*FROM view_teknis_izin WHERE suratizindata_permohonan_id = '$id'");
		
		echo "<tr>";
		//echo "<th>No.</th>";
		if(mysql_num_rows($query )==0){
			$query = mysql_query("SELECT*FROM jenis_izin_permohonan_data WHERE jenisizin_permohonan_id = '$id'");
		
		}

		
		echo "<th align='center'>No.</th>";
		for($i = 0; $i < mysql_num_fields($query); $i++){
			$name = mysql_field_name($query, $i);
			//$type = mysql_field_type($query, $i);
			//$size = mysql_field_len($query, $i);
			//echo $name.' - '.$type.' - '.$size.'<br>';
				
			if(($name == 'suratizin_field') or ($name == 'suratizindata_value') or ($name == 'jenisizin_permohonan_nilai')){
				$urutkan = $name.$order;
				echo "<th><a href='?send=$to_page////$urutkan'>$name</a></th>";
			}
		}
		echo "</tr>";

		// Nampilin Data
		$baris = 1;
		while ($r= mysql_fetch_array($query)){
			$id = $r['id'];
			
			if($baris % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			echo "<td align='center'>$baris</td>";
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				$value = $r[$name];
				if(($name == 'suratizin_field') or ($name == 'suratizindata_value') or ($name == 'jenisizin_permohonan_nilai')){
					if($name == "jenisizin_permohonan_nilai"){
						$isikolom = explode("::",$r[$name]);
						$value = "";
						for ($x=0;$x<count($isikolom);$x++){
							$value .= $isikolom[$x]."<br>";
						}

					}
					echo "<td align='left'>$value</td>";
				}
			}
			
			echo "</tr>";
			$baris++;
		}
		

koneksi2_tutup();
?>
</table>
</body>
