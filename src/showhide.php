<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	$data = $_GET['d'];
	$type = $_GET['t'];
	
	require_once('userinfo.php');
	$connection = getConnection();
	
	if($type == 'show')
	{
		if($data == 'location')
			showLocation($connection, $_SESSION['id']);
		else if($data == 'related')
			showRelated($connection, $_SESSION['id']);
		else
			die('invalid data.');
	}
	else if($type == 'hide')
	{
		if($data == 'location')
			hideLocation($connection, $_SESSION['id']);
		else if($data == 'related')
			hideRelated($connection, $_SESSION['id']);
		else
			die('invalid data.');
	}
	else
		die('invalid type.');
		
	//header('Location: http://www.relatablez.com/settings/profile');
	