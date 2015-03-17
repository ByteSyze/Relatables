<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
	require $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$uid = $_SESSION['recover_uid'];
	
	if(!$uid)
	{
		$_SESSION['error_msg'] = 'You have not requested recovery.';
		header('Location: /');
		
		die();
	}
	
	$password = $_POST['p'];
	$password_verification = $_POST['rp'];
	
	if(strlen($password) < 6)
	{
		$_SESSION['error_msg'] = 'Password must be atleast 6 characters long.';
		
		header('Location: /recover/');
	}
	else if($password !== $password_verification)
	{
		$_SESSION['error_msg'] = 'Password verification doesn\'t match.';
		
		header('Location: /recover/');
	}
	else
	{
		//Everything is good! Reset their password.
		$user = new User($uid);
		
		$user->setPassword($password);
		$user->update();
		
		$_SESSION['id'] = $uid; //Automatically login the user.
		unset($_SESSION['recover_uid']);
		
		$_SESSION['popup_msg'] = 'Password successfully reset.';
		header('Location: /');
	}
	
?>