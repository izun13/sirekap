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
$recpage = 25;
$to_page = "datainvestasi";

$search = explode(";",$search);
$search1 = $search[0]; 
$search2 = $search[1]; 
$search2 = str_replace("_"," ",$search2);
$search3 = $search[2]; 
$search4 = $search[3]; 
if($_POST["search1"]) $search1=$_POST["search1"];
if($_POST["search2"]) $search2=$_POST["search2"];
if($_POST["search3"]) $search3=$_POST["search3"];
if($_POST["search4"]) $search4=$_POST["search4"];


$tabel = "SELECT id,tahun,bulan,nama_perusahaan,bidang_usaha,jenis_modal,nilai_investasi,jumlah_tk,jumlah_tka,no_izin,kegiatan_usaha,no_telepon FROM realisasi_investasi WHERE id IS NOT NULL";
Function title($name){
	if($name == "id")$title = "ID";
	if($name == "tahun")$title = "Tahun";
	if($name == "bulan")$title = "Bulan";
	if($name == "nama_perusahaan")$title = "Nama Perusahaan";
	if($name == "bidang_usaha")$title = "Bidang Usaha";
	if($name == "jenis_modal")$title = "Jenis Modal";
	if($name == "nilai_investasi")$title = "Nilai Investasi";
	if($name == "jumlah_tk")$title = "Jumlah TKI";
	if($name == "jumlah_tka")$title = "Jumlah TKA";
	if($name == "no_izin")$title = "Nomor Izin";
	if($name == "kegiatan_usaha")$title = "Kegiatan Usaha";
	if($name == "uraian_jenis_perizinan")$title = "Jenis Perizinan";
	if($name == "no_telepon")$title = "No. Telp";
	return $title;
}
?>	
		
<div><span class='judul'>Data Realisasi Investasi</span></div>
<table width="100%">
<tr><td>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table>
<tr>
<td width="100px">Filter Kolom </td><td>:</td>
<td width="230px">
		<select name="search1" id="pilih2">
			<option value="">Pilih Kolom</option>
			<?php		
			$query = mysql_query($tabel);
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				//$type[$name] = mysql_field_type($query, $i);
					$selected = "";
					if($name == $search1)$selected = "selected";
					echo"<option value='".$name."' $selected>".title($name)."</option>";	
			}
			?>			
			</select></td>
	<td valign="center"><input type='text' name='search2' size='20' value='<?php echo $search2;?>'></td>
</tr>
<tr><td>Bulan</td><td>:</td>
		<td><select name="search4">
			<option value=""></option>
			<?php
			$bulane = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");		
			for($i = 0; $i < count($bulane); $i++){
					$selected = "";
					if($bulane[$i] == $search4)$selected = "selected";
					echo"<option value='".$bulane[$i]."' $selected>".$bulane[$i]."</option>";	
			}
			?>			
			</select>
		Tahun : <input type='text' name='search3' size='5' value='<?php echo $search3;?>'> 
</td></tr>
<tr><td colspan="4" align="right">
		<input type='submit' name='tombol' value='Tampilkan'></td>
</tr>
<?php
//}
?>	
</table>
</form>

<script type="text/javascript">
        $('#pilih').selectize({
            create: true,
            sortField: 'text'
        });
        $('#pilih2').selectize({
            create: true,
            sortField: 'text'
        });
</script>

</td><td align="right">

<table>
<tr><td>
<?php
	if (($opd_id == 1)or($opd_id == 0))echo"<a href='?send=$to_page-import'><img src=\"img/import.jpg\" width='50' title='import excel'></img></a>";
	//echo"<a href='reports/oss_rba/rekap_ossrbaproyek_excel.php?search=$search&act=$act'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	//echo"&nbsp; <a href='reports/oss_rba/rekap_ossrbaproyek_pdf.php?search=$search'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
?>
</td><td>
<?php
	echo"<a href='reports/investasi/rekap_investasi_excel.php?search1=$search1&search2=$search2&search3=$search3&search4=$search4'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	echo"&nbsp; <a href='reports/investasi/rekap_investasi_pdf.php?search1=$search1&search2=$search2&search3=$search3&search4=$search4'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
?>
</td></tr>
</table>

</td></tr>
</table>

<!--<div id="border">
* Klik judul kolom untuk mengurutkan.-->
	<table class="tabelbox1">
		<?php
		$order = "asc";
		//if(strpos($act,"asc")) $order = "desc";
		//if(strpos($act,"desc")) $order = "asc";
			
		if(($search1 != "") and ($search2 != ""))$tabel .= " AND $search1 LIKE '%$search2%'";
		if(($search3 != ""))$tabel .= " AND tahun LIKE '%$search3%'";
		if(($search4 != ""))$tabel .= " AND bulan LIKE '%$search4%'";

		//if(strpos($act,"asc")) {$kolom = preg_replace("/asc/","",$act); $tabel .= " ORDER BY $kolom asc";}
		//if(strpos($act,"desc")) {$kolom = preg_replace("/desc/","",$act); $tabel .= " ORDER BY $kolom desc";}
		
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search2 = str_replace(" ","_",$search2);
		$search = $search1.";".$search2.";".$search3.";".$search4;
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
			if($name != "id")echo "<th>$title</th>";
		}
		//echo "<th>Editor</th>";
		echo "</tr>";
		
		// Nampilin Data				
		$query = mysql_query($tabel." LIMIT $starting,$recpage");
		$current = $_REQUEST["x"];
		$current1 = 0;
		
		$baris=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			$id = $r['id'];
			
			if($baris % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			echo "<td align='center'>$baris</td>";
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				if($name != "id"){
					if($name == "nilai_investasi") echo "<td align='right'>".number_format($r[$name],0,",",".")."</td>";
					elseif(($name == "tahun") or ($name == "bulan") or ($name == "jenis_modal") or ($name == "jumlah_tk")) echo "<td align='center'>$r[$name]</td>";
					else echo "<td align='left'>$r[$name]</td>";
				}
			}
			
			//echo "<td align='center'><a href='?send=inputinvestasi/$id/$starting/$search'><img src=\"img/edit.png\" width='25' title='ubah'></img></a></td>";
			
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
</body>