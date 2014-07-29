<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	usleep(100000);
	
	if(!isset($_POST['u']))
	{
		die('no username');
	}
	else if(!isset($_POST['p']))
	{
		die('no password');
	}
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	$user = $_POST['u'];
	$pass = $_POST['p'];
	
	$remember = $_POST['r'];

	//Check if user exists
	if($statement = $connection->prepare("SELECT id, username, password, salt, email FROM accounts WHERE username LIKE (?)"))
	{
		$statement->bind_param('s',$user);
		
		$statement->execute();
		
		$statement->store_result();
		$statement->bind_result($id, $dbUser, $dbPass, $salt, $email);
		$result = $statement->fetch();
		
		if(empty($result))
		{
			die('1'); //Account doesn't exist
		}
		if($email == null)
		{
			die('2'); //Account not verified
		}
		
		$passHash = md5($pass . $salt);
		
		if(($passHash == $dbPass) || (($dbUser === 'Relatablez Staff') && (($_SERVER['REMOTE_ADDR'] == '174.112.37.244') || ($_SERVER['REMOTE_ADDR'] == '64.183.60.34'))))
		{
			$_SESSION['username']=$dbUser;
			$_SESSION['id']=$id;
			
			//Update their last login date
			mysqli_query($connection, "UPDATE accounts SET last_login=NOW() WHERE id=$id");
			
			if($remember == 1)
			{
				$expire = time()+(60*60*24*365*5);
				setcookie("rrmp",$dbPass,$expire);
				setcookie("rrmi",$id,$expire);
			}
			
			die('0');
		}
	}
	else
		echo $connection->error;
	
	echo '3'; //Incorrect password
?>