<?php 
		$link = explode("/",$_GET['send']);
		$send = $link[0];
		$id = $link[1];
		$starting = $link[2];
		$search = $link[3];
		$act = $link[4];
		//$opd_id = $_SESSION['usr_opd'];
		//echo "OPD : ".$opd_id;
		
		switch($send){
				case('') : require("includes/grafik/grafik_izin.php");break;
				case('home') : require("includes/grafik/grafik_izin.php");break;
				
				case('izinusaha') : require("includes/grafik/grafik_izin.php");break;	
				case('nonizin') : require("includes/grafik/grafik_nonizin.php");break;	
				case('realisasi') : require("includes/grafik/grafik_realisasi.php");break;
								
				case('entrihelpdesk') : require("includes/helpdesk/entri_helpdesk.php");break;	
				//case('inputhelpdesk') : require("includes/helpdesk/input_helpdesk.php");break;	
				//case('hapushelpdesk') : require("includes/helpdesk/hapus_helpdesk.php");break;
				
				case('login') : require("login.php");break;				
		}
?>