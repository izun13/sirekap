
<body>
<?php 
$gambar = $_REQUEST["x"]; 
if($gambar) echo"<img src='$gambar' width=''>";
else echo"You are not authorized to view this page";
?>
</body>
