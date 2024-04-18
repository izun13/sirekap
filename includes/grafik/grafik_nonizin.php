<style type="text/css">
		
.container {
   display: flex;
   flex-wrap: wrap;
}
 
.box {
   min-height: 150px;
   width: 100%;
}
 
/*untuk layar device berukuran kecil*/
@media screen and (min-width: 900px) {
   .light_blue, .green {
       width: 50%
   }
}
 
/*untuk layar device berukuran sedang*/
@media screen and (min-width: 1100px) {
   .red {
       width: 50%;
   }
 
   .orange {
       width: 50%;
   }
}
 
/*untuk layar device berukuran besar*/
@media screen and (min-width: 1200px) {
   .container {
       width: 100%;
       margin-left: auto;
       margin-right: auto;
   }
}
</style>
<body>
<?php
$to_page = "nonizin";

if($_POST["act"]) $act = $_POST["act"];
$search = explode(";",$search);
$search1 = $search[0]; 
if($_POST["search1"]) $search1 = $_POST["search1"];
$search2 = $search[1]; 
if($_POST["search2"]) $search2 = $_POST["search2"];

if($search1 == "") $search1 = date("m");
if($search2 == "") $search2 = date("Y");

//echo $search2."-".$search1;
?>
<div class="judul">Rekapitulasi Perizinan Non Berusaha</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table>
	<tr><td>Bulan : <select name="search1">
			<?php
				for ($x=1;$x<count($NAMA_BULAN);$x++){
					$selected = "";
					if($x == $search1)$selected = "selected";
					echo"<option value='".$x."' $selected>".$NAMA_BULAN[$x]."</option>";
				}
			?>			
			</select>
		</td>
		<td> Tahun : <input type='text' size='10' name='search2' value='<?php echo $search2;?>' id="inputField2" class='search'> 
		</td>
		<td align="right"><input type='submit' name='act' value='Tampilkan'>
	</td></tr>
</table>
</form>

<?php
if(strlen($search1)==1) $search1 = "0".$search1;

$tabel = "SELECT count(jenis_izin) AS jum,jenis_izin FROM permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND status_penetapan != 'ditolak' AND date(end_date) LIKE '%$search2-$search1%' GROUP BY jenis_izin ORDER BY jum DESC";

/*$arrayFramework = array();
$arrayNilai = array();
$ttl = 0;
$query = mysql_query($tabel);
while ($r= mysql_fetch_array($query)){
    $arrayFramework[] = '"'.$r['jenis_izin'].'"';
    $arrayNilai[] = $r['jum'];
	$ttl += $r['jum'];
}*/
?>

<!--<div class="container">
<div class="box red">-->
<table class="tabelbox3">
		<tr>
			<th><b>No.</b></th>
			<th><b>Jenis Izin</b></th>
			<th><b>Jumlah</b></th>
			<th><b>Rata-rata Lama Proses</b></th>
		</tr>
		<?php
		$z=0;
		$query_libur = mysql_query("SELECT tgl FROM libur_nasional WHERE tgl LIKE '%$search2%'");
		while ($r_libur= mysql_fetch_array($query_libur)){
			$libur_nasional[$z] = $r_libur['tgl'];
			$z++;
		}
		
		// Nampilin Data
		$query = mysql_query($tabel);
		
		$i=1;	
		$k = 0;
		$total = 0;
		$ttl_proses = 0;
		$rata_proses = 0;
		$arrayFramework = array();
		$arrayNilai = array();
		$ttl = 0;
		while ($r= mysql_fetch_array($query)){
			$arrayFramework[] = '"'.$r['jenis_izin'].'"';
			$arrayNilai[] = $r['jum'];
			$ttl += $r['jum'];
			
			$k++;
			
			if($i % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
				
			echo "<td align='center'>$i</td>";
			echo "<td align='left'>$r[jenis_izin]</td>";
			//echo "<td align='center'>$r[jum]</td>";
			
			$tabel2 = "SELECT id,end_date FROM permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND status_penetapan != 'ditolak' AND jenis_izin = '$r[jenis_izin]' AND date(end_date) LIKE '%$search2-$search1%'";
			$query2 = mysql_query($tabel2);
			
			$j=0;
			$rata=0;
			$jml_hr_kerja=0;
			while ($r2= mysql_fetch_array($query2)){
				
				$hari_kerja= 0;
				$tgl_awal = null;
				$tgl_akhir = null;	
								
				$j++;
				$tgl_akhir = $r2['end_date'];
				//tgl cetak tanda terima berkas
				$tglawal = mysql_fetch_array(mysql_query("SELECT end_date FROM proses_permohonan WHERE permohonan_izin_id = '$r2[id]' AND jenis_proses_id = '33' "));
				$tgl_awal = $tglawal['end_date'];
				$tgl_akhir = $r2['end_date'];	
								
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
				
				$jml_hr_kerja += $hari_kerja;
			}
			
			$rata = $jml_hr_kerja/$j;
			$rata = number_format($rata,2);
			$ttl_proses += $rata;
			echo "<td align='center'>$j</td>";
			echo "<td align='center'>$rata</td>";	
			
			echo "</tr>";
			
			$total += $j;
			$i++;
		}
			$rata_proses = $ttl_proses/$k;
			$rata_proses = number_format($rata_proses,2);
			
			echo "<tr><td align='center' colspan='2'><b>Total</b></td><td align='center'><b>$total</b></td><td align='center'><b>$rata_proses</b></td></tr>";
			
			
			
		
		?>
</table>		

<!--</div>
<div class="box orange">-->
<div id="grafik_izin1" style="min-width: 450px; height: 700px; max-width: 1024px; margin: 0 auto"></div>

<script type="text/javascript">
       
        var chart1;
        $(document).ready(function()
        {
            chart1 = new Highcharts.chart({
                chart:{
                    renderTo:'grafik_izin1',
                    type:'bar'
                },
                title:{
                    text:'Grafik Izin Per Jenis Izin'
                },
                xAxis:{
                    categories: [<?= join($arrayFramework, ',') ?>],
					title: {
						  text: null
					 }
                },
                yAxis:{
                    title:{
                        text:'Jumlah Izin : <?php echo $ttl;?>'
                    },
					labels: {
						overflow: 'justify'
					}
                },
				  tooltip: {
					  valueSuffix: ' izin'
				  },
				  plotOptions: {
					  bar: {
						  dataLabels: {
							  enabled: true
						  }
					  }
				  },
				  legend: {
					  layout: 'vertical',
					  align: 'right',
					  verticalAlign: 'top',
					  x: -40,
					  y: 80,
					  floating: true,
					  borderWidth: 1,
					  backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
					  shadow: true
				  },
				  credits: {
					  enabled: false
				  },
                series: [{
						  showInLegend: false,
						  name: 'Jumlah ',
						  data: [<?= join($arrayNilai, ',') ?>],
						  color: '#FFB41A',
					  }]
            });
        });
    </script>
<!--</div>
</div>-->

</body>
