<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
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