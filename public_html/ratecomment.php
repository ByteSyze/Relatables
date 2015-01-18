<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	if($_SESSION['username'] == null) die('Not logged in.');
	
	$vote 	= $_POST['v'];
	$cid	= $_POST['c'];
	
	if($vote == 'up')
		$vote = 1;
	else
		$vote = -1;
	
	$connection = GlobalUtils::getConnection();
	
	if($statement = $connection->prepare("INSERT INTO comment_ratings SET uid={$_SESSION['id']}, cid=(?), vote=(?) ON DUPLICATE KEY UPDATE vote=(?)"))
	{
		$statement->bind_param('iii', $cid, $vote, $vote);
		$statement->execute();
		
		echo $statement->affected_rows;
	}
