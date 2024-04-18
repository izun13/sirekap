
<body>
<div><span class='judul'>Input Data Tabel Database</span>
<p>	
	<?php 
		
//$id=$_REQUEST["x"];
//$starting = $_REQUEST['page'];
$con=mysql_query("select*from tabel where id='$id'");
$r=mysql_fetch_array($con);	

$smpn=$_POST["simpan"];	
$nama_tabel=$_POST["nama_tabel"];
$link_sicantik=$_POST["link_sicantik"];
$hide=$_POST["hide"];

if($smpn=="Batal"){
?>
			<script language="JavaScript">
			document.location.href='?send=datatabel/<?php echo $id;?>/<?php echo $starting;?>/<?php echo $search;?>';
			</script>
			<?php
}

if($smpn=="Simpan"){
	if(($nama_tabel == '')or($link_sicantik == '')){
		?>
			<script language="JavaScript">alert('data belum lengkap !');</script>
		<?php
	}else{
		if(empty($r)){
			
			//Membuat tabel database
			$new_tabel = "CREATE TABLE $nama_tabel(id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id)) ENGINE=MyISAM ROW_FORMAT=DYNAMIC";
			mysql_query($new_tabel);
			
			$tambah="insert into tabel (nama_tabel,link_sicantik,hide)values('$nama_tabel','$link_sicantik','$hide')";
			$hasil=mysql_query($tambah);
			if($hasil){
				?>
				<script language="JavaScript">alert('data Tabel Database telah ditambah...!');
				document.location.href='?send=data-tabel';
				</script>
				<?php
			}else{
			echo"data Tabel Database gagal ditambah";
			} 
		}else{
				$cek_tabel = mysql_query("select*from $nama_tabel");
				if (isset($cek_tabel)){
					$new_tabel = "CREATE TABLE $nama_tabel(id INT (11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id)) ENGINE=MyISAM ROW_FORMAT=DYNAMIC";
					mysql_query($new_tabel);
					//echo "cek......!";
				}
				
				$ubah="update tabel set nama_tabel='$nama_tabel',link_sicantik='$link_sicantik',hide='$hide' where id='$id'";
				$hasil=mysql_query($ubah);
				if($hasil){
				?>
					<script language="JavaScript">alert('data Tabel Database telah diubah...!');
						document.location.href='?send=datatabel/<?php echo $id;?>/<?php echo $starting;?>/<?php echo $search;?>';
					</script>
					<?php
				}else{
				echo"data Tabel Database gagal diubah";
				}
		}
	}
}

?>
<form action="" method="post" name='text' autocomplete="off">
	<table>
		<tr>
			<td>Id</td>
			<td>:</td>
			<td><?php echo $r[id];?></td>
		</tr>
		
		<tr>
			<td>Nama Tabel</td>
			<td>:</td>
			<td><input type="text" id="input" name="nama_tabel" size="40" value="<?php if($_POST["nama_tabel"]) echo $_POST["nama_tabel"]; else echo $r['nama_tabel'];?>" ></td>
		</tr>		
		
		<tr>
			<td>Link Json</td>
			<td>:</td>
			<td><textarea id="input" name="link_sicantik" rows="2" cols="60"><?php if($_POST["link_sicantik"]) echo $_POST["link_sicantik"]; else echo $r['link_sicantik'];?></textarea></td>
		</tr>
		
		<tr>
			<td>Sembunyikan</td>
			<td>:</td>
			<?php 
			$checked = ""; 
			if($r['hide'] == 1)$checked = "checked";
			?>
			<td><input type="checkbox" id="input" name="hide" size="40" value="1" <?php echo $checked; ?>></td>
		</tr>
		
		<tr>
			<td colspan="3">&nbsp;</td>	
		</tr>		
		<tr>
			<td colspan="3" align="center"> <input type="submit" name="simpan" value="Simpan"> &nbsp; <input type="submit" name="simpan" value="Batal"> </td>	
		</tr>
	</table>
	</form>
</body>