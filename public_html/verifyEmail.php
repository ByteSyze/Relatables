<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	if(!$_POST['e'])
		die('2');
		
	$email = $_POST['e'];
	
	$connection = GlobalUtils::getConnection();
	
	if($statement = $connection->prepare('SELECT 1 FROM accounts WHERE email LIKE (?) OR pending_email LIKE (?)'))
	{
		$statement->bind_param('ss',$email,$email);
		
		$statement->execute();
		
		$result = $statement->fetch();
		
		if(empty($result))
		{
			die('0');
		}
		else
		{
			die('1');
		}
	}
		
	echo 'somehow, nothing happened';
?>