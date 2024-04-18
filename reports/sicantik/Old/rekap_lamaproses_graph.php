<?php
require_once "../../includes/koneksi.php";
require_once "../../includes/tanggal.php";
require_once "../../jpgraph-3.0.7/src/jpgraph.php";
require_once "../../jpgraph-3.0.7/src/jpgraph_line.php";
require_once "../../jpgraph-3.0.7/src/jpgraph_bar.php";
require_once "../../jpgraph-3.0.7/src/jpgraph_pie.php";
require_once "../../jpgraph-3.0.7/src/jpgraph_pie3d.php";

$search1 = $_GET["search1"];
$search2 = $_GET["search2"];
$search3 = $_GET["search3"];
$search4 = $_GET["search4"];
$tgl1 = tgl2($search1);
$tgl2 = tgl2($search2);

$tabel = "SELECT count(jenis_izin) as jml, jenis_izin FROM view_permohonan_izin WHERE del != '1'";	
if(($search1 != "") and ($search2 != "")) $tabel .= " and tgl_penetapan >= '$search1' and tgl_penetapan <= '$search2'";
if($search3 != "") $tabel .= " and jenis_izin = '$search3'";
if($search4 == 1) $tabel .= " and no_izin != ''";
if($search4 == 2) $tabel .= " and no_izin = ''";

$tabel .= " GROUP BY jenis_izin ORDER BY id desc";

$i = 0;
$ttl = 0;
$query = mysql_query($tabel);
while ($r= mysql_fetch_array($query)){
	$ttl += $r["jml"];
	$jns[$i] = $r["jenis_izin"]." : ".$r["jml"];
	$data[$i] = $r["jml"];
	$i++;
	
	//echo $r["jenis_izin"]." = ".$r["jml"]."<br>";
}

$title = "Grafik Permohonan Izin";
$graph = new PieGraph(1000,600,"auto"); 
$graph->SetScale('textint'); 
//$graph->img->SetMargin(50,30,50,50); 
$graph->SetShadow();
$graph->title->Set($title);
//$graph->title->SetPos("center");
$graph->subtitle->Set("Tanggal : ".$tgl1." s/d ".$tgl2.", Jumlah : ".$ttl);
//$graph->subtitle->SetPos("center");
$p1 = new PiePlot3D($data);
$p1->SetCenter(0.30); 
$p1->SetLegends($jns);
$graph->Add($p1);
$graph->Stroke();

/*$graph = new PieGraph(350,250,"auto"); 
$graph->SetScale('textint'); 
$graph->img->SetMargin(50,30,50,50); 
$graph->SetShadow(); 
$graph->title->Set("Grafik Pie Chart 3 Dimensi"); 
$bplot = new PiePlot3D($jml); 
$bplot->SetCenter(0.45); 
$bplot->SetLegends($jns); 
$graph->Add($bplot); 
$graph->Stroke();*/
?>