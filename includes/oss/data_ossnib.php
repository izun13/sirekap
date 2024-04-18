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
$to_page = "dataossnib";


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
		
<div><span class='judul'>Data NIB OSS NSWI</span></div>

<table width="100%">
<tr>
<td>
<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off" id="formulir"> 
<table>
<?php
$tabel = "SELECT*FROM oss_nib WHERE id IS NOT NULL";	
		
echo "<tr><td width='150px'>Tanggal Input Proyek</td><td>:</td><td width='250px'><input type='text' size='10' name='search1' value='$search1' id='inputField1' class='search'>";
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
	
	$val2 = str_replace(" ","_",$val);
	$search .= $kol.":".$sim.":".$val.";";
				
	echo "<tr>";
	echo "<td width='100px'>Filter Kolom </td><td>:</td>";
	echo "<td width='230px'><select name='$kolom' id='$pilih'><option value=''>Pilih Kolom</option>";
		$query = mysql_query($tabel);	
		for($i = 0; $i < mysql_num_fields($query); $i++){
			$name = mysql_field_name($query, $i);
			//$type[$name] = mysql_field_type($query, $i);
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
	echo"<a href='reports/oss/rekap_nib_excel.php?search=$search'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	echo"&nbsp; <a href='reports/oss/rekap_nib_pdf.php?search=$search'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
?>
</td></tr>
</table>

</td></tr>
</table>

<!--<div id="border">-->
	<table class="tabelbox1">
		<?php
			
		if(($search1 != "") and ($search2 != "")) $tabel .= " AND tanggal_nib >= '$search1' AND tanggal_nib <= '$search2'";	
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
		
		if($group) $tabel .= " GROUP BY nama_perusahaan";		
		//$tabel .= " ORDER BY id desc";
		
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
			echo "<th>$name</th>";
		}
		echo "<th>Editor</th>";
		echo "</tr>";
		
		// Nampilin Data
		$query = mysql_query($tabel." LIMIT $starting,$recpage");
		//$current = $_REQUEST["x"];
		//$current1 = 0;
		
		$baris=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			//if($current1 == 0)$current1 = $r[id];		
			//if(!$current)$current = $current1;
			$id = $r['id'];
			
			if($baris % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			echo "<td align='center'>$baris</td>";
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				if(($name == "status_nib") or ($name == "status_pm")) echo "<td align='center'>$r[$name]</td>";
				else echo "<td align='left'>$r[$name]</td>";
			}
			
			echo "<td align='center'><a href='?send=dataossnib-edit/$r[id]/$starting/$search'><img src='img/edit.png' width='25' title='Edit Data'></img></a></td>";
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