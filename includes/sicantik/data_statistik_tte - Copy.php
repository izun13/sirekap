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
$to_page = "sicantik3b";

if($_POST["act"]) $act = $_POST["act"];
$search = explode(";",$search);
$search1 = $search[0]; 
if($_POST["search1"]) $search1 = $_POST["search1"];
$search2 = $search[1]; 
if($_POST["search2"]) $search2 = $_POST["search2"];
$search3 = $search[2]; 
if($_POST["search3"]) $search3 = $_POST["search3"];

?>
<div class="judul">Statisik Proses Permohonan Sicantik</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table>
	<tr><td width="210">Periode Tanggal Pengesahan (TTE)</td><td>:</td>
		<td width=""> <input type='text' size='10' name='search1' value='<?php echo $search1;?>' id="inputField1" class='search'> 
		s/d  <input type='text' size='10' name='search2' value='<?php echo $search2;?>' id="inputField2" class='search'> 
	</td></tr>
	<tr><td>Proses Dinas Teknis</td><td>:</td>
		<td> 
			<input type='radio' name='search3' value='0' <?php if($search3 == 0) echo "checked";?>> Ya
			<input type='radio' name='search3' value='1' <?php if($search3 == 1) echo "checked";?>> Tidak
	</td></tr>
	
	<tr><td>&nbsp;</td> <td>&nbsp;</td> 
		<td align="right"><input type='submit' name='act' value='Tampilkan'>
	</td></tr>
</table>
</form>

<script type="text/javascript">
        $('#pilih').selectize({
            create: true,
            sortField: 'text'
        });
</script>
<?php	
if($act == "Tampilkan"){
	
	if(($search1 == "") or ($search2 == "")){
		?>
			<script language="JavaScript">alert('Periode Tanggal Penetapan Harus Diisi !');
			document.location.href='?send=sicantik3';
			</script>
		<?php	
	}
	$searchlink = "$search0;$search1;$search2;$search3";
	echo"&nbsp; <a href='reports/sicantik/rekap_statistik_tte_graph.php?send=$searchlink'  target='_blank'><img src=\"img/graph1.png\" width='38' title='Lihat Grafik'></img></a>";
	echo"&nbsp; <a href='reports/sicantik/rekap_statistik_tte_json.php?send=$searchlink'  target='_blank'><img src=\"img/json.png\" width='38' title='Cetak Json'></img></a>";
	
	?>
	<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>Jenis Izin</b></th>
			<th><b>Jumlah</b></th>
			<th><b>Rata-rata Lama Proses</b></th>
		</tr>
		<?php
		$tahun = date('Y',strtotime($search1));
		$z=0;
		$query_libur = mysql_query("SELECT tgl FROM libur_nasional WHERE tgl LIKE '%$tahun%'");
		while ($r_libur= mysql_fetch_array($query_libur)){
			$libur_nasional[$z] = $r_libur['tgl'];
			$z++;
		}
				
		//$tabel = "SELECT count(jenis_izin) as jumlah,jenis_izin FROM view_permohonan_izin WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";
		//$tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
		//if($search3 == 1) $tabel .= " AND no_izin != ''";
		//if($search3 == 2) $tabel .= " AND no_izin = ''";
		
		$tabel = "SELECT count(jenis_izin) as jumlah,jenis_izin FROM view_permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND status_penetapan != 'ditolak'";	
		$tabel .= " AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";
		
		$tabel .= " GROUP BY jenis_izin ORDER BY jumlah desc";
		// Nampilin Data
		$query = mysql_query($tabel);
		
		$i=1;	
		$k = 0;
		$total = 0;
		$ttl_proses = 0;
		$rata_proses = 0;
		while ($r= mysql_fetch_array($query)){
			$k++;
			
			if($i % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
				
			echo "<td align='center'>$i</td>";
			echo "<td align='left'>$r[jenis_izin]</td>";
			//echo "<td align='center'>$r[jumlah]</td>";
			
			$tabel2 = "SELECT id,end_date FROM view_permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND status_penetapan != 'ditolak'";	
			$tabel2 .= " AND jenis_izin = '$r[jenis_izin]' AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";
			//if($search3 == 1) $tabel .= " AND no_izin != ''";
			//if($search3 == 2) $tabel .= " AND no_izin = ''";
			//$tabel2 .= " GROUP BY id";
			$query2 = mysql_query($tabel2);
			//echo $tabel2 ;
			$j=0;
			$rata=0;
			$jml_hr_kerja=0;
			while ($r2= mysql_fetch_array($query2)){
				
				$hari_kerja= 0;
				$tgl_awal = null;
				$tgl_akhir = null;	
				
				/*	//tgl cetak tanda terima berkas
				$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '33' "));
				//tgl ttd izin
				$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '40' "));
				//tgl penetapan
				if(empty($tglakhir))$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '8' "));
				
				
				$tgl_awal = $tglawal['end_date'];
				//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
				$tgl_akhir = $tglakhir['end_date'];
				//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");
				
				$tampil = 1;
				if(($search3 == 1) and ($tgl_akhir == null)) $tampil = 0;
				if(($search3 == 2) and ($tgl_akhir != null)) $tampil = 0;
								
				if($tampil == 1){
					$j++;			
					//jumlah hari kerja
					if(($tgl_awal != null) and ($tgl_akhir != null)){
						$awal=strtotime($tgl_awal);
						$akhir=strtotime($tgl_akhir);
						
						for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
							$i_date=date("Y-m-d",$x);
							if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
								$hari_kerja++;
							}
						}
					}
					$jml_hr_kerja += $hari_kerja;
				}*/
				
				$j++;
				$tgl_akhir = $r2['end_date'];
				//tgl cetak tanda terima berkas
				$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '33' "));
				$tgl_awal = $tglawal['end_date'];
				if($search3 == 0){
					$tgl_akhir = $r2['end_date'];	
				}else{
					//tgl ttd izin
					//$tgl_akhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '40' "));
					//tgl penetapan
					//$tgl_akhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '8' "));
				
					//tgl rekomendasi kesehatan
					$tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '7' "));
					//tgl rekomendasi diperindag
					if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '176' "));
					//tgl rekomendasi bpkad
					if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '108' "));
					
					if(!empty($tgl_rekomendasi))$tgl_akhir = $tgl_rekomendasi['start_date'];
				}
				
				if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
				if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
				
				//jumlah hari kerja
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
				
				if((!empty($tgl_rekomendasi)) and ($search3 == 1)){
					//tgl Cetak Rekomendasi dkk dan disperindag
					$tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '35' "));
					if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '7' "));
					//tgl Verifikasi status bayar bpkad
					if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '226' "));
					
					if(!empty($tgl_cetakrekomendasi))$tgl_awal = $tgl_cetakrekomendasi['end_date'];
					if( date('Y-m-d', strtotime($tgl_awal)) ==  $tgl_akhir) $tgl_awal = date('Y-m-d', strtotime('+1 days', strtotime($tgl_awal))); 
					
					$tgl_akhir = $r2['end_date'];
					if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
					
					//jumlah hari kerja
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
				}
				
				$jml_hr_kerja += $hari_kerja;
			}
			
			$rata = $jml_hr_kerja/$j;
			$rata = number_format($rata,2);
			$ttl_proses += $rata;
			echo "<td align='center'>$j</td>";
			echo "<td align='center'>$rata</td>";	
			
			echo "</tr>";
			
			$total += $j;
			$i++;
		}
			$rata_proses = $ttl_proses/$k;
			$rata_proses = number_format($rata_proses,2);
			
			echo "<tr><td align='center' colspan='2'><b>Total</b></td><td align='center'><b>$total</b></td><td align='center'><b>$rata_proses</b></td></tr>";
			
			
			
		
		?>
		
</table>

<?php
}
?>
</body>
