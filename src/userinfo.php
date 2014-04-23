<?php
	
	//Returns a connection to the MySQL database.
	function getConnection()
	{
		return mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
	}
	
	//Returns an array of relevant information pertaining to the specified user's profile.
	function getProfileData($username)
	{
		$connection = getConnection();
		
		if($statement = $connection->prepare("SELECT username, id, DATE_FORMAT(joined,'%M %d, %Y') AS joined, posts, comments, moderated  FROM accounts WHERE username like (?)"))
		{	
			$statement->bind_param("s",$username);
			
			$statement->execute();
			
			$data = array();
			
			$statement->store_result();
			$statement->bind_result($data['username']=false,$data['id'],$data['joined'],$data['posts'],$data['comments'],$data['moderated']);
			$result = $statement->fetch();
			
			if(!empty($result))
				return $data;
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