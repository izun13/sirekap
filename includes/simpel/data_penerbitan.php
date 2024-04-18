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
$to_page = "simpel1";
	
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
<div class="judul">Rekap Penerbitan Perizinan Pemakaman</div>

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
	
	<tr><td width="">Jenis Permohonan</td><td>:</td>
		<td width=""> <input type='text' size='' name='search3' value='<?php echo $search3;?>'> 
	</td></tr>
	
	<!--<tr><td width="">Nama Pemohon</td><td>:</td>
		<td width=""> <input type='text' size='' name='search4' value='<?php //echo $search4;?>'> 
	</td></tr>-->
	
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
	echo"&nbsp; <a href='reports/simpel/rekap_penerbitan_pdf.php?send=$searchlink'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
	//echo"&nbsp; <a href='reports/simpel/rekap_penerbitan_graph.php?send=$searchlink'  target='_blank'><img src=\"img/graph1.png\" width='38' title='Lihat Grafik'></img></a>";
?>
	<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>Nomor Permohonan</b></th>
			<th><b>Tanggal Daftar</b></th>	
			<th><b>Nama Pemohon</b></th>
			<th><b>Nama Jenazah</b></th>
			<th><b>Jenis Kelamin</b></th>
			<th><b>Agama</b></th>
			<!--<th><b>Tgl Lahir</b></th>-->
			<th><b>Alamat</b></th>
			<th><b>Jenis Permohonan</b></th>
			<th><b>Blok</b></th>
			<!--<th><b>Tgl Pemakaman</b></th>
			<!--<th><b>Nomor Izin</b></th>-->
			<th><b>Tanggal Pengesahan</b></th>	
			<th><b>File Izin</b></th>	
		</tr>
		<?php
		
		$i = 1;
		foreach ($data as $key=>$r) {
							
			//$tgl1 = "";
			//$tgl2 = "";
			//$tgl3 = "";
			//$tgl4 = "";
			$link = "";
			
			//if($r['daftar']) $tgl1 = tgl1($r['daftar']);
			//if($r['lahir']) $tgl2 = tgl1($r['lahir']);
			//if($r['wafat']) $tgl3 = tgl1($r['wafat']);
			//if($r['kubur']) $tgl4 = tgl1($r['kubur']);
			//if($r['tte']) $tgl5 = tgl1($r['tte']);
			$alamat = TRIM($r["alamat"])." ".$r["rt"]." ".$r["rw"]." ".$r["desa"]." ".$r["kec"]." ".$r["kota"];
			
			$tampil = 1;
			if($search3 != ""){
				$tampil = 0;
				if(stristr($r['jasa'],$search3)) $tampil = 1;
			}
			if($tampil){	
				
					if($i % 2==0) echo "<tr class='cyan'>";
					else echo "<tr>";
					echo "
					  <td align='center'>$i</td>
					  <td align='left'>$r[token]</td>
					  <td align='center'>$r[daftar]</td>
					  <td align='left'>$r[pemohon]</td>
					  <td align='left'>$r[nama]</td>
					  <td align='left'>$r[gender]</td>
					  <td align='left'>$r[agama]</td>
					  <td align='left'>$alamat</td>
					  <td align='left'>$r[jasa]</td>
					  <td align='center'>$r[blok]</td>
					  <td align='center'>$r[tte]</td>";
					  
					echo"<td align='center'><a href='$r[ijin]' target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a></td>";
					  
					echo "</tr>";
					$i++;
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
