<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	if(!isset($_POST['e']))
		die('2');
		
	$email = $_POST['e'];
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare('SELECT 1 FROM accounts WHERE email LIKE (?)'))
	{
		$statement->bind_param('s',$email);
		
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
	else
		echo $connection->error;
		
	echo 'somehow, nothing happened';
?>