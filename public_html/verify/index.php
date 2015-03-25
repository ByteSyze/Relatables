<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	include $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	
	if(!isset($_GET["i"]))
		die("No id specified");
	else if(!isset($_GET["v"]))
		die("No authentication set");
		
	$id = $_GET["i"];
	$verification = $_GET["v"];
	
	$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
	
	if($statement = $connection->prepare("SELECT verification FROM accounts WHERE id = (?)"))
	{
		$statement->bind_param("i",$id);
		
		$statement->execute();
		
		$statement->store_result();
		$statement->bind_result($db_verification);
		$result = $statement->fetch();
		
		if(password_verify($verification, $db_verification))
		{
			if($statement = $connection->prepare("UPDATE accounts SET email = pending_email WHERE id LIKE (?)"))
			{
				$statement->bind_param("i", $id);
				
				$statement->execute();
				
				$_SESSION['id'] = $id;
				$_SESSION['username'] = getUsername($id);
				
				mysqli_query($connection, "UPDATE accounts SET pending_email=NULL WHERE id=".$id);
				
				header("Location: /");
			}
		}
		else
		{
			header("Location: /");
		}
	}

?>