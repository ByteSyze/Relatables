<?php
	/*Copyright (C) Tyler Hackett 2014*/
	// verifyUser.php wil check if a username is taken.
	
	
	if(!isset($_POST["username"]))
		die("user too short");
	
	$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
	
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