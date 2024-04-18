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
$to_page = "dataossiumk";

if($search)$searching = explode(";",$search);

$filters = 1;
if($_POST["filters"])$filters = $_POST["filters"];
if($_POST["tambah"]) $filters++;

if($search) $filters=count($searching)-1;

$group = $_POST["group"];
if($act)$group=$act;
?>	
		
<div><span class='judul'>Data IUMK OSS NSWI</span></div>

<table width="100%">
<tr>
<td>
<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off" id="formulir"> 
<table>
<?php
$x = 0;
$search = "";
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
	
	$val2 = str_replace(" ","_",$val);
	$search .= $kol.":".$sim.":".$val.";";
				
	echo "<tr>";
	echo "<td width='100px'>Filter Kolom </td><td>:</td>";
	echo "<td width='230px'><select name='$kolom' id='$pilih'><option value=''>Pilih Kolom</option>";
		$tabel = mysql_query("SELECT nib,nama_usaha,sektor,kbli,kegiatan_usaha,status_nib,day_of_tanggal_terbit,modal_usaha FROM oss_iumk");
		for($i = 0; $i < mysql_num_fields($tabel); $i++){
			$name = mysql_field_name($tabel, $i);
			//$type[$name] = mysql_field_type($tabel, $i);
			$selected = "";
			if($name == $kol)$selected = "selected";
			echo"<option value='".$name."' $selected>".$name."</option>";	
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
	if($j == $filters) echo "<td><input type='submit' name='tambah' value='+' title='Tambah Filter'><input type='hidden' name='filters' value='$filters'></td>";
	echo "</tr>";
	
	?>
	<script type="text/javascript">
        $('#<?php echo $pilih;?>').selectize({
            create: true,
            sortField: 'text'
        });
	</script>
	<?php
}

if($group)	$checked = "checked";	
?>
<tr><td colspan="5" align="right"><input type='submit' name='tombol' value='Tampilkan'></td></tr>
</table>
Group by Nama Perusahaan : <input type='checkbox' name='group' onchange="tampilkan()" <?php echo $checked;?>>
</form>

</td><td align="right">

<table>
<tr><td>
<?php
	echo"<a href='reports/oss/rekap_ossiumk_excel.php?search=$search&group=$group'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	echo"&nbsp; <a href='reports/oss/rekap_ossiumk_pdf.php?search=$search&group=$group'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
?>
</td></tr>
</table>

</td></tr>
</table>

<!--<div id="border">
* Klik judul kolom untuk mengurutkan.-->
	<table class="tabelbox1">
		<?php
		//$order = "asc";
		//if(strpos($act,"asc")) $order = "desc";
		//if(strpos($act,"desc")) $order = "asc";
		
		$tabel2 = "SELECT nama_usaha,nib,status_nib,day_of_tanggal_terbit,sektor,kbli,kegiatan_usaha,nama,telp,modal_usaha,jml_tenaga_kerja FROM oss_iumk WHERE id IS NOT NULL";	
		
		$search = "";
		$x = 0;
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
				
			if($kol) {
				if(($sim == "LIKE") or ($sim == "NOT LIKE"))$tabel2 .= " AND ".$kol." ".$sim." '%".$val."%'";
				else $tabel2 .= " AND ".$kol." ".$sim." '".$val."'";
			}
			
			$val = str_replace(" ","_",$val);
			$search .= $kol.":".$sim.":".$val.";";
		}
		
		if($group) $tabel2 .= " GROUP BY nama_usaha";
		
		//if(strpos($act,"asc")) {$kolom = preg_replace("/asc/","",$act); $tabel2 .= " ORDER BY $kolom asc";}
		//if(strpos($act,"desc")) {$kolom = preg_replace("/desc/","",$act); $tabel2 .= " ORDER BY $kolom desc";}
		
		$query = mysql_query($tabel2);
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
			if($name == "nama_usaha")$title = "Nama Usaha";
			if($name == "nib")$title = "NIB";
			if($name == "status_nib")$title = "Status NIB";
			if($name == "day_of_tanggal_terbit")$title = "Tanggal Terbit";
			if($name == "sektor")$title = "Sektor";
			if($name == "kbli")$title = "KBLI";
			if($name == "kegiatan_usaha")$title = "Kegiatan Usaha";
			if($name == "nama")$title = "Kontak Person";
			if($name == "telp")$title = "No. Telpon";
			if($name == "modal_usaha")$title = "Modal Usaha";
			if($name == "jml_tenaga_kerja")$title = "Jumlah TK";
			if($name == "verifikasi")$title = "Status Verifikasi";
			if($name == "tgl_verifikasi")$title = "Tanggal Verifikasi";
			echo "<th>$title</th>";
		}
		echo "</tr>";
		
		// Nampilin Data				
		$query = mysql_query($tabel2." LIMIT $starting,$recpage");
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
				if($name == "modal_usaha") echo "<td align='right'>".rupiah($r[$name])."</td>";
				elseif(($name == "status_nib") or ($name == "jml_tenaga_kerja")) echo "<td align='center'>$r[$name]</td>";
				else echo "<td align='left'>$r[$name]</td>";
			}
			
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