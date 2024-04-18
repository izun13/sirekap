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
$tabel2 = "SELECT*FROM opd WHERE id='$id'";					
$query = mysql_query($tabel2);

if($_POST["submit"]=="BATAL"){
	?>
	<script language="JavaScript">
		document.location.href='?send=dataopd//<?php echo $starting; ?>/<?php echo $search; ?>';
	</script>
	<?php
}

if($_POST["submit"]=="SIMPAN"){
	
	$kolom = "";
	$isi = "";
	$update = "";
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);	
		$fill = $_POST[$name];
		if($name != "id"){
			$kolom .= $name.",";
			$isi .="'".$fill."',";
			$update .= $name."='".$fill."',";
		}
	}
	$kolom = substr($kolom, 0, -1);
	$isi = substr($isi, 0, -1);
	$update = substr($update, 0, -1);
	
	$cek=mysql_num_rows(mysql_query("select*from opd where id='$id'"));
	if($cek == 0){
		$tambah="insert into opd ($kolom) values ($isi)";
		$hasil=mysql_query($tambah);
		if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
	}else{
		$ubah="update opd set $update where id='$id'";
		$hasil=mysql_query($ubah);
		if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
	}
	
	//update data opd database simpadu
	koneksi1_tutup();
	koneksi2_buka();
	$cek=mysql_num_rows(mysql_query("select*from opd where id='$id'"));
	if($cek == 0){
		$tambah="insert into opd ($kolom) values ($isi)";
		$hasil=mysql_query($tambah);
		if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
	}else{
		$ubah="update opd set $update where id='$id'";
		$hasil=mysql_query($ubah);
		if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
	}		
	koneksi2_tutup();
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data opd telah diupdate !');
			document.location.href='?send=dataopd//<?php echo $starting; ?>/<?php echo $search; ?>';
		</script>
		<?php
	}
}


?>	
		
<div><span class='judul'>Input Data OPD</span></div>


<form action='' method='post' autocomplete="off"> 
	<table>
		<?php
		$r= mysql_fetch_array($query);
		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				echo "<tr>";
				echo "<td><b>$name</b></td><td><b>:</b></td>";
				if($name=="id") echo "<td>$r[$name]</td>";
				elseif($name=="alamat") echo "<td><textarea name='$name' rows='2' cols='47'>$r[$name]</textarea></td>";
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