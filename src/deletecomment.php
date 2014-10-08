<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	if($statement = $connection->prepare("UPDATE comments SET deleted=1 WHERE cid=(?) and uid={$_SESSION['id']}"))
	{
		$statement->bind_param('i', $_POST['c'];);
		$statement->execute();
	}
	echo $mysqli->affected_rows;
	