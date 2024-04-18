<body>
<?php
$cari=$_POST["submit"];
		$recpage = 10;
		$to_page = "datatabel";
		if($_POST["search"]) $search=$_POST["search"];
		
		if(($search != "")){		
		$tabel = "select * from tabel WHERE nama_tabel LIKE '%$search%' order by id desc";
		}else{
		$tabel = "select * from tabel order by id desc";
		}
		
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		page($query,$recpage,$starting,$to_page,$search,$act);
		
?>	
		
<div><span class='judul'>Data Tabel Database</span>

<table width='100%'>
<tr><td width='50%'>
		<?php echo "<a href='?send=inputtabel'><img src='img/tambah.png' width='30' title='tambah'></img></a>"; ?>
</td><td align='right'>	
	<form action='<?php echo "?send=".$to_page;?>' method='post'> 
	<input type='text' size='20' name='search' value='<?php echo $search;?>'> <input type='submit' name='submit' value='Search'></form>
</td></tr>	
</table>
<div id="border">
	<table class="tabelbox1">
		<tr>
			<th>No.</th>
			<th>Nama Tabel</th>
			<th>Link Json</th>
			<th>Sembunyikan</th>
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
		if($current1 == 0)$current1 = $r[id];		
		if(!$current)$current = $current1;
		$id = $r[id];
		
		if($baris % 2==0){
			echo "<tr class='cyan'>";
		}else{
			echo "<tr>";
		}
			
		echo "
		  <td align='center'>$baris</td>
		  <td align='left'>$r[nama_tabel]</td>
		  <td align='left'>$r[link_sicantik]</td>
		  <td align='center'>$r[hide]</td>";
		
		echo "<td align='center'><a href='?send=inputtabel/$id/$starting/$search'><img src=\"img/edit.png\" width='25' title='ubah'></img></a></td>";
		echo "<td align='center'><a href='?send=hapustabel/$id/$starting/$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
		
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