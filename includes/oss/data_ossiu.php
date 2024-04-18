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
$to_page = "dataossiu07";

$search = explode("x",$search);
$search1 = $search[0]; 
$search2 = $search[1]; 
$search2 = str_replace("_"," ",$search2);
if($_POST["search1"]) $search1=$_POST["search1"];
if($_POST["search2"]) $search2=$_POST["search2"];
?>	
		
<div><span class='judul'>Data Ijin Usaha 2007 s/d 2018</span></div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table>
<tr>
<td width="100px">Filter Kolom </td><td>:</td>
<td width="230px">
		<select name="search1" id="pilih2">
			<option value="">Pilih Kolom</option>
			<?php
			$tabel = mysql_query("SELECT no_siup,perusahaan,nama_pemilik,npwp FROM ijin_usaha");
			for($i = 0; $i < mysql_num_fields($tabel); $i++){
				$name = mysql_field_name($tabel, $i);
				//$type[$name] = mysql_field_type($tabel, $i);
					$selected = "";
					if($name == $search1)$selected = "selected";
					echo"<option value='".$name."' $selected>".$name."</option>";	
			}
			?>			
			</select></td>
	<td><input type='text' name='search2' size='20' value='<?php echo $search2;?>'> <input type='submit' name='tombol' value='Cari'></td>
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
<!--<div id="border">-->
* Klik judul kolom untuk mengurutkan.
	<table class="tabelbox1">
		<?php
		$order = "asc";
		if(strpos($act,"asc")) $order = "desc";
		if(strpos($act,"desc")) $order = "asc";
		$tabel2 = "SELECT no_siup,tanggal_cetak,perusahaan,nama_pemilik,telp_pemilik,alamat_perusahaan,npwp,nilai_modal,tenaga_kerja,dagangan_utama FROM ijin_usaha";	
		if(($search1 != "") and ($search2 != ""))$tabel2 .= " WHERE $search1 LIKE '%$search2%'";
		
		if(strpos($act,"asc")) {$kolom = preg_replace("/asc/","",$act); $tabel2 .= " ORDER BY $kolom asc";}
		if(strpos($act,"desc")) {$kolom = preg_replace("/desc/","",$act); $tabel2 .= " ORDER BY $kolom desc";}
		
		$query = mysql_query($tabel2);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search2 = str_replace(" ","_",$search2);
		$search = $search1."x".$search2;
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
			$urutkan = $name.$order;
			echo "<th><a href='?send=$to_page////$urutkan'>$name</a></th>";
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
				if($name == "nilai_modal") echo "<td align='right'>".rupiah($r[$name])."</td>";
				elseif(($name == "telp_pemilik") or ($name == "tenaga_kerja")) echo "<td align='center'>$r[$name]</td>";
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