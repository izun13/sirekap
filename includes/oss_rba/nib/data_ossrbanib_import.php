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
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/Reader/Excel2007.php';
require_once 'Classes/PHPExcel/IOFactory.php';

$to_page = "datarbanib";

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
	$field = array ("nib","day_of_tanggal_terbit_oss","nama_perusahaan","status_penanaman_modal","uraian_jenis_perusahaan","alamat_perusahaan","kab_kota","email","nomor_telp");
	//$reset_autoincrement = mysql_query("ALTER TABLE oss_rba_nibs AUTO_INCREMENT = 1077");
	
	//$objPHPExcel = PHPExcel_IOFactory::load("uploads/" . $file_import);
	$objPHPExcel = PHPExcel_IOFactory::load($dir."/".$nama_file);
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

		$worksheetTitle = $worksheet->getTitle();
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = 'J'; //$worksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	 
		for ($row = 2; $row <= $highestRow; ++ $row) {
			
			$i = 0;
			$id = "";
			$isi = "";
			$kolom = "";
			$update = "";
			$dataRow = array();
			for ($col = 1; $col < $highestColumnIndex; ++ $col) {
				$cell = $worksheet->getCellByColumnAndRow($col, $row);
				$val = $cell->getValue();
				$val = trim($val);
				$val = str_replace("'","",$val);
				if($col == 2){
					$tgl = explode("/",$val);
					$val = $tgl[2]."-".$tgl[0]."-".$tgl[1];
				}
				if($id == "")$id = $val;
				$isi .="'".$val."',";
				$kolom .= $field[$i].",";
				$update .= $field[$i]."='".$val."',";
				
				$i++;
			}
			
			$kolom = substr($kolom, 0, -1);
			$isi = substr($isi, 0, -1);
			$update = substr($update, 0, -1);
			//echo "$isi<br>";
			
			$cek=mysql_num_rows(mysql_query("select*from oss_rba_nibs where nib='$id'"));
			if($cek == 0){
				$tambah="insert into oss_rba_nibs ($kolom) values ($isi)";
				$hasil=mysql_query($tambah);
				if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
			}else{
				$r_nib=mysql_fetch_array(mysql_query("select*from oss_rba_nibs where nib='$id'"));
				$ubah="update oss_rba_nibs set $update where id='$r_nib[id]'";
				$hasil=mysql_query($ubah);
				if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
			}
		} 
	}
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data OSS RBA NIB telah diImport !');
			document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
		</script>
		<?php
	}
}


?>	
		
<div><span class='judul'>Import Data OSS RBA NIB</span></div>

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
if($exist) echo "<td>File Terupload </td><td><input type='text' name='openfile' value='$nama_file' class='readonly' readonly></td><td><input type='submit' name='tombol' value='Import'></td>";
?>
</tr>	
</table>
</form>
</body>