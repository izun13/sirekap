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
$to_page = "jns-izinsimp";

$search = explode(";",$search);
$search1 = $search[0]; 
$search2 = $search[1];  
if($_POST["search1"]) $search1=$_POST["search1"];
if($_POST["search2"]) $search2=$_POST["search2"];
$search2 = str_replace("_"," ",$search2);

koneksi1_tutup();
koneksi2_buka();

?>	
		
<div><span class='judul'>Data Jenis Izin</span></div>
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
			$tabel = mysql_query("SELECT jenisizin_name,jenisizin_singkatan,jenisizin_opd_id FROM jenis_izin");		
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
		
		$tabel2 = "SELECT jenisizin_id,jenisizin_name,jenisizin_singkatan,jenisizin_opd_id FROM jenis_izin WHERE jenisizin_opd_id IS NOT NULL";	
		if(($search1 != "") and ($search2 != ""))$tabel2 .= " AND $search1 LIKE '%$search2%'";

		if(strpos($act,"asc")) {$kolom = preg_replace("/asc/","",$act); $tabel2 .= " ORDER BY $kolom asc";}
		if(strpos($act,"desc")) {$kolom = preg_replace("/desc/","",$act); $tabel2 .= " ORDER BY $kolom desc";}
		
		$query = mysql_query($tabel2);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search2 = str_replace(" ","_",$search2);
		$search = $search1.";".$search2;
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
		echo "<th>Editor</th>";
		echo "</tr>";
		
		// Nampilin Data				
		$query = mysql_query($tabel2." LIMIT $starting,$recpage");
		$current = $_REQUEST["x"];
		$current1 = 0;
		
		$baris=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			$id = $r['jenisizin_id'];
			
			if($baris % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			echo "<td align='center'>$baris</td>";
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				if($name == "jenisizin_opd_id"){	
					$opd = mysql_fetch_array(mysql_query("SELECT*FROM opd WHERE id='$r[$name]'"));
					echo "<td align='left'>$opd[opd]</td>";
				} 
				else echo "<td align='left'>$r[$name]</td>";
			}
			
			echo "<td align='center'><a href='?send=inputjns-izinsimp/$id/$starting/$search'><img src=\"img/edit.png\" width='25' title='ubah'></img></a></td>";
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