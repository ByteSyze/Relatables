<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	if($_SESSION['username'] == null) die('Not logged in.');
	
	$vote 	= $_POST['v'];
	$cid	= $_POST['c'];
	
	if($vote == 'up')
		$vote = 1;
	else
		$vote = -1;
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare("INSERT INTO comment_ratings SET uid={$_SESSION['id']}, cid=(?), vote=(?) ON DUPLICATE KEY UPDATE vote=(?)"))
	{
		$statement->bind_param('iii', $cid, $vote, $vote);
		$statement->execute();
		echo $mysqli->affected_rows;
	}
