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
$to_page = "datarealisasi";

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
<div class="judul">Realisasi Investasi</div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table>
	<tr><td>Bulan : <select name="search1">
			<?php
				echo"<option value='all' selected>Pilih Semua</option>";
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
if($search1 == "all") $search1 = "";
?>

<div class="container">
<div class="box red">
<div class="judul1">Jumlah Investor Per Status Perusahaan
<?php
echo"&nbsp; <a href='reports/oss_rba/proyek_excel.php?send=$search2-$search1'  target='_blank'><img src=\"img/excel.png\" width='38' title='cetak Rincian'></img></a>";
?>
</div>
<table class="tabelbox3">
		<tr>
			<th><b>No.</b></th>
			<th><b>Status Perusahaan</b></th>
			<th><b>Jumlah Perusahaan</b></th>
			<th><b>Nilai Investasi</b></th>
		</tr>
		<?php
		$tabel = "SELECT COUNT(DISTINCT(nib)) AS jum,SUM(tambah_investasi) AS nilai,status_perusahaan FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND tambah_investasi != '0' AND 
					tgl_verifikasi LIKE '%$search2-$search1%' GROUP BY status_perusahaan";
		
		//$arrayFramework = array();
		//$arrayNilai = array();
		$ttl = 0;
		$ttl_nilai = 0;
		$query = mysql_query($tabel);
		$i = 1;
		while ($r= mysql_fetch_array($query)){
			//$arrayFramework[] = '"'.$r['uraian_status_penanaman_modal'].'"';
			//$arrayNilai[] = $r['jum'];
			$ttl += $r['jum'];
			$ttl_nilai += $r['nilai'];
			
			if($i % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			$nilai = rupiah($r['nilai']);
			echo "<td align='center'>$i</td>";
			echo "<td align='left'>$r[status_perusahaan]</td>";
			echo "<td align='right'>$r[jum]</td>";
			echo "<td align='right'>$nilai</td>";
			echo "</tr>";
			
			$i++;
		}
			
			$ttl_nilai = rupiah($ttl_nilai);
			echo "<tr><td align='center' colspan='2'><b>Total</b></td><td align='right'><b>$ttl</b></td><td align='right'><b>$ttl_nilai</b></td></tr>";
			
		?>
		
</table>
	
<div id="grafik_pie_3" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto" align="left"></div>

<script type="text/javascript">

        Highcharts.chart('grafik_pie_3', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Grafik Investor Per Status Perusahaan'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y} ({point.percentage:.1f}%)</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: ({point.percentage:.1f}%)',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'Nilai',
                colorByPoint: true,
                data: [
				 <?php
					$query = mysql_query($tabel);
					while ($r= mysql_fetch_array($query)){
						
                 ?>
					{
						name: '<?php echo $r["status_perusahaan"]; ?>',
						y: <?php echo $r["jum"]; ?>,
						sliced: true,
						selected: true
					}, 
				<?php } ?>
				
				]
            }]
        });
    </script>
</div>
<div class="box orange">
<div class="judul1">Realisasi Investasi Per Sektor Usaha
<?php
echo"&nbsp; <a href='reports/oss_rba/proyek_sektor_excel.php?send=$search2-$search1'  target='_blank'><img src=\"img/excel.png\" width='38' title='cetak Rincian'></img></a>";
echo"&nbsp; <a href='reports/oss_rba/realisasi_investasi_json.php?send=$search2-$search1'  target='_blank'><img src=\"img/json.png\" width='38' title='cetak Json'></img></a>";
?>
</div>
<table class="tabelbox3">
		<tr>
			<th><b>No.</b></th>
			<th><b>Sektor</b></th>
			<th><b>Nilai Investasi</b></th>
		</tr>
		<?php
		$tabel = "SELECT SUM(tambah_investasi) AS nilai,kelompok_sektor FROM view_proyek WHERE verifikasi = 'Terverifikasi' AND tambah_investasi != '0' AND 
					tgl_verifikasi LIKE '%$search2-$search1%' GROUP BY kelompok_sektor_id ORDER BY nilai DESC";
		
		$arrayFramework = array();
		$arrayNilai = array();
		$ttl = 0;
		$query = mysql_query($tabel);
		$i = 1;
		while ($r= mysql_fetch_array($query)){
			$arrayFramework[] = '"'.$r['kelompok_sektor'].'"';
			$arrayNilai[] = $r['nilai'];
			$ttl += $r['nilai'];
			
			if($i % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			$nilai = rupiah($r['nilai']);
			echo "<td align='center'>$i</td>";
			echo "<td align='left'>$r[kelompok_sektor]</td>";
			echo "<td align='right'>$nilai</td>";
			echo "</tr>";
			
			$i++;
		}
			
			$total = rupiah($ttl);
			echo "<tr><td align='center' colspan='2'><b>Total</b></td><td align='right'><b>$total</b></td></tr>";
			
		?>
		
</table>

<div id="grafik_izin1" style="min-width: 450px; height: 600px; max-width: 800px; margin: 0 auto" align="left"></div>
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
                    text:'Grafik Investasi Per Sektor Usaha'
                },
                xAxis:{
                    categories: [<?= join($arrayFramework, ',') ?>],
					title: {
						  text: null
					 }
                },
                yAxis:{
                    title:{
                        text:'Nilai Investasi'
                    },
					labels: {
						overflow: 'justify'
					}
                },
				  tooltip: {
					  valueSuffix: ' rupiah'
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
						  name: 'Nilai ',
						  data: [<?= join($arrayNilai, ',') ?>],
						  color: '#FFB41A',
					  }]
            });
        });
    </script>
</div>
</div>
</body>
