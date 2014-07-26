<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	session_start();
	
	$query = $_POST['q'];
	$verification = $_POST['v'];
	
	if(substr($query,0,1) == 'n')
	{
		$voteType = 'notalone';
		$voteTypeNum = 0;
		
		$id = intval(substr($query,2,strlen($query)));
	}
	else if(substr($query,0,1) == 'a')
	{
		$voteType = 'alone';
		$voteTypeNum = 1;
		
		$id = intval(substr($query,1,strlen($query)));
	}
	else
	{
		die('Invalid');
	}
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	//Check verification
	if($statement = $connection->prepare('SELECT 1 FROM submissions WHERE id=(?) AND verification=(?)'))
	{	
		$statement->bind_param('is',$id,$verification);	
		$statement->execute();
			
		$statement->store_result();
		$result = $statement->fetch();
		
		if(empty($result))
			die('No matching submission');
	}
	
	//Check if user is trying to re-vote
	if($statement = $connection->prepare('SELECT alone, 1 FROM related WHERE pid = (?) AND uid = (?)'))
	{	
		$statement->bind_param('ii',$id,$_SESSION['id']);	
		$statement->execute();
		
		$alreadyVoted = false;
		$alone = false;
			
		$statement->store_result();
		$statement->bind_result($alone, $alreadyVoted);
		$result = $statement->fetch();
		
		if($alone && ($voteType == 'alone'))
			die('Revote');
		if(!$alone && ($voteType == 'notalone'))
			die('Revote');
	}
	else
		echo mysqli_error($connection);
	
	if($statement = $connection->prepare('UPDATE submissions SET '.$voteType.' = '.$voteType.' + 1 WHERE id = (?)'))
	{	
		$statement->bind_param('i',$id);	
		$statement->execute();
		
		if($alreadyVoted)
		{
			if($statement = $connection->prepare('UPDATE related SET alone=(?) WHERE pid=(?) AND uid=(?)'))
			{	
				$statement->bind_param('iii',$voteTypeNum, $id, $_SESSION['id']);	
				$statement->execute();
				
				if($voteTypeNum == 1)
					die('00');
				else
					die('01');
			}	
		}
		else
		{
			if($statement = $connection->prepare('INSERT INTO related (pid, uid, alone) VALUES (?,?,?)'))
			{	
				$statement->bind_param('iii',$id,$_SESSION['id'],$voteTypeNum);	
				$statement->execute();
				
				if($voteTypeNum == 1)
					die('10');
				else
					die('11');
			}	
		}
	}	
	else
		echo mysqli_error($connection);
?>