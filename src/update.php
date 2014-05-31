<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	session_start();
	
	require_once('userinfo.php');
	
	$type = $_POST['t'];
	
	if($type == 'username')
	{		
		$user = $_POST['username'];
		
		if($user == $_SESSION['username'])
		{
			header('Location: http://www.relatablez.com/settings/account');
			return;
		}
		if(!preg_match('/^[A-Za-z0-9_]+$/',$user)) // Check that username only contains alphanumerics and underscore at most
		{
			header('Location: http://www.relatablez.com/settings/account?e=0&i=2');
			return;
		}
		if(!(strcasecmp($user,$_SESSION['username']) == 0))
		{
			$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');

			if($statement = $connection->prepare('SELECT username FROM accounts WHERE username LIKE (?)'))
			{
				$statement->bind_param('s',$user);
				
				$statement->execute();
				
				$statement->bind_result($dbUser);
				$result = $statement->fetch();
				
				if(!empty($result))
				{
					header('Location: http://www.relatablez.com/settings/account');
					return;
				}
			}
		}
		
		setUsername($user,$_SESSION['id']);
		$_SESSION['username'] = $user;
		
		header('Location: http://www.relatablez.com/settings/account');
	}
	else if($type == 'description')
	{
		$description = $_POST['description'];
		
		$length = strlen($description);
		
		if($length > 130)
		{
			header('Location: http://www.relatablez.com/settings/profile?e=0&i=0');
			return;
		}
		
		setDescription($description, $_SESSION['id']);
		
		header('Location: http://www.relatablez.com/settings/profile');
	}
	else if($type == 'location')
	{
		$country_id = $_POST['location'];
	
		if((!is_numeric($country_id)) || (($country_id < 1) || ($country_id > 250)))
		{
			header('Location: http://www.relatablez.com/settings/profile?e=3&i=0');
			return;
		}

		setCountry($country_id, $_SESSION['id']);

		header('Location: http://www.relatablez.com/settings/profile');
	}
	else if($type == 'password')
	{
		$old_pass = 	$_POST['oldpassword'];
		
		$new_pass = 	$_POST['newpassword'];
		$re_new_pass = 	$_POST['renewpassword'];
		
		if($new_pass != $re_new_pass)
		{
			header('Location: http://www.relatablez.com/settings/account?e=1&i=1');
			return;
		}
		
		$data = getPasswordAndSalt($_SESSION['id']);
		
		$passHash = md5($old_pass.$data['salt']);
		
		if($passHash == $data['hash'])
		{
			setPassword($new_pass,$_SESSION['id']);			
			header('Location: http://www.relatablez.com/settings/account');
		}
		else
		{
			header('Location: http://www.relatablez.com/settings/profile?e=1&i=2');
			return;
		}	
	}
	else if($type == 'email')
	{	
		$email = $_POST['email'];
		echo 'was email: ' . $email;
		
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
				header('Location: http://www.relatablez.com/settings/account?e=2&i=2');
				return;
		}
		
		$connection = getConnection();
		
		if($statement = $connection->prepare("SELECT 1 FROM accounts WHERE email LIKE (?)"))
		{
			$statement->bind_param("s",$email);
			
			$statement->execute();
			
			$result = $statement->fetch();
			
			if(!empty($result))
			{
				header('Location: http://www.relatablez.com/settings/account?e=2&i=3');
				return;
			}
		}
		
		setPendingEmail($email,$_SESSION['id']);
		
		$data = getPasswordAndSalt($_SESSION['id']);
		
		$from = 'From: Relatablez <noreply@relatablez.com>';
		$to = $email;
		$subject = 'Email Verification';
		$body = 'Hey ' . $_SESSION['username'] . ",\n\nYou are receiving this email because you have requested an email change.\n\nPlease click the link below to verify your new email.\nhttp://www.relatablez.com/verify?i=". $_SESSION['id'] .'&v=' . md5($_SESSION['id'] . $data['hash'] . $email) . "\n\nIf you didn't request this change, please ignore this message.";
		 
		mail($to,$subject,$body,$from);
		
		header('Location: http://www.relatablez.com/settings/account');
	}
	