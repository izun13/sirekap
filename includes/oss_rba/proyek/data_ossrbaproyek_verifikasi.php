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
$to_page = "dataproyek";

if($search){
	$searching = explode(";",$search);
	$search1 = $searching[0]; 
	$search2 = $searching[1]; 
	$filters=count($searching)-3;
}

$group = $_POST["group"];
if($act)$group=$act;
?>	
		
<div><span class='judul'>Verifikasi OSS RBA Proyek</span></div>


<?php
//"id_proyek","nib","npwp_perusahaan","nama_perusahaan","uraian_status_penanaman_modal","uraian_jenis_perusahaan","uraian_risiko_proyek","uraian_skala_usaha","alamat_usaha","kecamatan_usaha","kelurahan_usaha",
//"longitude","latitude","kbli","judul_kbli","kl_sektor_pembina","nama_user","nomor_identitas_user","email","nomor_telp","jumlah_investasi","jumlah_investasi","mesin_peralatan","mesin_peralatan_impor","pembelian_pematangan_tanah",
//"bangunan_gedung","modal_kerja","lain_lain","jumlah_investasi","tki",tgl_verifikasi,bulan_verifikasi,sektor,
$tabel = "SELECT id,nama_perusahaan,nib,kbli,judul_kbli,nama_proyek,alamat_usaha,jumlah_investasi,tambah_investasi,status_perusahaan,status_kbli,catatan,verifikasi,tgl_verifikasi FROM view_proyek WHERE id IS NOT NULL";	
		
		//(mesin_peralatan+mesin_peralatan_impor+modal_kerja+lain_lain) AS jumlah_investasi
		
Function title($name){
	if($name == "id")$title = "ID";
	//if($name == "npwp_perusahaan")$title = "NPWP Perusahaan";
	if($name == "nama_perusahaan")$title = "Nama Perusahaan";
	if($name == "nib")$title = "NIB";
	//if($name == "uraian_status_penanaman_modal")$title = "Status";
	//if($name == "uraian_jenis_perusahaan")$title = "Jenis Perusahaan";
	//if($name == "uraian_risiko_proyek")$title = "Resiko";
	//if($name == "uraian_skala_usaha")$title = "Skala Usaha";
	if($name == "alamat_usaha")$title = "Alamat Usaha";
	if($name == "kbli")$title = "KBLI";
	if($name == "judul_kbli")$title = "Judul KBLI";
	if($name == "jumlah_investasi")$title = "Jumlah Investasi";
	if($name == "tambah_investasi")$title = "Tambahan Investasi";
	//if($name == "tki")$title = "Tenaga Kerja";
	if($name == "nama_proyek")$title = "Nama Usaha";
	if($name == "status_perusahaan")$title = "Status Perusahaan";
	if($name == "status_kbli")$title = "Status KBLI";
	if($name == "catatan")$title = "Catatan";
	if($name == "verifikasi")$title = "Status Verifikasi";
	//if($name == "tgl_verifikasi")$title = "Tanggal Verifikasi";
	//if($name == "bulan_verifikasi")$title = "Bulan Laporan";
	
	return $title;
}	

if($_POST["submit"]=="--- KEMBALI ---"){
	?>
	<script language="JavaScript">
		document.location.href='?send=<?php echo $to_page.'//'.$starting;?>/<?php echo $search;?>';
	</script>
	<?php
}

	$tabel .= " AND tgl_input >= '$search1' AND tgl_input <= '$search2'";
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
		
	//if($group) $tabel .= " GROUP BY nama_perusahaan";
	$tabel .= " ORDER BY nama_perusahaan asc";
		
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
			//$cek_update = 0;
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				$variabel = $name.$baris;
					
				if(isset($_POST[$variabel])){
					$isi = $_POST[$variabel];
					if($name=="tambah_investasi"){
						$isi= str_replace(".","",$isi);
					}
				
					$update .= $name."='".$isi."',";
					//if($_POST[$variabel] != $r[$name]) $cek_update = 1;
					if($name == "verifikasi"){
						if(($_POST[$variabel] == "Terverifikasi") and ($r[$name] != "Terverifikasi")) $update .= "tgl_verifikasi = '$date',"; //and ($cek_update == 1)
						if(($_POST[$variabel] == "Pending") or ($_POST[$variabel] == "Non Aktif"))$update .= "tgl_verifikasi = '0000-00-00',";	
					}
				}
			}
			$baris++;
	
			
			$update = substr($update, 0, -1);
			if($update){
				//echo $update."<br>";
				$ubah="update oss_rba_proyeks set $update where id='$r[id]'";
				$hasil=mysql_query($ubah);
				if (!$hasil) echo "Update Gagal :".mysql_error()."<br>";
			}
		}
	
		if ($hasil){
			?>
			<script language="JavaScript">alert('data OSS RBA Proyek telah diupdate !');
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
			if(($name != "id") and ($name != "tgl_verifikasi")) echo "<th>$title</th>";// 
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
			$id = $r["id"];
			$nib = $r["nib"];
			/*$cek_nib = mysql_fetch_array(mysql_query("SELECT*FROM oss_iumk WHERE nib='$nib'"));
			$nilai_investasi = $cek_nib['modal_usaha'];
			if(empty($cek_nib)){
				$cek_nib = mysql_fetch_array(mysql_query("SELECT*FROM oss_noniumk WHERE nib='$nib'"));
				$nilai_investasi = $cek_nib['investasi'];
			}*/
			
			$tambah_investasi = rupiah($r["tambah_investasi"]);
			if(empty($r["tambah_investasi"]))$tambah_investasi = rupiah($r["jumlah_investasi"]);
			
			
			$bln = substr($r["day_of_tanggal_terbit_oss"],5,2);
			
			if($baris % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				$m_now = date("m");				
				$m_ver = substr($r["tgl_verifikasi"],5,2);
				if(($r["verifikasi"] != "Terverifikasi") or ($m_now == $m_ver)){
					
					$variabel = $name.$baris;
					
					if($name == "tgl_verifikasi") echo "";
					
					elseif($name == "id"){ 
						echo "<td align='center'>$baris</td>";
					}				
					elseif(($name=="jumlah_investasi") or ($name=="mesin_peralatan") or ($name=="mesin_peralatan_impor") or ($name=="pembelian_pematangan_tanah") 
							or ($name=="bangunan_gedung") or ($name=="modal_kerja") or ($name=="lain_lain")){
							echo "<td align='right'>".rupiah($r[$name])."</td>";
					}
					elseif($name=="tambah_investasi"){
							echo "<td align='center'><input type='text' size='10' class='right' name='$variabel' value='$tambah_investasi' id='inputku' onkeydown='return numbersonly(this, event);' onkeyup='javascript:tandaPemisahTitik(this);'></td>";
					}
					elseif($name=="tki"){
							echo "<td align='center'><input type='text' size='1' name='$variabel' value='$r[$name]'></td>";
					}
					elseif($name=="catatan"){
							echo "<td align='center'><textarea cols='20' rows='1' name='$variabel'>$r[$name]</textarea></td>";
					}
					elseif($name=="status_perusahaan"){					
						echo"<td align='center' width='120px'>";
						$checked = "";
						//if(($r[$name] == "") and (empty($cek_nib['nib']))) $checked = "checked";
						if($r[$name] == "Baru")$checked = "checked";
						echo "<input type='radio' name='$variabel' value='Baru' $checked> Baru ";
						$checked = "";
						//if(($r[$name] == "") and (!empty($cek_nib['nib']))) $checked = "checked";
						if($r[$name] == "Lama")$checked = "checked";//
						echo "<input type='radio' name='$variabel' value='Lama' $checked> Lama ";
						echo"</td>";
					}
					elseif($name=="status_kbli"){					
						echo"<td align='center' width='120px'>";
						$checked = "";
						//if(($r[$name] == "") and (empty($cek_nib['nib']))) $checked = "checked";
						if($r[$name] == "Baru")$checked = "checked";
						echo "<input type='radio' name='$variabel' value='Baru' $checked> Baru";
						$checked = "";
						if(($r[$name] == "Lama") or ($r[$name] == ""))$checked = "checked";
						echo "<input type='radio' name='$variabel' value='Lama' $checked> Lama";
					}
					//elseif($name=="tgl_verifikasi"){
							//echo "<td align='center'><input type='text' size='7' name='$variabel' value='$date'></td>";
					//}
					elseif($name=="verifikasi"){
						echo "<td><select name='$variabel' id='pilih'>";
						$status=array("","Terverifikasi","Pending","Non Aktif");
						
						for($j=0;$j<=3;$j++){
							$selected = "";
							if($status[$j] == $r[$name])$selected = "selected";
							echo"<option value='".$status[$j]."' $selected>".$status[$j]."</option>";			
						}
						echo"</select></td>";
					}
					/*elseif($name=="sektor"){
						echo "<td><select name='$variabel' id='pilih'><option value=''></option>";
						$query_sektor=mysql_query("SELECT*FROM sektor order by id asc");
						while($r_sektor=mysql_fetch_array($query_sektor)){
							$selected = "";
							if($r_sektor["id"] == $r[$name])$selected = "selected";
							echo"<option value='".$r_sektor["id"]."' $selected>".$r_sektor["sektor"]."</option>";			
						}
						echo"</select></td>";
					}*/
					/*elseif($name=="bulan_verifikasi"){
						echo "<td><select name='$variabel' id='pilih'>";
						$NAMA_BULAN=array("","Januari", "Pebruari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
						
						for($j=0;$j<=12;$j++){
							$selected = "";
							if($NAMA_BULAN[$j] == $r[$name])$selected = "selected";
							elseif(($r[$name] == "") and ($j == $bln))$selected = "selected";
							echo"<option value='".$NAMA_BULAN[$j]."' $selected>".$NAMA_BULAN[$j]."</option>";			
						}
						echo"</select></td>";
					}*/
					
					else echo "<td align='left'>$r[$name]</td>";
				
				}else{
					if($name == "tgl_verifikasi") echo "";
					elseif($name == "id") {
						echo "<td align='center'>$baris</td>";
					}
					elseif(($name=="jumlah_investasi") or ($name=="mesin_peralatan") or ($name=="mesin_peralatan_impor") or ($name=="pembelian_pematangan_tanah") 
							or ($name=="bangunan_gedung") or ($name=="modal_kerja") or ($name=="lain_lain") or ($name=="tambah_investasi")){
							echo "<td align='right'>".rupiah($r[$name])."</td>";
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