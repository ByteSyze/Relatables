<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	
	$email 	= $_GET['e']; //TODO change back to POST once testing is over.
	
	$verification = $_GET['v'];
	
	$connection = GlobalUtils::getConnection();
	
	if($email)   //If an email is provided, send a verification link to it.
	{
		if($statement = $connection->prepare('SELECT id FROM accounts WHERE email LIKE (?)'))
		{
			$statement->bind_param('s', $email);
			$statement->execute();
			
			$statement->bind_result($uid);
			$statement->fetch();
			
			if($uid) //Assure the user exists
			{
				$user = new User($uid);
				
				$verification 		= md5(openssl_random_pseudo_bytes(256, $crypto_strong));
				$verification_hash 	= password_hash($verification, PASSWORD_DEFAULT);
				
				$body = "Hey " . $user->getUsername() . ",\n\nYou are receiving this email because a request for password recovery has been made.\n\nPlease goto the link below to reset your password.\nhttp://www.relatablez.com/recover.php?v=$verification \n\nIf you didn't request password recovery, ignore this message.\n\nThanks,\nThe Relatablez Team";
				
				$user->setVerification($verification_hash);
				$user->update();
				
				$user->email('Password Recovery', $body);
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
				$_SESSION['error_msg'] = 'Invalid verification.'; //TODO do something with this
				header('Location: /');
			}
			else
			{
				$_SESSION['recover_uid'] = $uid;
				header('Location: /recover/');
			}
		}
	}
	else
	{
		$_SESSION['error_msg'] = "Something went wrong.";
		header('Location: /');
	}
	