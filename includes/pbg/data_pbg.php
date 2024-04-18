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
$recpage = 20;
$to_page = "datapbg";

$search = explode(";",$search);
$search1 = $search[0]; 
$search2 = $search[1]; 
$search3 = $search[2]; 
$search4 = $search[3];  
if($_POST["search1"]) $search1=$_POST["search1"];
if($_POST["search2"]) $search2=$_POST["search2"];
if($_POST["search3"]) $search3=$_POST["search3"];
if($_POST["search4"]) $search4=$_POST["search4"];
$search4 = str_replace("_"," ",$search4);

$tabel = "SELECT id,nomor,nama_pemohon,fungsi,klasifikasi,nama_bangunan,luas_bangunan,luas_tanah,lokasi,retribusi,tgl_terbit FROM tb_pbg WHERE id IS NOT NULL";	
?>	
		
<div><span class='judul'>Data Persetujuan Bangunan Gedung (PBG)</span></div>
<table width="100%">
<tr><td>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table width="100%">

	<tr><td width="210">Periode Tanggal Terbit</td><td>:</td>
		<td width=""><input type='text' size='10' name='search1' value='<?php echo $search1;?>' id="inputField1" class='search'> 
		s/d  <input type='text' size='10' name='search2' value='<?php echo $search2;?>' id="inputField2" class='search'> 
	</td></tr>
<tr>
<td width="100px">Filter Kolom </td><td>:</td>
<td width="230px">
		<select name="search3" id="pilih2">
			<option value="">Pilih Kolom</option>
			<?php
			$query = mysql_query($tabel);		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				//$type[$name] = mysql_field_type($tabel, $i);
					$selected = "";
					if($name == $search3)$selected = "selected";
					echo"<option value='".$name."' $selected>".$name."</option>";	
			}
			?>			
			</select></td>
	<td valign="center"><input type='text' name='search4' size='20' value='<?php echo $search4;?>'>
		<input type='submit' name='tombol' value='Tampilkan'></td>
		<td align="right">
		<a href="?send=inputpbg"><img src="img/tambah.png" width="40" title="tambah"></img></a>
		<a href="?send=importpbg"><img src="img/import.jpg" width="50" title="import"></img></a>
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
<?php
if(($search1 != "") and ($search2 != ""))$tabel .= " AND date(tgl_terbit) >= '$search1' AND date(tgl_terbit) <= '$search2'";
		if(($search3 != "") and ($search4 != ""))$tabel .= " AND $search3 LIKE '%$search4%'";
		
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search4 = str_replace(" ","_",$search4);
		$search = $search1.";".$search2.";".$search3.";".$search4;
		page($query,$recpage,$starting,$to_page,$search,$act);
		
		$starting = starting();
		$recpage = recpage();
		
		echo"<a href='reports/pbg/rekap_pbg_excel.php?send=$search' target='_blank'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
?>
	<table class="tabelbox1">
		<?php				
		echo "<tr>";
		echo "<th>No.</th>";
		
		for($i = 0; $i < mysql_num_fields($query); $i++){
			$name = mysql_field_name($query, $i);
			$title = str_replace("_"," ",$name);
			$title = ucwords($title);
			//$type = mysql_field_type($query, $i);
			//$size = mysql_field_len($query, $i);
			//echo $name.' - '.$type.' - '.$size.'<br>';
			if($name!="id"){				
				if(($name=="luas_tanah") or ($name=="luas_bangunan")) echo "<th>$title (m2)</th>";
				elseif($name=="retribusi") echo "<th>$title (Rp.)</th>";
				else echo "<th>$title</th>";
			}
		}
		echo "<th colspan='2'>Editor</th>";
		echo "</tr>";
		
		// Nampilin Data				
		$query = mysql_query($tabel." ORDER BY tgl_terbit DESC LIMIT $starting,$recpage");
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
				if($name!="id"){
					$value = $r[$name];
					if($name=="retribusi")$value = rupiah($r[$name]);
					if($name=="luas_tanah"){
						$luas_tanah = "";
						$query_tanah = mysql_query("SELECT*FROM tb_tanah WHERE pbg_id='$id'");
						while ($r_tanah = mysql_fetch_array($query_tanah)){
							$luas_tanah .= ", ".rupiah($r_tanah["luas_tanah"]);
						}
						$value = ltrim($luas_tanah,', '); 
						
					}
					
					echo "<td align='left'>$value</td>";
				}
			}
			
			echo "<td align='center'><a href='?send=updatepbg/$id/$starting/$search'><img src=\"img/edit.png\" width='25' title='ubah'></img></a></td>";
			echo "<td align='center'><a href='?send=hapuspbg/$id/$starting/$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
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