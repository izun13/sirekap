<?php

   /*$active = '';
   if(strstr($_GET['send'],"home")) $active = 'active-menu';
   echo"<li class=$active><a href='?send=home'><span>Home</span></a></li>";*/
  
if ($_SESSION['usr_id']){
	$akses=mysql_fetch_array(mysql_query("select*from petugas where id='".$_SESSION['usr_id']."' "));
   //echo $akses['copy'];
//if ($akses['copy']){         
   $active = '';
   if(strstr($_GET['send'],"helpdesk")) $active = 'active-menu';
   echo"<li class=$active><a href='?send=datahelpdesk'><span>Helpdesk IT</span></a></li>";
//}
//if ($akses['sektor']){
   //$active = '';
   //if(strstr($_GET['send'],"sektor")) $active = 'active-menu';
   //echo"<li class=$active><a href='?send=datasektor'><span>Sektor Investasi</span></a></li>";
//}
if ($akses['pokok']){  
   $active = '';
   if(strstr($_GET['send'],"opd")) $active = 'active-menu'; 
   if(strstr($_GET['send'],"petugas")) $active = 'active-menu'; 
   if(strstr($_GET['send'],"jnsizin")) $active = 'active-menu'; 
   if(strstr($_GET['send'],"jns-izinsimp")) $active = 'active-menu';
   if(strstr($_GET['send'],"sektor")) $active = 'active-menu'; 
   if(strstr($_GET['send'],"libur")) $active = 'active-menu';
   if(strstr($_GET['send'],"tabel")) $active = 'active-menu';  
   if(strstr($_GET['send'],"tbl")) $active = 'active-menu';  
   echo"<li class=$active><a href='?send='><span>Data Pokok</span></a>
      <ul class='nav nav-second-level'>";
		$aktiv = '';
		if(strstr($_GET['send'],"opd")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=dataopd'><span>Data OPD</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"petugas")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=datapetugas'><span>Data Petugas</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"jnsizin")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=datajnsizin'><span>Jenis Izin</span></a></li>";
		//$aktiv = '';
		//if(strstr($_GET['send'],"jns-izinsimp")) $aktiv = 'active-menu'; 
        //echo"<li class=$aktiv><a href='?send=jns-izinsimp'><span>Jenis Izin Simpadu</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"kbli")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=datakbli'><span>Data KBLI</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"sektor")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=datasektor'><span>Sektor Investasi</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"libur")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=datalibur'><span>Libur Nasional</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"tabel")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=datatabel'><span>Tabel Database</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"tbl")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=lihattbl'><span>Lihat Tabel</span></a></li>";
		
   echo"</ul>
   </li>";
}


$active = '';
if(strstr($_GET['send'],"oss")) $active = 'active-menu'; 
if(strstr($_GET['send'],"rba")) $active = 'active-menu'; 
if(strstr($_GET['send'],"rba")) $active = 'active-menu'; 
echo"<li class='$active'><a href='?send='><span>Perizinan Berusaha</span></a>
<ul class='nav nav-second-level'>";
	  
if ($akses['nswi']){
   $active = '';
   if(strstr($_GET['send'],"oss")) $active = 'active-menu'; 
   echo"<li class='$active'><a href='?send='><span>OSS NSWI</span></a>
      <ul class='nav nav-third-level'>";
		$aktiv = '';
		if(strstr($_GET['send'],"ossiu07")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=dataossiu07'><span>IU 2007/2018</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"ossnib")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=dataossnib'><span>OSS NIB</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"ossiumk")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=dataossiumk'><span>OSS IUMK</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"ossnoniumk")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=dataossnoniumk'><span>OSS NON IUMK</span></a></li>";
   echo"</ul>
   </li>";
}

if ($akses['oss_rba']){  
   $active = '';
   if(strstr($_GET['send'],"rba")) $active = 'active-menu'; 
   if(strstr($_GET['send'],"realisasi")) $active = 'active-menu';
   if(strstr($_GET['send'],"investasi")) $active = 'active-menu'; 
   echo"<li class='$active'><a href='?send='><span>OSS RBA</span></a>
      <ul class='nav nav-third-level'>";
		$aktiv = '';
		if(strstr($_GET['send'],"rbanib")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=datarbanib'><span>OSS RBA NIB</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"rbaizin")) $aktiv = 'active-menu'; 
        echo"<li class=$aktiv><a href='?send=datarbaizin'><span>OSS RBA Izin</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"grafik_rba")) $aktiv = 'active-menu';
		echo"<li class=$aktiv><a href='?send=grafik_rba'><span>Grafik Perizinan Berusaha</span></a></li>";		
   echo"</ul>
   </li>";
}
   
echo"</ul>
</li>";


$active = '';
if(strstr($_GET['send'],"simpadu")) $active = 'active-menu'; 
if(strstr($_GET['send'],"sicantik")) $active = 'active-menu';
if(strstr($_GET['send'],"simpel")) $active = 'active-menu';
if(strstr($_GET['send'],"pbg")) $active = 'active-menu'; 
echo"<li class='$active'><a href='?send='><span>Perizinan Non Berusaha</span></a>
<ul class='nav nav-second-level'>";

	if ($akses['simpadu']){  
	   $active = '';
	   if(strstr($_GET['send'],"simpadu")) $active = 'active-menu'; 
	   echo"<li class='$active'><a href='?send='><span>Rekap Simpadu</span></a>
		  <ul class='nav nav-third-level'>";
			$aktiv = '';
			if(strstr($_GET['send'],"simpadu0")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=simpadu0'><span>Rekap Pengajuan izin</span></a></li>";
			$aktiv = '';
			if(strstr($_GET['send'],"simpadu1")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=simpadu1'><span>Rekap Penerbitan izin</span></a></li>";
			
		if (($_SESSION['usr_opd'] == 1)or($_SESSION['usr_opd'] == 0)){ 
			$aktiv = '';
			if(strstr($_GET['send'],"simpadu2")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=simpadu2'><span>Rekap Lama Proses Perizinan</span></a></li>";
			$aktiv = '';
			if(strstr($_GET['send'],"simpadu3")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=simpadu3'><span>Statistik Perizinan</span></a></li>";
			$aktiv = '';
			if(strstr($_GET['send'],"simpadu4")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=simpadu4'><span>Total Per Bulan</span></a></li>";
		}	
	   echo"</ul>
	   </li>";
	 }

	if ($akses['sicantik']){		
	   $active = '';
	   if(strstr($_GET['send'],"sicantik")) $active = 'active-menu'; 
	   echo"<li class='$active'><a href='?send='><span>Rekap Sicantik</span></a>
		  <ul class='nav nav-third-level'>";
			if ($akses['import']){ 
				$active = '';
				if(strstr($_GET['send'],"copy")) $active = 'active-menu';
				echo"<li class=$active><a href='?send=datacopy'><span>Copy SiCantik</span></a></li>";
			}
			$aktiv = '';
			if(strstr($_GET['send'],"sicantik0")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=sicantik0'><span>Rekap Pengajuan Izin</span></a></li>";
			
			$aktiv = '';
			if(strstr($_GET['send'],"sicantik1")) $aktiv = 'active-menu'; 
			if($_SESSION['usr_opd'] == 3)echo"<li class=$aktiv><a href='?send=sicantik1-reklame'><span>Rekap Penerbitan Izin</span></a></li>";
			else echo"<li class=$aktiv><a href='?send=sicantik1'><span>Rekap Penerbitan Izin</span></a></li>";
			
			//$aktiv = '';
			//if(strstr($_GET['send'],"sicantik6")) $aktiv = 'active-menu'; 
			//echo"<li class=$aktiv><a href='?send=sicantik6'><span>Rekap Penolakan Izin</span></a></li>";
			
		if (($_SESSION['usr_opd'] == 1)or($_SESSION['usr_opd'] == 0)){ 		
			$aktiv = '';
			if(strstr($_GET['send'],"sicantik2")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=sicantik2'><span>Rekap Lama Proses Perizinan</span></a></li>";
			$aktiv = '';
			if(strstr($_GET['send'],"sicantik4")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=sicantik4'><span>Rekap Perizinan Reklame</span></a></li>";
			
			$aktiv = '';
			if(strstr($_GET['send'],"sicantik3a")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=sicantik3a'><span>Statistik Perizinan</span></a>";
			/*
			echo"<ul class='nav nav-third-level'>";
				$aktiv = '';
				if(strstr($_GET['send'],"sicantik3a")) $aktiv = 'active-menu'; 
				echo"<li class=$aktiv><a href='?send=sicantik3a'><span>Penetapan Izin</span></a></li>";
				$aktiv = '';
				if(strstr($_GET['send'],"sicantik3b")) $aktiv = 'active-menu'; 
				echo"<li class=$aktiv><a href='?send=sicantik3b'><span>Pengesahan Izin</span></a></li>";
			echo"</ul>";
			*/
			echo"</li>";
			
			$aktiv = '';
			if(strstr($_GET['send'],"sicantik5")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=sicantik5'><span>Total Per Bulan</span></a></li>";
		}	
	   echo"</ul>
	   </li>";
	}

	if ($akses['simpel']){		
	   $active = '';
	   if(strstr($_GET['send'],"simpel")) $active = 'active-menu'; 
	   echo"<li class='$active'><a href='?send='><span>Rekap Simpel</span></a>
		  <ul class='nav nav-third-level'>";		
			$aktiv = '';
			if(strstr($_GET['send'],"simpel1")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=simpel1'><span>Rekap Penerbitan Izin</span></a></li>";
				
			$aktiv = '';
			if(strstr($_GET['send'],"simpel2")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=simpel2'><span>Rekap Lama Proses Perizinan</span></a></li>";		
			
			$aktiv = '';
			if(strstr($_GET['send'],"simpel3")) $aktiv = 'active-menu'; 
			echo"<li class=$aktiv><a href='?send=simpel3'><span>Total Per Bulan</span></a></li>";	
	   echo"</ul>
	   </li>";
	}
	 
	if ($akses['pbg']){	 
	   $active = '';
	   if(strstr($_GET['send'],"pbg")) $active = 'active-menu';
	   echo"<li class=$active><a href='?send=datapbg'><span>Rekap PBG</span></a></li>";
	} 

echo"</ul>
</li>";

	
if ($akses['oss_rba']){  
	$active = '';
	if(strstr($_GET['send'],"proyek")) $active = 'active-menu'; 
	if(strstr($_GET['send'],"realisasi")) $active = 'active-menu';
	if(strstr($_GET['send'],"investasi")) $active = 'active-menu'; 
	echo"<li class='$active'><a href='?send='><span>Realisasi Investasi </span></a>
	<ul class='nav nav-second-level'>";
		$aktiv = '';
		if(strstr($_GET['send'],"proyek")) $aktiv = 'active-menu'; 
		echo"<li class=$aktiv><a href='?send=dataproyek'><span>Data Proyek</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"proyek_verified")) $aktiv = 'active-menu'; 
		echo"<li class=$aktiv><a href='?send=dataproyek_verified'><span>Data Proyek Terverifikasi</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"proyek_ori")) $aktiv = 'active-menu'; 
		echo"<li class=$aktiv><a href='?send=dataproyek_ori'><span>Data Proyek All</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"realisasi")) $aktiv = 'active-menu'; 
		echo"<li class=$aktiv><a href='?send=datarealisasi'><span>Realisasi Investasi</span></a></li>";
		/*$aktiv = '';
		if(strstr($_GET['send'],"lapproyek")) $aktiv = 'active-menu'; 
		echo"<li class=$aktiv><a href='?send=datalapproyek'><span>Laporan Proyek</span></a></li>";*/
		$aktiv = '';
		if(strstr($_GET['send'],"investasi")) $aktiv = 'active-menu'; 
		echo"<li class=$aktiv><a href='?send=datainvestasi'><span>Daftar Investasi < 2021</span></a></li>";
	echo"</ul>
	</li>";	
}


 
   echo"<li class='last'><a href='?send=logout'><span>Logout</span></a></li>";
   
}
?>
