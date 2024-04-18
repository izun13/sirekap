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
$to_page = "dataproyek";
//"id_proyek","nib","npwp_perusahaan","nama_perusahaan","uraian_status_penanaman_modal","uraian_jenis_perusahaan","uraian_risiko_proyek","uraian_skala_usaha","alamat_usaha","kecamatan_usaha","kbli","judul_kbli");
//"jumlah_investasi","mesin_peralatan","mesin_peralatan_impor","pembelian_pematangan_tanah","bangunan_gedung","modal_kerja","lain_lain","tki"
$tabel = "SELECT id,nama_perusahaan,nib,uraian_status_penanaman_modal,uraian_jenis_perusahaan,uraian_risiko_proyek,uraian_skala_usaha,alamat_usaha,kecamatan_usaha,
		kbli,judul_kbli,jumlah_investasi,mesin_peralatan,mesin_peralatan_impor,pembelian_pematangan_tanah,bangunan_gedung,modal_kerja,lain_lain,tki,
		verifikasi,tgl_verifikasi FROM oss_rba_proyeks WHERE id='$id'";	
		
Function title($name){
	if($name == "id")$title = "ID";
	if($name == "nama_perusahaan")$title = "Nama Perusahaan";
	if($name == "nib")$title = "NIB";
	if($name == "uraian_status_penanaman_modal")$title = "Status";
	if($name == "uraian_jenis_perusahaan")$title = "Jenis Perusahaan";
	if($name == "uraian_risiko_proyek")$title = "Resiko";
	if($name == "uraian_skala_usaha")$title = "Skala Usaha";
	if($name == "alamat_usaha")$title = "Alamat Usaha";
	if($name == "kecamatan_usaha")$title = "Kecamatan Usaha";
	if($name == "kbli")$title = "KBLI";
	if($name == "judul_kbli")$title = "Judul KBLI";
	if($name == "jumlah_investasi")$title = "Jumlah Investasi";
	if($name == "mesin_peralatan")$title = "Mesin Peralatan";
	if($name == "mesin_peralatan_impor")$title = "Mesin Peralatan Impor";
	if($name == "pembelian_pematangan_tanah")$title = "Pembelian Pematangan Tanah";
	if($name == "bangunan_gedung")$title = "Bangunan Gedung";
	if($name == "modal_kerja")$title = "Modal Kerja";
	if($name == "lain_lain")$title = "Lain-lain";
	if($name == "tki")$title = "Tenaga Kerja";
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
	
	//$cek=mysql_num_rows(mysql_query("select*from oss_rba_proyeks where id='$id'"));
	//if($cek == 0){
		//$tambah="insert into oss_rba_proyeks ($kolom) values ($isi)";
		//$hasil=mysql_query($tambah);
		//if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
	//}else{
		$ubah="update oss_rba_proyeks set $update where id='$id'";
		$hasil=mysql_query($ubah);
		if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
	//}
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data OSS RBA Proyek telah diupdate !');
			document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
		</script>
		<?php
	}
}


?>	
		
<div><span class='judul'>Edit Data OSS RBA Proyek</span></div>


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
						$status=array("Terverifikasi","Belum Verifikasi","Dicabut");
						
						for($j=0;$j<=2;$j++){
							$selected = "";
							if($status[$j] == $r[$name])$selected = "selected";
							echo"<option value='".$status[$j]."' $selected>".$status[$j]."</option>";			
						}
					echo"</select></td>";
				}
				elseif(($name=="jumlah_investasi") or ($name=="mesin_peralatan") or ($name=="mesin_peralatan_impor") or ($name=="pembelian_pematangan_tanah") 
						or ($name=="bangunan_gedung") or ($name=="modal_kerja") or ($name=="lain_lain") or ($name=="tki")){
					$value = "";
					if($r[$name]) $value = $r[$name];
					echo "<td><input type='text' size='20' name='$name' value='$value'></td>";
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