<?php
	session_start();
	
	$submission = $_POST['s'];	
	$category = $_POST['c'];
	$anon = $_POST['a'];
	
	if(($_SESSION['username'] !== null) && (strlen($submission) <= 300) && ($category != null))
	{	
		$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
		
		if($statement = $connection->prepare("INSERT INTO submissions (uid, verification, category, submission, anonymous) VALUES (?,?,?,?,?)"))
		{	
			$statement->bind_param("iiisi",$_SESSION['id'], openssl_random_pseudo_bytes(4), $category, $submission, $anon);
			
			$statement->execute();
		}
		else
			die($connection->error);
	}
	
	header('Location: http://www.relatablez.com/');
