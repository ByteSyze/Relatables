<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	$data = $_GET['d'];
	$type = $_GET['t'];
	
	require_once('userinfo.php');
	
	if($type == 'show')
	{
		if($data == 'location')
			showLocation($_SESSION['id']);
		else if($data == 'related')
			showRelated($_SESSION['id']);
		else
			die('invalid data.');
	}
	else if($type == 'hide')
	{
		if($data == 'location')
			hideLocation($_SESSION['id']);
		else if($data == 'related')
			hideRelated($_SESSION['id']);
		else
			die('invalid data.');
	}
	else
		die('invalid type.');
		
	header('Location: http://www.relatablez.com/settings/profile');
	