<?php

$namalocal = "localhost";
$pengguna_mysql = "root";
$katalaluan_mysql = "";
$dbname = "calculate";

$connection = mysqli_connect($namalocal,$pengguna_mysql,$katalaluan_mysql);

mysqli_select_db($connection,$dbname);

?>