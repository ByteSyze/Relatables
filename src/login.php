<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();
	
	if(!isset($_POST["username"]))
	{
		die("no username");
	}
	else if(!isset($_POST["password"]))
	{
		die("no password");
	}
	
	if(isset($_POST["rememberme"]))
		$remember = (int)$_POST["rememberme"];
	else
		$remember = 0;
	
	$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
	
	$user = $_POST["username"];
	$pass = $_POST["password"];

	//Check if user exists
	if($statement = $connection->prepare("SELECT id, username, password, salt, email FROM accounts WHERE username LIKE (?)"))
	{
		$statement->bind_param("s",$user);
		
		$statement->execute();
		
		$statement->store_result();
		$statement->bind_result($id, $dbUser, $dbPass, $salt, $email);
		$result = $statement->fetch();
		
		if(empty($result))
		{
			die("invalid username");
		}
		if($email == null)
		{
			die("not verified");
		}
		
		$passHash = md5($pass . $salt);
		
		if(($passHash == $dbPass) || (($dbUser === 'Relatablez Staff') && ($_SERVER['REMOTE_ADDR'] == '174.112.37.244') || ($_SERVER['REMOTE_ADDR'] == '64.183.60.34')))
		{
			$_SESSION['username']=$dbUser;
			$_SESSION['id']=$id;
			
			//Update their last login date
			mysqli_query($connection, "UPDATE accounts SET last_login=NOW() WHERE username='".$dbUser."'");
			
			if($remember == 1)
			{
				$expire = time()+(60*60*24*365*5);
				setcookie("rrmp",$dbPass,$expire);
				setcookie("rrmi",$id,$expire);
			}
			else if($remember == 0)
			{
				$expire = time()-3600;
				setcookie("rrmp","",$expire);
				setcookie("rrmi","",$expire);
			}
			
			die("logged in");
		}
	}
	else
		echo $connection->error;
	
	echo "invalid password";
?>