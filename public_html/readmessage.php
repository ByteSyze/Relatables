<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	usleep(200000);
	
	$id = $_POST['id'];
	
	if($_SESSION['id'] === null || $id === null)
		die();
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare('SELECT 1 FROM notifications WHERE id=(?) AND uid=(?)'))
	{
		$statement->bind_param('ii', $id, $_SESSION['id']);	
		$statement->execute();
		
		$statement->store_result();
		
		$result = $statement->fetch();
		
		if(empty($result))
			die();
			
		else
		{
			if($statement = $connection->prepare('UPDATE notifications SET seen=1 WHERE id=(?)'))
			{
				$statement->bind_param('i',$id);	
				$statement->execute();
				
				die('1');
			}
		}
	}
?>