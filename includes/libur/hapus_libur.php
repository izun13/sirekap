<?php
//$id=$_REQUEST["x"];
//$starting = $_REQUEST['page'];

$query = "DELETE FROM libur_nasional WHERE id='$id'";
mysql_query($query);

?><script type="text/javascript">
document.location.href='?send=datalibur//<?php echo $starting;?>/<?php echo $search;?>';
</script>