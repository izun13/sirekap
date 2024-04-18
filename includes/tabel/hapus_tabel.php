<?php
//$id=$_REQUEST["x"];
//$starting = $_REQUEST['page'];

$tabel=mysql_num_rows(mysql_query("select*from tabel where id='$id'"));
$nama_tabel=$tabel["nama_tabel"];
$query = "DROP TABLE IF EXISTS $nama_tabel";
mysql_query($query);

$query = "DELETE FROM tabel WHERE id='$id'";
mysql_query($query);

?><script type="text/javascript">
document.location.href='?send=datatabel//<?php echo $starting;?>/<?php echo $search;?>';
</script>