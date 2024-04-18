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
$to_page = "simpadu4";

if($_POST["act"]) $act = $_POST["act"];
$search = explode(";",$search);
$search1 = $search[0]; 
if($_POST["search1"]) $search1 = $_POST["search1"];
$search2 = $search[1]; 
if($_POST["search2"]) $search2 = $_POST["search2"];
$search3 = $search[2]; 
if($_POST["search3"]) $search3 = $_POST["search3"];

?>
<div class="judul">Total Permohonan Simpadu Per Bulan(oss.magelangkota.go.id)</div>

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
			<th><b>Bulan</b></th>
			<th><b>Jumlah</b></th>
		</tr>
		<?php
		
			
		$tabel = "SELECT*FROM view_permohonan_izin WHERE permohonan_id IS NOT NULL";
		if(($search1 != "") and ($search2 != "")) $tabel .= " AND permohonan_tgl_izin >= '$search1' AND permohonan_tgl_izin <= '$search2'";
		if($search3 == 1) $tabel .= " AND permohonan_nomor_surat != ''";
		if($search3 == 2) $tabel .= " AND permohonan_nomor_surat = ''";
		$tabel .= " ORDER BY permohonan_tgl_izin ASC ";
		// Nampilin Data
		$query = mysql_query($tabel);
		
		$i=0;	
		$newblnthn = "";
		while ($r= mysql_fetch_array($query)){
			$blnthn = substr($r['permohonan_tgl_izin'],0,7);
			//echo $blnthn."<br>";
			
			if($blnthn == $newblnthn){
				$k++;
				$jum[$i] = $k;
			}else{
				$i++;
				$k = 1;
				$bulan[$i] = $blnthn;
			}
			
			$newblnthn = $blnthn;
			
		}
		
		$total = 0;
		for($j=1;$j<=count($bulan);$j++){
			if($j % 2==0){
					echo "<tr class='cyan'>";
				}else{
					echo "<tr>";
				}
			//echo $bulan[$j]."<br>";
			$thn = substr($bulan[$j],0,4);
			$bln = substr($bulan[$j],5,2);
			$bln = (int)$bln;
			//echo $bln."<br>";
			$total += $jum[$j];
			echo "<td align='center'>$j</td>";
			echo "<td align='left'>$NAMA_BULAN[$bln] $thn</td>";
			echo "<td align='center'>$jum[$j]</td>";
		}
		
		echo "<tr><td align='center' colspan='2'><b>Total</b></td><td align='center'><b>$total</b></td></tr>";
		?>
		
</table>

<?php
}
koneksi2_tutup();
?>
</body>
