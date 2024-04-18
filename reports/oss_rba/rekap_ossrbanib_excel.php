<?php
include "../../includes/parser-php-version.php";
require_once "../../includes/tanggal.php";
require_once "../../includes/rupiah.php";
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();	
		
error_reporting(E_ALL);

/** PhpSpreadsheet */
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// sheet peratama
$sheet->setTitle('Sheet 1');

$act = $_GET["act"];
$searching = explode(";",$_GET["search"]);
$filters=count($searching);
$search1 = $searching[0]; 
$search2 = $searching[1];
		
$sheet->setCellValue('A1', "DATA NIB PENANAMAN MODAL/INVESTASI");
$sheet->setCellValue('A2', "DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU KOTA MAGELANG");
$sheet->setCellValue('A3', "Tanggal Terbit NIB: ".tgl1($search1)." s/d ".tgl1($search2));

//$header = array("No.","ID Proyek","Nama Perusahaan","NIB","Status","Jenis Perusahaan","Resiko","Skala Usaha","Alamat Usaha","Kelurahan","Kecamatan","No. Telepon","KBLI","Judul KBLI","Sektor",
//"Jumlah Investasi","Mesin Peralatan","Mesin Peralatan Impor","Pembelian Pematangan Tanah","Bangunan Gedung","Modal Kerja","Lain-lain","Tenaga Kerja");

//$col = "A";
//for($y=0;$y<count($header);$y++){
//$cols = $col;
//$sheet->setCellValue($col.'5',$header[$y]);
//$col++;
//}

 //id,id_proyek,nib,npwp_perusahaan,nama_perusahaan,uraian_status_penanaman_modal,uraian_jenis_perusahaan,uraian_risiko_proyek,uraian_skala_usaha,alamat_usaha,
		//kecamatan_usaha,kelurahan_usaha,longitude,latitude,kbli,judul_kbli,kl_sektor_pembina,nama_user,nomor_identitas_user,email,nomor_telp,mesin_peralatan,mesin_peralatan_impor,
		//pembelian_pematangan_tanah,bangunan_gedung,modal_kerja,lain_lain,jumlah_investasi,tki,sektor_id,sektor
		
$tabel = "SELECT*FROM oss_rba_nibs WHERE id IS NOT NULL";		

$query = mysql_query($tabel);
$col = "A";		
for($i = 0; $i < mysql_num_fields($query); $i++){
	$name = mysql_field_name($query, $i);
	$name = str_replace("_"," ",$name);
	$sheet->setCellValue($col.'5',$name);
	$col++;
}
	
		if(($search1 != "") and ($search2 != ""))$tabel .= " AND day_of_tanggal_terbit_oss >= '$search1' AND day_of_tanggal_terbit_oss <= '$search2'";
		
		/*for ($x=0;$x<$filters;$x++){
							
			if($searching[$x]){
				$searching2 = explode(":",$searching[$x]);
				$kol = $searching2[0];
				$sim = $searching2[1];
				$val = $searching2[2];
				$val = str_replace("_"," ",$val);
			}
				
			if((!empty ($kol)) and (!empty ($val))){
				if(($sim == "LIKE") or ($sim == "NOT LIKE"))$tabel .= " AND ".$kol." ".$sim." '%".$val."%'";
				else $tabel .= " AND ".$kol." ".$sim." '".$val."'";
			}
		}*/
	
	
	//if($act == 1) $tabel .= " GROUP BY nama_perusahaan";
	//if($act == 2) $tabel .= " GROUP BY alamat_usaha";
	
	//if($act)$tabel .= " GROUP BY nib";	
	$query = mysql_query($tabel);
	
	$i = 1;
	$j = 6;
	while ($r= mysql_fetch_array($query)){
		$col = "A";
		for($y = 0; $y < mysql_num_fields($query); $y++){
			$name = mysql_field_name($query, $y);
			$cols = $col;
			if($name=="id") $sheet->setCellValue($col.$j,$i);
			//elseif($name=="jumlah_investasi")$sheet->setCellValue($col.$j,rupiah($r[$name]));
			elseif(($name=="nib") or ($name=="nomor_telp")) $sheet->setCellValue($col.$j,"_".($r[$name]));
			elseif($name=="day_of_tanggal_terbit_oss") $sheet->setCellValue($col.$j,tgl1($r[$name]));
			else $sheet->setCellValue($col.$j,$r[$name]);				
			$col++;
		}			
		$i++;
		$j++;
	}

$writer = new Xlsx($spreadsheet);
$writer->save('Data NIB.xlsx');
echo "<script>window.location = 'Data NIB.xlsx'</script>";
?>
