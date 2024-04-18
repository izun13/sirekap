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

$tabel2 = "SELECT id,nama_perusahaan,jenis_perusahaan,status_pm,nib,tanggal_nib,status_nib,alamat_perusahaan,no_telp,verifikasi,tgl_verifikasi FROM oss_nib WHERE id='$id'";					
$query = mysql_query($tabel2);

if($_POST["submit"]=="BATAL"){
	?>
	<script language="JavaScript">
		document.location.href='?send=dataossnib//<?php echo $starting;?>/<?php echo $search;?>';
	</script>
	<?php
}

if($_POST["submit"]=="SIMPAN"){

	$kolom = "";
	$isi = "";
	$update = "";
	$query2 = mysql_query("select id,verifikasi,tgl_verifikasi from oss_nib where id='$id'");
	for($i = 0; $i < mysql_num_fields($query2); $i++){
		$name = mysql_field_name($query2, $i);	
		$fill = $_POST[$name];
		//if(($name == "verifikasi")and($fill == "")) $fill = 0;
		if($name != "id"){
			$kolom .= $name.",";
			$isi .="'".$fill."',";
			$update .= $name."='".$fill."',";
		}
	}
	$kolom = substr($kolom, 0, -1);
	$isi = substr($isi, 0, -1);
	$update = substr($update, 0, -1);
	
	$cek=mysql_num_rows(mysql_query("select*from oss_nib where id='$id'"));
	if($cek == 0){
		$tambah="insert into oss_nib ($kolom) values ($isi)";
		$hasil=mysql_query($tambah);
		if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
	}else{
		$ubah="update oss_nib set $update where id='$id'";
		$hasil=mysql_query($ubah);
		if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
	}
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data OSS NIB telah diupdate !');
			document.location.href='?send=dataossnib//<?php echo $starting;?>/<?php echo $search;?>';
		</script>
		<?php
	}
}


?>	
		
<div><span class='judul'>Edit Data OSS NIB</span></div>


<form action='' method='post' autocomplete="off"> 
	<table>
		<?php
		$r= mysql_fetch_array($query);
		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				echo "<tr>";
				echo "<td><b>$name</b></td><td><b>:</b></td>";
				if($name=="verifikasi") {
					echo "<td width='300'><select name='$name' id='pilih'><option value=''></option>";
						$status=array("Terverifikasi","Belum Verifikasi","Dicabut");
						
						for($j=0;$j<=2;$j++){
							$selected = "";
							if($status[$j] == $r[$name])$selected = "selected";
							echo"<option value='".$status[$j]."' $selected>".$status[$j]."</option>";			
						}
					echo"</select></td>";
				}
				elseif($name=="tgl_verifikasi"){
					$tgl_ver = date("Y-m-d");
					if($r[$name]) $tgl_ver = $r[$name];
					echo "<td><input type='text' size='10' name='$name' value='$tgl_ver' id='inputField1'></td>";
				}
				else echo "<td>$r[$name]</td>";
				
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