<script>
function select_item(item)
{
targetitem.value = item;
top.close();
return false;
}
</script>

<?php
error_reporting(0);
mysql_connect("localhost","root","");
mysql_select_db("db_perizinan");

$tabel = mysql_query("SELECT*FROM data_teknis_reklame");
$query = "select * from data_teknis_reklame order by id asc";		
$query = mysql_query($query);
while ($r= mysql_fetch_array($query)){		
	for($i = 0; $i < mysql_num_fields($tabel); $i++){
		$name = mysql_field_name($tabel, $i);
		$data[$name] .= $r[$name];
	}
}

for($i = 0; $i < mysql_num_fields($tabel); $i++){
	$name = mysql_field_name($tabel, $i);
	if($data[$name] != "") echo $name.",<br>";
}
?>

