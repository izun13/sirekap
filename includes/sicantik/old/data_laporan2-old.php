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
$recpage = 20;
$to_page = "sicantik2";

//if($_POST["act"]) $act = $_POST["act"];
$search = explode(";",$search);
$search0 = $search[0]; 
if($_POST["search0"]) $search0 = $_POST["search0"];
$search1 = $search[1]; 
if($_POST["search1"]) $search1 = $_POST["search1"];
$search2 = $search[2]; 
if($_POST["search2"]) $search2 = $_POST["search2"];
$search3 = $search[3]; 
if($_POST["search3"]) $search3 = $_POST["search3"];
$search4 = $search[4]; 
if($_POST["search4"]) $search4 = $_POST["search4"];
$search5 = $search[5]; 
if($_POST["search5"]) $search5 = $_POST["search5"];
$search6 = $search[6]; 
if($_POST["search6"]) $search6 = $_POST["search6"];
$search7 = $search[7]; 
if($_POST["search7"]) $search7 = $_POST["search7"];
	
$search3 = str_replace("_"," ",$search3);

$group = $_POST["group"];
if($act)$group=$act;
if($group)	$checked = "checked";
?>
<div class="judul">Rekap Lama Proses Permohonan Sicantik</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off" id="formulir"> 
<table>
	<tr><td width="200">Periode Tanggal Penetapan</td><td>:</td>
		<td width=""> <!--<select name="search0">
		<?php 
			/*$periode=array("Pengajuan","Penetapan");
			for ($x=0;$x<2;$x++){
				$selected = "";
				if($search0 == $periode[$x])$selected = "selected";
					echo"<option value='".$periode[$x]."' $selected>".$periode[$x]."</option>";
			}*/
		?></select>-->
		<input type='text' size='10' name='search1' value='<?php echo $search1;?>' id="inputField1" class='search'> 
		s/d  <input type='text' size='10' name='search2' value='<?php echo $search2;?>' id="inputField2" class='search'> 
	</td></tr>
	<tr><td>Jenis Izin  </td><td>:</td>
		<td width="400"> <select name="search3" id="pilih">
			<option value="">Pilih Semua</option>
			<?php
			$query_jns=mysql_query("SELECT*FROM jenis_izin WHERE del != '1' order by jenis_izin asc");
				while($r_jns=mysql_fetch_array($query_jns)){
					$selected = "";
					if($r_jns["jenis_izin"] == $search3)$selected = "selected";
					echo"<option value='".$r_jns["jenis_izin"]."' $selected>".$r_jns["jenis_izin"]."</option>";			
				}
			?>			
			</select>
	</td></tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td>
		<td>  atau seperti <input type='text' size='' name='search7' value='<?php echo $search7;?>'> 
	</td></tr>
	<tr><td>Pengesahan ( TTE )</td><td>:</td>
		<td> <input type='radio' name='search4' value='1' <?php if($search4 == 1) echo "checked";?>> Disahkan 
				<input type='radio' name='search4' value='2' <?php if($search4 == 2) echo "checked";?>> Belum Disahkan
				<input type='radio' name='search4' value='0' <?php if($search4 == 0) echo "checked";?>> Semua
	</td></tr>
	<tr><td>Lama Proses (s/d Penetapan)</td><td>:</td>
		<td> <input type='radio' name='search5' value='5' <?php if($search5 == 5) echo "checked";?>> <= 5 hari 
				<input type='radio' name='search5' value='6' <?php if($search5 == 6) echo "checked";?>> > 5 hari 
				<input type='radio' name='search5' value='0' <?php if($search5 == 0) echo "checked";?>> Semua
	</td></tr>
	<tr><td width="200">Nama Pemohon</td><td>:</td>
		<td width=""> <input type='text' size='' name='search6' value='<?php echo $search6;?>'> 
	</td></tr>
	<tr><td>&nbsp;</td> <td>&nbsp;</td> 
		<td align="right"><input type='submit' name='tombol' value='Tampilkan'>
	</td></tr>
</table>
Group by NIK : <input type='checkbox' name='group' onchange="tampilkan()" <?php echo $checked;?>>
</form>

<script type="text/javascript">
        $('#pilih').selectize({
            create: true,
            sortField: 'text'
        });
</script>
<?php	
if($_POST["tombol"] == "Tampilkan"){
	
	if(($search1 == "") or ($search2 == "")){
		?>
			<script language="JavaScript">alert('Periode Tanggal Pengajuan/Penetapan Harus Diisi !');
			document.location.href='?send=sicantik2';
			</script>
		<?php	
	}
}

if(($search1 != "") and ($search2 != "")){
	if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
	
	$tabel = "SELECT*FROM view_permohonan_izin WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
	//if(($search0 == "Pengajuan") and($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_pengajuan >= '$search1' AND tgl_pengajuan <= '$search2'";
	//if(($search0 == "Penetapan") and($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
	if(($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
	if($search3 != "") $tabel .= " AND jenis_izin = '$search3'";
	if($search6 != "") $tabel .= " AND nama LIKE '%$search6%'";
	if($search7 != "") $tabel .= " AND jenis_izin LIKE '%$search7%'";
	
	if($group) $tabel .= " GROUP BY pemohon_id";
	$tabel .= " ORDER BY no_permohonan ASC";
	//echo $tabel;
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search3x = str_replace(" ","_",$search3);
		$search = $search0.";".$search1.";".$search2.";".$search3x.";".$search4.";".$search5.";".$search6.";".$search7;
		$act = $group;
		page($query,$recpage,$starting,$to_page,$search,$act);
	
	$searchlink = "search0=$search0&search1=$search1&search2=$search2&search3=$search3&search4=$search4&search5=$search5&search6=$search6&search7=$search7";
	echo"<a href='reports/sicantik/rekap_izin_excel_2.php?$searchlink'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	echo"&nbsp; <a href='reports/sicantik/rekap_izin_pdf_2.php?$searchlink'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
	echo"&nbsp; <a href='reports/sicantik/rekap_izin_graph_2.php?$searchlink'  target='_blank'><img src=\"img/graph1.png\" width='38' title='Lihat Grafik'></img></a>";
?>
	<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>ID</b></th>
			<th><b>Nomor</b></th>	
			<th><b>Tanggal Pengajuan</b></th>	
			<th><b>Jenis Izin</b></th>
			<th><b>Jenis Permohonan</b></th>
			<th><b>Nama Pemohon</b></th>
			<!--<th><b>No. Identitas</b></th>-->
			<th><b>Telp./HP. Pemohon</b></th>
			<th><b>Tanggal Terima Berkas</b></th>
			<th><b>Nomor Izin</b></th>
			<th><b>Tanggal Penetapan</b></th>
			<th><b>Lama Proses</b></th>
			<th><b>Tanggal Pengesahan</b></th>
			<!--<th><b>Lokasi Izin</b></th>-->
			<th><b>Rincian Tahap</b></th>	
		</tr>
		<?php
		$starting = starting();
		$recpage = recpage();
		
		// Nampilin Data
		$libur_nasional=mysql_fetch_array(mysql_query("SELECT tgl FROM libur_nasional"));
		$query = mysql_query($tabel." LIMIT $starting,$recpage");	
		if(($search5 != 0) or ($search4 != 0)) $query = mysql_query($tabel);
		
		$i=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			$id = $r["id"];
			
			$hari_kerja= 0;
			$tgl_awal = null;
			$tgl_akhir = null;	
			$tgl_tte = null;		
			$tgl1 = "";
			$tgl2 = "";
			$tgl3 = "";
			$tgl4 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			//if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			//$r_penyerahan = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
			//if($r_penyerahan['tgl_penyerahan']) $tgl3 = tgl1($r_penyerahan['tgl_penyerahan']);
			$lokasi = TRIM($r["lokasi_izin"]);
			
			//tgl cetak tanda terima berkas
			$r_terima = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '33' "));
			//tgl ttd izin
			$r_tte = mysql_fetch_array(mysql_query("SELECT tgl_signed_report FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '40' "));
			
			
			$tgl_awal = $r_terima['end_date'];
			//if($tgl_awal == null) $tgl_awal = $r['tgl_pengajuan'];
			$tgl_akhir = $r['tgl_penetapan'];
			$tgl_tte = $r_tte['tgl_signed_report'];
			if($tgl_tte) $tgl3 = tgl1($tgl_tte);
			//if($tgl_akhir == null) $tgl_akhir = date("Y-m-d");
			
			if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
			if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
			//jumlah hari kerja
			if(($tgl_awal != null) and ($tgl_akhir != null)){
				$awal=strtotime($tgl_awal);
				$akhir=strtotime($tgl_akhir);
				
				for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
					$i_date=date("Y-m-d",$x);
					if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
						$hari_kerja++;
					}
				}
			}
			
			if(($r['tgl_penetapan'] != "") and ($hari_kerja == 0))$hari_kerja=1;
						
			$tlp = "";
			$hp = "";		
			if(($r['no_tlp'] != "") and ($r['no_tlp'] != "-") and ($r['no_tlp'] != "0"))$tlp = $r['no_tlp'];
			if(($r['no_hp'] != "") and ($r['no_hp'] != "-") and ($r['no_hp'] != "0"))$hp = $r['no_hp'];
			$contact = $tlp;
			if($hp != "") $contact = $hp;
			if(($tlp != "") and ($hp != "")) $contact = $tlp." / ".$hp;
			if($tlp == $hp) $contact = $tlp;
			
			/*$r_nama = explode(",",$r['nama']);
			for ($j=0; $j<count($r_nama); $j++) {
				if($j==0)$nama = strtoupper($r_nama[$j]).",";
				else $nama .= $r_nama[$j].",";
			}
			$nama = substr($nama, 0, -1);*/
			
			$tampil = 1;
			if(($search4 == 1) and ($tgl_tte == null)) $tampil = 0;
			if(($search4 == 2) and ($tgl_tte != null)) $tampil = 0;
							
			if($tampil == 1){
				
				$tampil2 = 1;
				if(($search5 == 5) and ($hari_kerja > $search5)) $tampil2 = 0;
				if(($search5 == 6) and ($hari_kerja < $search5)) $tampil2 = 0;
				
				if($tampil2 == 1){
					
					if($i % 2==0){
						echo "<tr class='cyan'>";
					}else{
						echo "<tr>";
					}
				
					echo "<td align='center'>$i</td>";
					echo "<td align='left'>$r[id]</td>";
					echo "<td align='left'>$r[no_permohonan]</td>";
					echo "<td align='center'>$tgl1</td>";
					echo "<td align='left'>$r[jenis_izin]</td>";
					echo "<td align='center'>$r[jenis_permohonan]</td>";
					echo "<td align='left'>$r[nama]</td>";
					//echo "<td align='left'>$r[tipe_identitas] : $r[no_identitas]</td>";
					echo "<td align='left'>$contact</td>";
					echo "<td align='center'>$tgl4</td>";
					echo "<td align='left'>$r[no_izin]</td>";
					echo "<td align='center'>$tgl2</td>";
					echo "<td align='center'>$hari_kerja</td>";
					echo "<td align='center'>$tgl3</td>";
					//echo "<td align='left'>$lokasi</td>";			  
					echo "<td align='center'><a href='?send=detil-sicantik2/$id/$starting/$search'><img src='img/draf.png' width='25' title='Rincian'></img></a></td>";
					
					//echo "<td align='center'><a href=input-kesehatan-$id-$starting-$search><img src=\"img/edit.png\" width='25' title='ubah'></img></td>";
					//echo "<td align='center'></a><a href='hapus-kesehatan-$id-$starting-$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
						
					echo "</tr>";
					$i++;
				}
			}
		}
		?>
		
</table>

<table width='100%'>
<tr><td width='100'>
<?php 

		//echo"<a href=index.php?send=input-penduduk&x=$current&page=$starting><img src=\"img/edit.png\" width='30' title='ubah'></img></a>				
		//<a href='index.php?send=hapus-penduduk&x=$current&page=$starting' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='30' title='hapus'></img></a>
		//<a href='includes/penduduk/cetak_pdf.php?x=$current' target='_blank'><img src=\"img/cetak.png\" width='30' height='35' title='cetak'></img></a>	";
?>
</td><td>
<?php
	if(($search5 == 0) and ($search4 == 0)){
		$page_showing = page_showing();
		echo show_navi();
	}
?>
</td></tr>
</table>
<?php
}
?>
</body>
