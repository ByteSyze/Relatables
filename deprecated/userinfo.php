<?php	
	/*Copyright (C) Tyler Hackett 2014*/
	
	//Returns an array of relevant information pertaining to the specified user's profile.
	function getProfileData($connection, $username)
	{
		if($statement = $connection->prepare('SELECT username, id, DATE_FORMAT(joined,\'%M %d, %Y\'), DATE_FORMAT(last_login,\'%M %d, %Y\'), description, (SELECT COUNT(uid) FROM submissions WHERE uid=accounts.id) AS posts, (Select COUNT(uid) FROM comments WHERE uid=accounts.id) AS comments, mod_index, hiderelated, hidelocation, admin, country_id  FROM accounts WHERE username like (?)'))
		{	
			$statement->bind_param('s',$username);
			
			$statement->execute();
			
			$data = array('username'=>false);
			
			$statement->store_result();
			$statement->bind_result($data['username'],$data['id'],$data['joined'],$data['last_login'],$data['description'],$data['posts'],$data['comments'],$data['moderated'],$data['hiderelated'],$data['hidelocation'],$data['admin'],$cid);
			$statement->fetch();
			
			$data['country'] = getCountry($connection, $cid);
			
			return $data;
		}	
	}
	
	function getProfileSettings($connection, $id)
	{
		if($statement = $connection->prepare('SELECT hidelocation, description, email, country_id  FROM accounts WHERE id = (?)'))
		{	
			$statement->bind_param('i',$id);
			
			$statement->execute();
			
			$data = array();
			
			$statement->store_result();
			$statement->bind_result($data['hidelocation'],$data['description'],$data['email'],$cid);
			$statement->fetch();
			
			$data['country_id'] = $cid;
			$data['country'] = getCountry($connection, $cid);
			
			return $data;
		}
	}
	
	function getRelated($connection, $id)
	{
		if($statement = $connection->prepare("SELECT uid, verification, category, DATE_FORMAT(date,'%M %d, %Y') AS fdate, alone, notalone, submission, anonymous FROM submissions WHERE submissions.pending=0 AND submissions.id IN (SELECT pid FROM related WHERE related.uid=(?) AND related.alone=0)"))
		{
			$statement->bind_param('i',$id);
			$statement->execute();
			
			$statement->store_result();
			$statement->bind_result($related['uid'],$related['verification'],$related['category'],$related['fdate'],$related['alone'],$related['notalone'],$related['submission'],$related['anonymous']);
			
			$data = array("related" => $related, "statement" => $statement);
			
			return $data;
		}
	}
	
	function deleteAccount($connection, $id)
	{
		if($statement = $connection->prepare('DELETE FROM accounts WHERE id=(?)'))
		{	
			$statement->bind_param('i',$id);		
			$statement->execute();
		}	
	}
		
	function getCountry($connection, $id)
	{
		if($id == -1)
			return 'No country specified';
		
		if($statement = $connection->prepare('SELECT short_name FROM countries WHERE country_id = (?)'))
		{	
			$statement->bind_param('i',$id);
			
			$statement->execute();
			
			$statement->store_result();
			$statement->bind_result($country);
			$statement->fetch();
			
			if($country)
				return $country;
			else
				return false;
		}
	}	
	
	
	function getPasswordAndSalt($connection, $id)
	{
		if($statement = $connection->prepare('SELECT password, salt FROM accounts WHERE id = (?)'))
		{	
			$statement->bind_param('i',$id);
			
			$statement->execute();
			
			$data = array('hash'=>'n/a','salt'=>'n/a');
			
			$statement->bind_result($data['hash'],$data['salt']);
			$statement->fetch();
			
			return $data;
		}	
	}
	
	function getPendingEmail($connection, $id)
	{	
		if($statement = $connection->prepare('SELECT pending_email FROM accounts WHERE id = (?)'))
		{	
			$statement->bind_param('i',$id);
			
			$statement->execute();
			
			$statement->bind_result($pendingEmail);
			$statement->fetch();
			
			return $pendingEmail;
		}	
	}
	
	function getId($connection, $username)
	{	
		if($statement = $connection->prepare('SELECT id FROM accounts WHERE username LIKE (?)'))
		{	
			$statement->bind_param('s',$username);
			
			$statement->execute();
			
			$statement->bind_result($id);
			$statement->fetch();
			
			return $id;
		}	
	}
	
	function getUsername($connection, $id)
	{	
		if($statement = $connection->prepare('SELECT username FROM accounts WHERE id = (?)'))
		{	
			$statement->bind_param('i',$id);
			
			$statement->execute();
			
			$statement->bind_result($username);
			$statement->fetch();
			
			return $username;
		}	
	}
	
	function getModerationIndex($connection, $id)
	{
		if($statement = $connection->prepare("SELECT mod_index FROM accounts WHERE id = (?)"))
		{	
			$statement->bind_param('i',$id);
			
			$statement->execute();
			
			$statement->bind_result($index);
			$statement->fetch();
			
			return $index;
		}	
	}
	
	function setPendingEmail($connection, $pendingEmail, $id)
	{	
		if($statement = $connection->prepare('UPDATE accounts SET pending_email=(?) WHERE id = (?)'))
		{	
			$statement->bind_param('si',$pendingEmail,$id);		
			$statement->execute();
		}	
	}
	
	function setPassword($connection, $password, $id)
	{
		$new_salt = mcrypt_create_iv(16);
		$new_hash = md5($password.$new_salt);
			
		if($statement = $connection->prepare('UPDATE accounts SET password=(?), salt=(?) WHERE id=(?)'))
		{	
			$statement->bind_param('ssi',$new_hash,$new_salt,$id);	
			$statement->execute();	
		}	
	}
	
	function setCountry($connection, $country_id,$id)
	{
		if($statement = $connection->prepare('UPDATE accounts SET country_id=(?) WHERE id=(?)'))
		{	
			$statement->bind_param('ii',$country_id,$id);	
			$statement->execute();
		}	
	}
	
	function setUsername($connection, $username,$id)
	{
		if($statement = $connection->prepare('UPDATE accounts SET username=(?) WHERE id=(?)'))
		{	
			$statement->bind_param('si',$username,$id);		
			$statement->execute();
		}	
	}
	
	function incModerationindex($connection, $id)
	{
		mysqli_query($connection, "UPDATE accounts SET mod_index=mod_index+1 WHERE id=$id") or die(mysqli_error($connection));
	}
	
	function setDescription($connection, $description,$id)
	{
		if($statement = $connection->prepare('UPDATE accounts SET description=(?) WHERE id=(?)'))
		{	
			$statement->bind_param('si',$description,$id);		
			$statement->execute();
		}	
	}
	
	function isAdmin($connection, $id)
	{
		if($statement = $connection->prepare('SELECT admin FROM accounts WHERE id = (?)'))
		{	
			$statement->bind_param('i',$id);
			
			$statement->execute();
			
			$statement->bind_result($admin);
			$statement->fetch();
			
			return($admin === 1);
		}	
	}
	
	function showLocation($connection, $id)
	{
		if($statement = $connection->prepare('UPDATE accounts SET hidelocation=0 WHERE id=(?)'))
		{	
			$statement->bind_param('i',$id);		
			$statement->execute();
		}	
	}
	
	function hideLocation($connection, $id)
	{
		if($statement = $connection->prepare('UPDATE accounts SET hidelocation=1 WHERE id=(?)'))
		{	
			$statement->bind_param('i',$id);		
			$statement->execute();
		}	
	}
	
	function showRelated($connection, $id)
	{
		if($statement = $connection->prepare('UPDATE accounts SET hiderelated=0 WHERE id=(?)'))
		{	
			$statement->bind_param('i',$id);		
			$statement->execute();
		}
		else
			die(mysqli_error($connection));
	}
	
	function hideRelated($connection, $id)
	{
		if($statement = $connection->prepare('UPDATE accounts SET hiderelated=1 WHERE id=(?)'))
		{	
			$statement->bind_param('i',$id);		
			$statement->execute();
		}	
		else
			die(mysqli_error($connection));
	}
	
	/**
	*	Send a notification to specified user ID.
	*
	*	@param	$sid		ID of notification sender (0 for non-specified)
	*	@param	$uid		ID of notification recipient
	*	@param	$subect		Subject of the notification
	*	@param	$message	Message to send to user.
	*
	*/
	function notify($connection, $sid = 0, $uid, $subject, $message)
	{
		if($statement = $connection->prepare("INSERT INTO notifications (sid, uid, subject, message) VALUES (?,?,?,?)"))
		{
			$statement->bind_param('iiss', $sid, $uid, $subject, $message);
			$statement->execute();
		}
		return $connection->error;
	}
	