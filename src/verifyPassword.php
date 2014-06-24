<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();	
	include('userinfo.php');
	
	usleep(100000); //Delay execution for 100ms, as a security measure.
	
	$password = $_POST['p'];
	
	$data = getPasswordAndSalt($_SESSION['id']);
	
	$passHash = md5($password . $data['salt']);
	
	//echo $passHash . ' ' . $data['hash'];
	
	if($passHash === $data['hash'])
		echo '0';
	else
		echo '1';
?>