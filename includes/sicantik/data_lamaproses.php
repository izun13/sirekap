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

$tanggal1 = date('Y-m-d',strtotime($search1));
$tanggal2 = date('Y-m-d',strtotime('2022-04-30'));
$tanggal3 = date('Y-m-d',strtotime('2023-03-31'));
					
$group = $_POST["group"];
if($act)$group=$act;
if($group)	$checked = "checked";
?>
<div class="judul">Rekap Lama Proses Permohonan Sicantik</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off" id="formulir"> 
<table>
	<tr><td width="210">Periode Tanggal</td><td>:</td>
		<td width=""><!--<select name="search0">
		<?php 
			/*$periode=array("Penetapan","Pengesahan");
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
		<td width="400"> 
		<!--<select name="search3" id="pilih">
			<option value="">Pilih Semua</option>
			<?php
			/*$query_jns=mysql_query("SELECT*FROM jenis_izin WHERE del != '1' order by jenis_izin asc");
				while($r_jns=mysql_fetch_array($query_jns)){
					$selected = "";
					if($r_jns["jenis_izin"] == $search3)$selected = "selected";
					echo"<option value='".$r_jns["jenis_izin"]."' $selected>".$r_jns["jenis_izin"]."</option>";			
				}*/
			?>			
			</select>-->
			<?php
			echo "<select name='search3'>";
			$str_simbol = array("LIKE","NOT LIKE");
				for($i = 0; $i<count($str_simbol); $i++){
					$selected = "";
					if($str_simbol[$i] == $search3)$selected = "selected";
					echo"<option value='".$str_simbol[$i]."' $selected>".$str_simbol[$i]."</option>";	
				}		
			echo "</select>";
			?>
		<input type='text' size='' name='search7' value='<?php echo $search7;?>'> 
	</td></tr>
	
	<!--<tr><td>&nbsp;</td><td>&nbsp;</td>
		<td>  atau seperti <input type='text' size='' name='search7' value='<?php //echo $search7;?>'> 
	</td></tr>-->
	
	<!--<tr><td>Proses Dinas Teknis</td><td>:</td>
		<td> 
			<input type='radio' name='search4' value='0' <?php //if($search4 == 0) echo "checked";?>> Ya
			<input type='radio' name='search4' value='1' <?php //if($search4 == 1) echo "checked";?>> Tidak
	</td></tr>-->
	<tr><td>Lama Proses</td><td>:</td>
		<td> <input type='radio' name='search5' value='1' <?php if($search5 == 1) echo "checked";?>> Sesuai SOP 
				<input type='radio' name='search5' value='2' <?php if($search5 == 2) echo "checked";?>> Melebihi SOP
				<input type='radio' name='search5' value='0' <?php if($search5 == 0) echo "checked";?>> Semua
	</td></tr>
	<!--<tr><td width="">Nama Pemohon</td><td>:</td>
		<td width=""> <input type='text' size='' name='search6' value='<?php echo $search6;?>'> 
	</td></tr>-->
	<tr><td>&nbsp;</td> <td>&nbsp;</td> 
		<td align="right"><input type='submit' name='tombol' value='Tampilkan'>
	</td></tr>
</table>
Group by Pemohon : <input type='checkbox' name='group' onchange="tampilkan()" <?php echo $checked;?>>
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
			<script language="JavaScript">alert('Periode Tanggal Pengesahan Harus Diisi !');
			document.location.href='?send=sicantik2';
			</script>
		<?php	
	}
}

if(($search1 != "") and ($search2 != "")){
	if($search1 == $search2) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
	
	//sampai dengan bulan mei
	//if($tanggal1 <= $tanggal2){
		$tabel = "SELECT*FROM permohonan_izin_penetapan WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND status_penetapan != 'ditolak'";	
		$tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
	//}else{
		//$tabel = "SELECT*FROM permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND status_penetapan != 'ditolak'";	
		//$tabel .= " AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";
	//}
	//if($search3 != "") $tabel .= " AND jenis_izin = '$search3'";
	//if($search6 != "") $tabel .= " AND nama LIKE '%$search6%'";
	
	if($search7 != "") $tabel .= " AND jenis_izin $search3 '%$search7%'";
	
	if($group) $tabel .= " GROUP BY pemohon_id";
	$tabel .= " ORDER BY id ASC";
	//echo $tabel;
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search3 = str_replace(" ","_",$search3);
		$search = $search0.";".$search1.";".$search2.";".$search3.";".$search4.";".$search5.";".$search6.";".$search7;
		$act = $group;
		page($query,$recpage,$starting,$to_page,$search,$act);
	
	//$searchlink = "search0=$search0&search1=$search1&search2=$search2&search3=$search3&search4=$search4&search5=$search5&search6=$search6&search7=$search7";
	$searchlink = $search;
	echo"<a href='reports/sicantik/rekap_lamaproses_excel.php?send=$searchlink' target='_blank'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	echo"&nbsp; <a href='reports/sicantik/rekap_lamaproses_pdf.php?send=$searchlink'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
	echo"&nbsp; <a href='reports/sicantik/rekap_lamaproses_graph.php?send=$searchlink'  target='_blank'><img src=\"img/graph1.png\" width='38' title='Lihat Grafik'></img></a>";
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
			<?php 
			//if($search0 == "Penetapan")echo"<th><b>Tanggal Penetapan</b></th>";
			//else echo"<th><b>Tanggal Pengesahan</b></th>";
			?>
			<th><b>Lama Proses</b></th>
			<!--<th><b>Tanggal Penyerahan</b></th>-->
			<!--<th><b>Lokasi Izin</b></th>-->
			<th><b>Rincian Tahap</b></th>	
		</tr>
		<?php
		$starting = starting();
		$recpage = recpage();
		
		// Nampilin Data
		// libur nasional
		$z=0;
		$query_libur = mysql_query("SELECT tgl FROM libur_nasional");
		while ($r_libur= mysql_fetch_array($query_libur)){
			$libur_nasional[$z] = $r_libur['tgl'];
			$z++;
		}

		$query = mysql_query($tabel." LIMIT $starting,$recpage");	
		if($search5 != 0) $query = mysql_query($tabel);
		
		$i=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			$id = $r["id"];
			$r_jns = mysql_fetch_array(mysql_query("SELECT*FROM jenis_izin WHERE jenis_izin = '$r[jenis_izin]'"));
			$opd_id = $r_jns['opd_id'];
			
			$hari_kerja= 0;
			$tgl_awal = null;
			$tgl_akhir = null;			
			$tgl1 = "";
			$tgl2 = "";
			$tgl3 = "";
			$tgl4 = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			//if(( $r['tgl_penetapan'] != null ) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			//$r_tgl = mysql_fetch_array(mysql_query("SELECT*FROM c_penyerahan_izin WHERE permohonan_izin_id = '$r[id]'"));
			//if($r_tgl['tgl_penyerahan']) $tgl3 = tgl1($r_tgl['tgl_penyerahan']);
			$lokasi = TRIM($r["lokasi_izin"]);
						
			//tgl cetak tanda terima berkas
			$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '33' "));
			$tgl_awal = $tglawal['end_date'];
			
			//if($tanggal1 <= $tanggal2)$tgl_akhir = $r['tgl_penetapan'];
			//else $tgl_akhir = $r['end_date'];
			
			$tgl_akhir = $r['tgl_penetapan'];
			
			//tgl ttd izin
			//$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '40' "));
			//tgl penetapan
			//if(empty($tglakhir))$tglakhir = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '8' "));
			
			$tgl_rekomendasi = "";
			//tgl rekomendasi kesehatan
			//$tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '7' "));
			//tgl rekomendasi diperindag
			//if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '176' "));
			//tgl rekomendasi bpkad
			if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '108' "));
			if(empty($tgl_rekomendasi)) $tgl_rekomendasi = mysql_fetch_array(mysql_query("SELECT start_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '192' "));
			
			if((!empty($tgl_rekomendasi)) and ($opd_id == 3))$tgl_akhir = $tgl_rekomendasi['start_date'];
			
			//if($search4 == 0)$tgl_akhir = $r['end_date'];
			
			if($tgl_awal != null)$tgl4 = tgl1($tgl_awal);
			if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
			
			//jumlah hari kerja
			if(($tgl_awal != null) and ($tgl_akhir != null)){
				$tgl_awal = date('Y-m-d', strtotime($tgl_awal));
				$tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
				$awal=strtotime($tgl_awal);
				$akhir=strtotime($tgl_akhir);
				
				for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
					$i_date=date("Y-m-d",$x);
					if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
						$hari_kerja++;
					}
				}
			}
			
			//echo "awal : $tgl_awal, akhir : $tgl_akhir, hari kerja : $hari_kerja";
			
			if((!empty($tgl_rekomendasi)) and ($opd_id == 3)){//and ($search4 == 1)
				//tgl Verifikasi Rekomendasi disperindag
				$tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '165' "));
				//tgl Cetak Rekomendasi dkk dan disperindag
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '35' "));
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '7' "));
				//tgl Verifikasi status bayar bpkad
				if(empty($tgl_cetakrekomendasi)) $tgl_cetakrekomendasi = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r[id]' AND jenis_proses_id = '226' "));
				
				if(!empty($tgl_cetakrekomendasi))$tgl_awal = $tgl_cetakrekomendasi['end_date'];
				if( date('Y-m-d', strtotime($tgl_awal)) ==  $tgl_akhir) $tgl_awal = date('Y-m-d', strtotime('+1 days', strtotime($tgl_awal))); 
				
				//$tgl_akhir = $r['end_date'];
				$tgl_akhir = $r['tgl_penetapan'];
				if($tgl_akhir != null)$tgl2 = tgl1($tgl_akhir);
				
				//jumlah hari kerja
				if(($tgl_awal != null) and ($tgl_akhir != null)){
					$tgl_awal = date('Y-m-d', strtotime($tgl_awal));
					$tgl_akhir = date('Y-m-d', strtotime($tgl_akhir));
					$awal=strtotime($tgl_awal);
					$akhir=strtotime($tgl_akhir);
					
					for ($x=$awal; $x <= $akhir; $x += (60 * 60 * 24)) {
						$i_date=date("Y-m-d",$x);
						if (date("w",$x) !="0" AND date("w",$x) !="6" AND !in_array($i_date,$libur_nasional)) {
							$hari_kerja++;
						}
					}
				}
			}
			
			$hari_kerja = $hari_kerja-1;
			if($hari_kerja <= 0) $hari_kerja = 1;
			
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
			
			//$tampil = 0;
			//if(($search4 == 0) or ($search4 == "")) $tampil = 1;
			//if(($search4 == 1) and ($tgl_akhir != null) and ($tgl_akhir <= $search2)) $tampil = 1;
			//if(($search4 == 2) and ($tgl_akhir > $search2)) $tampil = 1;
			//if(($search4 == 2) and ($tgl_akhir == null)) $tampil = 1;
			
			//if($tampil == 1){
				$tampil2 = 0;
				if($search5 == 1){
					//bulan sebelum april 2023
					if($tanggal1 <= $tanggal3){
						if (($hari_kerja <= 3) and ($opd_id != 3)) $tampil2 = 1;
						if (($hari_kerja <= 5) and ($opd_id == 3)) $tampil2 = 1;
						
					}else{
						$cek_sop = mysql_fetch_array(mysql_query("SELECT waktu_sop FROM jenis_izin WHERE id = '$r[jenis_izin_id]'"));
						if ($hari_kerja <= $cek_sop['waktu_sop']) $tampil2 = 1;
					}
					
				}
				elseif($search5 == 2){					
					//bulan sebelum april 2023
					if($tanggal1 <= $tanggal3){
						if (($hari_kerja > 3) and ($opd_id != 3)) $tampil2 = 1;
						if (($hari_kerja > 5) and ($opd_id == 3)) $tampil2 = 1;
					}else{
						$cek_sop = mysql_fetch_array(mysql_query("SELECT waktu_sop FROM jenis_izin WHERE id = '$r[jenis_izin_id]'"));
						if ($hari_kerja > $cek_sop['waktu_sop']) $tampil2 = 1;
					}
				}
				else $tampil2 = 1;		
				
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
					//echo "<td align='center'>$tgl3</td>";
					//echo "<td align='left'>$lokasi</td>";			  
					echo "<td align='center'><a href='?send=detil-sicantik2/$id/$starting/$search'><img src='img/draf.png' width='25' title='Rincian'></img></a></td>";
					
					//echo "<td align='center'><a href=input-kesehatan-$id-$starting-$search><img src=\"img/edit.png\" width='25' title='ubah'></img></td>";
					//echo "<td align='center'></a><a href='hapus-kesehatan-$id-$starting-$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
						
					echo "</tr>";
					$i++;
				}
			//}
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
	if($search5 == 0){
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
