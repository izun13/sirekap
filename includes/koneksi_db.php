<?php
// fungsi untuk melakukan koneksi ke database mysql pertama
function koneksi1_buka() {
    mysql_select_db(DB1_NAMA,mysql_connect(DB1_HOST,DB1_USER,DB1_PASSWORD));
}
 
// fungsi untuk menutup koneksi ke database mysql pertama
function koneksi1_tutup() {
    mysql_close(mysql_connect(DB1_HOST,DB1_USER,DB1_PASSWORD));
}
// fungsi untuk melakukan koneksi ke database mysql kedua
function koneksi2_buka() {
    mysql_select_db(DB2_NAMA,mysql_connect(DB2_HOST,DB2_USER,DB2_PASSWORD));
}
 
// fungsi untuk menutup koneksi ke database mysql kedua
function koneksi2_tutup() {
    mysql_close(mysql_connect(DB2_HOST,DB2_USER,DB2_PASSWORD));
}
?>