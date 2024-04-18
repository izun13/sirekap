<?php
//require_once "includes/createpass.php";

	$user = $_POST['username'];
	$pass = $_POST['password'];
	//$pass = base64_encode($pass);
	//$converter = new Encryption;
	//$pass = $converter->encode($pass);
	$login = $_POST['login'];
	
	if($login == "LOGIN"){
	$tampil ="select*from petugas where username='".$user."' ";// and password='".$pass."'
			$data=mysql_fetch_array(mysql_query($tampil));
		//if(password_verify($pass,$data["password"])){
		if($data["password"]){
			$_SESSION['usr_name']=$data['nama'];
			$_SESSION['usr_id']=$data['id'];
			$_SESSION['usr_opd']=$data['opd_id'];
			?>
			<script language="JavaScript">document.location='?send=home'</script>
			<?php
		}else{
			?>
			<script language="JavaScript">alert('Username atau Password Salah !');</script>
			<?php
		}
	}
	
?>
<div align="center">
<form method="POST" autocomplete="off">
<table width=''>
<tr>
<td align='center' colspan='2'>&nbsp;</td>
</tr>
<tr>
<td align='center' colspan='2'>
<img src='images/login.png' width='100'>
</td>
</tr>
<tr>
<td align='center' colspan='2'>&nbsp;</td>
</tr>
<tr>
<td align='left'><b>Username</td>
<td> : <input type='text' size='20' name='username' value='<?php echo $user;?>'></td>
</tr>
<tr>
<td align='left'><b>Password</td>
<td> : <input type='password' size='20' name='password' value='<?php echo $pass;?>'></td>
</tr>
<tr>
<td align='center' colspan='2'>&nbsp;</td>
</tr>
<tr>
<td align='center' colspan='2'><input type='submit' name='login' value='LOGIN'></td>
</tr>
<tr>
<td align='center' colspan='2'>&nbsp;</td>
</tr>
</table>
</form>
</div>