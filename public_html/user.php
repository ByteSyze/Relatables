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
		private $verification;
		private $joined;
		private $last_login;
		private $email;
		private $pending_email;
		private $country_id;
		private $country;
		private $description;
		private $mod_index = 0;
		private $exists;
		
		private $flags = 0;
		
		private $post_count;
		private $comment_count;
		
		private $editted_fields;
		
		function __construct($data)
		{
			if(self::$connection == null)
				self::$connection = GlobalUtils::getConnection();
				
			$this->editted_fields = array();
			
			if(is_int($data))
			{
				//Treat $data as ID
				
				$this->id = $data;
				
				if($data == 0) return; //0 is for users that aren't logged in.
				
				if($statement = self::$connection->prepare('SELECT username, password, cookie_login, verification, DATE_FORMAT(joined,\'%M %d, %Y\'), DATE_FORMAT(last_login,\'%M %d, %Y\'), email, pending_email, country_id, (SELECT short_name FROM countries WHERE country_id = accounts.country_id), description, mod_index, flags, (SELECT COUNT(uid) FROM submissions WHERE uid=accounts.id) AS posts, (Select COUNT(uid) FROM comments WHERE uid=accounts.id) AS comments FROM accounts WHERE id=(?)'))
				{	
					$statement->bind_param('i', $data);
					
					$statement->execute();
					
					$statement->bind_result($this->username, $this->password, $this->cookie_login, $this->verification, $this->joined, $this->last_login, $this->email, $this->pending_email, $this->country_id,  $this->country, $this->description, $this->mod_index, $this->flags, $this->post_count, $this->comment_count);
					$this->exists = $statement->fetch();
				}
			}
			else
			{
				//Treat $data as username
				if($statement = self::$connection->prepare('SELECT id, username, password, cookie_login, verification, DATE_FORMAT(joined,\'%M %d, %Y\'), DATE_FORMAT(last_login,\'%M %d, %Y\'), email, pending_email, country_id, (SELECT short_name FROM countries WHERE country_id = accounts.country_id) AS country, description, mod_index, flags, (SELECT COUNT(uid) FROM submissions WHERE uid=accounts.id) AS posts, (Select COUNT(uid) FROM comments WHERE uid=accounts.id) AS comments FROM accounts WHERE username LIKE (?)'))
				{	
					$statement->bind_param('s', $data);
					
					$statement->execute();
					
					$statement->bind_result($this->id, $this->username, $this->password, $this->cookie_login, $this->verification, $this->joined, $this->last_login, $this->email, $this->pending_email, $this->country_id, $this->country, $this->description, $this->mod_index, $this->flags, $this->post_count, $this->comment_count);
					$this->exists = $statement->fetch();
				}	
			}
		}
		
		private function setEditted($field, $data_type)
		{
			$this->editted_fields[$field] = $data_type;
		}
		
		public function update()
		{
			/*$query = 'UPDATE accounts SET ';
			$field_count = count($this->editted_fields);
			$i = 0;
			
			foreach($this->editted_fields as $editted_field => $data_type)
			{
				$i++;
				$query .= "$editted_field = (?)";
				
				if($i < $field_count)
					$query .= ', ';
			}
			
			$query .= ' WHERE id=' . $this->id;
			
			if($statement = self::$connection->prepare($query))
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
			}*/
			
			foreach($this->editted_fields as $editted_field => $data_type)
			{
				if($statement = self::$connection->prepare("UPDATE accounts SET $editted_field = (?) WHERE id=".$this->id))
				{
					$statement->bind_param($data_type, $this->$editted_field);
					$statement->execute();
				}
			}
			
			$this->editted_fields = array();
		}
		
		public function delete()
		{
			if($statement = self::$connection->prepare('DELETE FROM accounts WHERE id=(?)'))
			{	
				$statement->bind_param('i',$id);		
				$statement->execute();
			}	
		}
		
		public function getCountryId()
		{
			return $this->country_id;
		}
		
		public function setCountryId($cid)
		{
			$this->country_id = $cid;
			$this->setEditted('country_id', self::TYPE_INT);
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
		
		public function getJoined()
		{
			return $this->joined;
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
			{
				require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
				$this->password = password_hash($password, PASSWORD_DEFAULT);
			}
				
			$this->setEditted('password', self::TYPE_STRING);
		}
		
		public function setUsername($username)
		{
			$this->username = $username;	
			$this->setEditted('username', self::TYPE_STRING);
		}
		
		public function incModerationindex()
		{
			$this->mod_index+=1;
			$this->setEditted('mod_index', self::TYPE_INT);
		}
		
		public function getDescription()
		{
			return $this->description;
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
			return ($this->flags >> 0x01) & 0x01;
		}
		
		public function setBetaTester($tester = true)
		{
			if($tester)
				$this->flags |= 0x02;
			else
				$this->flags &= ~0x02;
				
			$this->setEditted('flags', self::TYPE_INT);
		}
		
		public function getHideRelated()
		{
			return ($this->flags >> 0x02) & 0x01;
		}
		
		public function setHideRelated($hide = true)
		{
			if($hide)
				$this->flags |= 0x03;
			else
				$this->flags &= ~0x03;
				
			$this->setEditted('flags', self::TYPE_INT);
		}
		
		public function getHideLocation()
		{
			return ($this->flags >> 0x03) & 0x01;
		}
		
		public function setHideLocation($hide = true)
		{
			if($hide)
				$this->flags |= 0x04;
			else
				$this->flags &= ~0x04;
				
			$this->setEditted('flags', self::TYPE_INT);
		}
		
		public function getPending()
		{
			return ($this->flags >> 0x04) & 0x01;
		}
		
		public function setPending($pending = true)
		{
			if($pending)
				$this->flags |= 0x10;
			else
				$this->flags &= ~0x10;
				
			$this->setEditted('flags', self::TYPE_INT);
		}
		
		public function getFlags()
		{
			return $this->flags;
		}
		
		public function getVerification()
		{
			return $this->verification;
		}
		
		public function setVerification($verification)
		{
			$this->verification = $verification;
			
			$this->setEditted('verification', self::TYPE_STRING);
		}
		
		public function generateVerification()
		{
			$verification = md5(openssl_random_pseudo_bytes(128, $crypto_strong));
			$this->setVerification(password_hash($verification, PASSWORD_DEFAULT));
			
			return $verification;
		}
		
		public function getCookieLogin()
		{
			return $this->cookie_login;
		}
		
		public function setCookieLogin($cookie)
		{
			$this->cookie_login = $verification;
			
			$this->setEditted('cookie_login', self::TYPE_STRING);
		}
		
		public function generateCookieLogin()
		{
			$cookie = md5(openssl_random_pseudo_bytes(128, $crypto_strong));
			$this->setCookieLogin(password_hash($cookie, PASSWORD_DEFAULT));
			
			return $cookie;
		}
		
		public function getPostCount()
		{
			return $this->post_count;
		}
		
		public function getCommentCount()
		{
			return $this->comment_count;
		}
		
		public function getEmail()
		{
			return $this->email;
		}
		
		public function setEmail($email)
		{
			$this->email = $email;
			
			$this->setEditted('email', self::TYPE_STRING);
		}
		
		public function exists()
		{
			return $this->exists;
		}
		
		function notify($message, $href = '')
		{
			if($statement = self::$connection->prepare("INSERT INTO notifications (uid, message, href) VALUES (?,?,?)"))
			{
				$statement->bind_param('iss', $this->id, $message, $href);
				$statement->execute();
			}
		}
		
		function email($subject, $message, $use_pending = false)
		{
			if($use_pending)
				mail($this->getPendingEmail(), $subject, $message, self::MAIL_FROM);
			else
				mail($this->getEmail(), $subject, $message, self::MAIL_FROM);
		}
	
	}
	