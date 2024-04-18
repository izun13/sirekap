<?php
//$id=$_REQUEST["x"];
//$starting = $_REQUEST['page'];
$query = "DELETE FROM tb_pbg WHERE id='$id'";
$jalan = mysql_query($query);

$query = "DELETE FROM tb_tanah WHERE pbg_id='$id'";
$jalan = mysql_query($query);

?><script type="text/javascript">
document.location.href='?send=datapbg/<?php echo $id; ?>/<?php echo $starting; ?>/<?php echo $search; ?>';
</script>