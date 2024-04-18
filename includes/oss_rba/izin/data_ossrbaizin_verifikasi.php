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
<script type="text/javascript">
function tampilkan(){
        document.getElementById("formulir").submit();
}
</script>
<body>
<?php
$recpage = 25;
$to_page = "datarbaizin";

if($search){
	$searching = explode(";",$search);
	$search1 = $searching[0]; 
	$search2 = $searching[1]; 
	$filters=count($searching)-3;
}

$group = $_POST["group"];
if($act)$group=$act;
?>	
		
<div><span class='judul'>Verifikasi OSS RBA Izin</span></div>


<?php

$tabel = "SELECT id,nama_perusahaan,nib,day_of_tanggal_terbit_oss,uraian_status_penanaman_modal,kd_resiko,kbli,day_of_tgl_izin,
		uraian_jenis_perizinan,nama_dokumen,uraian_status_respon,kewenangan,verifikasi FROM oss_rba_izins WHERE id IS NOT NULL";	
		//tgl_verifikasi
Function title($name){
	if($name == "id")$title = "ID";
	if($name == "nama_perusahaan")$title = "Nama Perusahaan";
	if($name == "nib")$title = "NIB";
	if($name == "day_of_tanggal_terbit_oss")$title = "Tanggal Terbit";
	if($name == "uraian_status_penanaman_modal")$title = "Status";
	if($name == "kd_resiko")$title = "Kode Resiko";
	if($name == "kbli")$title = "KBLI";
	if($name == "day_of_tgl_izin")$title = "Tanggal Izin";
	if($name == "uraian_jenis_perizinan")$title = "Jenis Perizinan";
	if($name == "nama_dokumen")$title = "Nama Dokumen";
	if($name == "uraian_status_respon")$title = "Status Respon";
	if($name == "kewenangan")$title = "Kewenangan";
	if($name == "verifikasi")$title = "Status Verifikasi";
	//if($name == "tgl_verifikasi")$title = "Tanggal Verifikasi";
	
	return $title;
}	

if($_POST["submit"]=="--- KEMBALI ---"){
	?>
	<script language="JavaScript">
		document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
	</script>
	<?php
}

	$tabel .= " AND day_of_tgl_izin >= '$search1' AND day_of_tgl_izin <= '$search2'";
	$x = 2;
	for ($j=1;$j<=$filters;$j++){
		
		if($searching[$x]){
			$searching2 = explode(":",$searching[$x]);
			if($kol=="")$kol = $searching2[0];
			if($sim=="")$sim = $searching2[1];
			if($val=="")$val = $searching2[2];
			$val = str_replace("_"," ",$val);
		}
		$x++;
			
		if($kol) {
			if(($sim == "LIKE") or ($sim == "NOT LIKE"))$tabel .= " AND ".$kol." ".$sim." '%".$val."%'";
			else $tabel .= " AND ".$kol." ".$sim." '".$val."'";
		}
	}
		
	if($group) $tabel .= " GROUP BY nama_perusahaan";
	$tabel .= " ORDER BY id asc";
		
	$query = mysql_query($tabel);
	if(isset($_GET['page'])) $starting = $_GET['page'];		
	if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
	if($starting=='') $starting = 0;
		
	$act = $group;
	page($query,$recpage,$starting,$to_page,$search,$act);
		
	$starting = starting();
	$recpage = recpage();		
		
		
	if($_POST["submit"]=="--- SIMPAN ---"){	
		$query = mysql_query($tabel." LIMIT $starting,$recpage");
		$date = date("Y-m-d");
		$baris=$starting+1;		
		while ($r= mysql_fetch_array($query)){
				
			$update = "";
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				$variabel = $name.$baris;	
				if(isset($_POST[$variabel]))$update .= $name."='".$_POST[$variabel]."',";
				if($_POST['status_perusahaan'.$baris] != "") $update .= "tgl_verifikasi = '$date',";
				else $update .= "tgl_verifikasi = 'NULL',";
			}
			$baris++;
	
			
			$update = substr($update, 0, -1);
			if($update){
				$ubah="update oss_rba_izins set $update where id='$r[id]'";
				$hasil=mysql_query($ubah);
				if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
			}
		}
	
		if ($hasil){
			?>
			<script language="JavaScript">alert('data OSS RBA nib telah diupdate !');
				document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
			</script>
			<?php
		}
	}
?>
<form action='' method='post' autocomplete="off" id="formulir"> 
<table>
<tr><td colspan='3' align='right'><input type='submit' name='submit' value='--- KEMBALI ---'></td></tr>
</table>

<!--<div id="border">
* Klik judul kolom untuk mengurutkan.-->
	<table class="tabelbox1">
		<?php		
		echo "<tr>";
		echo "<th>No.</th>";
		
		for($i = 0; $i < mysql_num_fields($query); $i++){
			$name = mysql_field_name($query, $i);
			//$type = mysql_field_type($query, $i);
			//$size = mysql_field_len($query, $i);
			//echo $name.' - '.$type.' - '.$size.'<br>';
			//$urutkan = $name.$order;
			//echo "<th><a href='?send=$to_page////$urutkan'>$name</a></th>";
			
			$title = "";
			$title = title($name);
			if($name != "id") echo "<th>$title</th>";
		}
		
		echo "</tr>";
		
		// Nampilin Data				
		$query = mysql_query($tabel." LIMIT $starting,$recpage");
		$current = $_REQUEST["x"];
		$current1 = 0;
		
		$date = date("Y-m-d");
		$baris=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			if($current1 == 0)$current1 = $r[id];		
			if(!$current)$current = $current1;
			$id = $r[id];
			$bln = substr($r["day_of_tanggal_terbit_oss"],5,2);
			
			if($baris % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			echo "<td align='center'>$baris</td>";
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				if($name != "id"){
					$variabel = $name.$baris;
					if($name=="verifikasi"){
						echo "<td><select name='$variabel' id='pilih'>";
						$status=array("","Terverifikasi","Pending","Dicabut");
						
						for($j=0;$j<=3;$j++){
							$selected = "";
							if($status[$j] == $r[$name])$selected = "selected";
							echo"<option value='".$status[$j]."' $selected>".$status[$j]."</option>";			
						}
						echo"</select></td>";
					}
					else echo "<td align='left'>$r[$name]</td>";
				}
				
			}
			
			echo "</tr>";
			$baris++;
		}
		?>
		
</table>
<table width='100%'>
<tr><td align=''><input type='submit' name='submit' value='--- KEMBALI ---'></td><td align='right'><input type='submit' name='submit' value='--- SIMPAN ---'>&nbsp;&nbsp;</td></tr>
</table>
</form>
<!--</div>-->
</body>