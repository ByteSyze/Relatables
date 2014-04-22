<?php
	
	$connection = false;
	
	//Function to connect to the MySQL database. Must be called prior to any other function in this file.
	public function connect()
	{
		$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
	}
	
	//Returns the case sensitive name of the user, or false if the user does not exist.
	public function getExactUsername($username)
	{
		if($statement = $connection->prepare("SELECT username FROM accounts WHERE username like (?)"))
		{	
			$statement->bind_param("s",$username);
			
			$statement->execute();
			
			$statement->store_result();
			$statement->bind_result($dbUser);
			$result = $statement->fetch();
			
			if(!empty($result))
				return $dbUser;
			else
				return false;
		}
	}
	
	//Returns the date the user first joined, or false if the user does not exist.
	public function getJoinDate($username)
	{
		if($statement = $connection->prepare("SELECT DATE_FORMAT(joined,'%M %d, %Y') AS fjoined FROM accounts WHERE username like (?)"))
		{	
			$statement->bind_param("s",$username);
			
			$statement->execute();
			
			$statement->store_result();
			$statement->bind_result($date);
			$result = $statement->fetch();
			
			if(!empty($result))
				return $date;
			else
				return false;
		}
	}