<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$email 	= $_POST['e'];
	$verification = $_GET['v'];
	
	$connection = GlobalUtils::getConnection();
	
	if($email)   //If an email is provided, send a verification link to it.
	{
		if($statement = $connection->prepare('SELECT verification, id WHERE email LIKE (?)'))
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
		
	}
	