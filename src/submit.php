<?php
	session_start();
	
	//Prevent users that aren't logged in from posting.
	if($_SESSION['username'] === null)
		die();
	
	$submission = $_POST['s'];
	
	//Prevent submissions over the size of 300 characters.
	if(strlen($submission) > 300)
		die();
		
	$category = $_POST['c'];
	
	//Prevent uncategorized submissions.
	if($category == null)
		die();
		
	$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
	
	if($statement = $connection->prepare("INSERT INTO submissions (uid, category, submission) VALUES (?,?,?)"))
	{	
		$statement->bind_param("iis",$_SESSION['id'], $category, $submission);
		
		$statement->execute();
	}
	
	header('Location: http://www.relatablez.com/');
?>