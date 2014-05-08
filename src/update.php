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
		if(!preg_match("/^[A-Za-z0-9_]+$/",$user)) // Check that username only contains alphanumerics and underscore at most
		{
			header('Location: http://www.relatablez.com/settings/account?e=Username%20must%20only%20contain%20letters,%20numbers,%20and/or%20underscores');
			return;
		}
		if(!(strcasecmp($user,$_SESSION['username']) == 0))
		{
			$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");

			if($statement = $connection->prepare("SELECT username FROM accounts WHERE username LIKE (?)"))
			{
				$statement->bind_param("s",$user);
				
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
			header('Location: http://www.relatablez.com/settings/profile?e=Description%20must%20be%20under%20130%20characters');
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
			header('Location: http://www.relatablez.com/settings/profile?e=Invalid%20location%20provided');
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
			header('Location: http://www.relatablez.com/settings/account?e=Password%20verification%20didn\'t%20match');
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
			header('Location: http://www.relatablez.com/settings/profile?e=Incorrect%20password%20provided');
			return;
		}	
	}
	