<?php
	session_start();
	unset($_SESSION['usr_name']);
	unset($_SESSION['usr_id']);
	unset($_SESSION['usr_opd']);
	session_destroy();	
?>
	<script language="JavaScript">document.location='?send=home'</script>