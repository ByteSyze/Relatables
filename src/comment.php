<?php
	session_start();
	
	if($_SESSION['username'] == null)
		die('1');
	
	$comment = $_POST['c'];
	
	if(strlen($comment))
		die('2');