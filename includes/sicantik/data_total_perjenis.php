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
		</tr>
		<?php
		
		$z=0;
		$query_libur = mysql_query("SELECT tgl FROM libur_nasional");
		while ($r_libur= mysql_fetch_array($query_libur)){
			$libur_nasional[$z] = $r_libur['tgl'];
			$z++;
		}
		
		$tanggal1 = date('Y-m-d',strtotime($search1));
		$tanggal2 = date('Y-m-d',strtotime($search2));
		$tanggal3 = date('Y-m-d',strtotime('2022-05-01'));//Mei 20222
		
		$x=0;
		while ($tanggal1 <= $tanggal2) {
			$blnthn = substr($tanggal1,0,7);
			if($blnthn != $newblnthn){
				$bulan[$x] = $blnthn;
				$tte[$x] = 0;
				if($tanggal1 >= $tanggal3) $tte[$x] = 1;
				$x++;
			}
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
		$total = 0;
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
			echo "<td align='center'>$j</td>";	
			echo "</tr>";
			
			$total += $j;
			$i++;
		}
			
			echo "<tr><td align='center' colspan='2'><b>Total</b></td><td align='center'><b>$total</b></td></tr>";
			
			
			
		
		?>
		
</table>

<?php
}
?>
</body>
