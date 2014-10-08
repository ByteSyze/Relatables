<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare("UPDATE comments SET deleted=1 WHERE cid=(?) AND uid={$_SESSION['id']}"))
	{
		$statement->bind_param('i', $_POST['c']);
		$statement->execute();
	}
	echo $mysqli->affected_rows;
	