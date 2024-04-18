<?php
//$id=$_REQUEST["x"];
//$starting = $_REQUEST['page'];
$query = "DELETE FROM petugas WHERE id='$id'";
$jalan = mysql_query($query);


?><script type="text/javascript">
document.location.href='?send=datapetugas//<?php echo $starting;?>/<?php echo $search;?>';
</script>