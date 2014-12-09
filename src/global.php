<?php
	
	//Prints default CSS style tags, as well as any extras that are passed in.
	function getCSS()
	{
		$cwd = substr(getcwd(), strlen($_SERVER['DOCUMENT_ROOT']));
		$base = basename(getcwd());
		echo "\r\n<link rel='stylesheet' type='text/css' href='/global.css'>";
		echo "\r\n<link rel='stylesheet' type='text/css' href='$cwd/$base.css'>";
		foreach(func_get_args() as $extra)
			echo "\r\n<link rel='stylesheet' type='text/css' href='/$extra.css'>";	
		echo "\r\n";
	}
	
	//Returns a connection to the MySQL database.
	function getConnection()
	{
		return mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	}
	
?>