<?php
	/*Copyright (C) Tyler Hackett 2014*/

	session_start();
	
	$user = $_GET['username'];
	
	if($user == $_SESSION['username'])
	{
		die('Same username');
	}
	if(!preg_match("/^[A-Za-z0-9_]+$/",$user)) // Check that username only contains alphanumerics and underscore at most
	{
		die('bad username'); // if username has wacky characters, die for now.
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
				die("username taken.");
			}
		}
	}
	
	include('userinfo.php');
	
	setUsername($user,$_SESSION['id']);
	$_SESSION['username'] = $user;
	
	header('Location: http://www.relatables.com/settings/profile');