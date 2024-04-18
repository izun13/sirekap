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
if($_POST["search1"]) $search1 = $_POST["search1"];
if($_POST["search2"]) $search2 = $_POST["search2"];

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/Reader/Excel2007.php';
require_once 'Classes/PHPExcel/IOFactory.php';
	
$to_page = "dataproyek";

$dir = "uploads";
$nama_file = $_FILES['file_excel']['name'];
$ukuran = $_FILES['file_excel']['size'];
$tipe = $_FILES['file_excel']['type'];
$extrak = pathinfo($nama_file);
if(($_POST["tombol"] == "Upload") and ($nama_file != ""))move_uploaded_file($_FILES['file_excel']['tmp_name'], $dir."/".$nama_file);

if($nama_file == "")$nama_file = $_POST["openfile"];
$open = opendir($dir) or die('Folder tidak ditemukan ...!');
$exist = "";
while ($file    =readdir($open)) {
	if($file !='.' && $file !='..'){   
		if($nama_file == $file) $exist = 1;
	}
}

if($_POST["tombol"]=="Batal"){
	?>
	<script language="JavaScript">
		document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
	</script>
	<?php
}

if($_POST["tombol"]=="Import"){
	/*if(($search1 == "") or ($search2 == "")){
		?>
			<script language="JavaScript">alert('Bulan dan Tahun Laporan Harus Diisi !');
			</script>
		<?php	
	}else{*/
	
		$field = array ("id_proyek","nib","nama_perusahaan","tgl_terbit_oss","uraian_status_penanaman_modal","uraian_jenis_perusahaan","uraian_risiko_proyek","nama_proyek",
		"uraian_skala_usaha","alamat_usaha","kab_kot_kantor_pusat","kecamatan_usaha","kelurahan_usaha","longitude","latitude","kbli","judul_kbli","kl_sektor_pembina","nama_user","nomor_identitas_user",
		"email","nomor_telp","luas_tanah","satuan_tanah","mesin_peralatan","mesin_peralatan_impor","pembelian_pematangan_tanah","bangunan_gedung","modal_kerja","lain_lain","jumlah_investasi","tki");
		
		//"id_proyek","nib","npwp_perusahaan","nama_perusahaan","uraian_status_penanaman_modal","uraian_jenis_perusahaan","uraian_risiko_proyek","uraian_skala_usaha","alamat_usaha","kecamatan_usaha","kelurahan_usaha",
		//"longitude","latitude","kbli","judul_kbli","kl_sektor_pembina","nama_user","nomor_identitas_user","email","nomor_telp","mesin_peralatan","mesin_peralatan_impor","pembelian_pematangan_tanah",
		//"bangunan_gedung","modal_kerja","lain_lain","jumlah_investasi","tki",tgl_verifikasi

		//$reset_autoincrement = mysql_query("ALTER TABLE oss_rba_proyeks AUTO_INCREMENT = 1482");
		
		//$objPHPExcel = PHPExcel_IOFactory::load("uploads/" . $file_import);
		$objPHPExcel = PHPExcel_IOFactory::load($dir."/".$nama_file);
		
		$date = date("Y-m-d");
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

			$worksheetTitle = $worksheet->getTitle();
			$highestRow = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn = 'AG'; //$worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		 
			for ($row = 2; $row <= $highestRow; ++ $row) {
				
				$i = 0;
				$id = "";
				$nib = "";
				$isi = "";
				$kolom = "";
				$update = "";
				$dataRow = array();
				for ($col = 1; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, $row);
					$val = $cell->getValue();
					$val = trim($val);
					$val = str_replace("'","",$val);
					if($col == 4){
						$tgl = explode("/",$val);
						$val = $tgl[2]."-".$tgl[1]."-".$tgl[0];
					}
				
					if($col == 1)$id = $val;
					//if($col == 2)$nib = $val;
					$isi .="'".$val."',";
					$kolom .= $field[$i].",";
					$update .= $field[$i]."='".$val."',";
					
					$i++;
				}
				
				//$kolom = substr($kolom, 0, -1);
				//$isi = substr($isi, 0, -1);
				$update = substr($update, 0, -1);
				
				$kolom .= "tgl_input";
				$isi .= "'".$date."'";
				
				if($_POST["periode"]){
					$kolom .= ",periode";
					$isi .= ",'".$_POST["periode"]."'";
					$update .= ",periode='".$_POST["periode"]."'";
				}
				
				$cek=mysql_num_rows(mysql_query("select*from oss_rba_proyeks where id_proyek='$id'"));
				if($cek == 0){
					//$kolom .= ",status_perusahaan";
					//$cek_nib = mysql_num_rows(mysql_query("SELECT*FROM oss_rba_nib WHERE nib='$nib'"));
					//if($cek_nib == 0) $isi .= ",'Lama'"; else $isi .= ",'Baru'";
					$tambah="insert into oss_rba_proyeks ($kolom) values ($isi)";
					$hasil=mysql_query($tambah);
					if (!$hasil) echo "Input Gagal :".mysql_error().$kolom."--".$isi."<br>";
				}else{
					$r_proyek=mysql_fetch_array(mysql_query("select*from oss_rba_proyeks where id_proyek='$id'"));
					$ubah="update oss_rba_proyeks set $update where id='$r_proyek[id]'";
					$hasil=mysql_query($ubah);
					if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
					//echo "Kode Proyek : ".$id." sudah ada <br>";
				}
			} 
		}
		
		if ($hasil){
			?>
			<script language="JavaScript">alert('data OSS RBA Izin telah diImport !');
				document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
			</script>
			<?php
		}
	//}
}


?>	
		
<div><span class='judul'>Import Data OSS RBA Proyek</span></div>

<form action='' method='post' autocomplete="off" ENCTYPE="multipart/form-data"> 
<table>
<tr><td>Upload File Excel</td>
	<td><input type="file" name="file_excel" id="file_excel" value=""></td>
	<td><input type="submit" name="tombol" value="Upload" onClick="return validateForm()">
	<input type="submit" name="tombol" value="Batal"></td>
</tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr>
<?php
if($exist){
	echo "<td>File Terupload </td><td><input type='text' name='openfile' value='$nama_file' class='readonly' readonly> periode : <input type='text' size='10' name='periode' value='".$_POST["periode"]."' id='inputField1' class='search'></td>";
	echo "<td><input type='submit' name='tombol' value='Import'></td>";
}
?>
</tr>	
</table>
</form>
</body>