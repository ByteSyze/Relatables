<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$id 			= $_POST['q'];	//Submission ID.
	$voteTypeNum 	= $_POST['vtn'];//Numerical representation of voting option.
	$verification 	= $_POST['v'];	//Sumbission's verification ID.
	
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
?>