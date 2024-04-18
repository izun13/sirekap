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
$tabel2 = "SELECT id,jenis_izin,opd_id,waktu_sop FROM jenis_izin WHERE id='$id'";					
$query = mysql_query($tabel2);

if($_POST["submit"]=="BATAL"){
	?>
	<script language="JavaScript">
		document.location.href='?send=datajnsizin//<?php echo $starting; ?>/<?php echo $search; ?>';
	</script>
	<?php
}

if($_POST["submit"]=="SIMPAN"){
	
	//$kolom = "";
	//$isi = "";
	$update = "";
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);	
		$fill = $_POST[$name];
		if(($name == "opd_id")and($fill == "")) $fill = 0;
		if($name != "id"){
			//$kolom .= $name.",";
			//$isi .="'".$fill."',";
			$update .= $name."='".$fill."',";
		}
	}
	//$kolom = substr($kolom, 0, -1);
	//$isi = substr($isi, 0, -1);
	$update = substr($update, 0, -1);
	
	//$cek=mysql_num_rows(mysql_query("select*from jenis_izin where id='$id'"));
	//if($cek == 0){
	//	$tambah="insert into jenis_izin ($kolom) values ($isi)";
	//	$hasil=mysql_query($tambah);
	//	if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
	//}else{
		$ubah="update jenis_izin set $update where id='$id'";
		$hasil=mysql_query($ubah);
		if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
	//}
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data jnsizin telah diupdate !');
			document.location.href='?send=datajnsizin//<?php echo $starting; ?>/<?php echo $search; ?>';
		</script>
		<?php
	}
}


?>	
		
<div><span class='judul'>Input Data Jenis Izin</span></div>


<form action='' method='post' autocomplete="off"> 
	<table>
		<?php
		$r= mysql_fetch_array($query);
		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				$title = str_replace("_"," ",$name);
				$title = ucwords($title);
				echo "<tr>";
				echo "<td><b>$title</b></td><td><b>:</b></td>";
				if($name=="id") echo "<td>$r[$name]</td>";
				elseif($name=="opd_id") {
					echo "<td><select name='$name' id='pilih'><option value=''>Pilih OPD</option>";
						$query_opd=mysql_query("SELECT*FROM opd order by id asc");
						while($r_opd=mysql_fetch_array($query_opd)){
							$selected = "";
							if($r_opd["id"] == $r[$name])$selected = "selected";
							echo"<option value='".$r_opd["id"]."' $selected>".$r_opd["opd"]."</option>";			
						}
					echo"</select></td>";
				}
				elseif($name=="waktu_sop") echo "<td><input type='text' size='10' name='$name' value='$r[$name]'> * pemisah desimal menggunakan tanda titik (<b> . </b>)</td>";
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