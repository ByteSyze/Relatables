<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$data = $_GET['d'];
	$type = $_GET['t'];
	$js	  = $_GET['j']; // Script called from javascript
	
	$connection = GlobalUtils::getConnection();
	
	if($type == 'show')
	{
		if($data == 'location')
			GlobalUtils::$user->setShowLocation();
		else if($data == 'related')
			GlobalUtils::$user->setShowRelated();
		else
			die('invalid data.');
	}
	else if($type == 'hide')
	{
		if($data == 'location')
			GlobalUtils::$user->setShowLocation(false);
		else if($data == 'related')
			GlobalUtils::$user->setShowRelated(false);
		else
			die('invalid data.');
	}
	else
		die('invalid type.');
		
	GlobalUtils::$user->update();
	
	if(!$js)
		header('Location: http://www.relatablez.com/settings/profile');
	