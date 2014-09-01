<?php
	$random = openssl_random_pseudo_bytes(4);
	die($random);
	session_start();
	
	$submission = $_POST['s'];	
	$category = $_POST['c'];
	$anon = $_POST['a'];
	
	$sublen = strlen($submission);
	
	if($sublen < 19 || $sublen > 300)
		die('1');
	else if($category < 1 || $category > 20)
		die('2');
	
	if(($_SESSION['username'] !== null) && (strlen($submission) <= 300) && ($category != null))
	{	
		$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
		
		if($statement = $connection->prepare("INSERT INTO submissions (uid, verification, category, submission, anonymous) VALUES (?,?,?,?,?)"))
		{	
			$statement->bind_param("iiisi",$_SESSION['id'], intval(openssl_random_pseudo_bytes(4)), $category, $submission, $anon);
			
			$statement->execute();
			
			echo '0';
		}
	}
