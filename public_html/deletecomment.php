<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$connection = GlobalUtils::getConnection();
	
	if($statement = $connection->prepare("UPDATE comments SET deleted=1 WHERE cid=(?) AND uid={$_SESSION['id']}"))
	{
		$statement->bind_param('i', $_POST['c']);
		$statement->execute();
	}
	echo $mysqli->affected_rows;
	