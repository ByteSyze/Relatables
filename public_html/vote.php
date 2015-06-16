<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$id 			= $_POST['q'];	//Submission ID.
	$voteTypeNum 	= $_POST['vtn'];//Numerical representation of voting option.
	$verification 	= $_POST['v'];	//Submission's verification ID.
	$unvote			= $_POST['u'];  //True if user is actually rescinding their vote.
	
	$connection = GlobalUtils::getConnection();
	
	if($unvote)
	{
		if($statement = $connection->prepare('DELETE FROM related WHERE pid=(?) AND uid=(?)'))
		{
			$statement->bind_param('ii', $id, $_SESSION['id']);
			$statement->execute();
			
			echo $statement->affected_rows;
		}
	}
	else
	{
		//Check verification
		if($statement = $connection->prepare('SELECT 1 FROM submissions WHERE id=(?) AND verification=(?)'))
		{	
			$statement->bind_param('is',$id,$verification);	
			$statement->execute();
				
			$statement->store_result();
			$result = $statement->fetch();
			
			if(empty($result))
				die('No matching submission');
			else
			{
				$statement->free_result();
				if($statement = $connection->prepare("INSERT INTO related (pid, uid, alone) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE alone=(?)"))
				{
					
					$statement->bind_param('iiii', $id, $_SESSION['id'], $voteTypeNum, $voteTypeNum);
					$statement->execute();
					
					echo $statement->affected_rows;
				}
			}
		}
	}
?>
