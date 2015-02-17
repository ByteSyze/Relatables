<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$email 	= $_GET['e']; //TODO change back to POST once testing is over.
	
	$verification = $_GET['v'];
	
	$connection = GlobalUtils::getConnection();
	
	if($email)   //If an email is provided, send a verification link to it.
	{
		if($statement = $connection->prepare('SELECT verification, id FROM accounts WHERE email LIKE (?)'))
		{
			$statement->bind_param('s', $email);
			$statement->execute();
			
			$statement->bind_result($verification, $uid);
			
			if($uid) //Assure the user exists
			{
				$user = new User($uid);
				
				$body = "Hey " . $user->getUsername() . ",\n\nYou are receiving this email because a request for password recovery has been made.\n\nPlease goto the link below to reset your password.\nhttp://www.relatablez.com/recover.php?v=$verification \n\nIf you didn't request password recovery, ignore this message.\n\nThanks,\nThe Relatablez Team";
			}
		}
	}
	else if($verification) //If verification is provided, attempt to verify the password recovery and send user to the password reset page.
	{
		if($statement = $connection->prepare('SELECT id FROM accounts WHERE verification=(?)'))
		{
			$statement->bind_param('s', $verification);
			$statement->execute();
			
			$statement->bind_result($uid);
			
			if(!$uid)
			{
				$_SESSION['popup_msg'] = 'Invalid verification.'; //TODO do something with this
				header('Location: http://www.relatablez.com/');
			}
			else
			{
				$_SESSION['recover_uid'] = $uid;
				header('Location: http://www.relatablez.com/recover/');
			}
		}
	}
	