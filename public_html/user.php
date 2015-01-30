<?php	
	/*Copyright (C) Tyler Hackett 2014*/
	
	class User
	{
		private static var $CONNECTION = GlobalUtils::getConnection();
		
		private var $id ;			//User ID
		private var $username; 		//User username
		private var $password;		//Encrypted password
		private var $cookie_login;	//Encrypted login key
		private var $joined;		//Date the user joined
		private var $last_login;	//Date the user last logged in
		private var $email;			//Current user email
		private var $pending_email;	//Current pending user email
		private var $country;		//User country
		private var $description;	//User's profile description
		private var $hide_location;	//Whether or not user is displaying location
		private var $hide_related;	//Whether or not user is displaying related with
		private var $admin;			//Whether or not user is an admin
		private var $mod_index;		//User's moderation index
		
		private var $post_count;	//Number of posts user has made
		private var $comment_count;	//Number of comments user has made
		
		/**
		*	Retrieve an existing user.
		*
		*	@param	$id			ID of the user to get
		*/
		function __construct($id)
		{
			$this->$id = $id;
			
			if($statement = $CONNECTION->prepare('SELECT username, password, cookie_login, DATE_FORMAT(joined,\'%M %d, %Y\'), DATE_FORMAT(last_login,\'%M %d, %Y\'), email, pending_email, (SELECT short_name FROM countries WHERE country_id = accounts.country_id), description, hidelocation, hiderelated, admin, mod_index, (SELECT COUNT(uid) FROM submissions WHERE uid=accounts.id) AS posts, (Select COUNT(uid) FROM comments WHERE uid=accounts.id) AS comments FROM accounts WHERE id=(?)'))
			{	
				$statement->bind_param('i', $id);
				
				$statement->execute();
				
				$statement->bind_result($this->username, $this->password, $this->cookie_login, $this->joined, $this->last_login, $this->email, $this->pending_email, $this->country, $this->description, $this->hide_location, $this->hide_related, $this->admin, $this->mod_index, $this->post_count, $this->comment_count);
				$statement->fetch();
			}	
		}
		
		//Returns an array of relevant information pertaining to the specified user's profile.
		public function getProfileData()
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
		
		public function getProfileSettings()
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
		
		public function getRelated()
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
		
		public function delete()
		{
			if($statement = $connection->prepare('DELETE FROM accounts WHERE id=(?)'))
			{	
				$statement->bind_param('i',$id);		
				$statement->execute();
			}	
		}
			
		public function getCountry()
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
		
		
		public function getPasswordAndSalt()
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
		
		public function getPendingEmail()
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
		
		public function getID()
		{
			return $id;
		}
		
		public static function GET_ID($connection, $username)
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
		
		public function getUsername()
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
		
		public function getModerationIndex()
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
		
		public function setPendingEmail($pending_email)
		{	
			if($statement = $connection->prepare('UPDATE accounts SET pending_email=(?) WHERE id = (?)'))
			{	
				$statement->bind_param('si',$pending_email,$id);		
				$statement->execute();
			}	
		}
		
		public function setPassword($password)
		{
			$new_salt = mcrypt_create_iv(16);
			$new_hash = md5($password.$new_salt);
				
			if($statement = $connection->prepare('UPDATE accounts SET password=(?), salt=(?) WHERE id=(?)'))
			{	
				$statement->bind_param('ssi',$new_hash,$new_salt,$id);	
				$statement->execute();	
			}	
		}
		
		public function setCountry($country_id)
		{
			if($statement = $connection->prepare('UPDATE accounts SET country_id=(?) WHERE id=(?)'))
			{	
				$statement->bind_param('ii',$country_id,$id);	
				$statement->execute();
			}	
		}
		
		public function setUsername($username)
		{
			if($statement = $connection->prepare('UPDATE accounts SET username=(?) WHERE id=(?)'))
			{	
				$statement->bind_param('si',$username,$id);		
				$statement->execute();
			}	
		}
		
		public function incModerationindex()
		{
			mysqli_query($connection, "UPDATE accounts SET mod_index=mod_index+1 WHERE id=$id") or die(mysqli_error($connection));
		}
		
		public function setDescription($description)
		{
			if($statement = $connection->prepare('UPDATE accounts SET description=(?) WHERE id=(?)'))
			{	
				$statement->bind_param('si',$description,$id);		
				$statement->execute();
			}	
		}
		
		public function isAdmin()
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
		
		public function showLocation($show = true)
		{
			if($show)
			{
				if($statement = $connection->prepare('UPDATE accounts SET hidelocation=0 WHERE id=(?)'))
				{	
					$statement->bind_param('i',$id);		
					$statement->execute();
				}	
			}
			else
			{
				if($statement = $connection->prepare('UPDATE accounts SET hidelocation=1 WHERE id=(?)'))
				{	
					$statement->bind_param('i',$id);		
					$statement->execute();
				}	
			}
		}
		
		function showRelated($show)
		{
			if($show)
			{
				if($statement = $connection->prepare('UPDATE accounts SET hiderelated=0 WHERE id=(?)'))
				{	
					$statement->bind_param('i',$id);		
					$statement->execute();
				}
			}
			else
			{
				if($statement = $connection->prepare('UPDATE accounts SET hiderelated=1 WHERE id=(?)'))
				{	
					$statement->bind_param('i',$id);		
					$statement->execute();
				}
			}
		}
		
		/**
		*	Send a notification to specified user ID.
		*
		*	@param	$message	Message to send to user.
		*/
		function notify($message)
		{
			if($statement = $connection->prepare("INSERT INTO notifications (uid, message) VALUES (?,?,?,?)"))
			{
				$statement->bind_param('is', $id, $message);
				$statement->execute();
			}
		}
	
	}
	