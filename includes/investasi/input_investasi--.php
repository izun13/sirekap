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
$tabel2 = "SELECT tahun,bulan,nama_perusahaan,bidang_usaha,jenis_modal,nilai_investasi,jumlah_tk,no_izin,kegiatan_usaha,no_telepon FROM realisasi_investasi WHERE id='$id'";					
$query = mysql_query($tabel2);

if($_POST["submit"]=="BATAL"){
	?>
	<script language="JavaScript">
		document.location.href='?send=datainvestasi//<?php echo $starting; ?>/<?php echo $search; ?>';
	</script>
	<?php
}

if($_POST["submit"]=="SIMPAN"){
	$update = "";
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);	
		$fill = $_POST[$name];
		if($name != "id")$update .= $name."='".$fill."',";
	}
	$update = substr($update, 0, -1);
	$ubah="update realisasi_investasi set $update where id='$id'";
	$hasil=mysql_query($ubah);
	if ($hasil){
		?>
		<script language="JavaScript">alert('data realisasi investasi telah diubah...!');
			document.location.href='?send=datainvestasi//<?php echo $starting; ?>/<?php echo $search; ?>';
		</script>
		<?php
	}
	if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
}


?>	
		
<div><span class='judul'>Input Realisasi Investasi</span></div>


<form action='' method='post' autocomplete="off"> 
	<table>
		<?php
				
		
		$r= mysql_fetch_array($query);
		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				echo "<tr>";
				echo "<td><b>$name</b></td><td><b>:</b></td>";
				if($name=="kegiatan_usaha") echo "<td><textarea name='$name' rows='2' cols='47'>$r[$name]</textarea></td>";
				else echo "<td><input type='text' size='50' name='$name' value='$r[$name]'></td>";
				echo "</tr>";
			}
				echo "<tr>";
				echo "<td colspan='3' align='right'><input type='submit' name='submit' value='BATAL'> <input type='submit' name='submit' value='SIMPAN'></td>";
				echo "</tr>";
		?>
		
	</table>
</form>
</body>