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

$filters=count($searching)-3;

$sheet->setCellValue('A1', "");
$sheet->setCellValue('A2', "DATA PROYEK PENANAMAN MODAL/INVESTASI");
$sheet->setCellValue('A3', "DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU KOTA MAGELANG");
//$sheet->setCellValue('A3', "Tanggal : ".tgl1($search1)." s/d ".tgl1($search2));

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
		
$tabel = "SELECT id,nama_perusahaan,nib,uraian_status_penanaman_modal,uraian_jenis_perusahaan,uraian_risiko_proyek,uraian_skala_usaha,alamat_usaha,kelurahan_usaha,nama_user,nomor_identitas_user,email,nomor_telp,
		kbli,judul_kbli,jumlah_investasi,tki,periode FROM oss_rba_proyeks WHERE id IS NOT NULL";		

$query = mysql_query($tabel);
$col = "A";		
for($i = 0; $i < mysql_num_fields($query); $i++){
	$name = mysql_field_name($query, $i);
	$title = str_replace("_"," ",$name);
	$title = ucwords($title);
	$sheet->setCellValue($col.'5',$title);
	$col++;
}
	
		if(($search1 != "") and ($search2 != ""))$tabel .= " AND periode >= '$search1' AND periode <= '$search2'";
		
		$x = 2;
		for ($j=1;$j<=$filters;$j++){;
						
			if($searching[$x]){
				$searching2 = explode(":",$searching[$x]);
				if($kol=="")$kol = $searching2[0];
				if($sim=="")$sim = $searching2[1];
				if($val=="")$val = $searching2[2];
				$val = str_replace("_"," ",$val);
			}
			$x++;
				
			if((!empty ($kol)) and (!empty ($val))){
				if(($sim == "LIKE") or ($sim == "NOT LIKE"))$tabel .= " AND ".$kol." ".$sim." '%".$val."%'";
				else $tabel .= " AND ".$kol." ".$sim." '".$val."'";
				$val = str_replace(" ","_",$val);
				$search .= $kol.":".$sim.":".$val.";";
			}
		}
	
	
	//if($act == 1) $tabel .= " GROUP BY nama_perusahaan";
	//if($act == 2) $tabel .= " GROUP BY alamat_usaha";
	
	if($act)$tabel .= " GROUP BY nib";	
	$query = mysql_query($tabel);
	
	$i = 1;
	$j = 6;
	while ($r= mysql_fetch_array($query)){
		$col = "A";
		for($y = 0; $y < mysql_num_fields($query); $y++){
			$name = mysql_field_name($query, $y);
			$cols = $col;
			if($name=="id") {$sheet->setCellValue($col.$j,$i);}
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
$writer->save('Data Proyek.xlsx');
echo "<script>window.location = 'Data Proyek.xlsx'</script>";
?>
