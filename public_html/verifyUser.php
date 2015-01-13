<?php
	/*Copyright (C) Tyler Hackett 2014*/
	// verifyUser.php will check if a username is taken.
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	if(!isset($_POST["username"]))
		die("user too short");
	
	$connection = GlobalUtils::getConnection();
	
	$user = $_POST["username"];

	if($statement = $connection->prepare("SELECT 1 FROM accounts WHERE username LIKE (?)"))
	{
		$statement->bind_param("s",$user);
		
		$statement->execute();
		
		$result = $statement->fetch();
		
		if(empty($result))
		{
			die("user available");
		}
		else
		{
			die("user unavailable");
		}
	}
?>