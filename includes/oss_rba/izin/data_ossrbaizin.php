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
$to_page = "datarbaizin";

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
		
<div><span class='judul'>Data OSS RBA Izin</span></div>


<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off" id="formulir"> 
<table>
<?php
//$tabel = "SELECT id,nama_perusahaan,nib,id_proyek,day_of_tgl_izin,uraian_status_penanaman_modal,kd_resiko,
		//uraian_jenis_perizinan,nama_dokumen,uraian_status_respon,kbli,uraian_kbli,sektor FROM view_ossrbaizin WHERE id IS NOT NULL";	
		//,verifikasi,tgl_verifikasi,day_of_tanggal_terbit_oss

$tabel = "SELECT id,day_of_tgl_izin,nama_perusahaan,nib,uraian_status_penanaman_modal,kd_resiko,uraian_jenis_perizinan,
			nama_dokumen,uraian_status_respon,id_proyek,kbli,uraian FROM view_ossrbaizin WHERE id IS NOT NULL";
Function title($name){
	if($name == "id")$title = "ID";
	if($name == "nama_perusahaan")$title = "Nama Perusahaan";
	if($name == "nib")$title = "NIB";
	//if($name == "day_of_tanggal_terbit_oss")$title = "Tanggal Terbit";
	if($name == "uraian_status_penanaman_modal")$title = "Status";
	if($name == "id_proyek")$title = "Kode Proyek";
	if($name == "day_of_tgl_izin")$title = "Tanggal Izin";
	if($name == "kd_resiko")$title = "Kode Resiko";
	if($name == "uraian_jenis_perizinan")$title = "Jenis Perizinan";
	if($name == "nama_dokumen")$title = "Nama Dokumen";
	if($name == "uraian_status_respon")$title = "Status Respon";
	if($name == "kbli")$title = "KBLI";
	if($name == "uraian")$title = "Uraian KBLI";
	//if($name == "sektor")$title = "Sektor";
	//if($name == "verifikasi")$title = "Status Verifikasi";
	//if($name == "tgl_verifikasi")$title = "Tanggal Verifikasi";
	
	return $title;
}	

echo "<tr><td width='150px'>Tanggal Terbit Izin</td><td>:</td><td width='250px'><input type='text' size='10' name='search1' value='$search1' id='inputField1' class='search'>";
echo " s/d  <input type='text' size='10' name='search2' value='$search2' id='inputField2' class='search'></td></tr>";
/*echo "<tr><td width='150px'>Bulan</td><td>:</td><td colspan='2'><select name='search1' id=''>";
	for($i = 0; $i < count($NAMA_BULAN); $i++){
		$selected = "";
		$bln = $i;
		if(strlen($bln) == 1) $bln = "0".$bln;
		if($bln == $search1)$selected = "selected";
		echo"<option value='".$bln."' $selected>".$NAMA_BULAN[$i]."</option>";	
	}		
echo "</select>";
echo " Tahun : <input type='text' size='10' name='search2' value='$search2' id='' class='search'></td></tr>";*/

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
				
	echo "<tr>";
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
			if($name != "id") echo"<option value='".$name."' $selected>".$title."</option>";	
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
<tr><td colspan="5" align="right"><input type='submit' name='tombol' value='Tampilkan'></td></tr>
</table>
<?php
/*if($_POST["tombol"] == "Tampilkan"){
	
	if(($search1 == "") or ($search2 == "")){
		?>
			<script language="JavaScript">alert('Periode Tanggal Pengajuan/Penetapan Harus Diisi !');
			document.location.href='?send=<?php echo $to_page;?>';
			</script>
		<?php	
	}
}*/

//if(($search1 != "") and ($search2 != "")){
	//$blnthn = $search2."-".$search1;
	//if(($search1 != "") and ($search2 != ""))$tabel .= " AND day_of_tgl_izin LIKE '%$blnthn%'";
	if(($search1 != "") and ($search2 != ""))$tabel .= " AND day_of_tgl_izin >= '$search1' AND day_of_tgl_izin <= '$search2'";
		
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
<tr><td>Group by Nama Perusahaan : <input type='checkbox' name='group' onchange="tampilkan()" <?php echo $checked;?>></td>
<td>
<?php
	//if (($opd_id == 1)or($opd_id == 0))echo"<a href='?send=$to_page-verifikasi//$starting/$search' class='tombol'>VERIFIKASI</a>&nbsp;";
?>
</td>
<?php
	if (($opd_id == 1)or($opd_id == 0))echo"<td><a href='?send=$to_page-import'><img src=\"img/import.jpg\" width='50' title='import excel'></img></a></td>";
	//echo"&nbsp; <a href='reports/oss_rba/rekap_ossrbaizin_pdf.php?search=$search'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
	echo"<td align='right'><a href='reports/oss_rba/rekap_ossrbaizin_excel.php?search=$search' target='_blank'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	echo"&nbsp; <a href='reports/oss_rba/rekap_izin_json.php?send=$search1;$search2' target='_blank'><img src=\"img/json.png\" width='38' title='Cetak Json'></img></a></td>";
?>
</tr>
</table>
</form>

<!--<div id="border">
* Klik judul kolom untuk mengurutkan.-->
	<table class="tabelbox1">
		<?php
		//$order = "asc";
		//if(strpos($act,"asc")) $order = "desc";
		//if(strpos($act,"desc")) $order = "asc";
				
		if($group) $tabel .= " GROUP BY nama_perusahaan ORDER BY id asc";
		
		//if(strpos($act,"asc")) {$kolom = preg_replace("/asc/","",$act); $tabel .= " ORDER BY $kolom asc";}
		//if(strpos($act,"desc")) {$kolom = preg_replace("/desc/","",$act); $tabel .= " ORDER BY $kolom desc";}
		
		//echo $tabel;
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
				//if($name == "modal_usaha") echo "<td align='right'>".rupiah($r[$name])."</td>";
				//elseif(($name == "status_nib") or ($name == "jml_tenaga_kerja")) echo "<td align='center'>$r[$name]</td>";
				if($name != "id") echo "<td align='left'>$r[$name]</td>";
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