<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	// PHP file for handling registration of new users. To ensure good security and integrity,
	// this file will do all the same checks that any client side checks do. This will assure
	// no data that has been altered from client to server via bypassing clientside checks
	// will make it through to our database.
	
	// The only checks this WON'T worry about are the users' re-entered password and email address,
	// because A) it will reduce the time registration takes (however negligible that time may be)
	// and B) if the user was messing with our code that makes sure they entered correct info, they're
	// stupid and gonna get what they deserve. End of.
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	
	
	if(!isset($_POST["password"]))
		echo "password not set";
	if(!isset($_POST["username"]))
		echo "user not set";
	if(!isset($_POST["email"]))
		echo "email not set";
	
	$user 	= 	$_POST["username"];
	$pass 	= 	$_POST["password"];
	$email	= 	$_POST["email"];
	
	$isValidCredentials = GlobalUtils::validateRegistrationCredentials($user, $email);
	
	if($isValidCredentials !== GlobalUtils::REGISTER_SUCCESS)
		die($isValidCredentials);
	
	$pass_hash = password_hash($pass, PASSWORD_DEFAULT);
	$connection = GlobalUtils::getConnection();
	
	if($statement = $connection->prepare("INSERT INTO accounts (username, password, last_login, email, pending_email) VALUES (?,?,NOW(),?,?)"))
	{
		$statement->bind_param('ssss', $user, $pass_hash, $email, $email);
		
		if($statement->execute())
		{
			$uid = $connection->insert_id;
			
			$new_user = new User($uid);							////
																//  TODO optimize this maybe.
			$verification = $new_user->generateVerification();	//
			$new_user->update();								////
		
			$from = "From: Relatablez <noreply@relatablez.com>";
			$to = $email;
			$subject = "Account Verification";
			$body = "Hello " . $user . ",\n\nThank you for signing up on Relatablez.com.\n\nTo activate your account, please goto the following link:\nhttp://www.relatablez.com/verify?i=". $uid ."&v=" . $verification;
			 
			mail($to,$subject,$body,$from);
			
			die("success");
		}
		else
			echo $statement->error;
	}
?>