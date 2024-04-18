<body>
<?php
$cari=$_POST["submit"];
		$recpage = 10;
		$to_page = "datauser";
		$search=$_POST["search"];		
		if($_POST['search'] == NULL) $search = $_GET["search"];
		
		if(($search != "")){		
		$tabel = "select * from tb_pegawai WHERE nama_pegawai LIKE '%$search%' order by id_pegawai desc";
		}else{
		$tabel = "select * from tb_pegawai order by id_pegawai desc";
		}
		
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		page($query,$recpage,$starting,$to_page,$search);
		
?>	
<div class="judul">Data Pegawai</div>		

<table width='100%'>
<tr><td width='50%'>
		<a href='?send=inputuser'><img src='img/tambah.png' width='30' title='tambah'></img></a>
</td><td align='right'>	
	<form action='<?php echo "?send=".$to_page;?>' method='post'> 
		Cari : <input type='text' size='20' name='search' value='<?php echo $search;?>' class='search'> <input type='submit' name='submit' value='Search'></form>
</td></tr>	
</table>
	<table class="tabelbox1">
		<tr>
			<th><b>&nbsp;</b></th>
			<th><b>No.</b></th>
			<th><b>Nama</b></th>
			<th><b>Alamat</b></th>	
			<th><b>Telp</b></th>
			<th><b>No. Ktp</b></th>
			<th><b>Tgl Kerja</b></th>
			<th><b>Username</b></th>
		</tr>
		<?php
		$starting = starting();
		$recpage = recpage();
		
		// Nampilin Data				
		$query = mysql_query($tabel." LIMIT $starting,$recpage");
		$current = $_REQUEST["x"];
		
		$i=$starting+1;		
		while ($r= mysql_fetch_array($query)){		
		if(!$current)$current = $r[id_pegawai];
		$id_pegawai = $r[id_pegawai];
		$tgl1 = tgl2($r[tgl_kerja]);
		
		$link = "<a href='?send=datauser/$id_pegawai/$starting/$search')>";
		
		if($current == $r[id_pegawai]) echo "<tr class='current'><td>$link<img src=\"img/go.png\" width='25' title='tampil'></img></a></td>";
		else echo "<tr><td>$link<img src=\"img/go2.png\" width='25' title='tampil'></img></a></td>";
			
		echo "
		  <td>$i</td>
		  <td>$r[nama_pegawai]</td>
		  <td>$r[alamat]</td>
		  <td>$r[telp]</td>
		  <td>$r[no_ktp]</td>
		  <td>$tgl1</td>
		  <td>$r[username]</td>";
			
		echo "</tr>";
		$i++;
		}
		?>
		
</table>
<table width='100%'>
<tr><td width='65'>
<?php 
		echo"<a href='?send=inputuser/$current/$starting/$search'><img src=\"img/edit.png\" width='30' title='ubah'></img></a>				
		<a href='?send=hapususer/$current/$starting/$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='30' title='hapus'></img></a>";
?>
</td><td>
	<?
	$page_showing = page_showing();
	echo show_navi();
	?>
</td></tr>
</table>
</body>
