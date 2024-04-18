<div id='cssmenu'>
<ul>
<?php

   /*$active = '';
   if(strstr($_GET['send'],"home")) $active = 'active';
   echo"<li class=$active><a href='?send=home'><span>Home</span></a></li>";*/
  
if ($_SESSION['usr_id']){
	$akses=mysql_fetch_array(mysql_query("select*from petugas where id='".$_SESSION['usr_id']."' "));
   //echo $akses['copy'];
if ($akses['copy']){   
   //$active = '';
   //if(strstr($_GET['send'],"sms")) $active = 'active';
   //echo"<li class=$active><a href='?send=datasms'><span>SMS Gateway</span></a></li>";
      
   $active = '';
   if(strstr($_GET['send'],"helpdesk")) $active = 'active';
   echo"<li class=$active><a href='?send=datahelpdesk'><span>Helpdesk</span></a></li>";
   $active = '';
   if(strstr($_GET['send'],"copy")) $active = 'active';
   echo"<li class=$active><a href='?send=datacopy'><span>Copy SiCantik</span></a></li>";
}
if ($akses['sektor']){
   $active = '';
   if(strstr($_GET['send'],"sektor")) $active = 'active';
   echo"<li class=$active><a href='?send=datasektor'><span>Sektor Investasi</span></a></li>";
}
if ($akses['pokok']){  
   $active = '';
   if(strstr($_GET['send'],"opd")) $active = 'active'; 
   if(strstr($_GET['send'],"petugas")) $active = 'active'; 
   if(strstr($_GET['send'],"tbl")) $active = 'active'; 
   if(strstr($_GET['send'],"tabel")) $active = 'active'; 
   if(strstr($_GET['send'],"libur")) $active = 'active';  
   if(strstr($_GET['send'],"jnsizin")) $active = 'active'; 
   if(strstr($_GET['send'],"jns-izinsimp")) $active = 'active'; 
   echo"<li class='has-sub' id='$active'><a href='?send='><span>Data Pokok</span></a>
      <ul>";
		$aktiv = '';
		if(strstr($_GET['send'],"opd")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=dataopd'><span>Data OPD</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"petugas")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datapetugas'><span>Data Petugas</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"jnsizin")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datajnsizin'><span>Jenis Izin</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"jns-izinsimp")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=jns-izinsimp'><span>Jenis Izin Simpadu</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"libur")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datalibur'><span>Libur Nasional</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"tabel")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datatabel'><span>Tabel Database</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"tbl")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=lihattbl'><span>Lihat Tabel</span></a></li>";
		
   echo"</ul>
   </li>";
}

if ($akses['nswi']){
   $active = '';
   if(strstr($_GET['send'],"oss")) $active = 'active'; 
   echo"<li class='has-sub' id='$active'><a href='?send='><span>OSS NSWI</span></a>
      <ul>";
		$aktiv = '';
		if(strstr($_GET['send'],"ossiu07")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=dataossiu07'><span>IU 2007/2018</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"ossnib")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=dataossnib'><span>OSS NIB</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"ossiumk")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=dataossiumk'><span>OSS IUMK</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"ossnoniumk")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=dataossnoniumk'><span>OSS NON IUMK</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"investasi")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datainvestasi'><span>Daftar Investasi</span></a></li>";
   echo"</ul>
   </li>";
}

if ($akses['oss_rba']){  
   $active = '';
   if(strstr($_GET['send'],"rba")) $active = 'active'; 
   if(strstr($_GET['send'],"lapproyek")) $active = 'active'; 
   echo"<li class='has-sub' id='$active'><a href='?send='><span>OSS RBA</span></a>
      <ul>";
		$aktiv = '';
		if(strstr($_GET['send'],"rbanib")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datarbanib'><span>OSS RBA NIB</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"rbaizin")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datarbaizin'><span>OSS RBA Izin</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"rbaproyek")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datarbaproyek'><span>OSS RBA Proyek</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"lapproyek")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datalapproyek'><span>Laporan Proyek</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"realisasi")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=datarealisasi'><span>Realisasi Investasi</span></a></li>";
   echo"</ul>
   </li>";
}

if ($akses['simpadu']){  
   $active = '';
   if(strstr($_GET['send'],"simpadu")) $active = 'active'; 
   echo"<li class='has-sub' id='$active'><a href='?send='><span>Rekap Simpadu</span></a>
      <ul>";
		$aktiv = '';
		if(strstr($_GET['send'],"simpadu0")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=simpadu0'><span>Rekap Pengajuan izin</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"simpadu1")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=simpadu1'><span>Rekap Penerbitan izin</span></a></li>";
		
	if (($_SESSION['usr_opd'] == 1)or($_SESSION['usr_opd'] == 0)){ 
		$aktiv = '';
		if(strstr($_GET['send'],"simpadu2")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=simpadu2'><span>Rekap Lama Proses Perizinan</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"simpadu3")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=simpadu3'><span>Statistik Perizinan</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"simpadu4")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=simpadu4'><span>Total Per Bulan</span></a></li>";
	}	
   echo"</ul>
   </li>";
 }

if ($akses['sicantik']){		
   $active = '';
   if(strstr($_GET['send'],"sicantik")) $active = 'active'; 
   echo"<li class='has-sub' id='$active'><a href='?send='><span>Rekap Sicantik</span></a>
      <ul>";
		$aktiv = '';
		if(strstr($_GET['send'],"sicantik0")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=sicantik0'><span>Rekap Pengajuan Izin</span></a></li>";
		
		$aktiv = '';
		if(strstr($_GET['send'],"sicantik1")) $aktiv = 'aktiv'; 
        if($_SESSION['usr_opd'] == 3)echo" <li class=$aktiv><a href='?send=sicantik1-reklame'><span>Rekap Penerbitan Izin</span></a></li>";
		else echo" <li class=$aktiv><a href='?send=sicantik1'><span>Rekap Penerbitan Izin</span></a></li>";
		
	if (($_SESSION['usr_opd'] == 1)or($_SESSION['usr_opd'] == 0)){ 		
		$aktiv = '';
		if(strstr($_GET['send'],"sicantik2")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=sicantik2'><span>Rekap Lama Proses Perizinan</span></a></li>";
		$aktiv = '';
		if(strstr($_GET['send'],"sicantik4")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=sicantik4'><span>Rekap Perizinan Reklame</span></a></li>";
		
		$aktiv = '';
		if(strstr($_GET['send'],"?send=")) $aktiv = 'aktiv'; 
        echo"<li> <a href='?send='><span>Statistik Perizinan</span></a>";
		echo"<ul>";
			$aktiv = '';
			if(strstr($_GET['send'],"sicantik3a")) $aktiv = 'aktiv'; 
			echo" <li class=$aktiv><a href='?send=sicantik3a'><span>Penetapan Izin</span></a></li>";
			$aktiv = '';
			if(strstr($_GET['send'],"sicantik3b")) $aktiv = 'aktiv'; 
			echo" <li class=$aktiv><a href='?send=sicantik3b'><span>Pengesahan Izin</span></a></li>";
		echo"</ul>";
		echo"</li>";
		
		$aktiv = '';
		if(strstr($_GET['send'],"sicantik5")) $aktiv = 'aktiv'; 
        echo" <li class=$aktiv><a href='?send=sicantik5'><span>Total Per Bulan</span></a></li>";
	}	
   echo"</ul>
   </li>";
}
   
   echo"<li class='last'><a href='?send=logout'><span>Logout</span></a></li>";
   
}
?>
</ul>
</div>
