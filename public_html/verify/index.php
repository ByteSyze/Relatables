<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	include($_SERVER['DOCUMENT_ROOT'].'/userinfo.php');
	
	session_start();
	
	if(!isset($_GET["i"]))
		die("No id specified");
	else if(!isset($_GET["v"]))
		die("No authentication set");
		
	$id = $_GET["i"];
	$verification = $_GET["v"];
	
	$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
	
	if($statement = $connection->prepare("SELECT password, pending_email FROM accounts WHERE id = (?)"))
	{
		$statement->bind_param("i",$id);
		
		$statement->execute();
		
		$statement->store_result();
		$statement->bind_result($pass, $pending_email);
		$result = $statement->fetch();
		
		$resultVerification = md5($id . $pass . $pending_email);
		
		if($verification == $resultVerification)
		{
			if($statement = $connection->prepare("UPDATE accounts SET email = (?) WHERE id LIKE (?)"))
			{
				$statement->bind_param("si",$pending_email, $id);
				
				$statement->execute();
				
				$_SESSION['id'] = $id;
				$_SESSION['username'] = getUsername($id);
				
				mysqli_query($connection, "UPDATE accounts SET pending_email=NULL WHERE id=".$id);
				
				header("Location: http://www.relatablez.com/?verified=true");
			}
		}
		else
		{
			header("Location: http://www.relatablez.com/?verified=false");
		}
	}

?>