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

if($_POST["submit"]=="BATAL"){
	?>
	<script language="JavaScript">
		document.location.href='?send=datarealisasi//<?php echo $starting; ?>/<?php echo $search; ?>';
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
		if(($name == "opd_id")and($fill == "")) $fill = 0;
		if($name != "id"){
			$kolom .= $name.",";
			$isi .="'".$fill."',";
			$update .= $name."='".$fill."',";
		}
	}
	$kolom = substr($kolom, 0, -1);
	$isi = substr($isi, 0, -1);
	$update = substr($update, 0, -1);
	
	$cek=mysql_num_rows(mysql_query("select*from realisasi_investasi where id='$id'"));
	if($cek == 0){
		$tambah="insert into realisasi_investasi ($kolom) values ($isi)";
		$hasil=mysql_query($tambah);
		if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
	}else{
		$ubah="update realisasi_investasi set $update where id='$id'";
		$hasil=mysql_query($ubah);
		if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
	}
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data realisasi investasi telah diupdate !');
			document.location.href='?send=datarealisasi//<?php echo $starting; ?>/<?php echo $search; ?>';
		</script>
		<?php
	}
}


?>	
		
<div><span class='judul'>Input Data Realisasi Investasi</span></div>


<form action='' method='post' autocomplete="off"> 
	<table>
		<?php
		$r= mysql_fetch_array($query);
		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				echo "<tr>";
				echo "<td><b>$name</b></td><td><b>:</b></td>";
				if($name=="id") echo "<td>$r[$name]</td>";
				else echo "<td><input type='text' size='50' name='$name' value='$r[$name]'></td>";
				echo "</tr>";
			}
				echo "<tr>";
				echo "<td colspan='3' align='right'><input type='submit' name='submit' value='BATAL'> <input type='submit' name='submit' value='SIMPAN'></td>";
				echo "</tr>";
		?>
	</table>
<script type="text/javascript">
        $('#pilih').selectize({
            create: true,
            sortField: 'text'
        });
</script>
</form>
</body>