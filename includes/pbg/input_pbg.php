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
$tabel = "SELECT id,nomor,nama_pemohon,alamat,peruntukan,nama_bangunan,fungsi,sub_fungsi,klasifikasi,luas_bangunan,lokasi,retribusi,tgl_terbit FROM tb_pbg";					
$tabel_tanah = "SELECT*FROM tb_tanah";

$sertifikat = 1;
if($_POST["sertifikat"])$sertifikat = $_POST["sertifikat"];
if($_POST["tambah"]) $sertifikat++;
if($_POST["hapus"]) $sertifikat--;

if($_POST["submit"]=="KEMBALI"){
	?>
	<script language="JavaScript">
		document.location.href='?send=datapbg//<?php echo $starting; ?>/<?php echo $search; ?>';
	</script>
	<?php
}

if($_POST["submit"]=="SIMPAN"){
	
	$kolom = "";
	$isi = "";
	//$update = "";
	$query = mysql_query($tabel);
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);	
		$fill = trim($_POST[$name]);
		if(($name == "opd_id")and($fill == "")) $fill = 0;
		if($name != "id"){
			$kolom .= $name.",";
			$isi .="'".$fill."',";
			//$update .= $name."='".$fill."',";
		}
	}
	$kolom = substr($kolom, 0, -1);
	$isi = substr($isi, 0, -1);
	//$update = substr($update, 0, -1);
	
	//$cek=mysql_num_rows(mysql_query("select*from tb_pbg where id='$id'"));
	//if($cek == 0){
		$tambah="insert into tb_pbg ($kolom) values ($isi)";
		$hasil=mysql_query($tambah);
		if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
	//}else{
		//$ubah="update tb_pbg set $update where id='$id'";
		//$hasil=mysql_query($ubah);
		//if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
	//}
	
	$current=mysql_fetch_array(mysql_query("SELECT*FROM tb_pbg ORDER BY id DESC"));
	
	
	$query_tanah = mysql_query($tabel_tanah);
	for ($j=1;$j<=$sertifikat;$j++){
		
		$kolom = "";
		$isi = "";
		for($i = 0; $i < mysql_num_fields($query_tanah); $i++){
			$name = mysql_field_name($query_tanah, $i);	
			$var = $name.$j;
			$fill = trim($_POST[$var]);
		
			if($name != "id"){
				$kolom .= $name.",";
				if($name == "pbg_id") $isi .="'".$current["id"]."',";
				else $isi .="'".$fill."',";
			}
		}
		
		$kolom = substr($kolom, 0, -1);
		$isi = substr($isi, 0, -1);
		$tambah="insert into tb_tanah ($kolom) values ($isi)";
		$hasil2=mysql_query($tambah);
		if (!$hasil2) echo "Input Gagal :".mysql_error()."<br>";
	}
	
	
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data PBG telah ditambah !');
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
		$fill = "";
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				$title = str_replace("_"," ",$name);
				$title = ucwords($title);
				
				$fill = trim($_POST[$name]);
		
				echo "<tr>";
				if(($name=="luas_tanah") or ($name=="luas_bangunan")) echo "<td><b>$title (m2)</b></td><td><b>:</b></td>";
				elseif($name=="retribusi") echo "<td><b>$title (Rp.)</b></td><td><b>:</b></td>";
				else echo "<td><b>$title</b></td><td><b>:</b></td>";			
				
				if($name=="id") echo "<td></td>";
				elseif($name=="tgl_terbit"){
					echo "<td><input type='text' size='15' name='$name' value='$fill' id='inputField1'></td>";
				}
				elseif(($name=="retribusi") or ($name=="luas_tanah") or ($name=="luas_bangunan")){
					echo "<td><input type='text' size='15' name='$name' value='$fill'> <span class='biru'>* hanya angka dan pemisah desimal menggunakan tanda titik (.)</span></td>";
				}
				elseif(($name=="alamat") or ($name=="lokasi") or ($name=="keterangan")){
					echo "<td><textarea name='$name' rows='3' cols='47'>$fill</textarea></td>";
				}				
				else echo "<td><input type='text' size='50' name='$name' value='$fill'></td>";
				echo "</tr>";
			}
			
			echo "<tr><td colspan='3'>&nbsp;</td></tr>";
			echo "<tr><td colspan='3'><b>Data Kepemilikan Tanah</b></td></tr>";
			
			for ($j=1;$j<=$sertifikat;$j++){
				$query_tanah = mysql_query($tabel_tanah);
				for($i = 0; $i < mysql_num_fields($query_tanah); $i++){
					$name = mysql_field_name($query_tanah, $i);
					$var = $name.$j;
					$fill = trim($_POST[$var]);
					$title = str_replace("_"," ",$name);
					$title = ucwords($title);
				
					echo "<tr>";
					
					if(($name!="id") and ($name!="pbg_id")){
						if($name=="luas_tanah") echo "<td><b>$title (m2)</b></td><td><b>:</b></td>";
						else echo "<td><b>$title</b></td><td><b>:</b></td>";
					
						if($name=="luas_tanah")echo "<td><input type='text' size='15' name='$var' value='$fill'> <span class='biru'>* hanya angka dan pemisah desimal menggunakan tanda titik (.)</span></td>";
						else echo "<td><input type='text' size='50' name='$var' value='$fill'></td>";
					}
					echo "</tr>";
				}
			}
						
			echo "<tr><td colspan='2'>&nbsp;</td><td><input type='submit' name='tambah' value='Tambah Sertifikat'><input type='hidden' name='sertifikat' value='$sertifikat'></td></tr>";
			
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