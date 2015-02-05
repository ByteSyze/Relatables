<?php	
	/*Copyright (C) Tyler Hackett 2014*/
	
	class User
	{
		const MAIL_FROM = "From: Relatablez <noreply@relatablez.com>";
		
		const TYPE_STRING 	= 's';
		const TYPE_INT		= 'i';
		
		private static $connection;
		
		private $id;
		private $username;
		private $password;
		private $cookie_login;
		private $joined;
		private $last_login;
		private $email;
		private $pending_email;
		private $country;
		private $description;
		private $mod_index;
		
		private $flags;
		
		private $post_count;
		private $comment_count;
		
		private $editted_fields;
		
		function __construct($id)
		{
			if(self::$connection == null)
				self::$connection = GlobalUtils::getConnection();
				
			$this->id = $id;
			$this->editted_fields = array();
			
			if($statement = self::$connection->prepare('SELECT username, password, cookie_login, DATE_FORMAT(joined,\'%M %d, %Y\'), DATE_FORMAT(last_login,\'%M %d, %Y\'), email, pending_email, (SELECT short_name FROM countries WHERE country_id = accounts.country_id), description, mod_index, flags, (SELECT COUNT(uid) FROM submissions WHERE uid=accounts.id) AS posts, (Select COUNT(uid) FROM comments WHERE uid=accounts.id) AS comments FROM accounts WHERE id=(?)'))
			{	
				$statement->bind_param('i', $id);
				
				$statement->execute();
				
				$statement->bind_result($this->username, $this->password, $this->cookie_login, $this->joined, $this->last_login, $this->email, $this->pending_email, $this->country, $this->description, $this->mod_index, $this->flags, $this->post_count, $this->comment_count);
				$statement->fetch();
			}	
		}
		
		private function setEditted($field, $data_type)
		{
			$this->editted_fields[$field] = $data_type;
		}
		
		public function update()
		{
			$query = 'UPDATE accounts SET ';
			$field_count = count($this->editted_fields);
			
			foreach($this->editted_fields as $editted_field => $data_type)
			{
				$query .= "$editted_field = (?)";
				
				if($i < $field_count-1)
					$query .= ', ';
			}
			
			$query .= ' WHERE id=' . $this->id;
			
			echo " '$query' ";
			
			if($statement = self::$connection->query($query))
			{
				$data_types = '';
				$data = array();
				
				foreach($this->editted_fields as $editted_field => $data_type)
				{
					$data_types .= $data_type;
					$data[] = $this->$editted_field;
				}
					
				$statement->bind_param($data_types, ...$data);
				$statement->execute();
			}
			echo self::$connection->error;
			
			$this->editted_fields = array();
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
			$this->setEditted('pending_email', self::TYPE_STRING);
		}
		
		public function setPassword($password, $hashed = false)
		{
			if($hashed)
				$this->password = $password;
			else
				$this->password = pass_hash($password, PASSWORD_DEFAULT);
				
			$this->setEditted('password', self::TYPE_STRING);
		}
		
		public function setCountry($country)
		{
			$this->country = $country; //TODO figure out wtf to do about this in update()
		}
		
		public function setUsername($username)
		{
			$this->username = $username;	
			$this->setEditted('username', self::TYPE_STRING);
		}
		
		public function incModerationindex()
		{
			$this->mod_index+=1;
		}
		
		public function setDescription($description)
		{
			$this->description = $description;
			$this->setEditted('description', self::TYPE_STRING);
		}
		
		public function isAdmin()
		{
			return $this->flags & 0x01;
		}
		
		public function setAdmin($admin = true)
		{
			if($admin)
				$this->flags |= 0x01;
			else
				$this->flags &= 0xFE;
				
			$this->setEditted('flags', self::TYPE_INT);
		}
		
		public function isBetaTester()
		{
			return $this->flags >> 0x02 & 0x01;
		}
		
		public function setBetaTester($tester = true)
		{
			if($tester)
				$this->flags |= 0x02;
			else
				$this->flags &= 0xFD;
				
			$this->setEditted('flags', self::TYPE_INT);
		}
		
		public function getHideRelated()
		{
			return $this->flags >> 0x04 & 0x01;
		}
		
		public function setHideRelated($hide = true)
		{
			if($hide)
				$this->flags |= 0x04;
			else
				$this->flags &= 0xFB;
				
			$this->setEditted('flags', self::TYPE_INT);
		}
		
		public function getHideLocation()
		{
			return $this->flags >> 0x08 & 0x01;
		}
		
		public function setHideLocation($hide = true)
		{
			if($hide)
				$this->flags |= 0x08;
			else
				$this->flags &= 0xF7;
				
			$this->setEditted('flags', self::TYPE_INT);
		}
		
		public function getFlags()
		{
			return $this->flags;
		}
		
		/**
		*	Send a notification to this user.
		*
		*	@param	$message	Message to send to user.
		*/
		function notify($message)
		{
			if($statement = self::$connection->prepare("INSERT INTO notifications (uid, message) VALUES (?,?)"))
			{
				$statement->bind_param('is', $this->id, $message);
				$statement->execute();
			}
		}
		
		/**
		*	Send an email to this user.
		*
		*	@param	$subject	Subject of the email
		*	@param	$message	Body of the email
		*/
		function email($subject, $message)
		{
			mail($this->email, $subject, $message, self::MAIL_FROM);
		}
	
	}
	