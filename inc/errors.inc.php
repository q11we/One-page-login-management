<?php
$errors  = array(
"username"=>array(),
"name"=>array(),
"email"=>array(),
"password"=>array(),
"login"=>array()
);
$errors['username']['long'] = "Username too long. Maximum 39 characters";
$errors['username']['dub'] = "Username already exists.";
$errors['username']['short'] = "Username too short. Minimum 5 characters";
$errors['username']['char'] = "Username includes illegal character";
$errors['name']['long'] = "Name too long. Maximum 39 characters";
$errors['name']['short'] = "Name too short. Minimum 5 characters";
$errors['name']['char'] = "Name includes illegal character";
$errors['password']['long'] = "Password too long. Maximum 39 characters";
$errors['password']['short'] = "Password too short. Minimum 5 characters";
$errors['password']['match'] = "Passwords don't match";
$errors['password']['empty'] = "Password field empty";
$errors['login']['password'] = "Wrong password, try again!";
$errors['login']['user'] = "No such user";
$errors['email']['long'] = "Email too long. Maximum 40 characters";
$errors['email']['short'] = "Email too short. Minimum 9 characters";
$errors['email']['char'] = "Email includes illegal character";

?>