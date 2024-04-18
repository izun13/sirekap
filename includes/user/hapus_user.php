<?php
$id=$_REQUEST["x"];
$starting = $_REQUEST['page'];
$query = "DELETE FROM tb_pegawai WHERE id_pegawai='$id'";
$jalan = mysql_query($query);


?><script type="text/javascript">
document.location.href='?send=datauser/<? echo $id;?>/<? echo $starting;?>/<? echo $search;?>';
</script>