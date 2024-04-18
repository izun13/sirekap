<?php 
include "includes/parser-php-version.php";
error_reporting(E_ALL);
session_start();
require_once "includes/cipher.php";
require_once "includes/tanggal.php";
require_once "includes/page.php";
require_once "includes/rupiah.php";

require_once "includes/konfigurasi_db.php";
koneksi1_buka();
?>
<html>
<head>
	<link rel="icon" type="image/png" href="images/logo.png">
	<!--<meta charset='utf-8'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/styles-4.css">-->
	
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap-2.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
     <!-- MORRIS CHART STYLES-->
    <link href="assets/js/morris/morris-0.4.3.min.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   
	<link rel="stylesheet" href="css/styles-7.css">
	<script type="text/javascript" src="js/jquery-latest.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/script.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="css/jsDatePick_ltr.min.css" />
	<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
	<link href="css/selectize.min-3.css" rel="stylesheet"/>
	<script type="text/javascript" src="js/jquery-3.4.1.js"></script>
	<script type="text/javascript" src="js/selectize.min.js"></script>
	<script type="text/javascript" src="js/nominal.js"></script>
    <script type="text/javascript" src="js/highcharts.js"></script>
   <title>REKAP PERIZINAN DAN INVESTASI</title>
</head>
<body>
<div id="wrapper">
         <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">
                        <img src='images/logo.png' width='30'>SIREKAP
                    </a>
            </div>
			<div class="navbar-header2">
				<?php if($_SESSION['usr_name'])echo "<a href='?send=profile/".$_SESSION['usr_id']."'><img src='img/admin.png' width='40'> ".$_SESSION['usr_name']."</a>";?>
            </div>
        </nav>

	<nav class="navbar-default navbar-side" role="navigation">	
	<div class="sidebar-collapse">
		<ul class="nav" id="main-menu">
		<?php if (isset($_SESSION['usr_id']))include "menu.php";
				else include "menu-awal.php";
		?>	
		</ul>
	</div>
	</nav>
	
	<div id="page-wrapper" >
		<div id="page-inner">
			<!--<div class="row">
				<div class="col-lg-12">
				&nbsp;<h2>ADMIN DASHBOARD</h2> 
				</div>
			</div>--> 
			<div class="row">
                <div class="col-md-12">
                    <!-- Form Elements -->
                    <!--<div class="panel panel-default">-->
                        <!-- <div class="panel-heading">
                            Form Element Examples
                        </div>-->
                        <div class="panel-body">
                            <div class="row">
							 <?php 
								if (isset($_SESSION['usr_id'])) include "content.php";
								else include "content-awal.php";
								
								
								koneksi1_tutup();
							?>
							</div>
						<!--</div>-->
					<!--</div>-->
				</div> 
			</div> 
		</div> 	
	</div> 		
</div>

     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- METISMENU SCRIPTS -->
    <script src="assets/js/jquery.metisMenu.js"></script>
     <!-- MORRIS CHART SCRIPTS -->
     <script src="assets/js/morris/raphael-2.1.0.min.js"></script>
    <script src="assets/js/morris/morris.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>

</body>
<html>
