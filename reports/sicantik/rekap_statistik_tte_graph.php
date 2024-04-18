<?php
include"../../includes/parser-php-version.php";
require_once "../../jpgraph-4.4.1/src/jpgraph.php";
require_once "../../jpgraph-4.4.1/src/jpgraph_line.php";
require_once "../../jpgraph-4.4.1/src/jpgraph_bar.php";
require_once "../../jpgraph-4.4.1/src/jpgraph_pie.php";
require_once "../../jpgraph-4.4.1/src/jpgraph_pie3d.php";
require_once "../../includes/tanggal.php";

// Koneksi ke database dan tampilkan datanya
require_once "../../includes/konfigurasi_db.php";
koneksi1_buka();

$search = explode(";",$_GET["send"]);
$search0 = $search[0]; 
$search1 = $search[1]; 
$search2 = $search[2]; 
$search3 = $search[3];

$tgl1 = tgl2($search1);
$tgl2 = tgl2($search2);

$tabel = "SELECT count(jenis_izin) as jml, jenis_izin FROM view_permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
if(($search1 != "") and ($search2 != "")) $tabel .= " AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";

$tabel .= "GROUP BY jenis_izin ORDER BY id desc";

$i = 0;
$ttl = 0;
$query = mysql_query($tabel);
while ($r= mysql_fetch_array($query)){
	$ttl += $r["jml"];
	$jns[$i] = $r["jenis_izin"];//." : ".$r["jml"]
	$data[$i] = $r["jml"];
	$i++;
	
	//echo $r["jenis_izin"]." = ".$r["jml"]."<br>";
}

/*$title = "Grafik Penerbitan Izin";
$graph = new PieGraph(1300,800,"auto"); 
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
$graph->Stroke();*/
 
//$graph = new Graph(300,200,"auto"); 
$graph = new Graph(1200,900,"auto");   
$graph->SetScale("textint");
 
// menampilkan plot batang dari data jumlah penduduk
$bplot = new BarPlot($data);
$graph->Add($bplot);
$bplot->value->show();
$bplot->value->SetFont(FF_FONT1);
//$bplot->value->SetAngle(45);
$bplot->value->SetColor("black","navy");
$bplot->SetFillColor("red");
$bplot->SetFillGradient("blue","yellow",GRAD_MIDVER);
 
// menampilkan plot garis dari data jumlah penduduk
//$lineplot=new LinePlot($data);
//$graph->Add($lineplot);
//$lineplot->SetColor("blue"); 
 
//$graph->img->SetMargin(40,20,20,40);
$graph->title->Set("Grafik Penerbitan Izin Tanggal ".$tgl1." s/d ".$tgl2);
$graph->title->SetFont(FF_FONT1,FS_BOLD); 
$graph->xaxis->title->Set("Jenis Izin");
//$graph->yaxis->title->Set("Jumlah");
$graph->xaxis->SetTickLabels($jns); 
$graph->SetShadow();
$graph->Set90AndMargin(350,40,50,20);
$graph->Stroke();
?>