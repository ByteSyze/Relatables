<?php
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
				echo "\r\n<script type='text/javascript' href='/$extra.js'></script>";	
			echo "\r\n";
		}
		
		/**Returns a connection to the MySQL database. */
		public static function getConnection()
		{
			return mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
		}
	}
?>