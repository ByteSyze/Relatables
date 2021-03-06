<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	if(!$_SESSION['id']) die('Not logged in.');
	
	$vote 	 = $_POST['v'];
	$cid	 = $_POST['c'];
	$rescind = $_POST['r'];
		
	$connection = GlobalUtils::getConnection();
	
	if($rescind == "true")
	{
		if($statement = $connection->prepare('DELETE FROM comment_ratings WHERE cid=(?) AND uid=(?)'))
		{
			$statement->bind_param('ii', $cid, $_SESSION['id']);
			$statement->execute();
			
			echo $statement->affected_rows;
		}
	}
	else
	{
		if($vote == 'up')
			$vote = 1;
		else
			$vote = -1;
		
		if($statement = $connection->prepare("INSERT INTO comment_ratings SET uid={$_SESSION['id']}, cid=(?), vote=(?) ON DUPLICATE KEY UPDATE vote=(?)"))
		{
			$statement->bind_param('iii', $cid, $vote, $vote);
			$statement->execute();
			
			echo $statement->affected_rows;
		}
	}
