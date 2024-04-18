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
$search1 = $searching[0]; 
$search2 = $searching[1];
		
$sheet->setCellValue('A1', "DATA PROYEK PENANAMAN MODAL/INVESTASI");
$sheet->setCellValue('A2', "DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU KOTA MAGELANG");
$sheet->setCellValue('A3', "Tanggal : ".tgl1($search1)." s/d ".tgl1($search2));

//$header = array("No.","ID Proyek","Nama Perusahaan","NIB","Status","Jenis Perusahaan","Resiko","Skala Usaha","Alamat Usaha","Kelurahan","Kecamatan","No. Telepon","KBLI","Judul KBLI","Sektor",
//"Jumlah Investasi","Mesin Peralatan","Mesin Peralatan Impor","Pembelian Pematangan Tanah","Bangunan Gedung","Modal Kerja","Lain-lain","Tenaga Kerja");

//$col = "A";
//for($y=0;$y<count($header);$y++){
//$cols = $col;
//$sheet->setCellValue($col.'5',$header[$y]);
//$col++;
//}

$tabel = "SELECT id,id_proyek,nib,nama_perusahaan,uraian_status_penanaman_modal,uraian_jenis_perusahaan,nama_proyek,uraian_risiko_proyek,uraian_skala_usaha,alamat_usaha,
		kecamatan_usaha,kelurahan_usaha,longitude,latitude,kbli,judul_kbli,kl_sektor_pembina,nama_user,nomor_identitas_user,email,nomor_telp,mesin_peralatan,mesin_peralatan_impor,
		pembelian_pematangan_tanah,bangunan_gedung,modal_kerja,lain_lain,jumlah_investasi,tambah_investasi,tki,sektor,status_perusahaan,status_kbli,catatan FROM view_proyek WHERE id IS NOT NULL";		

$query = mysql_query($tabel);
$col = "A";		
for($i = 0; $i < mysql_num_fields($query); $i++){
	$name = mysql_field_name($query, $i);
	$title = str_replace("_"," ",$name);
	$title = ucwords($title);
	$sheet->setCellValue($col.'5',$title);
	$col++;
}
	
		$tabel .= " AND tgl_input >= '$search1' AND tgl_input <= '$search2'";
		
		$x = 2;
		$filters=count($searching);
		for ($j=2;$j<$filters;$j++){
							
			if($searching[$j]){
				$searching2 = explode(":",$searching[$j]);
				if($kol=="")$kol = $searching2[0];
				if($sim=="")$sim = $searching2[1];
				if($val=="")$val = $searching2[2];
				$val = str_replace("_"," ",$val);
			}
			$x++;
				
			if((!empty ($kol)) and (!empty ($val))){
				if(($sim == "LIKE") or ($sim == "NOT LIKE"))$tabel .= " AND ".$kol." ".$sim." '%".$val."%'";
				else $tabel .= " AND ".$kol." ".$sim." '".$val."'";
			}
		}
	
	
	if($act) $tabel .= " GROUP BY nib";
	
	$tabel .= " ORDER BY nama_perusahaan asc";	
	$query = mysql_query($tabel);
	
	$i = 1;
	$j = 6;
	while ($r= mysql_fetch_array($query)){
		$col = "A";
		for($y = 0; $y < mysql_num_fields($query); $y++){
			$name = mysql_field_name($query, $y);
			$cols = $col;
			if($name=="id") $sheet->setCellValue($col.$j,$i);
			/*elseif(($name=="jumlah_investasi") or ($name=="mesin_peralatan") or ($name=="mesin_peralatan_impor") or ($name=="pembelian_pematangan_tanah") 
			or ($name=="bangunan_gedung") or ($name=="modal_kerja") or ($name=="lain_lain")){
				$sheet->setCellValue($col.$j,rupiah($r[$name]));
			}*/
			else{
				$sheet->setCellValue($col.$j,$r[$name]);				
			}
			$col++;
		}			
		$i++;
		$j++;
	}

$writer = new Xlsx($spreadsheet);
$writer->save('Data Investasi.xlsx');
echo "<script>window.location = 'Data Investasi.xlsx'</script>";
?>
