<?php
	// verify.php will handle all verification checks that need to be done serverside.
	
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
	if($statement = $connection->prepare("SELECT 1 FROM accounts WHERE email LIKE (?)"))
	{
		$statement->bind_param("s",$email);
		
		$statement->execute();
		
		$result = $statement->fetch();
		
		if(empty($result))
		{
			die("email available");
		}
		else
		{
			die("email unavailable");
		}
	}
?>