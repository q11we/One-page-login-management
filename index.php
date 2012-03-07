<?php
$title = 'Googler';
include_once 'inc/header.php';
$error = array();
$post = array();
$get = array();


if(isset($_POST)) {
	foreach ($_POST as $key=>$value) {
		$key = c4db($key); $value = c4db($value);
		$post[$key] = $value;
	}
}

if(isset($_GET)) {
	foreach ($_GET as $key=>$value) {
		$key = c4db($key); $value = c4db($value);
		$get[$key] = $value;
	}
}


//Check for user info updates
if(isset($post['usubmit'])&&$post['usubmit']=='Update') {
$pass=0;
//check if all required fields are filled in
if(!isset($post['fname'])||!isset($post['lname'])||
!isset($post['email'])) {
	$error[0] = 'Please fill Required(First name, last name,
	email) fields';
}
else {
	//check name
	if(!name_length($post['fname'])||!name_length($post['lname'])) {
		if(!name_validator($post['fname'])||!name_validator($post['lname'])) {
		 $error['name'] = $errors['name']['char'];
		}
	}
	else {
	  $error['name']=$errors['name'][username_length($post['uname'])];
	}
	//check email
	if(!name_length($post['email'])) {
		if(!email_validator($post['email'])) {
		 $error['email'] = $errors['email']['char'];
		}
	}
	else {
	  $error['email']=$errors['email'][username_length($post['email'])];
	}
	
	//check password
	if(isset($post['upass'])&&$post['upass']=='yes') {
	$pass=1;
		if(!name_length($post['oldpassword'])) {
			$row = user_exists($_SESSION['luser']);
			if(is_resource($row)) {
				$res = fetch($row);
				if(passhash($post['oldpassword'])!=$res['passhash']) {
					$error['pass'] = $errors['login']['password'];
				}
				else {
				  if(!isset($post['newpassword'])||!isset($post['newpassword2'])){
					$error['pass'] = $errors['password']['empty'];
				  }
				  else if($post['newpassword']!=$post['newpassword2']) {
					$error['pass'] = $errors['password']['match'];
				  }
				}
			}
			else {logout();}
		}
		else {
	     $error['pass']=$errors['password'][username_length($post['oldpassword'])];
		}
	}
}
if(empty($error)) {
    $fname = $post['fname'];
	$lname = $post['lname'];   $email = $post['email'];
	$website = $post['website'];$phone = $post['phone'];
	$passw='';
	if($pass) {$passw=$post['newpassword'];}
	updatedata($fname,$lname,$email,$website,$phone,$passw,$_SESSION['luser']);
	header('Location: index.php?url=home');
}
else {

}

}

//check if signed in
if(isset($post['signin'])&&$post['signin']=='Signin') {
//check if all required fields are filled in
if(!isset($post['loginusername'])||!isset($post['loginpassword'])||
$post['loginusername']==''||$post['loginpassword']=='') {
	$error[0] = 'Please fill in all fields';
}
else {
	//check username
	if(!username_length($post['loginusername'])) {
		if(!username_validator($post['loginusername'])) {
		 $error['name'] = $errors['name']['char'];
		}
		else {
			$row = user_exists($post['loginusername']);
			if(!is_resource($row)) {
			  $error['username'] = $errors['login']['user'];
			}
			else {
				$res = fetch($row);
				if(!$res['approved']) {
					$error['approved'] = "User unapproved";
				}
				else {
					$pass = $post['loginpassword'];
					if($res['passhash']!=passhash($pass)) {
						$error['password'] = $errors['login']['password'];
					}
				}
			}
		}
	}
	else {
	  $error['username']=$errors['username'][username_length($post['loginusername'])];
	}
	//check password
	if(!name_length($post['loginpassword'])) {
	}
	else {
	  $error['password']=$errors['password'][username_length($post['loginpassword'])];
	}	
}
if(empty($error)) {
//go to login
	$user = $post['loginusername'];
	$pass = $post['loginpassword'];
	if(login($user,$pass)) {
	header('Location:index.php?url=home');
	}
	else {
		header('Location:index.php?url=login');
	}
}
else {

}

}

//check for register
if(isset($post['rsubmit'])&&$post['rsubmit']=='Register') {
//check if all required fields are filled in
if(!isset($post['uname'])||!isset($post['fname'])||!isset($post['lname'])||
!isset($post['email'])||!isset($post['newpassword'])) {
	$error['all'] = 'Please fill Required(username, first name, last name,
	email,password) fields';
}
else {
	//check username
	if(!username_length($post['uname'])) {
	  $row = user_exists($post['uname']);
	  if(!is_resource($row)) {
		if(!username_validator($post['uname'])) {
		 $error['username'] = $errors['username']['char'];
		}
	  }
	  else {
		$error['username'] = $errors['username']['dub'];
	  }
	}
	else {
	  $error['username']=$errors['username'][username_length($post['uname'])];
	}
	//check name
	if(!name_length($post['fname'])||!name_length($post['lname'])) {
		if(!name_validator($post['fname'])||!name_validator($post['lname'])) {
		 $error['name'] = $errors['name']['char'];
		}
	}
	else {
	  $index=(username_length($post['lname'])==0)?
	  username_length($post['lname']):username_length($post['fname']);
	  $error['name']=$errors['name'][$index];
	}
	//check email
	if(!name_length($post['email'])) {
		if(!email_validator($post['email'])) {
		 $error['email'] = $errors['email']['char'];
		}
	}
	else {
	  $error['email']=$errors['email'][username_length($post['email'])];
	}
	
	//check password
		if(!name_length($post['newpassword'])) {
			if(!isset($post['newpassword'])||!isset($post['newpassword'])){
					$error['pass'] = $errors['password']['empty'];
				  }
			else if($post['newpassword']!=$post['newpassword']) {
					$error['pass'] = $errors['password']['match'];
				  }
		}
		else {
	     $error['pass']=$errors['password'][username_length($post['newpassword'])];
		}
	
}
if(empty($error)) {
    $uname = $post['uname'];   $fname = $post['fname'];
	$lname = $post['lname'];   $email = $post['email'];
	$website = $post['website'];$phone = $post['phone'];
	$pass = $post['newpassword'];
	register($uname,$pass,$fname,$lname,$email,$phone,$website);
	header('Location: index.php?url=home');
}
else {

}

}

//
$links = get_links($_SESSION['ulevel']);
$lo = array('Logout'=>'?url=logout');
$rg = array('Register'=>'?url=register');
$float = ($_SESSION['ulevel'] > 0)?$lo:$rg;

?>
<body>
<?php
if(!isset($get['url'])) {$get['url']='home';}
$ac='';
if($get['url']=='login') {$ac='login';}
else if($_SESSION['ulevel'] == 9) {$ac='admin';}
if($get['url']=='logout') {logout();}

?>
<div id="<?php if($_SESSION['ulevel']==9) {echo "a";}?>all-container">
	<div id="top">
		<h1>Simple Login Management System</h1>
	</div>
	<div id="header">
		<ul id="header-list">
		<?php foreach ($links as $key=>$value) {
		if(strtolower($get['url'])!=strtolower($key)) {
		echo "<li><a href='$value'>$key</a></li>";
		}
		else {
		echo "<li><a href='#'>$key</a></li>";
		}
		}
		?>
			<li style="float:right;">
			<?php foreach ($float as $key=>$value) {
			echo "<a href='$value'>$key</a>";
			}?>
			</li>
		</ul>
	</div>
	<div id="<?php echo $ac;?>content">
		<?php 
		if($get['url']=='home') {
		
		?>
	  <h2>Willkommen Usar</h2>
	  <p>This is a simple (very simple) login management system.
		contact me at <a href="mailto:issizcilimkes@yahoo.com">
		issizcilimkes@yahoo.com</a> for source code or any questions.
		<span style="color:red;">Please do spam. :) </span>If you're a user
		login, or if you want to register, you can register too.
		Feel free to use the service.</p>
		<p class="copyright">copyright &copy; I&amp;G 2012</p>
		<?php 
		}

		else if($get['url']=='account'&&$_SESSION['ulevel']>0) {
		  $row = user_exists($_SESSION['luser']);
		  if(is_resource($row)) {
		  $res = fetch($row);
		?>
		<table>
			<!--<tr><th>Key</th><th></th></tr>-->
			<tr><td class="first">Username:</td>
			<td><?php echo $res['username'];?></td></tr>
			<tr><td class="first">First name:</td>
			<td><?php echo $res['firstname'];?></td></tr>
			<tr><td class="first">Last Name:</td>
			<td><?php echo $res['lastname'];?></td></tr>
			<tr><td class="first">Email:</td>
			<td><?php echo $res['email'];?></td></tr>
			<tr><td class="first">Phone number:</td>
			<td><?php echo $res['phone'];?></td></tr>
			<tr><td class="first">Website:</td>
			<td><a><?php echo $res['website'];?></a></td></tr>
		</table>
		<?php
		  }
		  else {
			echo "SESSION error. Logging out"; logout();
		  }
		}
		
		else if($get['url']=='account'&&$_SESSION['ulevel']==0) {
		header('Location: index.php?url=home');}
		
		else if($get['url']=='update'&&$_SESSION['ulevel']>0) {
		  $row = user_exists($_SESSION['luser']);
		  if(is_resource($row)) {
		  $res = fetch($row);
		?>
		<form action="index.php?url=update" method="post" name="edit-update">
		<table>
			<tr><th></th><th></th><th></th></tr>
			<tr><td class="first">Username:</td>
				<td><?php echo $res['username'];?></td></tr>
			<tr><td class="first">First name:</td>
				<td><input type="text" name="fname" 
				value="<?php echo $res['firstname'];?>"/></td></tr>
			<tr><td class="first">Last Name:</td>
				<td><input type="text" name="lname" 
				value="<?php echo $res['lastname'];?>"/></td></tr>
			<tr><td class="first">Email:</td>
				<td><input type="text" name="email" 
				value="<?php echo $res['email'];?>"/></td></tr>
			<tr><td class="first">Phone number(optional):</td>
				<td><input type="text" name="phone" 
				value="<?php echo $res['phone'];?>"/></td></tr>
			<tr><td class="first">Website:</td>
				<td><input type="text" name="website" 
				value="<?php echo $res['website'];?>"/></td></tr>
			<tr><td class="first" rowspan="3">Update Password:<br />
			<label for="upass">(Check to update):</label>
			<input type="checkbox" name='upass' id="upass" value='yes' /></td>
			<td><label for="oldpassword">Old password:</label><br />
				<input type="password" name="oldpassword" /></td></tr>
			<tr><td><label for="newpassword">New password:</label><br/>
			<input type="password" name="newpassword" /></td></tr>
			<tr><td><label for="oldpassword">Again new password:</label><br />
			<input type="password" name="newpassword2" /></td></tr>
			<tr><td class=""><input class="bt" type="submit" value="Update" 
			name="usubmit" /></td>
				<td><input class="bt" type="reset" value="Clear" name="clear" />
					<input class="bt" type="button" value="cancel" name="cancel" 
				onclick="Location.href='?url=home'"/></td></tr>
		</table>
	  </form>
	  <?php
		  }
		  else {
			echo "SESSION error. Logging out"; logout();
		  }
		if(!empty($error)) 
			{
		?>
	  <ul id='err'>
		<?php
			foreach($error as $value) {
				echo "<li>$value</li>";
			}
			?>
		</ul>
	  <?php
			}
		}
		
		else if($get['url']=='update'&&$_SESSION['ulevel']==0) {
		header('Location: index.php?url=home');}
		
		else if ($get['url']=='login'&&$_SESSION['ulevel']==0) {
		?>
		<form action="index.php?url=login" method="post" name="userlogin">
		<label for="loginusername">Username:
			<input type="text" name="loginusername" value=""/></label>
		<label for="loginpassword">Password:&nbsp;
			<input type="password" name="loginpassword" value=""/></label>
			<input class="bt" type="submit" 
					value="Signin" name="signin" />
				<input class="bt" type="reset" value="Clear" name="clear" />
	  </form>
	  <?php
			if(!empty($error)) 
			{
		?>
	  <ul id='err'>
		<?php
			foreach($error as $value) {
				echo "<li>$value</li>";
			}
			?>
		</ul>
	  <?php
			}
	  }
	  else if($get['url']=='register'&&$_SESSION['ulevel']==0) {
	  
	  ?>
	  <form action="index.php?url=register" method="post" name="register">
		<table>
			<tr><th></th><th></th><th></th></tr>
			<tr><td class="first">Username:</td>
				<td><input type="text" name="uname" 
				value="<?php echo p($post,'uname');?>"/></td></tr>
			<tr><td class="first">First name:</td>
				<td><input type="text" name="fname" 
				value="<?php echo p($post,'fname');?>"/></td></tr>
			<tr><td class="first">Last Name:</td>
				<td><input type="text" name="lname" 
				value="<?php echo p($post,'lname');?>"/></td></tr>
			<tr><td class="first">Email:</td>
				<td><input type="text" name="email" 
				value="<?php echo p($post,'email');?>"/></td></tr>
			<tr><td class="first">Phone number(optional):</td>
				<td><input type="text" name="phone" 
				value="<?php echo p($post,'phone');?>"/></td></tr>
			<tr><td class="first">Website:</td>
				<td><input type="text" name="website" 
				value="<?php echo p($post,'website');?>"/></td></tr>
			<tr><td class="first">New password:</td><td>
			<input type="password" name="newpassword" 
			value="<?php echo p($post,'newpassword');?>"/></td></tr>
			<tr><td class="first">Again new password:</td><td>
			<input type="password" name="newpassword2" 
			value="<?php echo p($post,'newpassword2');?>"/></td></tr>
			<tr><td class=""><input class="bt" type="submit" value="Register" 
			name="rsubmit" /></td>
				<td><input class="bt" type="reset" value="Clear" name="clear" />
					<input class="bt" type="button" value="cancel" name="cancel" 
				onclick="Location.href='?url=home'"/></td></tr>
		</table>
	  </form>
	  <?php
			if(!empty($error)) 
			{
		?>
	  <ul id='err'>
		<?php
			foreach($error as $value) {
				echo "<li>$value</li>";
			}
			?>
		</ul>
	  <?php
			}	
	   }
	   
	   else if($get['url']=='register'&&$_SESSION['ulevel']>0) {
		header('Location: index.php?url=home');
	   }
	else if($_SESSION['ulevel']==9&&($get['url']=='new'||$get['url']=='all')) {
	
if(isset($post['approve'])&&$post['approve']=='Approve'&&
isset($get['action'])&&$get['action']=='approval'&&isset($get['userid'])) {
	approve($get['userid']);
	header('Location: index.php?url=home');
}
if(isset($post['reject'])&&$post['reject']=='Reject'&&
isset($get['action'])&&$get['action']=='approval'&&isset($get['userid'])) {
	reject($get['userid']);
}
?>
	  <div id="newusers">
			<ul>
				<?php
					$useron = 0;
					if(isset($get['userid'])) 
					{ $useron = $get['userid'];}
					$row = ($get['url']=='new')?get_unapp():get_all();
					if(is_resource($row)) {
						while($res = fetch($row)) {
						$cl = ($useron==$res['id'])?'on':'';
						echo "<li class='$cl'><a href='?url=".$get['url'].
						"&userid=".$res['id']."'>".$res['firstname']." ".
						$res['lastname']."</a></li>";
						}
					}
				
				?>
			</ul>
	  </div>
	  <div id="theuser">
		<?php 
		if($useron!=0&&is_resource(user_exists_id($useron))) { 
		$res = fetch(user_exists_id($useron));
		?>
		<form name="userapproval" action="index.php?action=approval&userid=
		<?php echo $res['id'];?>" 
		method="post">
			<table>
			<tr><th></th><th></th><th></th></tr>
			<tr><td class="first">Username:</td>
				<td><?php echo $res['username'];?></td></tr>
			<tr><td class="first">First name:</td>
				<td><?php echo $res['firstname'];?></td></tr>
			<tr><td class="first">Last Name:</td>
				<td><?php echo $res['lastname'];?></td></tr>
			<tr><td class="first">Email:</td>
				<td><?php echo $res['email'];?></td></tr>
			<tr><td class="first">Phone number(optional):</td>
				<td><?php echo $res['phone'];?></td></tr>
			<tr><td class="first">Website:</td>
				<td><?php echo $res['website'];?></td></tr>
			
			<tr><td class=""><input class="bt" type="submit" value="Approve"
			name="approve" /></td>
			<td class=""><input class="bt" type="submit" value="Reject"
			name="reject" /></td>
			</tr>
			</table>
		</form>
		<?php
		}
		else {
		?>
		<h2>No users yet</h2>
		<?php }?>
	  </div>
	</div>
</div>
<?php
}
?>

</body>