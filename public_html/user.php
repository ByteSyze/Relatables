<?php	
	/*Copyright (C) Tyler Hackett 2014*/
	
	class User
	{
		private static $connection;
		
		private $id ;			//User ID
		private $username; 		//User username
		private $password;		//Encrypted password
		private $cookie_login;	//Encrypted login key
		private $joined;		//Date the user joined
		private $last_login;	//Date the user last logged in
		private $email;			//Current user email
		private $pending_email;	//Current pending user email
		private $country;		//User country
		private $description;	//User's profile description
		private $hide_location;	//Whether or not user is displaying location
		private $hide_related;	//Whether or not user is displaying related with
		private $admin;			//Whether or not user is an admin
		private $mod_index;		//User's moderation index
		
		private $post_count;	//Number of posts user has made
		private $comment_count;	//Number of comments user has made
		
		/**
		*	Retrieve an existing user.
		*
		*	@param	$id			ID of the user to get
		*/
		function __construct($id)
		{
			if(self::$connection == null)
				self::$connection = GlobalUtils::getConnection();
			
			if($statement = self::$connection->prepare('SELECT username, password, cookie_login, DATE_FORMAT(joined,\'%M %d, %Y\'), DATE_FORMAT(last_login,\'%M %d, %Y\'), email, pending_email, (SELECT short_name FROM countries WHERE country_id = accounts.country_id), description, hidelocation, hiderelated, admin, mod_index, (SELECT COUNT(uid) FROM submissions WHERE uid=accounts.id) AS posts, (Select COUNT(uid) FROM comments WHERE uid=accounts.id) AS comments FROM accounts WHERE id=(?)'))
			{	
				$statement->bind_param('i', $id);
				
				$statement->execute();
				
				$statement->bind_result($this->username, $this->password, $this->cookie_login, $this->joined, $this->last_login, $this->email, $this->pending_email, $this->country, $this->description, $this->hide_location, $this->hide_related, $this->admin, $this->mod_index, $this->post_count, $this->comment_count);
				$statement->fetch();
			}	
		}
		
		public function getRelated()
		{
			if($statement = self::$connection->prepare("SELECT pid FROM related WHERE related.uid=(?) AND related.alone=0"))
			{
				$statement->bind_param('i',$this->id);
				$statement->execute();
				
				$posts = array();
				
				$statement->store_result();
				$statement->bind_result($id);
				
				while($statement->fetch())
					array_push($posts, new Post($id));
				
				return $posts;
			}
		}
		
		public function delete()
		{
			if($statement = self::$connection->prepare('DELETE FROM accounts WHERE id=(?)'))
			{	
				$statement->bind_param('i',$id);		
				$statement->execute();
			}	
		}
			
		public function getCountry()
		{
			return $this->country;
		}	
		
		
		public function getPassword()
		{
			return $this->password;
		}
		
		public function getPendingEmail()
		{	
			return $this->pending_email;
		}
		
		public function getID()
		{
			return $this->id;
		}
		
		public static function getIDFromUsername($username)
		{	
			if($statement = self::$connection->prepare('SELECT id FROM accounts WHERE username LIKE (?)'))
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
			return $this->username;	
		}
		
		public function getModerationIndex()
		{
			return $this->mod_index;
		}
		
		public function setPendingEmail($pending_email)
		{	
			$this->pending_email = $pending_email;
		}
		
		public function setPassword($password, $hashed = false)
		{
			if($hashed)
				$this->password = $password;
			else
				$this->password = pass_hash($password, PASSWORD_DEFAULT);
		}
		
		public function setCountry($country)
		{
			$this->country = $country;	
		}
		
		public function setUsername($username)
		{
			$this->username = $username;	
		}
		
		public function incModerationindex()
		{
			$this->mod_index+=1;
			//mysqli_query($connection, "UPDATE accounts SET mod_index=mod_index+1 WHERE id=$id") or die(mysqli_error($connection));
		}
		
		public function setDescription($description)
		{
			$this->description = $description;
		}
		
		public function isAdmin()
		{
			return $this->admin;
		}
		
		public function showLocation($show = true)
		{
			$this->show_location = $show;
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
	