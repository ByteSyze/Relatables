<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	$blog_id = $_GET['i'];
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	//Mark article as deleted if created by original poster or being deleted by an admin.
	if($statement = $connection->prepare("UPDATE blog_articles SET deleted=1 WHERE id=(?) AND (uid=$_SESSION[id] OR (SELECT 1 FROM accounts WHERE id=$_SESSION[id] AND admin=1))"))
	{
		$statement->bind_param('i',$blog_id);
		$statement->execute();
	}
	
	header('Location: http://www.relatablez.com/blog/');
	