<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	
	$password = $_POST['p'];
	
	if(password_verify($password, GlobalUtils::$user->getPassword()))
		echo '0';
	else
		echo '1';
?>