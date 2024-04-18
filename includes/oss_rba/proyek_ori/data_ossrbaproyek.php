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
  
<script type="text/javascript">
function tampilkan(){
        document.getElementById("formulir").submit();
}
</script>
<body>
<?php
$recpage = 25;
$to_page = "dataproyek_ori";

if($search){
	$searching = explode(";",$search);
	$search1 = $searching[0]; 
	$search2 = $searching[1]; 
}

if($_POST["search1"]) $search1 = $_POST["search1"];
if($_POST["search2"]) $search2 = $_POST["search2"];

if($search) $filters=count($searching)-3;
if(empty($filters))$filters = 1;
if($_POST["filters"])$filters = $_POST["filters"];
if($_POST["tambah"]) $filters++;
if($_POST["hapus"]) $filters--;

$group = $_POST["group"];
if($act)$group=$act;
?>	
		
<div><span class='judul'>Data OSS RBA Proyek All (https://oss.go.id/)</span></div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off" id="formulir"> 
<table>
<?php
//"id_proyek","nib","npwp_perusahaan","nama_perusahaan","uraian_status_penanaman_modal","uraian_jenis_perusahaan","uraian_risiko_proyek","uraian_skala_usaha","alamat_usaha","kecamatan_usaha","kelurahan_usaha",
//"longitude","latitude","kbli","judul_kbli","kl_sektor_pembina","nama_user","nomor_identitas_user","email","nomor_telp","jumlah_investasi","mesin_peralatan","mesin_peralatan_impor","pembelian_pematangan_tanah",
//"bangunan_gedung","modal_kerja","lain_lain","tki",uraian_jenis_perizinan,sektor,
$tabel = "SELECT id,nama_perusahaan,nib,uraian_status_penanaman_modal,uraian_jenis_perusahaan,nama_proyek,uraian_risiko_proyek,uraian_skala_usaha,
		kbli,judul_kbli,jumlah_investasi,periode FROM oss_rba_proyeks WHERE id IS NOT NULL";	//day_of_tgl_izin,uraian_jenis_perizinan,
		
		//(mesin_peralatan+mesin_peralatan_impor+modal_kerja+lain_lain) AS jumlah_investasi
		
Function title($name){
	if($name == "id")$title = "ID";
	if($name == "nama_perusahaan")$title = "Nama Perusahaan";
	if($name == "nib")$title = "NIB";
	//if($name == "day_of_tgl_izin")$title = "Tgl Terbit Izin";
	//if($name == "uraian_jenis_perizinan")$title = "Jenis Izin";
	if($name == "uraian_status_penanaman_modal")$title = "Status";
	if($name == "uraian_jenis_perusahaan")$title = "Jenis Perusahaan";
	if($name == "nama_proyek")$title = "Nama Usaha";
	if($name == "uraian_risiko_proyek")$title = "Resiko";
	if($name == "uraian_skala_usaha")$title = "Skala Usaha";
	//if($name == "alamat_usaha")$title = "Alamat Usaha";
	if($name == "kbli")$title = "KBLI";
	if($name == "judul_kbli")$title = "Judul KBLI";
	//if($name == "sektor")$title = "Sektor";
	if($name == "jumlah_investasi")$title = "Jumlah Investasi";
	if($name == "tki")$title = "Tenaga Kerja";
	if($name == "periode")$title = "Periode";
	
	return $title;
}	
echo "<tr><td width='150px'>Periode Proyek</td><td>:</td><td width='250px'><input type='text' size='10' name='search1' value='$search1' id='inputField1' class='search'>";
echo " s/d  <input type='text' size='10' name='search2' value='$search2' id='inputField2' class='search'></td></tr>";

$x = 2;
for ($j=1;$j<=$filters;$j++){
	$kolom = "kolom".$j;
	$simbol = "simbol".$j;
	$value = "value".$j;
	$pilih = "pilih".$j;
	
	$kol = $_POST[$kolom];
	$sim = $_POST[$simbol];
	$val = $_POST[$value];
	
	if($searching[$x]){
		$searching2 = explode(":",$searching[$x]);
		if($kol=="")$kol = $searching2[0];
		if($sim=="")$sim = $searching2[1];
		if($val=="")$val = $searching2[2];
		$val = str_replace("_"," ",$val);
	}
	$x++;
					
	echo "<tr valign='middle'>";
	echo "<td width='100px'>Filter Kolom </td><td>:</td>";
	echo "<td width='230px'><select name='$kolom' id='$pilih'><option value=''>Pilih Kolom</option>";
		$query = mysql_query($tabel);
		for($i = 0; $i < mysql_num_fields($query); $i++){
			$name = mysql_field_name($query, $i);
			//$type[$name] = mysql_field_type($query, $i);
			$selected = "";
			if($name == $kol)$selected = "selected";
			$title = "";
			$title = title($name);
			if(($name != "id")) echo"<option value='".$name."' $selected>".$title."</option>";	
		}		
	echo "</select></td>";
	
	echo "<td width=''><select name='$simbol'>";
		$str_simbol = array("=","!=","<=",">=","LIKE","NOT LIKE");
		for($i = 0; $i<count($str_simbol); $i++){
			$selected = "";
			if($str_simbol[$i] == $sim)$selected = "selected";
			echo"<option value='".$str_simbol[$i]."' $selected>".$str_simbol[$i]."</option>";	
		}		
	echo "</select></td>";
	echo "<td><input type='text' name='$value' size='20' value='".$val."'></td>";
	
	echo "<td>";
	if($j == $filters){
		if($filters>1) echo "<input type='submit' name='hapus' value='-' title='Hapus Filter'> &nbsp;";
		echo "<input type='submit' name='tambah' value='+' title='Tambah Filter'><input type='hidden' name='filters' value='$filters'>";
	}
	echo "</td></tr>";
	
	?>
	<script type="text/javascript">
        $('#<?php echo $pilih;?>').selectize({
            create: true,
            sortField: 'text'
        });
	</script>
	<?php
}	
?>
<tr><td colspan="5" align="right"><input type='submit' name='tombol' value='Tampilkan'></td>
</tr>
</table>

<?php
/*if($_POST["tombol"] == "Tampilkan"){
	
	if(($search1 == "") or ($search2 == "")){
		?>
			<script language="JavaScript">alert('Periode Tanggal Terbit Izin Harus Diisi !');
			document.location.href='?send=<?php echo $to_page;?>';
			</script>
		<?php	
	}
}*/

	if(($search1 != "") and ($search2 != ""))$tabel .= " AND periode >= '$search1' AND periode <= '$search2'";
		
		$search = $search1.";".$search2.";";
		$x = 2;
		for ($j=1;$j<=$filters;$j++){
			$kolom = "kolom".$j;
			$simbol = "simbol".$j;
			$value = "value".$j;
			$kol = $_POST[$kolom];
			$sim = $_POST[$simbol];
			$val = $_POST[$value];
						
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
				
if($group)	$checked = "checked";
?>

<table width="100%">
<tr><td>Group by NIB Perusahaan: 
<?php
$checked = "";
if($group == 1)$checked = "checked";
echo "<input type='checkbox' name='group' value='1' onchange='tampilkan()' $checked>";
/*$checked = "";
if($group == 2)$checked = "checked";
echo "<input type='checkbox' name='group' value='2' onchange='tampilkan()' $checked> Alamat Perusahaan ";
$checked = "";
if($group == "")$checked = "checked";
echo "<input type='checkbox' name='group' value='' onchange='tampilkan()' $checked> Semua ";*/

	echo"<a href='reports/oss_rba/rekap_ossrbaproyekall_excel.php?search=$search&act=$group' target='_blank'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	//echo"&nbsp; <a href='reports/oss_rba_ori/rekap_ossrbaproyek_pdf.php?search=$search'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
?>
</td><td align="center">
<?php
	//if (($opd_id == 1)or($opd_id == 0))echo"<a href='?send=$to_page-verifikasi//$starting/$search' class='tombol'>VERIFIKASI</a>&nbsp;";
	//if (($opd_id == 0))echo"<a href='?send=$to_page-import'><img src=\"img/import.jpg\" width='50' title='import excel'></img></a>";//($opd_id == 1)or
?>
</td></tr>
</table>
</form>

<!--<div id="border">
* Klik judul kolom untuk mengurutkan.-->
	<table class="tabelbox1">
		<?php
		//$order = "asc";
		//if(strpos($act,"asc")) $order = "desc";
		//if(strpos($act,"desc")) $order = "asc";
		if($group == 1) $tabel .= " GROUP BY nib";
		//if($group == 2) $tabel .= " GROUP BY alamat_usaha";
		$tabel .= " ORDER BY nama_perusahaan asc";
		//if(strpos($act,"asc")) {$kolom = preg_replace("/asc/","",$act); $tabel .= " ORDER BY $kolom asc";}
		//if(strpos($act,"desc")) {$kolom = preg_replace("/desc/","",$act); $tabel .= " ORDER BY $kolom desc";}
		
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$act = $group;
		page($query,$recpage,$starting,$to_page,$search,$act);
		
		$starting = starting();
		$recpage = recpage();
		
		echo "<tr>";
		echo "<th>No.</th>";
		
		for($i = 0; $i < mysql_num_fields($query); $i++){
			$name = mysql_field_name($query, $i);
			//$type = mysql_field_type($query, $i);
			//$size = mysql_field_len($query, $i);
			//echo $name.' - '.$type.' - '.$size.'<br>';
			//$urutkan = $name.$order;
			//echo "<th><a href='?send=$to_page////$urutkan'>$name</a></th>";
			
			$title = "";
			$title = title($name);
			if($name != "id") echo "<th>$title</th>";
		}
		//echo "<th>Edit</th>";
		echo "</tr>";
		
		// Nampilin Data				
		$query = mysql_query($tabel." LIMIT $starting,$recpage");
		$current = $_REQUEST["x"];
		$current1 = 0;
		
		$baris=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			if($current1 == 0)$current1 = $r[id];		
			if(!$current)$current = $current1;
			$id = $r[id];
			
			if($baris % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			echo "<td align='center'>$baris</td>";
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				if($name != "id"){
					if($name == "jumlah_investasi" or $name == "tambah_investasi") echo "<td align='right'>".rupiah($r[$name])."</td>";
					elseif($name == "tki") echo "<td align='center'>$r[$name]</td>";
					else echo "<td align='left'>$r[$name]</td>";
				}
				//elseif(($name == "status_nib") or ($name == "jml_tenaga_kerja")) echo "<td align='center'>$r[$name]</td>";
			}
			
			//echo "<td align='center'><a href='?send=$to_page-edit/$r[id]/$starting/$search'><img src='img/edit.png' width='25' title='Edit Data'></img></a></td>";
			echo "</tr>";
			$baris++;
		}
		?>
		
</table>

<table width='100%'>
<tr><td>
	<?php
	$page_showing = page_showing();
	echo show_navi();
	?>
</td></tr>
</table>
<!--</div>-->
<?php
//}
?>
</body>