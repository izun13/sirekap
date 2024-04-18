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
$tabel = "SELECT tgl_permohonan,pemohon,jabatan,jenis_masalah_it,permasalahan FROM tb_helpdesk WHERE id='$id'";					
$query = mysql_query($tabel);

if($_POST["submit"]=="KEMBALI"){
	?>
	<script language="JavaScript">
		document.location.href='?send=datahelpdesk//<?php echo $starting; ?>/<?php echo $search; ?>';
	</script>
	<?php
}

if($_POST["submit"]=="SIMPAN"){
		
	$cekisi = 0;
	$kolom = "";
	$isi = "";
	$update = "";
	for($i = 0; $i < mysql_num_fields($query); $i++){
		$name = mysql_field_name($query, $i);	
		$fill = $_POST[$name];
		if($fill == "")$cekisi = 1;
		//if(($name == "opd_id")and($fill == "")) $fill = 0;
		//if($name != "id"){
			$kolom .= $name.",";
			$isi .="'".$fill."',";
			$update .= $name."='".$fill."',";
		//}
	}
	$kolom = substr($kolom, 0, -1);
	$isi = substr($isi, 0, -1);
	$update = substr($update, 0, -1);
	
	if ($cekisi){
		?>
		<script language="JavaScript">alert('Data Belum Lengkap!');
			//document.location.href='?send=datahelpdesk//<?php echo $starting; ?>/<?php echo $search; ?>';
		</script>
		<?php
	}else{
	
		$cek=mysql_num_rows(mysql_query("select*from tb_helpdesk where id='$id'"));
		if($cek == 0){
			$tambah="insert into tb_helpdesk ($kolom) values ($isi)";
			$hasil=mysql_query($tambah);
			if (!$hasil) echo "Input Gagal :".mysql_error()."<br>";
		}else{
			$ubah="update tb_helpdesk set $update where id='$id'";
			$hasil=mysql_query($ubah);
			if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
		}
		
		if ($hasil){
			?>
			<script language="JavaScript">alert('Data Permohonan Telah Disimpan!');
				//document.location.href='?send=datahelpdesk//<?php echo $starting; ?>/<?php echo $search; ?>';
			</script>
			<?php
		}
	}
}


?>	
		
<div><span class='judul'>Input Data Helpdesk</span></div>


<form action='' method='post'> 
	<table>
		<?php
		$query = mysql_query($tabel);
		$r= mysql_fetch_array($query);
		
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				$title = str_replace("_"," ",$name);
				$title = ucwords($title);
				echo "<tr>";
				echo "<td><b>$title</b></td><td><b>:</b></td>";
				if($name=="id") echo "<td>$r[$name]</td>";
				elseif($name=="tgl_permohonan"){
					echo "<td><input type='text' size='8' name='$name' value='$r[$name]' id='inputField1'></td>";
				}
				elseif($name=="jenis_masalah_it"){
					echo "<td><input type='radio' name='$name' value='Infrastruktur TI'> Infrastruktur TI <input type='radio' name='$name' value='Jaringan LAN'> Jaringan LAN</td>";
				}
				elseif(($name=="permasalahan")){
					echo "<td><textarea name='$name' rows='3' cols='50'>$r[$name]</textarea></td>";
				}				
				else echo "<td><input type='text' size='50' name='$name' value='$r[$name]'></td>";
				
				echo "</tr>";
			}
				echo "<tr> <td colspan='3' align='right'>";
				//echo "<input type='submit' name='submit' value='KEMBALI'>";
				echo "<input type='submit' name='submit' value='SIMPAN'>";
				echo "</td></tr>";
		?>
	</table>
<script type="text/javascript">
        $('#pilih').selectize({
            create: true,
            sortField: 'text'
        });
</script>
</form>

	<table class="tabelbox1">
		<?php
		
		$recpage = 25;
		$tabel = "SELECT*FROM tb_helpdesk ORDER BY id DESC";		
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		page($query,$recpage,$starting,$to_page,$search,$act);
		
		$starting = starting();
		$recpage = recpage();
		
		echo "<tr>";
		echo "<th>No.</th>";
		
		for($i = 0; $i < mysql_num_fields($query); $i++){
			$name = mysql_field_name($query, $i);
			$title = str_replace("_"," ",$name);
			$title = ucwords($title);
			//$type = mysql_field_type($query, $i);
			//$size = mysql_field_len($query, $i);
			//echo $name.' - '.$type.' - '.$size.'<br>';
			$urutkan = $name.$order;
			if($name != "id") echo "<th>$title</th>";
		}
		//echo "<th colspan='2'>Editor</th>";
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
			
			echo "<td align='center'>$baris</td>";
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				if($name != "id") echo "<td align='left'>$r[$name]</td>";
			}
			
			//echo "<td align='center'><a href='?send=hapushelpdesk/$id/$starting/$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
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
</body>