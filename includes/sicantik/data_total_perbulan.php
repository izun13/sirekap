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
$to_page = "sicantik5";

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

?>
<div class="judul">Total Permohonan Sicantik Per Bulan</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table>
	<tr><td width="200">Tahun </td><td>:</td>
		<td width=""> <input type='text' size='10' name='search1' value='<?php echo $search1;?>' id="" class='search'> 
		<!--s/d  <input type='text' size='10' name='search2' value='<?php //echo $search2;?>' id="inputField2" class='search'> -->
	</td></tr>
	
	<!--<tr><td>Diterbitkan</td><td>:</td>
		<td> <input type='radio' name='search3' value='1' <?php //if($search3 == 1) echo "checked";?>> Sudah 
				<input type='radio' name='search3' value='2' <?php //if($search3 == 2) echo "checked";?>> Belum 
				<input type='radio' name='search3' value='0' <?php //if($search3 == 0) echo "checked";?>> Semua
	</td></tr>-->
	<tr><td width="">Jenis Izin</td><td>:</td>
		<td width=""> <input type='text' size='' name='search3' value='<?php echo $search3;?>'> 
	</td></tr>
	<tr><td>Jenis Permohonan</td><td>:</td>
		<td> <input type='radio' name='search4' value='1' <?php if($search4 == 1) echo "checked";?>> Baru 
				<input type='radio' name='search4' value='2' <?php if($search4 == 2) echo "checked";?>> Perpanjangan 
				<input type='radio' name='search4' value='' <?php if($search4 == "") echo "checked";?>> Semua
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
	
	if(($search1 == "")){// or ($search2 == "")
		?>
			<script language="JavaScript">alert('Tahun Harus Diisi !');
			document.location.href='?send=sicantik3';
			</script>
		<?php	
	}
	
	echo"&nbsp; <a href='reports/sicantik/rekap_total_perbulan_json.php?send=$search1'  target='_blank'><img src=\"img/json.png\" width='38' title='Cetak Json'></img></a>";
	
	?>
	<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>Bulan</b></th>
			<th><b>Jumlah</b></th>
		</tr>
		<?php
		//$tanggal1 = date('Y-m-d',strtotime($search1));
		//$tanggal2 = date('Y-m-d',strtotime($search2));
		//$tanggal3 = date('Y-m-d',strtotime('2022-05-01'));//Mei 20222
		
		/*$x=0;
		$newblnthn = "";
		while ($tanggal1 <= $tanggal2) {
			$blnthn = substr($tanggal1,0,7);
			if($blnthn != $newblnthn){
				$bulan[$x] = $blnthn;
				$tte[$x] = 0;
				if($tanggal1 >= $tanggal3) $tte[$x] = 1;
				$x++;
			}
			$newblnthn = $blnthn;
			$tanggal1 = date('Y-m-d',strtotime('+1 days',strtotime($tanggal1)));
		}*/
		
		
		
		$total = 0;
		$jum = 0;
		$j = 1;
		//for($x=0;$x<count($bulan);$x++){
		for($x=0;$x<12;$x++){
			if($j % 2==0){
					echo "<tr class='cyan'>";
				}else{
					echo "<tr>";
				}
			//echo $bulan[$j]."<br>";
			
			//$query ="SELECT*FROM view_permohonan_izin WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND tgl_penetapan LIKE '%$bulan[$x]%'";
			//if($tte[$x] == 1) $query = "SELECT*FROM permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND end_date LIKE '%$bulan[$x]%'";
			
			$bulan = $search1."-".$j;
			if(strlen($j)==1)$bulan = $search1."-0".$j;
			$query = "SELECT*FROM permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND end_date LIKE '%$bulan%'";
			
			if($search3)$query .= " AND jenis_izin LIKE '%$search3%'";
			if($search4==1)$query .= " AND jenis_permohonan LIKE '%Baru%'";
			if($search4==2)$query .= " AND jenis_permohonan LIKE '%Perpanjangan%'";
			
			$jum = mysql_num_rows(mysql_query($query));
			$jumlah[$x] = $jum ;
			//$thn = substr($bulan[$x],0,4);
			//$bln = substr($bulan[$x],5,2);
			//$bln = (int)$bln;
			//$nmbulan[$x] = $NAMA_BULAN[$bln];
			$nmbulan[$x] = $NAMA_BULAN[$j];
			//echo $bln."<br>";
			$total += $jum;
			echo "<td align='center'>$j</td>";
			echo "<td align='left'>$NAMA_BULAN[$j] $search1</td>";
			echo "<td align='center'>$jum</td>";
			echo "</tr>";
			$j++;
		}
	
		echo "<tr><td align='center' colspan='2'><b>Total</b></td><td align='center'><b>$total</b></td></tr>";
		
		/*$tabel = "SELECT*FROM view_permohonan_izin WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";
		if(($search1 != "") and ($search2 != "")) $tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
		
		if($search3 == 1) $tabel .= " AND no_izin != ''";
		if($search3 == 2) $tabel .= " AND no_izin = ''";
		$tabel .= " ORDER BY tgl_penetapan ASC";
		// Nampilin Data
		$query = mysql_query($tabel);
		
		$i=0;	
		$newblnthn = "";
		while ($r= mysql_fetch_array($query)){
			$blnthn = substr($r['tgl_penetapan'],0,7);
			//echo $blnthn."<br>";
			
			if($blnthn == $newblnthn){
				$k++;
				$jum[$i] = $k;
			}else{
				$i++;
				$k = 1;
				$bulan[$i] = $blnthn;
			}
			
			$newblnthn = $blnthn;
			
		}
		
		$total = 0;
		for($j=1;$j<=count($bulan);$j++){
			if($j % 2==0){
					echo "<tr class='cyan'>";
				}else{
					echo "<tr>";
				}
			//echo $bulan[$j]."<br>";
			$thn = substr($bulan[$j],0,4);
			$bln = substr($bulan[$j],5,2);
			$bln = (int)$bln;
			//echo $bln."<br>";
			$total += $jum[$j];
			echo "<td align='center'>$j</td>";
			echo "<td align='left'>$NAMA_BULAN[$bln] $thn</td>";
			echo "<td align='center'>$jum[$j]</td>";
		}
		
		echo "<tr><td align='center' colspan='2'><b>Total</b></td><td align='center'><b>$total</b></td></tr>";*/
		?>
		
</table>

<?php
}
?>

<script type="text/javascript">
       
        var chart1;
        $(document).ready(function()
        {
            chart1 = new Highcharts.chart({
                chart:{
                    renderTo:'grafik',
                    type:'column'
                },
                title:{
                    text:'Grafik Penerbitan Izin'
                },
                xAxis:{
                    categories:['Bulan']
                },
                yAxis:{
                    title:{
                        text:'Jumlah Izin'
                    }
                },
                series:
                [
                    <?php
                        for($x=0;$x<12;$x++){
                                ?>
                                {
                                    name:'<?php echo $nmbulan[$x]; ?>',
                                    data:[<?php echo $jumlah[$x]; ?>]
                                },
                    <?php } ?>
                ]
            });
        });
 
    </script>
	<div id="grafik"></div>
</body>
