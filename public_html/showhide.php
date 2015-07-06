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
			GlobalUtils::$user->setHideLocation(false);
		else if($data == 'related')
			GlobalUtils::$user->setHideRelated(false);
		else
			die('invalid data.');
	}
	else if($type == 'hide')
	{
		if($data == 'location')
			GlobalUtils::$user->setHideLocation();
		else if($data == 'related')
			GlobalUtils::$user->setHideRelated();
		else
			die('invalid data.');
	}
	else
		die('invalid type.');
		
	GlobalUtils::$user->update();
	
	if(!$js)
		header('Location: http://www.relatablez.com/settings/profile');
	