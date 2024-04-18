<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"inputField1",
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
<div><span class='judul'>Input Hari Libur Nasional</span>
<p>	
	<?php 
		
//$id=$_REQUEST["x"];
//$starting = $_REQUEST['page'];
$con=mysql_query("select*from libur_nasional where id='$id'");
$r=mysql_fetch_array($con);	

$smpn=$_POST["simpan"];	
$tgl=$_POST["tgl"];
$ket=$_POST["ket"];

if($smpn=="Batal"){
?>
			<script language="JavaScript">
			document.location.href='?send=datalibur/<?php echo $id;?>/<?php echo $starting;?>/<?php echo $search;?>';
			</script>
			<?php
}

if($smpn=="Simpan"){
	if(($tgl == '')or($ket == '')){
	?>
		<script language="JavaScript">alert('data belum lengkap !');</script>
	<?php
	}else{
	if(empty($r)){
		$tambah="insert into libur_nasional (tgl,ket)values('$tgl','$ket')";
		$hasil=mysql_query($tambah);
		if($hasil){
			?>
			<script language="JavaScript">alert('data Libur Nasional telah ditambah...!');
			document.location.href='?send=datalibur';
			</script>
			<?php
		}else{
		echo"data Libur Nasional gagal ditambah";
		} 
	}else{
		$ubah="update libur_nasional set tgl='$tgl',ket='$ket' where id='$id'";
		$hasil=mysql_query($ubah);
		if($hasil){
		?>
			<script language="JavaScript">alert('data Libur Nasional telah diubah...!');
			document.location.href='?send=datalibur/<?php echo $id;?>/<?php echo $starting;?>/<?php echo $search;?>';
			</script>
			<?php
		}else{
		echo"data Libur Nasional gagal diubah";
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
			<td>Tanggal</td>
			<td>:</td>
			<td><input type="text" id="inputField1" name="tgl" size="10" value="<?php if($_POST["tgl"]) echo $_POST["tgl"]; else echo $r["tgl"];?>"></td>
		</tr>
		
		
		<tr>
			<td>Keterangan</td>
			<td>:</td>
			<td><textarea id="input" name="ket" rows="2" cols="60"><?php if($_POST["ket"]) echo $_POST["ket"]; else echo $r[ket];?></textarea></td>
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