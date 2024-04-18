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
$tabel = "SELECT*FROM realisasi_investasi WHERE id='$id'";					
$query = mysql_query($tabel);

$tgl1 = $_POST["tgl1"];
$tgl2 = $_POST["tgl2"];
$bulan = $_POST["bulan"];
$tahun = $_POST["tahun"];

if($_POST["submit"]=="BATAL"){
	?>
	<script language="JavaScript">
		document.location.href='?send=datarealisasi//<?php echo $starting; ?>/<?php echo $search; ?>';
	</script>
	<?php
}

if($_POST["submit"]=="UPDATE"){
	if(($tgl1 == "") or ($tgl2 == "") or ($bulan == "") or ($tahun == "")){
		?>
		<script language="JavaScript">alert('data belum lengkap !');</script>
		<?php
	}else{
		$tabel_proyek = "SELECT SUM(tambah_investasi) AS nilai_investasi,SUM(tki) AS jml_tki,sektor_id,sektor FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND tambah_investasi != '0' AND tgl_verifikasi >= '$tgl1' AND tgl_verifikasi <= '$tgl2' 
				GROUP BY sektor_id ORDER BY sektor_id asc";	
		$query_proyek = mysql_query($tabel_proyek);
		
		$cek=mysql_num_rows(mysql_query("select*from realisasi_investasi where bulan = '$bulan' and tahun = '$tahun'"));
		if($cek == 0){
			while ($r_proyek= mysql_fetch_array($query_proyek)){
				//$cek=mysql_num_rows(mysql_query("select*from realisasi_investasi where bulan = '$bulan' and tahun = '$tahun'"));
				//if($cek == 0){
					$tambah="insert into realisasi_investasi (bulan,tahun,sektor_id,sektor,nilai_investasi,jumlah_tki) values ('$bulan','$tahun','$r_proyek[sektor_id]','$r_proyek[sektor]','$r_proyek[nilai_investasi]','$r_proyek[jml_tki]')";
					$hasil=mysql_query($tambah);
					if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
				//}else{
					//$ubah="update realisasi_investasi set $update where id='$id'";
					//$hasil=mysql_query($ubah);
					//if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
				//}
			}
		}
		if ($hasil){
			?>
			<script language="JavaScript">alert('data realisasi investasi telah diupdate !');
				document.location.href='?send=datarealisasi//<?php echo $starting; ?>/<?php echo $search; ?>';
			</script>
			<?php
		}
	}
}


?>	
		
<div><span class='judul'>Input Data Realisasi Investasi</span></div>


<form action="" method="post" autocomplete="off">
	<table class="">
		<tr>
			<td width=""><b>Periode Tanggal</b></td><td>:</td>
			<td width=""><input type='text' size='10' name='tgl1' value='<?php echo $tgl1;?>' id="inputField1" class='search'> 
			s/d  <input type='text' size='10' name='tgl2' value='<?php echo $tgl2;?>' id="inputField2" class='search'></td>
		</tr>
		<tr>
			<td width=""><b>Bulan</b></td><td>:</td>
			<td width="">
			<?php
				echo "<select name='bulan' id=''>";
				for($i = 0; $i < count($NAMA_BULAN); $i++){
					$selected = "";
					if($NAMA_BULAN[$i] == $bulan)$selected = "selected";
					echo"<option value='".$NAMA_BULAN[$i]."' $selected>".$NAMA_BULAN[$i]."</option>";	
				}		
				echo "</select>";
			?>
			</td>
		</tr>
		<tr>
			<td width=""><b>Tahun</b></td><td>:</td>
			<td width=""><input type='text' size='8' name='tahun' value='<?php echo $tahun;?>' id="" class='search'></td>
		</tr>
		<tr>
			<td colspan='3' align='right'><input type='submit' name='submit' value='BATAL'> <input type='submit' name='submit' value='UPDATE'></td>
		</tr>

	</table>
</form>	

<script type="text/javascript">
        $('#pilih').selectize({
            create: true,
            sortField: 'text'
        });
</script>
</body>