<body>
<?php
$cari=$_POST["submit"];
		$recpage = 10;
		$to_page = "datalibur";
		if($_POST["search"]) $search=$_POST["search"];
		
		if(($search != "")){		
		$tabel = "select * from libur_nasional WHERE tgl LIKE '%$search%' order by id desc";
		}else{
		$tabel = "select * from libur_nasional order by id desc";
		}
		
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		page($query,$recpage,$starting,$to_page,$search);
		
?>	
		
<div><span class='judul'>Data Hari Libur Nasional</span>

<table width='100%'>
<tr><td width='50%'>
		<?php echo "<a href='?send=inputlibur'><img src='img/tambah.png' width='30' title='tambah'></img></a>"; ?>
</td><td align='right'>	
	<form action='<?php echo "?send=".$to_page; ?>' method='post'> 
	Tanggal/Tahun : <input type='text' size='20' name='search' value='<?php echo $search;?>'> <input type='submit' name='submit' value='Search'></form>
</td></tr>	
</table>
<div id="border">
	<table class="tabelbox1">
		<tr>
			<th>No.</th>
			<th>Tanggal</th>
			<th>Keterangan</th>
			<th colspan="2">Editor</th>
		</tr>
		<?php
		$starting = starting();
		$recpage = recpage();
		
		// Nampilin Data				
		$query = mysql_query($tabel." LIMIT $starting,$recpage");
		$current = $_REQUEST["x"];
		$current1 = 0;
		
		$baris=$starting+1;		
		while ($r= mysql_fetch_array($query)){
		$id = $r["id"];
		
		if($baris % 2==0){
			echo "<tr class='cyan'>";
		}else{
			echo "<tr>";
		}
			
		echo "
		  <td align='center'>$baris</td>
		  <td align='center'>$r[tgl]</td>
		  <td align='left'>$r[ket]</td>";
		
		echo "<td align='center'><a href='?send=inputlibur/$id/$starting/$search'><img src=\"img/edit.png\" width='25' title='ubah'></img></a></td>";
		echo "<td align='center'><a href='?send=hapuslibur/$id/$starting/$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
		
		echo "</tr>";
		$baris++;
		}
		?>
		
</table>
</div>
<table width='100%'>
<tr><td>
	<?php
	$page_showing = page_showing();
	echo show_navi();
	?>
</td></tr>
</table>
</div>
</body>