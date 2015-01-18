<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$id 			= $_POST['q'];	//Submission ID.
	$voteTypeNum 	= $_POST['vtn'];//Numerical representation of voting option.
	$verification 	= $_POST['v'];	//Sumbission's verification ID.
	
	if($voteTypeNum)
		$voteType = 'alone';
	else
		$voteType = 'notalone';
	
	$connection = GlobalUtils::getConnection();
	
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
				{
					$statement = $connection->prepare('UPDATE submissions SET notalone=notalone-1 WHERE id=(?)');
					$statement->bind_param('i',$id);
					$statement->execute();
					
					die('00');
				}
				else
				{
					$statement = $connection->prepare('UPDATE submissions SET alone=alone-1 WHERE id=(?)');
					$statement->bind_param('i',$id);
					$statement->execute();
					
					die('01');
				}
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