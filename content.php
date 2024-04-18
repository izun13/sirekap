<?php 
		$link = explode("/",$_GET['send']);
		$send = $link[0];
		$id = $link[1];
		$starting = $link[2];
		$search = $link[3];
		$act = $link[4];
		$opd_id = $_SESSION['usr_opd'];
		//echo "OPD : ".$opd_id;
		switch($send){
				case('') : require("includes/home.php");break;
				case('home') : require("includes/home.php");break;
				
				case('datauser') : require("includes/user/data_user.php");break;	
				case('inputuser') : require("includes/user/input_user.php");break;	
				case('hapususer') : require("includes/user/hapus_user.php");break;	

				case('datatabel') : require("includes/tabel/data_tabel.php");break;	
				case('inputtabel') : require("includes/tabel/input_tabel.php");break;	
				case('hapustabel') : require("includes/tabel/hapus_tabel.php");break;	
				case('lihattbl') : require("includes/tabel/lihat_tabel.php");break;	
				case('updatetbl') : require("includes/tabel/update_datatabel.php");break;
				
				case('dataopd') : require("includes/opd/data_opd.php");break;	
				case('inputopd') : require("includes/opd/input_opd.php");break;	
				case('hapusopd') : require("includes/opd/hapus_opd.php");break;
				
				case('datapetugas') : require("includes/petugas/data_petugas.php");break;	
				case('inputpetugas') : require("includes/petugas/input_petugas.php");break;	
				case('hapuspetugas') : require("includes/petugas/hapus_petugas.php");break;	
				case('profile') : require("includes/petugas/profile.php");break;
				
				case('datajnsizin') : require("includes/jnsizin/data_jnsizin.php");break;	
				case('inputjnsizin') : require("includes/jnsizin/input_jnsizin.php");break;	
				case('hapusjnsizin') : require("includes/jnsizin/hapus_jnsizin.php");break;
				
				case('jns-izinsimp') : require("includes/jnsizin_simpadu/data_jnsizin.php");break;	
				case('inputjns-izinsimp') : require("includes/jnsizin_simpadu/input_jnsizin.php");break;	
				case('hapusjns-izinsimp') : require("includes/jnsizin_simpadu/hapus_jnsizin.php");break;
				
				case('datacopy') : require("includes/copy/data_copy.php");break;	
				case('inputcopy') : require("includes/copy/input_copy.php");break;	
				case('hapuscopy') : require("includes/copy/hapus_copy.php");break;				
				
				case('datahelpdesk') : require("includes/helpdesk/data_helpdesk.php");break;	
				case('inputhelpdesk') : require("includes/helpdesk/input_helpdesk.php");break;	
				case('hapushelpdesk') : require("includes/helpdesk/hapus_helpdesk.php");break;

				case('datareklame') : require("includes/reklame/data_reklame.php");break;	
				case('inputreklame') : require("includes/reklame/input_reklame.php");break;	
				case('hapusreklame') : require("includes/reklame/hapus_reklame.php");break;	
				
				case('datapenyerahan') : require("includes/penyerahan/data_penyerahan.php");break;	
				case('inputpenyerahan') : require("includes/penyerahan/input_penyerahan.php");break;	
				case('hapuspenyerahan') : require("includes/penyerahan/hapus_penyerahan.php");break;	
				
				case('datakbli') : require("includes/kbli/data_kbli.php");break;	
				case('inputkbli') : require("includes/kbli/input_kbli.php");break;	
				case('hapuskbli') : require("includes/kbli/hapus_kbli.php");break;
				
				case('datajenis') : require("includes/jenis/data_jenis.php");break;	
				case('inputjenis') : require("includes/jenis/input_jenis.php");break;	
				case('hapusjenis') : require("includes/jenis/hapus_jenis.php");break;	

				case('datapermohonan') : require("includes/permohonan/data_permohonan.php");break;	
				case('inputpermohonan') : require("includes/permohonan/input_permohonan.php");break;	
				case('hapuspermohonan') : require("includes/permohonan/hapus_permohonan.php");break;

				case('datapemohon') : require("includes/pemohon/data_pemohon.php");break;	
				case('inputpemohon') : require("includes/pemohon/input_pemohon.php");break;	
				case('hapuspemohon') : require("includes/pemohon/hapus_pemohon.php");break;

				case('datalibur') : require("includes/libur/data_libur.php");break;	
				case('inputlibur') : require("includes/libur/input_libur.php");break;	
				case('hapuslibur') : require("includes/libur/hapus_libur.php");break;

				case('datasektor') : require("includes/sektor/data_sektor.php");break;	
				case('inputsektor') : require("includes/sektor/input_sektor.php");break;	
				case('hapussektor') : require("includes/sektor/hapus_sektor.php");break;
				
				case('dataossiu07') : require("includes/oss/data_ossiu.php");break;
				case('dataossnib') : require("includes/oss/data_ossnib.php");break;
				case('dataossnib-edit') : require("includes/oss/data_ossnib_edit.php");break;
				case('dataossiumk') : require("includes/oss/data_ossiumk.php");break;
				case('dataossnoniumk') : require("includes/oss/data_ossnoniumk.php");break;	
				
				case('datarbanib') : require("includes/oss_rba/nib/data_ossrbanib.php");break;
				case('datarbanib-import') : require("includes/oss_rba/nib/data_ossrbanib_import.php");break;
				case('datarbanib-verifikasi') : require("includes/oss_rba/nib/data_ossrbanib_verifikasi.php");break;
				
				case('datarbaizin') : require("includes/oss_rba/izin/data_ossrbaizin.php");break;		
				case('datarbaizin-edit') : require("includes/oss_rba/izin/data_ossrbaizin_edit.php");break;	
				case('datarbaizin-import') : require("includes/oss_rba/izin/data_ossrbaizin_import.php");break;
				case('datarbaizin-verifikasi') : require("includes/oss_rba/izin/data_ossrbaizin_verifikasi.php");break;	
				
				case('grafik_rba') : require("includes/grafik/grafik_izin.php");break;
				
				case('dataproyek') : require("includes/oss_rba/proyek/data_ossrbaproyek.php");break;		
				case('dataproyek-edit') : require("includes/oss_rba/proyek/data_ossrbaproyek_edit.php");break;	
				case('dataproyek-import') : require("includes/oss_rba/proyek/data_ossrbaproyek_import.php");break;	
				case('dataproyek-verifikasi') : require("includes/oss_rba/proyek/data_ossrbaproyek_verifikasi.php");break;		
				case('datalapproyek') : require("includes/oss_rba/proyek/data_osslapproyek.php");break;		
					
				case('dataproyek_verified') : require("includes/oss_rba/proyek/data_ossrbaproyek_terverifikasi.php");break;
				case('dataproyek_ori') : require("includes/oss_rba/proyek_ori/data_ossrbaproyek.php");break;	
				case('dataproyek_ori-import') : require("includes/oss_rba/proyek_ori/data_ossrbaproyek_import.php");break;
				
				case('datarealisasi') : require("includes/oss_rba/proyek/data_realisasi.php");break;	
				case('inputrealisasi') : require("includes/realisasi/input_realisasi.php");break;	
				case('hapusrealisasi') : require("includes/realisasi/hapus_realisasi.php");break;
				
				case('datapbg') : require("includes/pbg/data_pbg.php");break;	
				case('inputpbg') : require("includes/pbg/input_pbg.php");break;	
				case('updatepbg') : require("includes/pbg/update_pbg.php");break;	
				case('importpbg') : require("includes/pbg/import_pbg.php");break;	
				case('hapuspbg') : require("includes/pbg/hapus_pbg.php");break;
				
				case('datainvestasi') : require("includes/investasi/data_investasi.php");break;	
				case('datainvestasi-import') : require("includes/investasi/data_investasi_import.php");break;	
				case('inputinvestasi') : require("includes/investasi/input_investasi.php");break;	
				case('hapusinvestasi') : require("includes/investasi/hapus_investasi.php");break;
				
				case('sicantik0') : require("includes/sicantik/data_pengajuan.php");break;
				case('sicantik1') : require("includes/sicantik/data_penerbitan.php");break;
				case('sicantik1-reklame') : require("includes/sicantik/data_penerbitan_reklame.php");break;
				case('sicantik2') : require("includes/sicantik/data_lamaproses.php");break;
				case('sicantik3a') : require("includes/sicantik/data_statistik_penetapan.php");break;
				case('sicantik3b') : require("includes/sicantik/data_statistik_tte.php");break;
				case('sicantik4') : require("includes/sicantik/data_penerbitan_reklame.php");break;
				case('sicantik5') : require("includes/sicantik/data_total_perbulan.php");break;
				case('sicantik6') : require("includes/sicantik/data_penolakan.php");break;
				case('detil-sicantik2') : require("includes/sicantik/data_lamaproses_detil.php");break;
				case('detil-sicantik4') : require("includes/sicantik/data_reklame_detil.php");break;
								
				case('simpadu0') : require("includes/simpadu/data_pengajuan.php");break;
				case('simpadu1') : require("includes/simpadu/data_penerbitan.php");break;
				case('simpadu1_detil') : require("includes/simpadu/data_penerbitan_detil.php");break;
				case('simpadu2') : require("includes/simpadu/data_lamaproses.php");break;
				case('simpadu3') : require("includes/simpadu/data_statistik.php");break;
				case('simpadu4') : require("includes/simpadu/data_total.php");break;
				
				
				case('simpel1') : require("includes/simpel/data_penerbitan.php");break;
				case('simpel2') : require("includes/simpel/data_lamaproses.php");break;
				case('simpel3') : require("includes/simpel/data_total.php");break;
				
				case('logout') : require("logout.php");break;				
		}
?>