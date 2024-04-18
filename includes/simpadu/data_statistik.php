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
$libur_nasional=mysql_fetch_array(mysql_query("SELECT tgl FROM libur_nasional"));
koneksi1_tutup();
koneksi2_buka();
$to_page = "simpadu3";

if($_POST["act"]) $act = $_POST["act"];
$search = explode(";",$search);
$search1 = $search[0]; 
if($_POST["search1"]) $search1 = $_POST["search1"];
$search2 = $search[1]; 
if($_POST["search2"]) $search2 = $_POST["search2"];
$search3 = $search[2]; 
if($_POST["search3"]) $search3 = $_POST["search3"];

?>
<div class="judul">Statisik Proses Permohonan Simpadu (oss.magelangkota.go.id)</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table>
	<tr><td width="200">Periode Tanggal Penetapan </td><td>:</td>
		<td width=""> <input type='text' size='10' name='search1' value='<?php echo $search1;?>' id="inputField1" class='search'> 
		s/d  <input type='text' size='10' name='search2' value='<?php echo $search2;?>' id="inputField2" class='search'> 
	</td></tr>
	
	<tr><td>Diterbitkan</td><td>:</td>
		<td> <input type='radio' name='search3' value='1' <?php if($search3 == 1) echo "checked";?>> Sudah 
				<input type='radio' name='search3' value='2' <?php if($search3 == 2) echo "checked";?>> Belum 
				<input type='radio' name='search3' value='0' <?php if($search3 == 0) echo "checked";?>> Semua
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
	
	?>
	<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>Jenis Izin</b></th>
			<th><b>Jumlah</b></th>
			<th><b>Rata-rata Lama Proses</b></th>
		</tr>
		<?php
		
			
		$tabel = "SELECT count(jenisizin_id) as jumlah,jenisizin_id,jenisizin_name FROM view_permohonan_izin WHERE permohonan_id IS NOT NULL";
		if(($search1 != "") and ($search2 != "")) $tabel .= " AND permohonan_tgl_izin >= '$search1' AND permohonan_tgl_izin <= '$search2'";
		if($search3 == 1) $tabel .= " AND permohonan_nomor_surat != ''";
		if($search3 == 2) $tabel .= " AND permohonan_nomor_surat = ''";
		$tabel .= " GROUP BY jenisizin_id ORDER BY jumlah desc";
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
			echo "<td align='left'>$r[jenisizin_name]</td>";
			echo "<td align='center'>$r[jumlah]</td>";
			
			$tabel2 = "SELECT*FROM view_permohonan_izin WHERE jenisizin_id = '$r[jenisizin_id]'";
			if(($search1 != "") and ($search2 != "")) $tabel2 .= " AND permohonan_tgl_izin >= '$search1' AND permohonan_tgl_izin <= '$search2'";
			if($search3 == 1) $tabel2 .= " AND permohonan_nomor_surat != ''";
			if($search3 == 2) $tabel2 .= " AND permohonan_nomor_surat = ''";
			$query2 = mysql_query($tabel2);
			
			$j=0;
			$rata=0;
			$jml_hr_kerja=0;
			while ($r2= mysql_fetch_array($query2)){
				$j++;
				
				$hari_kerja= 0;
				$tgl_awal = null;
				$tgl_akhir = null;	
				
				//tgl cetak tanda terima berkas
				$tglawal = mysql_fetch_array(mysql_query("SELECT permproc_date FROM permohonan_process WHERE permproc_permohonan_id = '$r2[permohonan_id]' AND permproc_statusproses_id = '51' "));
				//tgl penetapan izin
				//$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '8' "));
				
				$tgl_awal = $tglawal['permproc_date'];
				//$tgl_akhir = $tglakhir['permproc_date'];
				$tgl_akhir = $r2['permohonan_tgl_izin'];
				
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
				
			}
			
			$rata = $jml_hr_kerja/$j;
			$rata = number_format($rata,2);
			$ttl_proses += $rata;
			echo "<td align='center'>$rata</td>";	
			
			echo "</tr>";
			
			$total += $r[jumlah];
			$i++;
		}
			$rata_proses = $ttl_proses/$k;
			$rata_proses = number_format($rata_proses,2);
			
			echo "<tr><td align='center' colspan='2'><b>Total</b></td><td align='center'><b>$total</b></td><td align='center'><b>$rata_proses</b></td></tr>";
			
			
			
		
		?>
		
</table>

<?php
}
koneksi2_tutup();
?>
</body>
