<?php
	session_start();
	
	if($_SESSION['username'] == null)
		die('1');
	
	$comment = $_POST['c'];
	$clen = strlen($comment);
	
	if($clen < 1 || $clen > 140)
		die('2');
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare("INSERT INTO comments (pid, comment, uid, rid, reply) VALUES (?,?,?,?,?)"))
	{
		
	}
		