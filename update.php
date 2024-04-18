<?php
include "includes/parser-php-version.php";
require_once "includes/cipher.php";
error_reporting(E_ALL);
//mysql_connect("localhost","root","");
mysql_connect("localhost","dpmptsp","Pass*010101");
mysql_select_db("db_perizinan");

//require_once 'Classes/PHPExcel.php';
//require_once 'Classes/PHPExcel/Reader/Excel2007.php';
//require_once 'Classes/PHPExcel/IOFactory.php';
//$tabel = "select*from oss_rba_proyek where bulan='Mei' and tahun='2022'";		
//$query = mysql_query($tabel);
//while ($r= mysql_fetch_array($query)){

/*$id_proyek = array ("R-202207171002460117754","R-202206100729364234611","R-202207120752347312366","R-202207170920371004290","R-202207211106005298640","R-202207120933318692046","R-202207121353435214157","R-202207141942293824527",
"R-202207121342306353756","R-202207121114453819158","R-202207111117414618283","R-202207201114384955167","R-202207131154126316418","R-202207120944521836992","R-202207192242260439545","R-202207031531136572299",
"R-202207251406581896943","R-202207111630579258379","R-202207251407275705188","R-202207181139521176434","R-202207072258530574315","R-202207191507543057510","R-202207080845435004804","R-202207131048201482373",
"R-202207250743367882162","R-202207071307296097412","R-202202131717279501037","R-202207040901370085091","R-202207121118320443556");
*/

$tabel = "SELECT*FROM tb_pbg ORDER BY id asc";	
$query = mysql_query($tabel);
while ($r= mysql_fetch_array($query)){
	$id = $r["id"];
	//$password = $r["password"];
	//$password = password_hash($password, PASSWORD_DEFAULT);
	
	$input="insert into tb_tanah (pbg_id,hak_tanah,luas_tanah,pemilik_tanah) values ('$r[id]','$r[hak_tanah]','$r[luas_tanah]','$r[pemilik_tanah]')";
	//$ubah="update petugas set password='$password' where id='$id'";
	$hasil=mysql_query($input);
	if ($hasil) echo "Update Sukses !<br>";
	else "Update Gagal !<br>";
}
		
/*$objPHPExcel = PHPExcel_IOFactory::load("Book1.xlsx");
		
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

			$worksheetTitle = $worksheet->getTitle();
			$highestRow = $worksheet->getHighestRow(); // e.g. 10
			//$highestColumn = 'B'; //$worksheet->getHighestColumn(); // e.g 'F'
			//$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		 
			for ($row = 2; $row <= $highestRow; ++ $row) {
				
				$cell = $worksheet->getCellByColumnAndRow(1,$row);
				$val = $cell->getValue();
				$id = trim($val);
								
				$ubah="update oss_rba_proyek set inactive='1' where id_proyek='$id'";
				$hasil=mysql_query($ubah);
				if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
				else echo "Update Berhasil !<br>";
			} 
		}
*/
?>