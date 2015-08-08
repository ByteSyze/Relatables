<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$submission = $_POST['s'];	
	$category = $_POST['c'];
	$anon = $_POST['a'];
	
	$sublen = strlen($submission);
	
	if($sublen < 19 || $sublen > 300)
		die('1');
	else if($category < 1 || $category > 20)
		die('2');
	
	if(($_SESSION['id'] !== null))
	{	
		$connection = GlobalUtils::getConnection();
		
		if($statement = $connection->prepare("INSERT INTO submissions (uid, verification, category, submission, anonymous) VALUES (?,?,?,?,?)"))
		{	
			$temp_verif = 1234;
			$statement->bind_param("iiisi",$_SESSION['id'], $temp_verif, $category, $submission, $anon);
			
			echo ($statement->execute() ? 0 : -1);
		}
	}
