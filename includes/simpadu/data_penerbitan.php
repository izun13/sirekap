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
koneksi1_tutup();
koneksi2_buka();

$recpage = 20;
$to_page = "simpadu1";
	
if($_POST["act"]) $act = $_POST["act"];
$search = explode(";",$search);
$search1 = $search[0]; 
if($_POST["search1"]) $search1 = $_POST["search1"];
$search2 = $search[1]; 
if($_POST["search2"]) $search2 = $_POST["search2"];
$search3 = $search[2]; 
if($_POST["search3"]) $search3 = $_POST["search3"];
$search4 = $search[3]; 
if($_POST["search4"]) $search4 = $_POST["search4"];
$search5 = $search[4]; 
if($_POST["search5"]) $search5 = $_POST["search5"];
$search6 = $search[5]; 
if($_POST["search6"]) $search6 = $_POST["search6"];
	
$search3 = str_replace("_"," ",$search3);
?>
<div class="judul">Rekap Penerbitan Perizinan Simpadu (oss.magelangkota.go.id)</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table width="">
	<tr><td width="200">Periode Tanggal Penetapan </td><td>:</td>
		<td width=""> <input type='text' size='10' name='search1' value='<?php echo $search1;?>' id="inputField1" class='search'> 
		s/d  <input type='text' size='10' name='search2' value='<?php echo $search2;?>' id="inputField2" class='search'> 
	</td></tr>
	<tr><td>Jenis Izin  </td><td>:</td>
		<td width="400"><select name="search3" id="pilih">
			<option value="">Pilih Semua</option>
			<?php
			if (($opd_id == 1)or($opd_id == 0)){ 
				$query_jns=mysql_query("SELECT*FROM jenis_izin WHERE jenisizin_opd_id IS NOT NULL ORDER BY jenisizin_name asc");
			}else{
				$query_jns=mysql_query("SELECT*FROM jenis_izin WHERE jenisizin_opd_id ='$opd_id' ORDER BY jenisizin_name asc");
			}
			
				while($r_jns=mysql_fetch_array($query_jns)){
					$selected = "";
					if($r_jns["jenisizin_id"] == $search3)$selected = "selected";
					echo"<option value='".$r_jns["jenisizin_id"]."' $selected>".$r_jns["jenisizin_name"]."</option>";			
				}
			?>			
			</select>
	</td></tr>
	<tr><td>&nbsp;</td><td>&nbsp;</td>
		<td>  atau seperti <input type='text' size='' name='search6' value='<?php echo $search6;?>'> 
	</td></tr>
	<!--<tr><td>Diterbitkan</td><td>:</td>
		<td> <input type='radio' name='search4' value='1' <?php //if($search4 == 1) echo "checked";?>> Sudah 
				<input type='radio' name='search4' value='2' <?php //if($search4 == 2) echo "checked";?>> Belum 
				<input type='radio' name='search4' value='0' <?php //if($search4 == 0) echo "checked";?>> Semua
	</td></tr>-->
	<tr><td width="200">Nama Pemohon</td><td>:</td>
		<td width=""> <input type='text' size='' name='search5' value='<?php echo $search5;?>'> 
	</td></tr>
	<tr><td>&nbsp;</td> <td>&nbsp;</td>
		<td align="right"><input type='submit' name='act' value='Tampilkan'>
	</td></tr>
</table>
</form>

<script type="text/javascript">
        $('#pilih').selectize({
            create: true,
            sortField: 'text'
        });
</script>
<?php
//if($act == "Tampilkan"){
	if(($search1 != "") or ($search2 != "")){
		?>
			<!--<script language="JavaScript">alert('Periode Tanggal Penetapan Harus Diisi !');
			document.location.href='?send=simpadu';
			</script>-->
		<?php	
	//}else{
		
	if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
		
	if (($opd_id == 1)or($opd_id == 0)){ 
		$tabel = "SELECT*FROM view_permohonan_izin WHERE permohonan_id IS NOT NULL";	
	}else{
		$tabel = "SELECT*FROM view_permohonan_izin WHERE permohonan_id IS NOT NULL and jenisizin_opd_id ='$opd_id'";
	}
		
	if(($search1 != "") and ($search2 != "")) $tabel .= " and permohonan_tgl_izin >= '$search1' and permohonan_tgl_izin <= '$search2'";
	if($search3 != "") $tabel .= " and jenisizin_id = '$search3'";
	//if($search4 == 1) $tabel .= " and permohonan_nomor_surat != ''";
	//if($search4 == 2) $tabel .= " and permohonan_nomor_surat = ''";
	if($search5 != "") $tabel .= " AND pemohon_nama LIKE '%$search5%'";
	if($search6 != "") $tabel .= " AND jenisizin_name LIKE '%$search6%'";
	
	$tabel .= " ORDER BY permohonan_id asc";
	//echo $tabel;
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search3x = str_replace(" ","_",$search3);
		$search = $search1.";".$search2.";".$search3x.";".$search4.";".$search5.";".$search6;
		page($query,$recpage,$starting,$to_page,$search,$act);
		
		echo"<a href='reports/simpadu/rekap_penerbitan_excel.php?search1=$search1&search2=$search2&search3=$search3&search4=$search4&search5=$search5&search6=$search6&opd_id=$opd_id' target='_blank'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
		//echo"&nbsp; <a href='reports/simpadu/rekap_penerbitan_pdf.php?search1=$search1&search2=$search2&search3=$search3&search4=$search4&search5=$search5&search6=$search6&opd_id=$opd_id'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
	
?>
	<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>ID</b></th>
			<th><b>Nomor Permohonan</b></th>
			<th><b>Jenis Izin</b></th>
			<th><b>Jenis Permohonan</b></th>
			<th><b>Nama Pemohon</b></th>
			<th><b>Badan Usaha</b></th>
			<th><b>Telpon Pemohon</b></th>
			<th><b>Alamat</b></th>
			<th><b>Nomor Izin</b></th>
			<th><b>Tanggal Penetapan</b></th>
			<th><b>Akhir Masa Berlaku</b></th>	
			<th><b>Data Teknis</b></th>	
		</tr>
		<?php
		$starting = starting();
		$recpage = recpage();
		
		// Nampilin Data				
		$query = mysql_query($tabel." LIMIT $starting,$recpage");		
		
		$i=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			$id = $r['permohonan_id'];
					
		//permohonan_id,permohonan_nomor_urut,permohonan_pemohon_id,pemohon_nama,pemohon_nomor_identitas,pemohon_alamat,
		//pemohon_telepon,permohonan_alamat_usaha,permohonan_tgl_daftar,statusizin_name,jenisizin_name,permohonan_nomor_surat,
		//permohonan_tgl_izin,permohonan_tgl_berakhir_izin,permohonan_retribusi,permohonan_tarif,permohonan_keterangan
	
			if($i % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			echo "
			  <td align='center'>$i</td>
			  <td align='left'>$r[permohonan_id]</td>
			  <td align='left'>$r[permohonan_nomor_urut]</td>
			  <td align='left'>$r[jenisizin_name]</td>
			  <td align='left'>$r[statusizin_name]</td>
			  <td align='left'>$r[pemohon_nama]</td>
			  <td align='left'>$r[permohonan_badan_usaha]</td>
			  <td align='left'>$r[pemohon_telepon]</td>
			  <td align='left'>$r[pemohon_alamat]</td>
			  <td align='left'>$r[permohonan_nomor_surat]</td>
			  <td align='center'>$r[permohonan_tgl_izin]</td>
			  <td align='center'>$r[permohonan_tgl_berakhir_izin]</td>";
			
			echo "<td align='center'><a href=?send=simpadu1_detil/$id/$starting/$search><img src=\"img/draf.png\" width='25' title='Detil Data Teknis'></img></td>";
			//echo "<td align='center'></a><a href='hapus-kesehatan-$id-$starting-$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
				
			echo "</tr>";
			$i++;	
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
	$page_showing = page_showing();
	echo show_navi();
	
	koneksi2_tutup();
?>
</td></tr>
</table>
<?php
}
//}
?>
</body>
