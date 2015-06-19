<?php
	/*Copyright (C) Tyler Hacket 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/user.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/post.php';
	
	session_start();
	
	if($_SESSION['id'] == null)
		$_SESSION['id'] = 0;
	
	class GlobalUtils
	{
		const REGISTER_INVALID_EMAIL 	= 1;
		const REGISTER_INVALID_USER  	= 2;
		const REGISTER_TAKEN_USER		= 3;
		const REGISTER_TAKEN_EMAIL		= 4;
		const REGISTER_SUCCESS			= 5;
		const REGISTER_BLACKLISTED		= 6;
		
		const DATATYPE_STRNUM			= "";
		const DATATYPE_STRING			= "string";
		const DATATYPE_NUMBER			= "number";
		const DATATYPE_ALLDATA			= "everything";
		
		private static $BLACKLISTED_EMAIL_HOSTS = null;
		
		const ENABLE_LOG				= false;
		
		public static $user;
	
		/**Prints default CSS style tags, as well as any extras that are passed in. */
		public static function getCSS()
		{
			$cwd = substr(getcwd(), strlen($_SERVER['DOCUMENT_ROOT']));
			echo "\r\n<link rel='stylesheet' type='text/css' href='/global.css'>";
			echo "\r\n<link rel='stylesheet' type='text/css' href='$cwd/theme.css'>";
			
			foreach(func_get_args() as $extra)
				echo "\r\n<link rel='stylesheet' type='text/css' href='/$extra.css'>";	
			echo "\r\n";
		}
		
		/**Prints default JavaScript script tags, as well as any extras that are passed in. */
		public static function getJS()
		{
			echo "\r\n<script type='text/javascript' src='http://code.jquery.com/jquery-1.11.0.min.js'></script>";
			echo "\r\n<script type='text/javascript' src='/global.js'></script>";
			
			foreach(func_get_args() as $extra)
				echo "\r\n<script type='text/javascript' src='/$extra.js'></script>";	
			echo "\r\n";
		}

		public static function getMeta($keywords = array(), $description = 'Relatablez is a compilation of user-submitted posts starting with the phrase "Am I the only one". We offer users the opportunity to share their thoughts, secrets, fears; you name it, only to discover how connected we truly are.')
		{
			echo "\r\n<meta charset='UTF-8'>";
			echo "\r\n<meta name='keywords' content='Am I The Only One, Relatablez, Am I The Only One That";
			foreach($keywords as $keyword)
				echo ", $keyword";
			echo "'>";
			echo "\r\n<meta name='description' content='$description'>";
			echo "\r\n<link rel='shortcut icon' href='/images/icons/favicon.ico'>";
		}
		
		public static function getShareButton($url, $text)
		{
			echo "
			<button class='button small blue-hover' data-share-button=''>Share</button>
				<div class='share-wrapper'><div class='share-links'>
				<a href='http://www.facebook.com/sharer.php?u=$url'><img src='/images/icons/fb_ico.png' /></a>
				<a href='https://plus.google.com/share?url=$url'><img src='/images/icons/gp_ico.png' /></a>
				<a href='http://twitter.com/share?text=$text&url=$url&hashtags=Relatablez'><img src='/images/icons/tw_ico.png' /></a>
				</div>
			</div>";
		}
		
		public static function getFooter()
		{
			echo "<div class='footer'><div class='right'><span>&copy; Relatablez 2015</span><a href='/about/privacy'>Privacy</a><a href='/about/terms'>Terms</a><a href='/contact'>Contact</a></div></div>";
		}
		
		public static function validateRegistrationCredentials($user, $email)
		{
			//TODO instead of dying, point the user to a page pointing out what data was wrong.
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				return self::REGISTER_INVALID_EMAIL; // if email isn't valid, die for now.
			}
			if(!preg_match("/^[A-Za-z0-9_]+$/",$user)) // Check that username only contains alphanumerics and underscore at most
			{
				return self::REGISTER_INVALID_USER; // if username has wacky characters, die for now.
			}
			$uLen = strlen($user); // Get length of username
			if(($uLen > 32) || ($uLen < 1))
			{
				return self::REGISTER_INVALID_USER; // if username is larger than max length or less than one.
			}
			
			if(self::$BLACKLISTED_EMAIL_HOSTS)
			{
				$start = strpos($email, '@');
				$length = strrpos($email, '.') - $start;
				$webhost = substr($email, $start, $length);
				
				foreach($blacklisted as self::$BLACKLISTED_EMAIL_HOSTS)
				{
					if(strcasecmp($webhost, $blacklisted) !== 0) // check if the provided email comes from a blacklisted host.
						return self::REGISTER_BLACKLISTED;
				}
			}
			
			$connection = GlobalUtils::getConnection();
			
			if($statement = $connection->prepare("SELECT id FROM accounts WHERE username LIKE (?)"))
			{
				$statement->bind_param("s",$user);
				
				$statement->execute();
				
				$result = $statement->fetch();
				
				if(!empty($result))
				{
					return self::REGISTER_TAKEN_USER;
				}
			}
			
			if($statement = $connection->prepare("SELECT id FROM accounts WHERE email LIKE (?)"))
			{
				$statement->bind_param("s", $email);
				
				$statement->execute();
				
				$result = $statement->fetch();
				
				if(!empty($result))
				{
					return self::REGISTER_TAKEN_EMAIL;
				}
			}
			return self::REGISTER_SUCCESS;
		}
		
		public static function filter($data, $datatype = "everything")
		{
			$data = AntiXSS::setEncoding($data, "UTF-8");
			
			return AntiXSS::whitelistFilter($data, $datatype);
		}
		
		public static function log($message, $uid = 0, $ip = '127.0.0.1')
		{
			if(self::ENABLE_LOG)
			{
				$connection = GlobalUtils::getConnection();
				
				if($statement = $connection->prepare('INSERT INTO logs (message, uid, ip) VALUES ((?), (?), INET_ATON(?))'))
				{
					$statement->bind_param('sis', $message, $uid, $ip);
					$statement->execute();
				}
			}
		}
		
		public function generateVerification()
		{
			require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/password.php';
			
			$verification = md5(openssl_random_pseudo_bytes(128, $crypto_strong));
			$hash = password_hash($verification, PASSWORD_DEFAULT);
			
			return array('verification' => $verification, 'hash' => $hash);
		}
		
		/**Returns a connection to the MySQL database. */
		public static function getConnection()
		{
			if($_SERVER['SERVER_NAME'] != 'www.relatablez.com' && $_SERVER['SERVER_NAME'] != 'relatablez.com')
				return mysqli_connect('localhost','root','','u683362690_rtblz');
			else
				return mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
		}
	}
	
	GlobalUtils::$user = new User($_SESSION['id']);
	
	GlobalUtils::log(GlobalUtils::$user->getUsername() . ' accessed '. $_SERVER["REQUEST_URI"], $_SESSION['id'], $_SERVER['REMOTE_ADDR']);
?>
