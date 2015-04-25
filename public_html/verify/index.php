<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
	
	if(!isset($_GET["i"]))
		die("No id specified");
	else if(!isset($_GET["v"]))
		die("No authentication set");
		
	$id = intval($_GET["i"]);
	$verification = $_GET["v"];
	
	$connection = GlobalUtils::getConnection();
	
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
				
				mysqli_query($connection, "UPDATE accounts SET pending_email=NULL WHERE id=".$id);
				
			}
		}
		
		header("Location: /");
	}

?>