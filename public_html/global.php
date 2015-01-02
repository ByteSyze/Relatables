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
			echo "\r\n<script type='text/javascript' src='http://www.relatablez.com/global.js'></script>";
			
			foreach(func_get_args() as $extra)
				echo "\r\n<script type='text/javascript' src='http://www.relatablez.com/$extra.js'></script>";	
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
			echo "\r\n<link rel='shortcut icon' href='/favicon.ico'>";
		}
		
		public static function getShareButton($url, $text)
		{
			echo "\r\n<div class='share-button' data-share-button=''>Share &raquo;</div>";
			echo "\r\n<div class='share-wrapper'><div class='share-links'>";
			echo "\r\n<a href='http://www.facebook.com/sharer.php?u=$url'><img src='fb_ico.png' /></a>";
			echo "\r\n<a href='https://plus.google.com/share?url=$url'><img src='gp_ico.png' /></a>";
			echo "\r\n<a href='http://twitter.com/share?text=$text&url=$url&hashtags=Relatablez'><img src='tw_ico.png' /></a>";
			echo "\r\n</div></div>";
		}
		/**Returns a connection to the MySQL database. */
		public static function getConnection()
		{
			return mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
		}
	}
?>