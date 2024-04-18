<?php
require_once "../../includes/koneksi.php";
require_once "../../includes/tanggal.php";
require_once "../../jpgraph-3.0.7/src/jpgraph.php";
require_once "../../jpgraph-3.0.7/src/jpgraph_line.php";
require_once "../../jpgraph-3.0.7/src/jpgraph_bar.php";
require_once "../../jpgraph-3.0.7/src/jpgraph_pie.php";
require_once "../../jpgraph-3.0.7/src/jpgraph_pie3d.php";

$search1 = $_GET["search1"
$search = explode(";",$_GET["send"]);
$search0 = $search[0]; 
$search1 = $search[1]; 
$search2 = $search[2]; 
$search3 = $search[3]; 
$search4 = $search[4];

$tanggal1 = date('Y-m-d',strtotime($search1));
$tanggal2 = date('Y-m-d',strtotime('2022-04-30'));

$tgl1 = tgl2($search1);
$tgl2 = tgl2($search2);

if($tanggal1 <= $tanggal2){
	$tabel = "SELECT*FROM view_permohonan_izin WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%'";	
	$tabel .= " AND tgl_penetapan >= '$search1' AND tgl_penetapan <= '$search2'";
}else{
	$tabel = "SELECT*FROM view_permohonan_izin_tte WHERE del != '1' AND no_permohonan NOT LIKE '%EXP%' AND no_permohonan NOT LIKE '%TEST%' AND status_penetapan != 'ditolak'";	
	$tabel .= " AND date(end_date) >= '$search1' AND date(end_date) <= '$search2'";
}

if($search3 != "") $tabel .= " and jenis_izin = '$search3'";
if($search4 == 1) $tabel .= " and no_izin != ''";
if($search4 == 2) $tabel .= " and no_izin = ''";

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