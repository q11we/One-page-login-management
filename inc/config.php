<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$db = 'login_system';

$link = mysql_connect($server,$user,$pass) or 
die("Could not connect server");

mysql_select_db($db);



?>