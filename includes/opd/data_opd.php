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
koneksi1_tutup();
koneksi2_buka();

$recpage = 25;
$to_page = "dataopd";

$search = explode("x",$search);
$search1 = $search[0]; 
$search2 = $search[1];  
if($_POST["search1"]) $search1=$_POST["search1"];
if($_POST["search2"]) $search2=$_POST["search2"];
?>	
		
<div><span class='judul'>Data Organisasi Perangkat Daerah (OPD)</span></div>
<table width="100%">
<tr><td>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table width="100%">
<tr>
<td width="100px">Filter Kolom </td><td>:</td>
<td width="230px">
		<select name="search1" id="pilih2">
			<option value="">Pilih Kolom</option>
			<?php
			$tabel = mysql_query("SELECT*FROM opd");		
			for($i = 0; $i < mysql_num_fields($tabel); $i++){
				$name = mysql_field_name($tabel, $i);
				//$type[$name] = mysql_field_type($tabel, $i);
					$selected = "";
					if($name == $search1)$selected = "selected";
					echo"<option value='".$name."' $selected>".$name."</option>";	
			}
			?>			
			</select></td>
	<td valign="center"><input type='text' name='search2' size='20' value='<?php echo $search2;?>'>
		<input type='submit' name='tombol' value='Tampilkan'></td>
	<td align="right">
		<?php echo "<a href='?send=inputopd'><img src='img/tambah.png' width='30' title='tambah'></img></a>"; ?>
	</td>
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

* Klik judul kolom untuk mengurutkan.
	<table class="tabelbox1">
		<?php
		$order = "asc";
		if(strpos($act,"asc")) $order = "desc";
		if(strpos($act,"desc")) $order = "asc";
		
		$tabel2 = "SELECT*FROM opd WHERE id IS NOT NULL";	
		if(($search1 != "") and ($search2 != ""))$tabel2 .= " AND $search1 LIKE '%$search2%'";

		if(strpos($act,"asc")) {$kolom = preg_replace("/asc/","",$act); $tabel2 .= " ORDER BY $kolom asc";}
		if(strpos($act,"desc")) {$kolom = preg_replace("/desc/","",$act); $tabel2 .= " ORDER BY $kolom desc";}
		
		$query = mysql_query($tabel2);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search2 = str_replace(" ","_",$search2);
		$search = $search1."x".$search2."x".$search3."x".$search4;
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
			echo "<th><a href='?send=$to_page////$urutkan'>$name</a></th>";
		}
		echo "<th colspan='2'>Editor</th>";
		echo "</tr>";
		
		// Nampilin Data				
		$query = mysql_query($tabel2." LIMIT $starting,$recpage");
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
				echo "<td align='left'>$r[$name]</td>";
			}
			
			echo "<td align='center'><a href='?send=inputopd/$id/$starting/$search'><img src=\"img/edit.png\" width='25' title='ubah'></img></a></td>";
			echo "<td align='center'><a href='?send=hapusopd/$id/$starting/$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
		
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
	
	koneksi2_tutup();
	?>
</td></tr>
</table>
<!--</div>-->
</body>