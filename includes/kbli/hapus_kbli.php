<?php
//$id=$_REQUEST["x"];
//$starting = $_REQUEST['page'];
$query = "DELETE FROM kbli WHERE id='$id'";
$jalan = mysql_query($query);


?><script type="text/javascript">
document.location.href='?send=datasektor/<?php echo $id; ?>/<?php echo $starting; ?>/<?php echo $search; ?>';
</script>