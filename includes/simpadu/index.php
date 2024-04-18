<?php
require_once"../koneksi.php";
require_once"../tanggal.php";
require_once ("../../jpgraph-3.0.7/src/jpgraph.php");
require_once ("../../jpgraph-3.0.7/src/jpgraph_line.php");
require_once ("../../jpgraph-3.0.7/src/jpgraph_bar.php");
require_once ("../../jpgraph-3.0.7/src/jpgraph_pie.php");
require_once ("../../jpgraph-3.0.7/src/jpgraph_pie3d.php");

$tabel = "SELECT count(jenis_izin) as jml, jenis_izin FROM view_permohonan_izin WHERE no_permohonan NOT LIKE '%EXP%' ";	
//if(($search1 != "") and ($search2 != "")) $tabel .= "and tgl_penetapan >= '$search1' and tgl_penetapan <= '$search2' ";
//if($search3 != "") $tabel .= "and jenis_izin = '$search3' ";
//if($search4 == "Sudah") $tabel .= "and no_izin != '' ";
//if($search4 == "Belum") $tabel .= "and no_izin = '' ";

$tabel .= "GROUP BY jenis_izin ORDER BY id desc";

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

$title = "Permohonan Izin : ".$ttl;
//$data = array(40,60,21,33);
//$jns = array(40,60,21,33);
$graph = new PieGraph(900,600,"auto");
$graph->SetShadow();
$graph->title->Set($title);
$p1 = new PiePlot($data);
$p1->SetLegends($jns);
$p1->SetCenter(0.25);  
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