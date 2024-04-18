<!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">-->
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
$to_page = "izinusaha";

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
<div class="judul">Rekapitulasi Perizinan Berusaha</div>

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
<div class="judul1">Nomor Induk Berusaha (NIB) Per Status PM</div>
<table class="tabelbox3">
	<tr>
		<th>No.</th>
		<th>Status PM</th>
		<th>Jumlah</th>
	</tr>
<?php
$tabel = "SELECT count(nib) AS jum,status_penanaman_modal FROM oss_rba_nibs WHERE day_of_tanggal_terbit_oss LIKE '%$search2-$search1%' GROUP BY status_penanaman_modal";
$query = mysql_query($tabel);
$ttl = 0;
$i = 1;
while ($r= mysql_fetch_array($query)){
    if($i % 2==0){
		echo "<tr class='cyan'>";
	}else{
		echo "<tr>";
	}
	echo "<td align='center'>$i</td>";
	echo "<td>$r[status_penanaman_modal]</td>";
	echo "<td align='center'>$r[jum]</td>";
	echo "</tr>";
	$ttl += $r['jum'];
	$i++;
}
echo "<tr><td colspan='2'><b>Total</b></td><td align='center'><b>$ttl</b></td></tr>";
?>
</table>

<div id="grafik_pie_1" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto" align="left"></div>
<script type="text/javascript">

        Highcharts.chart('grafik_pie_1', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Grafik NIB Per Status PM'
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
                        format: '<b>{point.name}</b>: {point.y} ({point.percentage:.1f}%)',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'Jumlah',
                colorByPoint: true,
                data: [
				 <?php	
					$query = mysql_query($tabel);
					while ($r= mysql_fetch_array($query)){
                 ?>
					{
						name: '<?php echo $r["status_penanaman_modal"]; ?>',
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
<div class="judul1">Nomor Induk Berusaha (NIB) Jenis Perusahaan</div>
<table class="tabelbox3">
	<tr>
		<th>No.</th>
		<th>Jenis Perusahaan</th>
		<th>Jumlah</th>
	</tr>
<?php
$tabel = "SELECT count(nib) AS jum,uraian_jenis_perusahaan FROM oss_rba_nibs WHERE day_of_tanggal_terbit_oss LIKE '%$search2-$search1%' GROUP BY uraian_jenis_perusahaan";
$query = mysql_query($tabel);
$ttl = 0;
$i = 1;
while ($r= mysql_fetch_array($query)){
    if($i % 2==0){
		echo "<tr class='cyan'>";
	}else{
		echo "<tr>";
	}
	echo "<td align='center'>$i</td>";
	echo "<td>$r[uraian_jenis_perusahaan]</td>";
	echo "<td align='center'>$r[jum]</td>";
	echo "</tr>";
	$ttl += $r['jum'];
	$i++;
}

echo "<tr><td colspan='2'><b>Total</b></td><td align='center'><b>$ttl</b></td></tr>";
?>
</table>

<!-- Membuat area untuk menampilkan grafik -->
<div id="grafik_pie_2" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto" align="left"></div>

<script type="text/javascript">

        Highcharts.chart('grafik_pie_2', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Grafik NIB Per Jenis Perusahaan'
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
                        format: '<b>{point.name}</b>: {point.y} ({point.percentage:.1f}%)',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'Jumlah',
                colorByPoint: true,
                data: [
				 <?php
					$query = mysql_query($tabel);
					while ($r= mysql_fetch_array($query)){
                 ?>
					{
						name: '<?php echo $r["uraian_jenis_perusahaan"]; ?>',
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
</div>

<p>&nbsp;</p>
<div class="container">
<div class="box red">
<div class="judul1">Perizinan Berusaha Per Jenis Resiko</div>
<table class="tabelbox3">
	<tr>
		<th>No.</th>
		<th>Jenis Resiko</th>
		<th>Jumlah</th>
	</tr>
<?php
Function title($name){
	$title = "";
	if($name == "R")$title = "Rendah";
	if($name == "MR")$title = "Menengah Rendah";
	if($name == "MT")$title = "Menengah Tinggi";
	if($name == "T")$title = "Tinggi";
	
	return $title;
}
$tabel = "SELECT count(id_permohonan_izin) AS jum,kd_resiko FROM oss_rba_izins WHERE day_of_tgl_izin LIKE '%$search2-$search1%' GROUP BY kd_resiko";
$query = mysql_query($tabel);
$ttl = 0;	
$i = 1;
while ($r= mysql_fetch_array($query)){
	if($r['kd_resiko']){
		$resiko = title($r['kd_resiko']);
		if($i % 2==0){
			echo "<tr class='cyan'>";
		}else{
			echo "<tr>";
		}
		echo "<td align='center'>$i</td>";
		echo "<td>$resiko</td>";
		echo "<td align='center'>$r[jum]</td>";
		echo "</tr>";
		$ttl += $r['jum'];
		$i++;
	}
}

echo "<tr><td colspan='2'><b>Total</b></td><td align='center'><b>$ttl</b></td></tr>";
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
                    type:'column'
                },
                title:{
                    text:'Grafik Perizinan Per Jenis Resiko'
                },
                xAxis:{
                    categories:['Jenis Resiko']
                },
                yAxis:{
                    title:{
                        text:'Jumlah Izin'
                    }
                },
                series:
                [
                    <?php	
						$query = mysql_query($tabel);
						while ($r= mysql_fetch_array($query)){
                    ?>
                        {
                            name:'<?php echo $r["kd_resiko"]; ?>',
                            data:[<?php echo $r["jum"]; ?>]
                        },
                    <?php } ?>
                ]
            });
        });
 
    </script>


</div>
<div class="box orange">
<div class="judul1">Perizinan Berusaha Per Jenis Izin</div>
<table class="tabelbox3">
	<tr>
		<th>No.</th>
		<th>Jenis Perizinan</th>
		<th>Jumlah</th>
	</tr>
<?php
$tabel = "SELECT count(id_permohonan_izin) AS jum,uraian_jenis_perizinan FROM oss_rba_izins WHERE day_of_tgl_izin LIKE '%$search2-$search1%' GROUP BY uraian_jenis_perizinan";
$query = mysql_query($tabel);
$ttl = 0;	
$i = 1;
while ($r= mysql_fetch_array($query)){
	if($r['uraian_jenis_perizinan']){
		if($i % 2==0){
			echo "<tr class='cyan'>";
		}else{
			echo "<tr>";
		}
		echo "<td align='center'>$i</td>";
		echo "<td>$r[uraian_jenis_perizinan]</td>";
		echo "<td align='center'>$r[jum]</td>";
		echo "</tr>";
		$ttl += $r['jum'];
		$i++;
	}
}

echo "<tr><td colspan='2'><b>Total</b></td><td align='center'><b>$ttl</b></td></tr>";
?>
</table>
<div id="grafik_izin2" style="min-width: 450px; height: 600px; max-width: 800px; margin: 0 auto" align="left"></div>
<script type="text/javascript">
       
        var chart1;
        $(document).ready(function()
        {
            chart1 = new Highcharts.chart({
                chart:{
                    renderTo:'grafik_izin2',
                    type:'column'
                },
                title:{
                    text:'Grafik Perizinan Per Jenis Izin'
                },
                xAxis:{
                    categories:['Jenis Resiko']
                },
                yAxis:{
                    title:{
                        text:'Jumlah Izin'
                    }
                },
                series:
                [
                    <?php	
						$query = mysql_query($tabel);
						while ($r= mysql_fetch_array($query)){
                    ?>
                        {
                            name:'<?php echo $r["uraian_jenis_perizinan"]; ?>',
                            data:[<?php echo $r["jum"]; ?>]
                        },
                    <?php } ?>
                ]
            });
        });
 
    </script>

</div>
</div>


</body>
