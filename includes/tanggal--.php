<?php
$NAMA_BULAN=array("","Januari", "Pebruari", "Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");

Function pilihan_tanggal($nama_tg,$nama_bl,$nama_th,$th_awal,$th_akhir,$tg_bawaan,$bl_bawaan,$th_bawaan)
{
global $NAMA_BULAN;

if ($tg_bawaan=="")$tg_bawaan=1;
if ($bl_bawaan=="")$bl_bawaan=1;
if ($th_bawaan=="")$th_bawaan=$th_awal;
print("<select name=\"$nama_tg\">\n");
$ada_selected=False;
for($tg=1;$tg<=31;$tg++){
if ($tg_bawaan==$tg)
{
$selected="selected";
$ada_selected=TRUE;
}
else
$selected="";
print("<option value=\"$tg\" $selected>$tg</option>\n");
}

if ($ada_selected==FALSE)
print("<option value=\"0\" selected></option>\n");
print("<select>\n");
print("<select name=\"$nama_bl\">\n");

$ada_selected=False;
for($bl=1;$bl<=12;$bl++){
if ($bl_bawaan==$bl)
{
$selected="selected";
$ada_selected=TRUE;
}
else
$selected="";
print("<option value=\"$bl\" $selected>$NAMA_BULAN[$bl]</option>\n");
}
if ($ada_selected==FALSE)
print("<option value=\"0\" selected></option>\n");
print("</select>\n");
print("<select name=\"$nama_th\">\n");
$ada_selected=False;
for ($th=$th_awal;$th<=$th_akhir;$th++)
{
if($th_bawaan==$th)
{
$selected="selected";
$ada_selected=TRUE;
}
else
$selected="";
print("<option value=\"$th\" $selected>$th</option>\n");
}
if ($ada_selected==FALSE)
print("<option value=\"0\" selected></option>\n");
print("</select>\n");
}
function tgl($tanggal){
$tanggal = explode("/",$tanggal);
$d = $tanggal[0];
$m = $tanggal[1];
$y = $tanggal[2];
//$d = substr($tanggal,0,2);
//$m = substr($tanggal,3,2);
//$y = substr($tanggal,6,4);
return"$y-$m-$d";
}
function tgl1($tanggal){
$tanggal = explode("-",$tanggal);
$y = $tanggal[0];
$m = $tanggal[1];
$d = $tanggal[2];
//$y = substr($tanggal,0,4);
//$m = substr($tanggal,5,2);
//$d = substr($tanggal,8,2);
return"$d/$m/$y";
}
function tgl2($tanggal){
global $NAMA_BULAN;
$y = (integer) substr($tanggal,0,4);
$m = (integer) substr($tanggal,5,2);
$d = (integer) substr($tanggal,8,2);
return"$d $NAMA_BULAN[$m] $y";
}
function tgl3($tg,$bl,$th){
$y = (int) $th;
$m = (int) $bl;
$d = (int) $tg;
return sprintf("%$04d-%02d-%02d",$y,$m,$d);
}
function bulantahun($tanggal){
global $NAMA_BULAN;
$y = (integer) substr($tanggal,0,4);
$m = (integer) substr($tanggal,5,2);
$d = (integer) substr($tanggal,8,2);
return"$NAMA_BULAN[$m] $y";
}
?>
