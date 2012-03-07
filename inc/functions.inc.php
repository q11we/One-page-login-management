<?php

include_once 'errors.inc.php';
include_once 'config.php';

function passhash($pass, $salt='dfgae37ga') {
		if(strlen($pass) >= 5) {
			$num = (strlen($pass)> 12) ? 7 : strlen($pass)-5;
			$substr = substr($pass,0,strlen($pass)-$num);
			$substr2 = substr($pass,strlen($pass)-$num,$num);
			return md5($substr).$substr2;
		}
		else {
			echo "<span style=\"color:#ab1111;\">Password too short.<br />
			At least 5 characters!</span>";
		}
}

function geripass($pass) {
	if(strlen($pass)<40) {
	return strrev($pass);
	}
	else {
		echo "<span style=\"color:#ab1111;\">Password too long.<br />
		Maximum 40 alphanumeric characters</span>";
	}
}

function numrows($row) {
	//row as a resource
	return mysql_num_rows($row);
}

function query($query) {
	//return resourse from 
	return mysql_query($query);
}

function fetch($result) {
	//result as a resource 
	return mysql_fetch_array($result,MYSQL_ASSOC);
}

//c4db - clean for database
function c4db($data) {
	if(is_array($data)) {
		foreach ($data as $value) {
			return c4db($value);
		}
	}
	else {
		$data = trim($data);
		$allow = '<p><ul><li><b><strong><br>';
		$data = strip_tags($data,$allow);
		$data = htmlentities($data);
		$data = mysql_real_escape_string($data);
		return $data;
	}
}


//clear for html view
function c4html($data) {
if (get_magic_quotes_gpc()) {
		$data = stripslashes($data);
	}
	$data = html_entity_decode($data);
	return $data;
}



//email length checker
function email_length($email) {
  if(strlen($email) > 39) { return 'long';}
  else if(strlen($email)<5) { return 'short';}
  else {return 0;}
}

//email validator
function email_validator($email) {
return preg_match("/^(\w{3,30})(\.)*(\w{0,10})(@|\[at\])((\w|\.){2,20})\.([a-z]{2,5})$/",$email);
}


//query builder for url
// instead of this html_build_query() can be used
function bquery($v) {
	$s = ''; $n=0; $m=sizeof($v);
	if(is_array($v)) {
		foreach($v as $k=>$c) {
			$k=urlencode($k);
			$c=urlencode($c);
			if($n!=($m-1)) {$s.="$k=$c&";}
			else {$s.="$k=$c";}
			$n++;
		}
	}
	else {
		$s=$v;
	}
	return $s;
}

function p($arr, $index) {
	if(isset($arr[$index])) {return $arr[$index];}
	else {return '';}
}

function username_length($username) {
	$l = strlen($username);
	if($l > 39) {return 'long';}
	else if($l < 3) {return 'short';}
	else {return 0;}
}

function name_length($name) {return username_length($name);}

function username_validator($username) {
	return preg_match("/^(\w{4,40})$/",$username);
}

function name_validator($name) {
	return preg_match("/^([a-zA-Z]{4,40})$/",$name);
}


function timenow() {
	return date("Y-m-d H:i:s");
}

function update($column,$to,$id) {
	$query = "UPDATE `users` SET `$column`='$to' WHERE `id`=$id";
	query($query);
}

function approve($id) {
	$query = "UPDATE `users` SET `approved`=1 WHERE `id`=$id";
	query($query);
}

function reject($id) {
	$query = "UPDATE `users` SET `approved`=0 WHERE `id`=$id";
	query($query);
}


function updatedata($fname,$lname,$email,$website,$phone,$passw,$user) {
	$query = "UPDATE `users` SET 
	`firstname`='$fname',
	`lastname`='$lname',
	`email`='$email',
	`website`='$website',
	`phone`='$phone'
	";
	if($passw!='') {
	$passhash = passhash($passw); 
	$geripass = geripass($passw);
	$query.=", `passhash`='$passhash', `geripass`='$geripass'";
	}
	$query.= " WHERE `username`='$user'";
	//echo $query;
	query($query);
}


function user_exists($user) {
	$q = "SELECT * FROM `users` WHERE `username`='$user'";
	$row = query($q);
	
	if(is_resource($row))
	{
	 if(numrows($row) > 0) {
		return $row;
	 }
	 else {
		return false;
	 }
	}
	else { return false;}
}

function user_exists_id($userid) {
	$q = "SELECT * FROM `users` WHERE `id`='$userid'";
	$row = query($q);
	
	if(is_resource($row))
	{
	 if(numrows($row) > 0) {
		return $row;
	 }
	 else {
		return false;
	 }
	}
	else { return false;}
}

function get_links($ulevel) {
	$links = array();
	if($ulevel == 0) {
		$links['Home']='?url=home';
		$links['Login']='?url=login'; 
	}
	else if($ulevel == 1) {
		$links['Home']='?url=home';
		$links['Edit/Update my info']='?url=update';
		$links['My Account']='?url=account';
	}
	
	else if($ulevel == 9) {
		$links['Home']='?url=home';
		$links['Edit/Update my info']='?url=update';
		$links['My Account']='?url=account';
		$links['New Users']='?url=new';
		$links['All Users']='?url=all';
	}
	return $links;
}

function chk() {
if(!isset($_SESSION)) {
session_start();
session_regenerate_id();
$_SESSION['sessionid'] = session_id();
}
		//first check cookies
		//if no success then see if there is a valid session
		if(isset($_COOKIE)&&isset($_COOKIE['luser'])) {
			$row = user_exists($_COOKIE['luser']);
			if(is_resource($row)) {
			 $res = fetch($row);
			 if($_COOKIE['luserid'] == passhash($res['id'])) {
			  //set sessions
			  $_SESSION['luser'] = $_COOKIE['luser'];
			  $_SESSION['userid'] = md5($_COOKIE['luserid']);
			  session_regenerate_id();
			  $_SESSION['sessionid'] = session_id();
			  $_SESSION['ulevel'] = $res['ulevel'];
			 }
			 else {
				logout();
			 }
			}
			else {
			 logout();
			}
		}
		//else check for sessions
		else if(isset($_SESSION['luser'])) {
			//$sql="SELECT `id` FROM `users` WHERE `username`=".$_SESSION['luser'];
			//$res = query($sql);
			$row = user_exists($_SESSION['luser']);
			if($_SESSION['luser']!='guest'&&is_resource($row)) {
				$res = fetch($row);
				if(md5($res['id']) === $_SESSION['userid']) {
				$_SESSION['logged_in'] = 1;
				}
				else {logout();}
			}
			else {
				logout();
			}
		}
		//else $_SESSION['logged_in'] is false and show login page
		else {
			logout();
		}
}

function login($user, $pass, $length = 3600) {
	$row = user_exists($user);
	if(is_resource($row)) {
		$res = fetch($row);
		if(passhash($pass)===$res['passhash']&&$res['approved']&&!$res['blocked']) 
		{
			$newip = $_SERVER['SERVER_ADDR'];
			$lastlogin = timenow();
			$userid = $res['id'];
			
			//set sessions
			$_SESSION['luser'] = $user;
			$_SESSION['userid'] = md5($userid);
			session_regenerate_id();
			$_SESSION['sessionid'] = session_id();
			$_SESSION['ulevel'] = $res['ulevel'];
			$_SESSION['logged_in'] = 1;
			
			//update some database columns
			update('lastip',$newip,$userid);
			update('lastlogin',$lastlogin,$userid);
			update('last_visit',$lastlogin,$userid);
			
			//setcookies
			setcookie('luser',$user,time()+$length);
			setcookie('luserid',passhash($userid),time()+$length);
			return true;
		}
		else {
			return false;
		}
	}
	
	else {
		return false;
	}
}

function register($username,$pass,$fname,$lname, $email,$phone='',$web='') {
	$passhash = passhash($pass);
	$geripass = geripass($pass);
	$created = timenow();
	$ip = $_SERVER['SERVER_ADDR'];
	$q = "INSERT INTO `users`(`username`,`firstname`,`lastname`,`email`,
	`phone`,`website`,`passhash`,`geripass`,`created`,`ip`,`lastlogin`,`lastip`,
	`approved`,`blocked`,`ulevel`,`last_visit`) VALUES 
	('$username','$fname','$lname','$email','$phone','$web','$passhash',
	'$geripass','$created','$ip','$created','$ip',0,0,1,'$created')";
	$res = query($q);
	if(is_resource($res)) {
		return true;
	}
	else {
		return false;
	}
}

//admin functions
function get_unapp() {
	$query = "SELECT * FROM `users` WHERE `approved`=0";
	$row = query($query);
	if(numrows($row) > 0) {
		return $row;
	}
	else {
		return false;
	}
}

function get_all() {
	$query = "SELECT * FROM `users` WHERE `ulevel`=1";
	$row = query($query);
	if(numrows($row) > 0) {
		return $row;
	}
	else {
		return false;
	}
}


function logout() {
	//if(isset($_SESSION['userid'])) {
	//update('last_visit',timenow(),$_SESSION['userid']);
	//}
	setcookie('luser','',1);
	setcookie('luserid','',1);
	session_destroy();
	session_start();
	$_SESSION['logged_in'] = 0;
	$_SESSION['luser'] = 'guest';
	$_SESSION['userid'] = 0;
	$_SESSION['userAgent'] = passhash(c4db($_SERVER['HTTP_USER_AGENT']));
	$_SESSION['ulevel'] = 0;
	session_regenerate_id();
	$_SESSION['sessionid'] = session_id();
	header('Location: index.php?action=logout');
}


?>