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
$to_page = "datarealisasi";

$search = explode(";",$search);
$search1 = $search[0]; 
$search2 = $search[1];  
$search3 = $search[2];  
$search4 = $search[3];  
if($_POST["search1"]) $search1=$_POST["search1"];
if($_POST["search2"]) $search2=$_POST["search2"];
if($_POST["search3"]) $search2=$_POST["search3"];
if($_POST["search4"]) $search2=$_POST["search4"];

$tabel = "SELECT*FROM realisasi_investasi WHERE id IS NOT NULL";	
//$cek = mysql_fetch_array(mysql_query("SELECT SUM(nilai_investasi) AS nilai FROM realisasi_investasi WHERE bulan = 'Juli'"));
//echo $cek["nilai"];

$search = $search1.";".$search2.";".$search3.";".$search4;
$search4 = str_replace("_"," ",$search4);
?>	
		
<div><span class='judul'>Data Realisasi Investasi</span></div>
<table width="100%">
<tr><td>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table width="100%">
<tr>
	<td width="">Bulan</td><td>:</td>
		<td width="">
		<?php
			echo "<select name='search1' id=''>";
			for($i = 0; $i < count($NAMA_BULAN); $i++){
				$selected = "";
				if($NAMA_BULAN[$i] == $search1)$selected = "selected";
				echo"<option value='".$NAMA_BULAN[$i]."' $selected>".$NAMA_BULAN[$i]."</option>";	
			}		
			echo "</select>";
		?>
	
	Tahun <input type='text' size='8' name='search2' value='<?php echo $search2;?>' id="" class='search'></td>
</tr>
<tr>
<td width="100px">Filter Kolom </td><td>:</td>
<td width="230px">
		<select name="search3" id="pilih2">
			<option value="">Pilih Kolom</option>
			<?php		
			for($i = 0; $i < mysql_num_fields($tabel); $i++){
				$name = mysql_field_name($tabel, $i);
				//$type[$name] = mysql_field_type($tabel, $i);
					$selected = "";
					if($name == $search3)$selected = "selected";
					echo"<option value='".$name."' $selected>".$name."</option>";	
			}
			?>			
			</select></td>
	<td valign="center"><input type='text' name='search4' size='20' value='<?php echo $search4;?>'>
		<input type='submit' name='tombol' value='Tampilkan'></td>
		<td width='50%' align="right">
		<?php
		echo "<a href='?send=inputrealisasi'><img src='img/tambah.png' width='30' title='tambah'></img></a>"; 
		echo"&nbsp; &nbsp; <a href='reports/realisasi/rekap_realisasi_json.php?send=$search' target='_blank'><img src=\"img/json.png\" width='38' title='Cetak Json'></img></a>";
		?>
</td>
</tr>
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

<!--* Klik judul kolom untuk mengurutkan.-->
	<table class="tabelbox1">
		<?php
		//$order = "asc";
		//if(strpos($act,"asc")) $order = "desc";
		//if(strpos($act,"desc")) $order = "asc";
		
		if($search1 != "")$tabel .= " AND bulan = '$search1'";
		if($search2 != "")$tabel .= " AND tahun = '$search2'";
		if(($search3 != "") and ($search4 != ""))$tabel .= " AND $search3 LIKE '%$search4%'";
		
		$tabel .= " ORDER BY id desc";
		
		//if(strpos($act,"asc")) {$kolom = preg_replace("/asc/","",$act); $tabel .= " ORDER BY $kolom asc";}
		//if(strpos($act,"desc")) {$kolom = preg_replace("/desc/","",$act); $tabel .= " ORDER BY $kolom desc";}
		
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search4 = str_replace(" ","_",$search4);
		$search = $search1.";".$search2.";".$search3.";".$search4;
		page($query,$recpage,$starting,$to_page,$search,$act);
		
		$starting = starting();
		$recpage = recpage();
		
		echo "<tr>";
		//echo "<th>No.</th>";
		
		for($i = 0; $i < mysql_num_fields($query); $i++){
			$name = mysql_field_name($query, $i);
			//$type = mysql_field_type($query, $i);
			//$size = mysql_field_len($query, $i);
			//echo $name.' - '.$type.' - '.$size.'<br>';
			$urutkan = $name.$order;
			
			if($name == "id") echo "<th align='center'>No.</th>";
			else echo "<th>$name</th>"; // <a href='?send=$to_page////$urutkan'><img src=\"img/urut.png\" width='10' title='urutkan'> </a>
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
			
			//echo "<td align='center'>$baris</td>";
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				if($name == "id") echo "<td align='center'>$baris</td>";
				elseif($name == "nilai_investasi" or $name == "nilai_akumulasi" or $name == "target") echo "<td align='right'>".rupiah($r[$name])."</td>";
				elseif($name == "sektor") echo "<td align='left'>$r[$name]</td>";
				else echo "<td align='center'>$r[$name]</td>";
			}
			//echo "<td align='center'>";
			//echo "<a href='?send=inputrealisasi/$id/$starting/$search'><img src=\"img/edit.png\" width='25' title='ubah'></img></a>";
			//echo "<a href='?send=updaterealisasi/$id/$starting/$search'><img src=\"img/updates.png\" width='25' title='update'></img></a>";
			//echo "</td>";
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