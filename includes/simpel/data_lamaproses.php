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
$to_page = "simpel2";
	
if($_POST["act"]) $act = $_POST["act"];
$search = explode(";",$search);
//$search0 = $search[0]; 
//if($_POST["search0"]) $search0 = $_POST["search0"];
$search1 = $search[1]; 
if($_POST["search1"]) $search1 = $_POST["search1"];
$search2 = $search[2]; 
if($_POST["search2"]) $search2 = $_POST["search2"];
$search3 = $search[3]; 
if($_POST["search3"]) $search3 = $_POST["search3"];
//$search4 = $search[4]; 
//if($_POST["search4"]) $search4 = $_POST["search4"];
//$search5 = $search[5]; 
//if($_POST["search5"]) $search5 = $_POST["search5"];

//$tanggal1 = date('Y-m-d',strtotime($search1));
//$tanggal2 = date('Y-m-d',strtotime('2022-04-30'));
?>
<div class="judul">Rekap Lama Proses Perizinan Pemakaman</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table width="">
	<tr><td width="210">Periode Tanggal Pengesahan (TTE)</td><td>:</td>
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
	
	<tr><td>Lama Proses</td><td>:</td>
		<td> <input type='radio' name='search3' value='1' <?php if($search3 == 1) echo "checked";?>> Sesuai SOP 
				<input type='radio' name='search3' value='2' <?php if($search3 == 2) echo "checked";?>> Melebihi SOP
				<input type='radio' name='search3' value='0' <?php if($search3 == 0) echo "checked";?>> Semua
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
			<script language="JavaScript">alert('Periode Tanggal Pengesahan Harus Diisi !');
			document.location.href='?send=simpel1';
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
	
	//$sumber = "https://dlh.magelangkota.go.id/simpel/get-json.php?s=2";
	$sumber = "https://dlh.magelangkota.go.id/simpel/get-json.php?p=tte";
	
	$sumber .= "&a=$search1&z=$search2";
	//echo $sumber;
	$konten = file_get_contents($sumber, false, stream_context_create($arrContextOptions));//
	$data = json_decode($konten, true);
	
	$searchlink = "$search0;$search1;$search2;$search3";
		
	//echo"<a href='reports/simpel/rekap_penerbitan_excel.php?send=$searchlink' target='_blank'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	echo"&nbsp; <a href='reports/simpel/rekap_lamaproses_pdf.php?send=$searchlink'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
	//echo"&nbsp; <a href='reports/simpel/rekap_penerbitan_graph.php?send=$searchlink'  target='_blank'><img src=\"img/graph1.png\" width='38' title='Lihat Grafik'></img></a>";
?>
	<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>Nomor Permohonan</b></th>
			<th><b>Tanggal Daftar</b></th>	
			<th><b>Tanggal Konfirmasi</b></th>
			<th><b>Nama Pemohon</b></th>
			<th><b>Nama Jenazah</b></th>
			<!--<th><b>Jenis Kelamin</b></th>
			<th><b>Agama</b></th>
			<th><b>Tgl Lahir</b></th>
			<th><b>Alamat</b></th>-->
			<th><b>Jenis Makam</b></th>
			<th><b>Blok</b></th>
			<!--<th><b>Nomor Izin</b></th>-->
			<th><b>Tanggal Pengesahan</b></th>	
			<th><b>Lama Proses</b></th>
		</tr>
		<?php
		// libur nasional		
		$tahun = date('Y',strtotime($search2));
		$z=0;
		$query_libur = mysql_query("SELECT tgl FROM libur_nasional WHERE tgl LIKE '%$tahun%'");
		while ($r_libur= mysql_fetch_array($query_libur)){
			$libur_nasional[$z] = $r_libur['tgl'];
			$z++;
			//echo $r_libur['tgl'].",";
		}
		
		$i = 1;
		$jum_hari_kerja = 0;
		foreach ($data as $key=>$r) {
			
			$hari_kerja= 0;
			$link = "";			
			
			//$alamat = TRIM($r["alamat"])." ".$r["rt"]." ".$r["rw"]." ".$r["desa"]." ".$r["kec"]." ".$r["kota"];
			
			//$tampil = 0;
			//$tanggal1 = date('Y-m-d',strtotime($r['tte']));
			//if(($tanggal1 >= $search1) and ($tanggal1 <= $search2)) $tampil = 1;
			
			//if($tampil){
				
				$tgl_awal = $r['konfirm'];
				$tgl_akhir = $r['tte'];			
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
				
				$tampil2 = 0;
				if($search3 == 1){
					if ($hari_kerja <= 3) $tampil2 = 1;				
				}elseif($search3 == 2){					
					if ($hari_kerja > 3) $tampil2 = 1;	
				}else $tampil2 = 1;	
				
				if($tampil2){
					if($i % 2==0) echo "<tr class='cyan'>";
					else echo "<tr>";
					echo "
					  <td align='center'>$i</td>
					  <td align='left'>$r[token]</td>
					  <td align='center'>$r[daftar]</td>
					  <td align='center'>$r[konfirm]</td>
					  <td align='left'>$r[pemohon]</td>
					  <td align='left'>$r[nama]</td>
					  <td align='left'>$r[jasa]</td>
					  <td align='center'>$r[blok]</td>
					  <td align='center'>$r[tte]</td>
					  <td align='center'>$hari_kerja</td>";
					  					  
					echo "</tr>";
					$jum_hari_kerja += $hari_kerja;
					$i++;
				}
			//}		
		}
		
		$rata_hari_kerja = $jum_hari_kerja/($i-1);
		$rata_hari_kerja = number_format($rata_hari_kerja,2,',','.');
		echo "<tr><td align='center' colspan='9'><b>RATA-RATA</b></td><td align='center'><b>$rata_hari_kerja</b></td></tr>";
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
