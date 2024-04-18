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
//require_once "includes/createpass.php";	

$tabel2 = "SELECT nama,nip,jabatan,telp,username,password FROM petugas WHERE id='$id'";					
$query = mysql_query($tabel2);

if($_POST["submit"]=="SIMPAN"){

	$kolom = "";
	$isi = "";
	$update = "";
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);	
		$fill = $_POST[$name];
		if ($fill != ""){
			if($name != "id"){
				if($name == "password")$fill = password_hash($fill, PASSWORD_DEFAULT);
				if($name == "username")$cek_user=mysql_fetch_array(mysql_query("select*from petugas where username='$fill'"));
				$kolom .= $name.",";
				$isi .="'".$fill."',";
				$update .= $name."='".$fill."',";
			}
		}
	}
	
	$kolom = substr($kolom, 0, -1);
	$isi = substr($isi, 0, -1);
	$update = substr($update, 0, -1);
	
	if($cek_user["id"] != $id){
		?>
			<script language="JavaScript">alert('username sudah ada !');
			</script>
		<?php
	}else{
		
		$ubah="update petugas set $update where id='$id'";
		$hasil=mysql_query($ubah);
		if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
		
		if ($hasil){
			?>
			<script language="JavaScript">alert('data petugas telah diupdate !');
			</script>
			<?php
		}
	}
}

if($_POST["submit"]=="Simpan Password"){

	$update = "";
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);	
		$fill = $_POST[$name];
		if ($fill != ""){
			if($name == "password"){
				//$converter = new Encryption;
				//$fill = $converter->encode($_POST[$name]);
				$fill = password_hash($fill, PASSWORD_DEFAULT);
				$update .= $name."='".$fill."'";
			}
		}
	}
	
	$ubah="update petugas set $update where id='$id'";
	$hasil=mysql_query($ubah);
	if ($hasil){
		?>
		<script language="JavaScript">alert('password telah diupdate !');
		</script>
		<?php
	}else echo "Update Gagal :".mysql_error()."<br>";
}
?>	
		
<div><span class='judul'>Profil Pengguna</span></div>


<form action='' method='post' autocomplete="off"> 
	<table>
		<?php
		$r= mysql_fetch_array($query);
		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				$title = str_replace("_"," ",$name);
				$title = ucwords($title);
				echo "<tr>";
				if($name == "import" or $name == "pokok" or $name == "izin_usaha" or $name == "izin_nonusaha" or $name == "realisasi") echo "<td><b>Akses $title</b></td><td><b>:</b></td>";
				else echo "<td><b>$title</b></td><td><b>:</b></td>";
				if($name=="id") echo "<td>$r[$name]</td>";
				elseif($name == "password"){
					//$converter = new Encryption;
					//$pass = $converter->decode($r[$name]);
					if($r[$name] == "")echo "<td><input type='text' size='50' name='$name' value=''></td>";
					else{
						if($_POST["submit"]=="Ubah Password"){
							echo "<td><input type='text' size='30' name='$name' value=''>";
							echo "<input type='submit' name='submit' value='Simpan Password'></td>";
						}else echo "<td><input type='submit' name='submit' value='Ubah Password'></td>";
					}
				}
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
				elseif(($name == "import") or ($name == "pokok") or ($name == "izin_usaha") or ($name == "izin_nonusaha") or ($name == "realisasi")){
					$checked = "";
					if($r[$name]==1) $checked = "checked";	
					echo "<td><input type='checkbox' name='$name' value='1' $checked></td>";
				}
				else echo "<td><input type='text' size='50' name='$name' value='$r[$name]'></td>";
				echo "</tr>";
			}
				echo "<tr>";
				echo "<td colspan='3' align='right'><input type='submit' name='submit' value='SIMPAN'></td>";
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