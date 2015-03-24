<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	
	$type = $_POST['t'];
	
	if($type == 'account')
	{
	
		$user = $_POST['username'];
		$userLen = strLen($user);
		
		if(($user !== null) && ($user !== ''))
		{
			if(($userLen >= 3) && ($userLen <= 16))
			{
				if(preg_match('/^[A-Za-z0-9_]+$/',$user)) // Check that username only contains alphanumerics and underscore at most
				{
					if((!(strcasecmp($user,$_SESSION['username']) == 0)) && ($user !== $_SESSION['username']))
					{
						$connection = GlobalUtils::getConnection();

						if($statement = $connection->prepare('SELECT username FROM accounts WHERE username LIKE (?)'))
						{
							$statement->bind_param('s',$user);
							
							$statement->execute();
							
							$statement->bind_result($dbUser);
							$result = $statement->fetch();
							
							if(empty($result))
							{		
								GlobalUtils::$user->setUsername($user);
							}
						}
					}
					
					
				}
			}
		}
		
		$old_pass = $_POST['oldpassword'];
		
		$new_pass = 	$_POST['newpassword'];
		$re_new_pass = 	$_POST['renewpassword'];
		
		if((($old_pass !== null) && ($old_pass !== '')) && (($new_pass !== null) && ($new_pass !== '')) && (($re_new_pass !== null) && ($re_new_pass !== '')))
		{
			if(strlen($new_pass) >= 6)
			{
				if($new_pass === $re_new_pass)
				{		
					if(password_verify($old_pass, GlobalUtils::$user->getPassword()))
					{
						GlobalUtils::$user->setPassword($new_pass);			
					}
				}
			}
		}
		
		$email = $_POST['email'];
			
		if(($email !== null) && ($email !== ''))
		{	
			
			if(filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$connection = getConnection();
				
				if($statement = $connection->prepare("SELECT 1 FROM accounts WHERE email LIKE (?)"))
				{
					$statement->bind_param("s",$email);
					
					$statement->execute();
					
					$result = $statement->fetch();
					
					if(empty($result))
					{
						GlobalUtils::$user->setPendingEmail($email,$_SESSION['id']);
				
						$data = getPasswordAndSalt($_SESSION['id']); //TODO generation a verification code.
						
						$subject = 'Email Verification';
						$body = 'Hey ' . $_SESSION['username'] . ",\n\nYou are receiving this email because you have requested an email change.\n\nPlease click the link below to verify your new email.\n/verify?i=". $_SESSION['id'] .'&v=' . md5($_SESSION['id'] . $data['hash'] . $email) . "\n\nIf you didn't request this change, please ignore this message.";
						 
						GlobalUtils::$user->email($subject,$body);
					}
				}	
			}
		}
		
		GlobalUtils::$user->update();
		header('Location: /settings/account');
	}	
	else if($type == 'profile')
	{
	
		$description = $_POST['description'];
			
		$length = strlen($description);
		
		if($length > 130)
			header('Location: /settings/profile?e=0&i=0');
		
		GlobalUtils::$user->setDescription($description, $_SESSION['id']);
			
		$country_id = intval($_POST['location']);
	
		if($country_id >= 1 && $country_id <= 250)
			GlobalUtils::$user->setCountryId($country_id);
			
		GlobalUtils::$user->update();
		header('Location: /settings/profile');
	}
	else if($type == 'delete')
	{
		GlobalUtils::$user->delete();  //TODO make sure the user wasn't somehow tricked into coming here.
		
		session_unset();
		
		$expire = -60*60*24*365*5;
		
		setcookie("rrmp",0,$expire);
		setcookie("rrmi",0,$expire);
		
		header('Location: /');
	}
	