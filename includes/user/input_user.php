
<link rel="stylesheet" type="text/css" media="all" href="css/jsDatePick_ltr.min.css" />
<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"inputField",
			dateFormat:"%Y-%m-%d"
		});
		new JsDatePick({
			useMode:2,
			target:"inputField2",
			dateFormat:"%Y-%m-%d"
		});
	};
</script>
<body>
<?php 
		
$id=$_REQUEST["x"];
$starting = $_REQUEST['page'];
$con=mysql_query("select*from tb_pegawai where id_pegawai='$id'");
$r=mysql_fetch_array($con);	


$smpn=$_POST["simpan"];	
$nm=$_POST["nama"];
$no_ktp=$_POST["no_ktp"];
$tgl=$_POST["tgl_kerja"];
$al=$_POST["alamat"];
$tlp=$_POST["telp"];
$user=$_POST["user"];
$pass=ubah_teks($_POST["pass"]);

if($smpn=="Batal"){
?>
			<script language="JavaScript">
				document.location.href='?send=datauser/<? echo $id;?>/<? echo $starting;?>/<? echo $search;?>';
			</script>
			<?php
}

if($smpn=="Simpan"){
if(($nm == '')or($no_ktp == '')or($tgl == '')or($al == '')or($tlp == '')or($user == '')or($pass == '')){
?>
	<script language="JavaScript">alert('data belum lengkap !');</script>
<?php
}else{
	if(empty($r)){
		$tambah="insert into tb_pegawai (nama_pegawai,alamat,telp,no_ktp,tgl_kerja,username,password)values('$nm','$al','$tlp','$no_ktp','$tgl','$user','$pass')";
		$hasil=mysql_query($tambah);
		if($hasil){
			?>
			<script language="JavaScript">alert('data pegawai telah ditambah...!');
				document.location.href='?send=datauser';
			</script>
			<?php
		}else{
		echo"data pegawai gagal ditambah";
		} 
	}else{
		$ubah="update tb_pegawai set nama_pegawai='$nm',no_ktp='$no_ktp',alamat='$al',telp='$tlp',tgl_kerja='$tgl',username='$user',password='$pass' where id_pegawai='$id'";
		$hasil=mysql_query($ubah);
		if($hasil){
		?>
			<script language="JavaScript">alert('data pegawai telah diubah...!');
				document.location.href='?send=datauser/<? echo $id;?>/<? echo $starting;?>/<? echo $search;?>';
			</script>
			<?php
		}else{
		echo"data pegawai gagal diubah";
		} 
	}
}
}
?>
<div class="judul">Input Data Pegawai</div>
<form action="?send=inputuser/<? echo $id; ?>/<? echo $starting; ?>/<? echo $search; ?>" method="post" name='text'>
	<table>		
		<tr>
			<td>Nama</td>			
			<td>:</td>
			<td><input type="text" id="input" name="nama" value="<?php if($r[nama_pegawai]) echo $r[nama_pegawai]; else echo $_POST["nama"];?>" ></td>
		</tr>
		
		<tr>
			<td>Alamat</td>			
			<td>:</td>		
			<td><textarea id="input" name="alamat" cols="40" rows="3"><?php if($r[alamat]) echo $r[alamat]; else echo $_POST["alamat"];?></textarea></td>
		</tr>
						
		<tr>
			<td>Telepon</td>			
			<td>:</td>		
			<td><input type="text" id="input" name="telp" value="<?php if($r[telp]) echo $r[telp]; else echo $_POST["telp"];?>" ></td>
		</tr>
		
		<tr>
			<td>No. KTP</td>			
			<td>:</td>		
			<td><input type="text" id="input" name="no_ktp" value="<?php if($r[no_ktp]) echo $r[no_ktp]; else echo $_POST["no_ktp"];?>" size="40"></td>
		</tr>
		
		<tr>
			<td>Tgl Kerja</td>			
			<td>:</td>		
			<td><input type="text" name="tgl_kerja" size="12" id="inputField" value="<?php if($r[tgl_kerja])echo $r[tgl_kerja];  else echo $_POST["tgl_kerja"];?>"/></td>
		</tr>
		
		<tr>
            <td width="">Username</td>   		
			<td>:</td>         
			<td><input type="text" name="user" value="<?php if($r[username]) echo $r[username]; else echo $_POST["user"];?>" size="10" /></td>
        </tr>
			
               
        <tr>
            <td>Password</td>          		
			<td>:</td>  
			<td><input name="pass" type="text" value="<?php if($r[password]) echo balik_teks($r[password]); else echo $_POST["pass"];?>" size="10" /></td>
        </tr>
		<tr>			
			<td colspan="3" align="center">&nbsp;</td>	
		</tr>
		<tr>
			
			<td colspan="3" align="center"> <input type="submit" name="simpan" value="Simpan"> &nbsp; <input type="submit" name="simpan" value="Batal"> </td>	
		</tr>
	</table>
	</form>
</body>