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

$to_page = "datapbg";

$dir = "uploads";
$nama_file = $_FILES['file_excel']['name'];
//$ukuran = $_FILES['file_excel']['size'];
//$tipe = $_FILES['file_excel']['type'];
//$extrak = pathinfo($nama_file);
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
	$query = mysql_query("SELECT*FROM tb_pbg");
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);	
		if($name != "id")$field[$i] = $name;
	}

	//$field = array ("nomor","day_of_tanggal_terbit_oss","nama_perusahaan","status_penanaman_modal","uraian_jenis_perusahaan","alamat_perusahaan","kab_kota","email","nomor_telp");
	//$reset_autoincrement = mysql_query("ALTER TABLE tb_pbg AUTO_INCREMENT = 1077");
	
	//$objPHPExcel = PHPExcel_IOFactory::load("uploads/" . $file_import);
	$objPHPExcel = PHPExcel_IOFactory::load($dir."/".$nama_file);
	foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

		$worksheetTitle = $worksheet->getTitle();
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = 'J'; //$worksheet->getHighestColumn(); // e.g 'F'
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
	 
		for ($row = 2; $row <= $highestRow; ++ $row) {
			
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
				
				if($id == "")$id = $val;
				$isi .="'".$val."',";
				$kolom .= $field[$col].",";
				$update .= $field[$col]."='".$val."',";
			}
			
			$kolom = substr($kolom, 0, -1);
			$isi = substr($isi, 0, -1);
			$update = substr($update, 0, -1);
			//echo "$isi<br>";
			
			$cek=mysql_num_rows(mysql_query("select*from tb_pbg where nomor='$id'"));
			if($cek == 0){
				$tambah="insert into tb_pbg ($kolom) values ($isi)";
				$hasil=mysql_query($tambah);
				if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
			}else{
				$r_nomor=mysql_fetch_array(mysql_query("select*from tb_pbg where nomor='$id'"));
				$ubah="update tb_pbg set $update where id='$r_nomor[id]'";
				$hasil=mysql_query($ubah);
				if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
			}
		} 
	}
	
	if ($hasil){
		?>
		<script language="JavaScript">alert('data PBG telah diImport !');
			document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
		</script>
		<?php
	}
}


?>	
		
<div><span class='judul'>Import Data Persetujuan Bangunan Gedung (PBG)</span></div>

<form action='' method='post' autocomplete="off" ENCTYPE="multipart/form-data"> 
<table width="100%">
<tr><td align="left">		
	Upload File Excel &nbsp;<input type="file" name="file_excel" id="file_excel" value=""> 
	<!--<input type="checkbox" name="drop" value="1" /> <u>Kosongkan tabel sql terlebih dahulu.</u>-->
	<input type="submit" name="tombol" value="Upload" onClick="return validateForm()">
	<input type="submit" name="tombol" value="Batal">
</td><td>
<?php
if($exist) echo "File Terupload : <input type='text' name='openfile' value='$nama_file' class='readonly' readonly> <input type='submit' name='tombol' value='Import'>";
?>
</td></tr>	
</table>
</form>
</body>