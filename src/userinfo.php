<?php	
	/*Copyright (C) Tyler Hackett 2014*/
	
	//Returns a connection to the MySQL database.
	function getConnection()
	{
		return mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
	}
	
	//Returns an array of relevant information pertaining to the specified user's profile.
	function getProfileData($username)
	{
		$connection = getConnection();
		
		if($statement = $connection->prepare("SELECT username, id, DATE_FORMAT(joined,'%M %d, %Y'), DATE_FORMAT(last_login,'%M %d, %Y'), posts, comments, moderated, country_id  FROM accounts WHERE username like (?)"))
		{	
			$statement->bind_param("s",$username);
			
			$statement->execute();
			
			$data = array('username'=>false);
			
			$statement->store_result();
			$statement->bind_result($data['username'],$data['id'],$data['joined'],$data['last_login'],$data['posts'],$data['comments'],$data['moderated'],$cid);
			$result = $statement->fetch();
			
			$data['country'] = getCountry($cid);
			
			if(!empty($result))
				return $data;
			else
				return false;
		}	
	}
	
	function getProfileSettings($id)
	{
		$connection = getConnection();
		
		if($statement = $connection->prepare("SELECT hideposts, hidedescription, hidelocation, description, email, country_id  FROM accounts WHERE id = (?)"))
		{	
			$statement->bind_param("i",$id);
			
			$statement->execute();
			
			$data = array();
			
			$statement->store_result();
			$statement->bind_result($data['hideposts'],$data['hidedescription'],$data['hidelocation'],$data['description'],$data['email'],$cid);
			$result = $statement->fetch();
			
			$data['country'] = getCountry($cid);
			
			if(!empty($result))
				return $data;
			else
				return false;
		}	
	}
		
	function getCountry($id)
	{
		if($id == -1)
			return "No country specified";
			
		$connection = getConnection();
		
		if($statement = $connection->prepare("SELECT short_name FROM countries WHERE country_id = (?)"))
		{	
			$statement->bind_param("i",$id);
			
			$statement->execute();
			
			$statement->store_result();
			$statement->bind_result($country);
			$result = $statement->fetch();
			
			if(!empty($result))
				return $country;
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