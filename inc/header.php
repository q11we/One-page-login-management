<?php

date_default_timezone_set("Asia/Tokyo");
include_once 'functions.inc.php';

if(!isset($_SESSION)) {session_start();}
global $title;

if(!isset($_SESSION['logged_in'])) {
chk();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?php if(isset($title)){echo $title;} 
else {?> User Login<?php } ?></title>
<link rel="stylesheet" href="style/style.css" />
<script src="interface.js"></script>
</head>