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
$recpage = 20;
$to_page = "sicantik1-reklame";
	
if($_POST["act"]) $act = $_POST["act"];
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

?>
<div class="judul">Rekap Penerbitan Perizinan Reklame Sicantik</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table width="">
	<tr><td width="210">Periode Tanggal Penetapan</td><td>:</td>
		<td width="">  <!--<select name="search0">
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
		<td width="400"><select name="search3" id="pilih">
			<option value="">Pilih Semua</option>
			<?php
				$query_jns=mysql_query("SELECT*FROM jenis_izin WHERE opd_id = '3' ORDER BY jenis_izin asc");
				$x = 0;
				while($r_jns=mysql_fetch_array($query_jns)){
					$jenis_izin[$x] = $r_jns["id"];
					
					$selected = "";
					if($r_jns["id"] == $search3)$selected = "selected";
					echo"<option value='".$r_jns["id"]."' $selected>".$r_jns["jenis_izin"]."</option>";	
					$x++;
				}
			?>			
			</select>
	</td></tr>
	<tr><td width="">Nama Pemohon</td><td>:</td>
		<td width=""> <input type='text' size='' name='search4' value='<?php echo $search4;?>'> 
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
if($act == "Tampilkan"){
		
	if(($search1 == "") or ($search2 == "")){
		?>
			<script language="JavaScript">alert('Periode Tanggal Penetapan Harus Diisi !');
			document.location.href='?send=sicantik1';
			</script>
		<?php	
	}else{
		
	if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
	
	$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
	);  
	
	/*if($search0 == "Penetapan"){
		//View Permohona Izin Penetapan
		//$sumber = file_get_contents('https://sicantik.go.id/api/TemplateData/keluaran/23265.json', false, stream_context_create($arrContextOptions));		
		$sumber = "https://sicantik.go.id/api/TemplateData/keluaran/27646.json";
		$sumber .= "?key1='$search1'&key2='$search2'";
		//echo $sumber;
		$konten = file_get_contents($sumber, false, stream_context_create($arrContextOptions));
		$data = json_decode($konten, true);
	}else{*/
		//View Permohona Izin TTE
		//$sumber = file_get_contents('https://sicantik.go.id/api/TemplateData/keluaran/23265.json', false, stream_context_create($arrContextOptions));		
		$sumber = "https://sicantik.go.id/api/TemplateData/keluaran/37133.json";
		$sumber .= "?key1='$search1'&key2='$search2'";
		//echo $sumber;
		$konten = file_get_contents($sumber, false, stream_context_create($arrContextOptions));
		$data = json_decode($konten, true);
	//}
		$search = $search0.";".$search1.";".$search2.";".$search3.";".$search4;
		echo"<a href='reports/sicantik/rekap_penerbitan_reklame_excel.php?search=$search' target='_blank'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
		echo"&nbsp; <a href='reports/sicantik/rekap_penerbitan_reklame_pdf.php?search=$search'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
?>
	<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>Nomor Permohonan</b></th>
			<th><b>Jenis Izin</b></th>
			<th><b>Jenis Permohonan</b></th>
			<th><b>Nama Pemohon</b></th>
			<th><b>Nama Perusahaan</b></th>
			<th><b>Jenis Reklame</b></th>
			<th><b>Isi Reklame</b></th>
			<th><b>Ukuran Reklame</b></th>
			<th><b>Lokasi Reklame</b></th>
			<th><b>Nomor Izin</b></th>
			<th><b>Tanggal Penetapan</b></th>
			<?php 
			//if($search0 == "Penetapan")echo"<th><b>Tanggal Penetapan</b></th>";
			//else echo"<th><b>Tanggal Pengesahan</b></th>";
			?>
			<th><b>Akhir Masa Berlaku</b></th>	
			<th><b>File izin</b></th>	
		</tr>
		<?php
		
		$i = 1;
		foreach ($data["data"]["data"] as $key=>$r) {
							
			$tgl1 = "";
			$tgl2 = "";
			$link = "";
			
			if($r['tgl_pengajuan']) $tgl1 = tgl1($r['tgl_pengajuan']);
			
			//if($search0 == "Penetapan") {
				//if(($r['tgl_penetapan'] != null) and ($r['tgl_penetapan'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_penetapan']);
			//}else{
				if(($r['tgl_signed_report'] != null) and ($r['tgl_signed_report'] != "0000-00-00")) $tgl2 = tgl1($r['tgl_signed_report']);
			//}
			
			$masaberlaku = $r["tgl_permohonan_perusahaan"];			
			if($masaberlaku) $masaberlaku = tgl1($masaberlaku);
						
			$tlp = "";
			$hp = "";		
			if(($r['no_tlp'] != "") and ($r['no_tlp'] != "-") and ($r['no_tlp'] != "0"))$tlp = $r['no_tlp'];
			if(($r['no_hp'] != "") and ($r['no_hp'] != "-") and ($r['no_hp'] != "0"))$hp = $r['no_hp'];
			$contact = $tlp;
			if($hp != "") $contact = $hp;
			if(($tlp != "") and ($hp != "")) $contact = $tlp." / ".$hp;
			if($tlp == $hp) $contact = $tlp;
			
			$usaha = "";
			$usaha = $r['nama_perusahaan'];
			//if($usaha == "") $usaha = $r['irt'];
			$lokasi = TRIM($r["lokasi_pasang"]);
			
			$tampil = 1;		
			if ($search3){ 
				if($search3 == $r['jenis_izin_id']) $tampil = 1;
				else $tampil = 0;
			}else{
				if(in_array($r['jenis_izin_id'],$jenis_izin)) $tampil = 1;
				else $tampil = 0;
			}
			
			if($tampil){	
				
				$tampil2 = 1;
				if ($search4 != ""){
					if(preg_match("/{$search4}/i", $r['nama']))$tampil2 = 1;
					else $tampil2 = 0;
				}
				
				if($tampil2){	
					if($i % 2==0) echo "<tr class='cyan'>";
					else echo "<tr>";
										
					$link = "https://sicantik.go.id/files/signed/".$r["file_signed_report"];//ttd_qrcode
					echo "
					  <td align='center'>$i</td>
					  <td align='center'>$r[no_permohonan]</td>
					  <td align='left'>$r[jenis_izin]</td>
					  <td align='left'>$r[jenis_permohonan]</td>
					  <td align='left'>$r[nama]</td>
					  <td align='left'>$usaha</td>
					  <td align='left'>$r[jenis_reklame]</td>
					  <td align='left'>$r[isi_reklame]</td>
					  <td align='left'>$r[ukuran]</td>
					  <td align='left'>$lokasi</td>
					  <td align='left'>$r[no_izin]</td>
					  <td align='center'>$tgl2</td>
					  <td align='center'>$masaberlaku</td>";
					  
					  if($r["file_signed_report"]) echo"<td align='center'><a href='$link' target='_blank'><img src=\"img/pdf.jpg\" width='38' title='cetak PDF'></img></a></td>";	
					  else echo"<td align='center'></td>";
					  
					echo "</tr>";
					$i++;
				}
			}
			//echo "<td align='center'><a href=input-kesehatan-$id-$starting-$search><img src=\"img/edit.png\" width='25' title='ubah'></img></td>";
			//echo "<td align='center'></a><a href='hapus-kesehatan-$id-$starting-$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
				
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
	//$page_showing = page_showing();
	//echo show_navi();
?>
</td></tr>
</table>
<?php
	}
}
?>
</body>
