<?php
//mysql_connect("","","");
define('DB1_NAMA', 'db_perizinan'); // sesuaikan dengan nama database pertama anda
define('DB1_USER', 'root'); // sesuaikan dengan nama pengguna database pertama anda
define('DB1_PASSWORD', ''); // sesuaikan dengan kata sandi database pertama anda
define('DB1_HOST', 'localhost'); // ganti jika letak database mysql di komputer lain

//mysql_connect("","","");
//$con=mysql_select_db("admin_bo");
define('DB2_NAMA', 'admin_bo'); // sesuaikan dengan nama database kedua anda
define('DB2_USER', 'root'); // sesuaikan dengan nama pengguna database kedua anda //admin_bo
define('DB2_PASSWORD', ''); // sesuaikan dengan kata sandi database kedua anda // uKD97mtPr6
define('DB2_HOST', 'localhost'); // ganti jika letak database mysql di komputer lain // localhost
 
// mengambil alamat direktori tempat berkas konfigurasi.php disimpan
define('ABSPATH', dirname(__FILE__).'/');
 
// memanggil berkas fungsi.php
require ABSPATH.'koneksi_db.php';
?>
