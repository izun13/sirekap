<?php 
error_reporting(1);
session_start();
require_once "includes/konversi.php";
require_once "includes/tanggal.php";
require_once "includes/page.php";
require_once "includes/rupiah.php";

require_once "includes/konfigurasi_db.php";
koneksi1_buka();
?>
<html>
<head>
	<link rel="icon" type="image/png" href="images/logo.png">
	<meta charset='utf-8'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/styless.css">
	<script src="js/jquery-latest.min.js" type="text/javascript"></script>
	<script src="js/script.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="css/jsDatePick_ltr.min.css" />
	<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
	<link href="css/selectize.min.css" rel="stylesheet"/>
	<script src="js/jquery-3.4.1.js"></script>
	<script src="js/selectize.min.js"></script>
   <title>	REKAP PERIZINAN DPMPTSP</title>
</head>
<body>

<div id='welcome' align='right'><?php //if ($_SESSION['usr_name']) echo "Hello, ".$_SESSION['usr_name']." !";?></div>
<div id='toplogo'><img src='images/logo.png' width='60'></div>
<div id='topContainer'>
<div class='header'>REKAP PERIZINAN DPMPTSP KOTA MAGELANG</div>
</div>
<div id='mainContainer'>
<?php 
if (!isset($_SESSION['usr_id'])){
include "login.php";
}else{
?>
<table width='100%'>
<tr><td width='' valign='top'>
<?php include "menu.php"; 
 //include "jam.php";?>
</td></tr>
<tr><td width='' valign='top'>
<?php include "content.php"; ?>
</td></tr>
</table>
<?php
}
koneksi1_tutup();
?>
<div class='bottomContainer'>DPMPTSP Kota Magelang - 2021</div>
</div>
</body>
<html>
