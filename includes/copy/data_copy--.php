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
$to_page = "data-copy";
if($_POST["search"]) $search=$_POST["search"];
if($_POST["key1"]) $key1=$_POST["key1"];
if($_POST["key2"]) $key2=$_POST["key2"];

//$search = explode("x",$search);
//$tabeldb = $search[0]; 
//$key1 = $search[1]; 
//$key2 = $search[2];
//$key1 = str_replace("_","-",$key1);
//$key2 = str_replace("_","-",$key2);
//if($_POST["tabeldb"]) $tabeldb=$_POST["tabeldb"];
//if($_POST["key1"]) $key1=$_POST["key1"];
//if($_POST["key2"]) $key2=$_POST["key2"];

if ($act=="backup"){
	
	$link = mysql_fetch_array(mysql_query("SELECT*FROM tabel WHERE nama_tabel='$search'"));
	$sumber = $link["link_sicantik"]."?key1='$key1'&key2='$key2'";;
	//$sumber = 'https://sicantikws.layanan.go.id/api/TemplateData/keluaran/27653.json';
	$konten = file_get_contents($sumber);
	$data = json_decode($konten, true);
	
	$j = 1;
	$tabel1 = mysql_query("SELECT*FROM $search");
	for($i = 0; $i < mysql_num_fields($tabel1); $i++){
		$name[$j] = mysql_field_name($tabel1, $i);
		$j++;
	}
	
	$kolom = "";
	$x=0;
	foreach ($data["data"]["data"][0] as $key=>$r) {
		$kolom = $kolom.$key.",";
		$col[$x] = $key;
		$cari = array_search($key,$name);
		 if ($cari == null){
			//echo $cari." - ".$key."<br>";
			$field = "ALTER TABLE $search ADD $key VARCHAR (200)";
			if($key == "id") $field = "ALTER TABLE $search ADD $key INT (11)";//NOT NULL AUTO_INCREMENT
			$hasil=mysql_query($field);
		 }
		$x++;
	}
	$kolom = substr($kolom, 0, -1);
	
	$tabel1 = mysql_query("SELECT*FROM $search");
	for($i = 0; $i < mysql_num_fields($tabel1); $i++){
		$name = mysql_field_name($tabel1, $i);
		$type[$name] = mysql_field_type($tabel1, $i);
	}
	
	$i=1;
	foreach ($data["data"]["data"] as $key=>$r) {		
		$isi = "";
		$update = "";
		for ($x=0;$x<count($col);$x++){
			$fill = str_replace("'","",$r[$col[$x]]);
			
			//membuat default isi kolom
			if(($type[$col[$x]] == "int") or ($type[$col[$x]] == "bigint") or ($type[$col[$x]] == "integer")) {
				if ($fill == '') $fill = 0;
			}
			if(($type[$col[$x]] == "date") and ($fill == '')) $fill = "0000-00-00";
			
			$fill = trim($fill);
			$isi = $isi."'".$fill."',";
			if($col[$x] != "id")$update = $update.$col[$x]."='".$fill."',";
		}
		
		$isi = substr($isi, 0, -1);
		$update = substr($update, 0, -1);	
		
		$cek=mysql_num_rows(mysql_query("select*from $search where id='$r[id]'"));
		$r_izin=mysql_fetch_array(mysql_query("select*from $search where id='$r[id]'"));
		if($cek == 0){
			$tambah="insert into $search ($kolom) values ($isi)";
			$hasil=mysql_query($tambah);
			if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
		}else{
			//if($search == "permohonan_izin"){
				//if(($r_izin['tgl_penetapan'] == null) or ($r_izin['tgl_penetapan'] == '0000-00-00')){
					//$ubah="update $search set $update where id='$r[id]'";
					//$hasil=mysql_query($ubah);	
					//if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
				//}				
			//}else{
				$ubah="update $search set $update where id='$r[id]'";
				$hasil=mysql_query($ubah);
				if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";		
			//}
		}
	}
		if($hasil) echo "Back-Up Berhasil !"; else echo "Back-Up Gagal !";
}
		
		
?>	
		
<div><span class='judul'>Tabel : <?php echo $search;?></span></div>

<form action='data-copy' method='post' autocomplete="off"> 
<table>
<tr><td width="100px">Tabel Database</td><td>:</td>
	<td width="230px">
		<select name="search" id="pilih">
			<option value="">Pilih Tabel Database</option>
			<?php
			$query_jns=mysql_query("SELECT*FROM tabel order by id asc");
				while($r_jns=mysql_fetch_array($query_jns)){
					$selected = "";
					if($r_jns["nama_tabel"] == $search)$selected = "selected";
					echo"<option value='".$r_jns["nama_tabel"]."' $selected>".$r_jns["nama_tabel"]."</option>";			
				}
			?>			
			</select></td>
	<td><input type='submit' name='tombol' value='Tampilkan'></td>
</tr>
</table>
</form>
<form action='<?php echo "data-copy---$search-backup";?>' method='post' autocomplete="off"> 
<table>
<?php 
//$key1x = str_replace("-","_",$key1);
//$key2x = str_replace("-","_",$key2);
//$search = $search."x".$key1x."x".$key2x;

if ($_POST["tombol"]=="Tampilkan"){

$link = mysql_fetch_array(mysql_query("SELECT*FROM tabel WHERE nama_tabel='$search'"));
$sumber = $link["link_sicantik"];//."?key1='$key1'&key2='$key2'"
//echo"<a href='data-copy---$search-backup'><img src=\"img/backup.png\" width='50' title='Back-Up Data'></img></a>";
//echo $sumber;

?>
<tr><td width="100px">Periode Back-Up</td><td>:</td>
		<td width=""> <input type='text' size='10' name='key1' value='<?php echo $key1;?>' id="inputField1" class='search'> 
		s/d  <input type='text' size='10' name='key2' value='<?php echo $key2;?>' id="inputField2" class='search'> 
	</td>
	<td><input type="image" src="img/backup.png" width='50' name="BackUp"> <?php echo $sumber; ?></td>	
</tr>
<?php
}
?>	
</table>
</form>

<script type="text/javascript">
        $('#pilih').selectize({
            create: true,
            sortField: 'text'
        });
</script>
<?php 
if (($_POST["tombol"]=="Tampilkan") or ($search!="")){
?>
<!--<div id="border">-->
	<table class="tabelbox1">
		<?php
		
		echo "<tr>";
		
		$j = 1;
		$tabel1 = mysql_query("SELECT*FROM $search");
		for($i = 0; $i < mysql_num_fields($tabel1); $i++){
		$name[$j] = mysql_field_name($tabel1, $i);
		$name = mysql_field_name($tabel1, $i);
		//$type = mysql_field_type($tabel1, $i);
		//$size = mysql_field_len($tabel1, $i);
		//echo $name.' - '.$type.' - '.$size.'<br>';
		echo "<th>$name</th>";
		$j++;
		}
		echo "</tr>";
		
		// Nampilin Data
		$tabel2 = "SELECT*FROM $search ORDER BY id desc";			
		$query = mysql_query($tabel2);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		page($query,$recpage,$starting,$to_page,$search);
		
		$starting = starting();
		$recpage = recpage();
		
		$query = mysql_query($tabel2." LIMIT $starting,$recpage");
		$current = $_REQUEST["x"];
		$current1 = 0;
		
		$j=0;
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
			
			for($i = 0; $i < mysql_num_fields($tabel1); $i++){
				$name = mysql_field_name($tabel1, $i);
				echo "<td align=''>$r[$name]</td>";
			}
			
			echo "</tr>";
			$baris++;
			$j++;
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
<?php
}
?>
</body>