<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$email = $_POST['e'];
	
	$connection = GlobalUtils::getConnection();