<?php
	session_start();

	$description = $_GET['description'];
	
	$length = strlen($description);
	
	if($length > 130)
		die('Description too big.');
		
	include('userinfo.php');
	
	setDescription($description, $_SESSION['id']);
	
	header('Location: http://www.relatablez.com/settings/profile');