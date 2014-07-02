<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	session_start();
	
	require_once('userinfo.php');
	
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
						$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');

						if($statement = $connection->prepare('SELECT username FROM accounts WHERE username LIKE (?)'))
						{
							$statement->bind_param('s',$user);
							
							$statement->execute();
							
							$statement->bind_result($dbUser);
							$result = $statement->fetch();
							
							if(empty($result))
							{		
								setUsername($user,$_SESSION['id']);
								$_SESSION['username'] = $user;
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
					$data = getPasswordAndSalt($_SESSION['id']);
					
					$passHash = md5($old_pass.$data['salt']);
					
					if($passHash == $data['hash'])
					{
						setPassword($new_pass,$_SESSION['id']);			
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
						setPendingEmail($email,$_SESSION['id']);
				
						$data = getPasswordAndSalt($_SESSION['id']);
						
						$from = 'From: Relatablez <noreply@relatablez.com>';
						$to = $email;
						$subject = 'Email Verification';
						$body = 'Hey ' . $_SESSION['username'] . ",\n\nYou are receiving this email because you have requested an email change.\n\nPlease click the link below to verify your new email.\nhttp://www.relatablez.com/verify?i=". $_SESSION['id'] .'&v=' . md5($_SESSION['id'] . $data['hash'] . $email) . "\n\nIf you didn't request this change, please ignore this message.";
						 
						mail($to,$subject,$body,$from);
					}
				}	
			}
		}	
		header('Location: http://www.relatablez.com/settings/account');
	}	
	else if($type == 'profile')
	{
	
		$description = $_POST['description'];
			
		$length = strlen($description);
		
		if($length > 130)
			header('Location: http://www.relatablez.com/settings/profile?e=0&i=0');
		
		setDescription($description, $_SESSION['id']);
			
		$country_id = $_POST['location'];
	
		if(is_numeric($country_id) && ($country_id >= 1) && ($country_id <= 250))
			setCountry($country_id, $_SESSION['id']);
			
		header('Location: http://www.relatablez.com/settings/profile');
	}
	else if($type == 'delete')
	{
		deleteAccount($_SESSION['id']);
		
		session_unset();
		
		$expire = -60*60*24*365*5;
		
		setcookie("rrmp",0,$expire);
		setcookie("rrmi",0,$expire);
		
		header('Location: http://www.relatablez.com/');
	}
	