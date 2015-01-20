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
			echo "\r\n<div class='share-button' data-share-button=''>Share &raquo;</div>";
			echo "\r\n<div class='share-wrapper'><div class='share-links'>";
			echo "\r\n<a href='http://www.facebook.com/sharer.php?u=$url'><img src='/images/icons/fb_ico.png' /></a>";
			echo "\r\n<a href='https://plus.google.com/share?url=$url'><img src='/images/icons/gp_ico.png' /></a>";
			echo "\r\n<a href='http://twitter.com/share?text=$text&url=$url&hashtags=Relatablez'><img src='/images/icons/tw_ico.png' /></a>";
			echo "\r\n</div></div>";
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
			
			echo '<div class="box">';
				echo '<div class="box-content">';
					echo $submission['submission'];
					echo '<div class="post-actions">';
						echo '<div class="buttons">';

							$button_yes_classes = "green-hover";
							$button_yes_meta = "";
							$button_no_classes = "red-hover";
							$button_no_meta = "";

							if($_SESSION["username"] != null) {
									$button_yes_meta = $button_no_meta = 'id="bna{$submission[\'id\']}" data-vid="{$submission[\'id\']}" data-v="{$submission[\'verification\']}" ';
									if($submission['user_vote'] === '0') {
										$button_yes_classes = "green";
										$button_yes_meta .= "disabled";
									} else if($submission['user_vote'] === '1') {
										$button_no_classes = "red";
										$button_no_meta .= "disabled";
									}
							} else {
								$button_no_meta = "data-signup-header='Please sign up to vote'";
								$button_yes_meta = "data-signup-header='Please sign up to vote'";
							}

							echo '<button class="button small ' . $button_yes_classes . '" ' . $button_yes_meta . '>No, me too!</button>';
							echo '<button class="button small ' . $button_no_classes . '" ' . $button_no_meta . '>No, me too!</button>';
							echo '<a href="/post/' . $submission[id] . '" class="button small">...</a>';

						echo '</div>';
						echo '<div class="submission-info">';
							if($submission['anonymous']) {
								echo '<span>' . $user . '</span>';
							} else {
								echo '<a class="user ';

								if($submission['admin'])
									echo 'admin';

								echo '" href="/user/' . $user . '">' . $user . '</a>';
							}

							echo '<span class="datediff">' . $date_diff . '</span>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
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
?>