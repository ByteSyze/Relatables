<?php
	session_start();
	
	include_once $_SERVER['DOCUMENT_ROOT'] . '/userinfo.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/post.php';
	
	class GlobalUtils
	{
	
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
			echo "\r\n<link rel='shortcut icon' href='images/icons/favicon.ico'>";
		}
		
		public static function getShareButton($url, $text)
		{
			echo "
			<div class='share-button' data-share-button=''>Share &raquo;</div>
				<div class='share-wrapper'><div class='share-links'>
				<a href='http://www.facebook.com/sharer.php?u=$url'><img src='/images/icons/fb_ico.png' /></a>
				<a href='https://plus.google.com/share?url=$url'><img src='/images/icons/gp_ico.png' /></a>
				<a href='http://twitter.com/share?text=$text&url=$url&hashtags=Relatablez'><img src='/images/icons/tw_ico.png' /></a>
				</div>
			</div>";
		}
		
		public static function parseSubmission($submission)
		{
			$date_diff = $submission['date_diff'];
			
			if($date_diff/60/24/365 >= 1)
				$date_diff = '(' . floor($date_diff/60/24/365) . ' years ago)'; 
			else if($date_diff/60/24 >= 1)
				$date_diff = '(' . floor($date_diff/60/24) . ' days ago)'; 
			else if($date_diff/60 >= 1)
				$date_diff = '(' . floor($date_diff/60) . ' hours ago)'; 
			else
				$date_diff = '(' . floor($date_diff) . ' minutes ago)'; 
				
			if($submission['anonymous'])
				$user='Anonymous';
			else
				$user = $submission['author'];
			
			echo "\r\n<div class='dialogue downpadding' id='{$submission['id']}'>";
			echo "\r\n<p class='dialogue'>{$submission['submission']}</p>";
			echo "\r\n<table class='vote-table'>";
			echo "\r\n<tr>";
			if($_SESSION["username"] != null)
			{
				if($submission['user_vote'] === '0')
					echo "\r\n<td><button class='dialoguebutton' id='bna{$submission['id']}' data-vid='{$submission['id']}' data-v='{$submission['verification']}' disabled>No, me too!</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton' id='bna{$submission['id']}' data-vid='{$submission['id']}' data-v='{$submission['verification']}'>No, me too!</button></td>";
					
				if($submission['user_vote'] === '1')
					echo "\r\n<td><button class='dialoguebutton' id='ba{$submission['id']}'  data-vid='{$submission['id']}' data-v='{$submission['verification']}' disabled>You're alone.</button></td>";
				else
					echo "\r\n<td><button class='dialoguebutton' id='ba{$submission['id']}'  data-vid='{$submission['id']}' data-v='{$submission['verification']}'>You're alone.</button></td>";
			}
			else
			{
				echo "\r\n<td><button class='dialoguebutton showreg' data-signup-header='Please sign up to vote'>No, me too!</button></td>";
				echo "\r\n<td><button class='dialoguebutton showreg' data-signup-header='Please sign up to vote'>You're alone</button></td>";				
			}
			echo "\r\n<td><a href='/post/{$submission['id']}'  target='_blank' class='comment-button hover-icon'></a></td>";
			echo "\r\n<td>"; GlobalUtils::getShareButton("http://www.relatablez.com/post/$submission[id]", $submission['submission']); echo "</td>";
			echo "\r\n<tr>";
			echo "\r\n<td><span class='vote-counter' id='na{$submission['id']}'>(" . number_format($submission["notalone"]) . ")</span></td>";
			echo "\r\n<td><span class='vote-counter' id='a{$submission['id']}'>(" . number_format($submission["alone"]) . ")</span></td>";
			echo "\r\n</table>";
			echo "\r\n<div style='text-align:right;'><span class='submissioninfo'><a ";
			
			if($submission['anonymous'])
				echo ' >' . $user . "</a> - $date_diff</span></div>";
			else
			{
				if($submission['admin'])
					echo 'class=\'admin\'';
				echo " href='/user/$user'>$user</a> $date_diff</span></div>";
			}
			echo "\r\n</div>";
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
				return self::REGISTER_INVALID_USER; // if username is larger than max length or less than one, die for now.
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
				$statement->bind_param("s",$email);
				
				$statement->execute();
				
				$result = $statement->fetch();
				
				if(!empty($result))
				{
					return self::REGISTER_TAKEN_EMAIL;
				}
			}
			return self::REGISTER_SUCCESS;
		}
		
		/**Returns a connection to the MySQL database. */
		public static function getConnection()
		{
			if($_SERVER['SERVER_NAME'] != 'www.relatablez.com')
				return mysqli_connect('localhost','root','','u683362690_rtblz');
			else
				return mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
		}
	}
?>