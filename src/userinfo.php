<?php
	
	//Returns a connection to the MySQL database.
	function getConnection()
	{
		return mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
	}
	
	//Returns the case sensitive name of the user, or false if the user does not exist.
	function getExactUsername($username)
	{
	
		$connection = getConnection();
		
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
	
	//Returns the date the user first joined, or false if the specified id does not match any current account.
	function getJoinDate($id)
	{
		$connection = getConnection();
		
		if($statement = $connection->prepare("SELECT DATE_FORMAT(joined,'%M %d, %Y') AS fjoined FROM accounts WHERE id = (?)"))
		{	
			$statement->bind_param("i",$id);
			
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
	
	//Returns the id of the specified username, or false if the username is not an existing account.
	function getUserId($username)
	{	
		$connection = getConnection();
		
		if($statement = $connection->prepare("SELECT id FROM accounts WHERE username like (?)"))
		{	
			$statement->bind_param("s",$username);
			
			$statement->execute();
			
			$statement->store_result();
			$statement->bind_result($id);
			$result = $statement->fetch();
			
			if(!empty($result))
				return $id;
			else
				return false;
		}
	}
	
	//Returns the data from the specified column for the specified id, or false if the id does not match any current user.
	function getUserColumn($id, $column)
	{
		$connection = getConnection();
		
		if($statement = $connection->prepare("SELECT ".$column." FROM accounts WHERE id = (?)"))
		{	
			$statement->bind_param("i",$id);
			
			$statement->execute();
			
			$statement->store_result();
			$statement->bind_result($data);
			$result = $statement->fetch();
			
			if(!empty($result))
				return $data;
			else
				return false;
		}
	}