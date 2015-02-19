<?php
	require $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	require $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$username = $_GET['username'];
	$pass = $_GET['password'];
	
	$user = new User(User::getIDFromUsername($username));
	$user->setPassword($pass, false);
	$user->update();
	
	