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
$tabel = "SELECT id,nomor,nama_pemohon,alamat,peruntukan,nama_bangunan,fungsi,sub_fungsi,klasifikasi,luas_bangunan,lokasi,retribusi,tgl_terbit FROM tb_pbg WHERE id='$id'";
$tabel_tanah = "SELECT*FROM tb_tanah WHERE pbg_id='$id'";					

if($_POST["submit"]=="KEMBALI"){
	?>
	<script language="JavaScript">
		document.location.href='?send=datapbg//<?php echo $starting; ?>/<?php echo $search; ?>';
	</script>
	<?php
}

if($_POST["submit"]=="SIMPAN"){
	
	//$kolom = "";
	//$isi = "";
	$update = "";
	$query = mysql_query($tabel);
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);	
		$fill = trim($_POST[$name]);
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
	
	//$cek=mysql_num_rows(mysql_query("select*from tb_pbg where id='$id'"));
	//if($cek == 0){
		//$tambah="insert into tb_pbg ($kolom) values ($isi)";
		//$hasil=mysql_query($tambah);
		//if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
	//}else{
		$ubah="update tb_pbg set $update where id='$id'";
		$hasil=mysql_query($ubah);
		if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
	//}
	
	$j = 1;
	$query_tanah = mysql_query($tabel_tanah);
	while ($r_tanah= mysql_fetch_array($query_tanah)){
		$update = "";
		for($i = 0; $i < mysql_num_fields($query_tanah); $i++){
			$name = mysql_field_name($query_tanah, $i);
			$var = $name.$j;
			$fill = trim($_POST[$var]);
			
			$update .= $name."='".$fill."',";
		}
		
		$update = substr($update, 0, -1);
		$ubah="update tb_tanah set $update where id='$id'";
		$hasil2=mysql_query($ubah);		
		if (!$hasil2) echo "Input Gagal :".mysql_error()."<br>";
		
		$j++;
	}
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data PBG telah diupdate !');
			document.location.href='?send=datapbg//<?php echo $starting; ?>/<?php echo $search; ?>';
		</script>
		<?php
	}
}


?>	
		
<div><span class='judul'>Input Data Persetujuan Bangunan Gedung (PBG)</span></div>


<form action='' method='post' autocomplete="off"> 
	<table>
		<?php
		$query = mysql_query($tabel);
		$r= mysql_fetch_array($query);
		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				$title = str_replace("_"," ",$name);
				$title = ucwords($title);
				echo "<tr>";
				if(($name=="luas_tanah") or ($name=="luas_bangunan")) echo "<td><b>$title (m2)</b></td><td><b>:</b></td>";
				elseif($name=="retribusi") echo "<td><b>$title (Rp.)</b></td><td><b>:</b></td>";
				else echo "<td><b>$title</b></td><td><b>:</b></td>";
				
				
				
				if($name=="id") echo "<td>$r[$name]</td>";
				elseif($name=="tgl_terbit"){
					echo "<td><input type='text' size='15' name='$name' value='$r[$name]' id='inputField1'></td>";
				}
				elseif(($name=="retribusi") or ($name=="luas_tanah") or ($name=="luas_bangunan")){
					echo "<td><input type='text' size='15' name='$name' value='$r[$name]'> <span class='biru'>* hanya angka dan pemisah desimal menggunakan tanda titik (.)</span></td>";
				}
				elseif(($name=="alamat") or ($name=="lokasi") or ($name=="keterangan")){
					echo "<td><textarea name='$name' rows='3' cols='47'>$r[$name]</textarea></td>";
				}				
				else echo "<td><input type='text' size='50' name='$name' value='$r[$name]'></td>";
				echo "</tr>";
			}
			
			$j = 1;
			$query_tanah = mysql_query($tabel_tanah);
			while ($r_tanah= mysql_fetch_array($query_tanah)){
				for($i = 0; $i < mysql_num_fields($query_tanah); $i++){
					$name = mysql_field_name($query_tanah, $i);
					$var = $name.$j;
					$title = str_replace("_"," ",$name);
					$title = ucwords($title);
					echo "<tr>";
					if(($name!="id") and ($name!="pbg_id")){
						if($name=="luas_tanah") echo "<td><b>$title (m2)</b></td><td><b>:</b></td>";
						else echo "<td><b>$title</b></td><td><b>:</b></td>";
						
						if($name=="luas_tanah")echo "<td><input type='text' size='15' name='$var' value='$r_tanah[$name]'> <span class='biru'>* hanya angka dan pemisah desimal menggunakan tanda titik (.)</span></td>";
						else echo "<td><input type='text' size='50' name='$var' value='$r_tanah[$name]'></td>";
					}
					echo "</tr>";
				}
				$j++;
			}
			
				echo "<tr>";
				echo "<td colspan='3' align='right'>&nbsp;</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td colspan='3' align='right'><input type='submit' name='submit' value='KEMBALI'> <input type='submit' name='submit' value='SIMPAN'></td>";
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