<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	
	if(!isset($_GET["i"]))
		die("No id specified");
	else if(!isset($_GET["v"]))
		die("No authentication set");
		
	$id = intval($_GET["i"]);
	$verification = $_GET["v"];
	
	$connection = GlobalUtils::getConnection();
	
	$user = new User($id);
	
	if($user->getPending()) //Ensure user is pending
	{
		if(password_verify($verification, $user->getVerification())) //Verify
		{
			$user->setPending(false);   // Verified! set user to no longer pending, give them a cookie, whatever.
			$user->setVerification('');
			
			$user->update();
			
			$_SESSION['id'] = $user->getID();
			$_SESSION['popup_msg'] = '<h1>Verification successful!</h1><br>Welcome, ' . $user->getUsername(); // Take advantage of the fact that we loaded in the entire user.
		}
		else
		{
			$_SESSION['error_msg'] = 'Invalid verification.';
		}
	}
	else
	{
		$_SESSION['error_msg'] = 'Your account is already verified!';
	}
	
	header("Location: /");
	