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
$to_page = "datarbaizin";
$tabel = "SELECT id,nama_perusahaan,nib,day_of_tanggal_terbit_oss,uraian_status_penanaman_modal,kd_resiko,kbli,day_of_tgl_izin,
		uraian_jenis_perizinan,nama_dokumen,uraian_status_respon,kewenangan,verifikasi,tgl_verifikasi FROM oss_rba_izins WHERE id='$id'";	

Function title($name){
	if($name == "id")$title = "ID";
	if($name == "nama_perusahaan")$title = "Nama Perusahaan";
	if($name == "nib")$title = "NIB";
	if($name == "day_of_tanggal_terbit_oss")$title = "Tanggal Terbit";
	if($name == "uraian_status_penanaman_modal")$title = "Status";
	if($name == "kd_resiko")$title = "Kode Resiko";
	if($name == "kbli")$title = "KBLI";
	if($name == "day_of_tgl_izin")$title = "Tanggal Izin";
	if($name == "uraian_jenis_perizinan")$title = "Jenis Perizinan";
	if($name == "nama_dokumen")$title = "Nama Dokumen";
	if($name == "uraian_status_respon")$title = "Status Respon";
	if($name == "kewenangan")$title = "Kewenangan";
	if($name == "verifikasi")$title = "Status Verifikasi";
	if($name == "tgl_verifikasi")$title = "Tanggal Verifikasi";
	
	return $title;
}	

if($_POST["submit"]=="BATAL"){
	?>
	<script language="JavaScript">
		document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
	</script>
	<?php
}

if($_POST["submit"]=="SIMPAN"){

	$kolom = "";
	$isi = "";
	$update = "";
	$query2 = mysql_query($tabel);
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
	
	//$cek=mysql_num_rows(mysql_query("select*from oss_rba_izins where id='$id'"));
	//if($cek == 0){
		//$tambah="insert into oss_rba_izins ($kolom) values ($isi)";
		//$hasil=mysql_query($tambah);
		//if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
	//}else{
		$ubah="update oss_rba_izins set $update where id='$id'";
		$hasil=mysql_query($ubah);
		if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
	//}
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data OSS RBA Izin telah diupdate !');
			document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
		</script>
		<?php
	}
}


?>	
		
<div><span class='judul'>Edit Data OSS RBA Izin</span></div>


<form action='' method='post' autocomplete="off"> 
	<table>
		<?php
		$query = mysql_query($tabel);
		$r= mysql_fetch_array($query);
		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				echo "<tr>";
				$title = "";
				$title = title($name);
				echo "<td><b>$title</b></td><td><b>:</b></td>";
				if($name=="verifikasi") {
					echo "<td width='300'><select name='$name' id='pilih'><option value=''></option>";
						$status=array("Terverifikasi","Pending","Dicabut");
						
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
				else{
					$value = "";
					if($r[$name]) $value = $r[$name];
					echo "<td><input type='text' size='50' name='$name' value='$value' class='readonly' readonly></td>";
				}
				
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