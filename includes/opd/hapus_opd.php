<?php
//$id=$_REQUEST["x"];
//$starting = $_REQUEST['page'];
$query = "DELETE FROM opd WHERE id='$id'";
$jalan = mysql_query($query);

//delete data opd database simpadu
koneksi1_tutup();
koneksi2_buka();
$query = "DELETE FROM opd WHERE id='$id'";
$jalan = mysql_query($query);
koneksi2_tutup()

?><script type="text/javascript">
document.location.href='?send=dataopd/<?php echo $id; ?>/<?php echo $starting; ?>/<?php echo $search; ?>';
</script>