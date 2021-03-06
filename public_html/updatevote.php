<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	session_start();
	
	
	$query = $_POST['q'];
	
	if(substr($query,0,1) == 'n')
	{
		$voteType = 'notalone';
		$id = intval(substr($query,2,strlen($query)));
	}
	else if(substr($query,0,1) == 'a')
	{
		$voteType = 'alone';
		$id = intval(substr($query,1,strlen($query)));
	}
	else
	{
		die();
	}
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare('SELECT alone FROM related WHERE pid = (?) AND uid = (?)'))
	{	
		$statement->bind_param('ii',$id,$_SESSION['id']);	
		$statement->execute();
		
		$alone = false;
			
		$statement->store_result();
		$statement->bind_result($alone);
		$result = $statement->fetch();
		
		if(($alone !== false))
		{
			if(($alone == 0 && $voteType == 'notalone') || ($alone == 1 && $voteType == 'alone'))
				die();
		}
	}
	
	if($statement = $connection->prepare('UPDATE submissions SET '.$voteType.' = '.$voteType.' + 1 WHERE id = (?)'))
	{	
		$statement->bind_param('i',$id);	
		$statement->execute();
		
		if($statement = $connection->prepare('INSERT INTO related (pid, uid) VALUES (?,?)'))
		{	
			$statement->bind_param('ii',$id,$_SESSION['id']);	
			$statement->execute();
			echo '1';
		}	
	}	
?>