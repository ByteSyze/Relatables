<?php
	session_start();
	
	usleep(200000);
	
	$id = $_POST['id'];
	$vid = $_POST['vid'];
	
	if($vid === null || $id === null)
		die();
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare('SELECT vid FROM notifications WHERE id=(?)'))
	{
		$statement->bind_param('i',$id);	
		$statement->execute();
		
		$statement->store_result();
		$statement->bind_result($dbVid);
		
		$result = $statement->fetch();
		
		if(empty($result))
			die();
			
		else
		{
			if($dbVid == $vid)
			{
				if($statement = $connection->prepare('UPDATE notifications SET deleted=1 WHERE id=(?)'))
				{
					$statement->bind_param('i',$id);	
					$statement->execute();
					
					die('1');
				}
			}
		}
	}
?>