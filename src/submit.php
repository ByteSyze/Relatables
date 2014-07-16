<?php
	session_start();
	
	$submission = $_POST['s'];	
	$category = $_POST['c'];
	
	if(($_SESSION['username'] !== null) && (strlen($submission) <= 300) && ($category != null))
	{	
		$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
		
		if($statement = $connection->prepare("INSERT INTO submissions (uid, category, submission) VALUES (?,?,?)"))
		{	
			$statement->bind_param("iis",$_SESSION['id'], $category, $submission);
			
			$statement->execute();
		}
	}
	
	header('Location: http://www.relatablez.com/');
