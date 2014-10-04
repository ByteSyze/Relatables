<?php
	/*Copyright (C) Tyler Hackett 2014*/
	require($_SERVER['DOCUMENT_ROOT'] . '/userinfo.php');
	
	session_start();
	
	if($_SESSION['username'] == null)
		die('1');
	
	$pid 	 = $_POST['p'];
	$rid	 = $_POST['r'];
	$rUser	 = $_POST['u'];
	
	$comment = $_POST['c'];
	$clen = strlen($comment);
	
	if($clen < 1 || $clen > 140)
		die('2');
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($rid != 0)
		$comment = "@$rUser $comment";
	
	if($statement = $connection->prepare("INSERT INTO comments (pid, comment, uid, rid) VALUES (?,?,?,?)"))
	{
		$statement->bind_param('isii',$pid, $comment, $_SESSION['id'], $rid);
		$statement->execute();
		
		$comment = htmlspecialchars($comment);
		
		if($rid != 0)
		{
			notify($connection, 0, getId($connection, $rUser), "Reply from ".$_SESSION['username'], "You have received a reply from ".$_SESSION['username']." on <a href='http://www.relatablez.com/post/$pid'>this post</a>.");
			echo "<div class='comment reply'>";
		}
		else
			echo "<div class='comment'>";
		
		echo "<a href='http://www.relatablez.com/user/{$_SESSION['username']}'>{$_SESSION['username']}</a><span class='points'>0</span><span>0 seconds ago</span>";	

		if($rid != 0)
		{
			$rUserPos = strpos($comment, ' ');
			$rUser = substr($comment, 0, $rUserPos);
			$comment = substr($comment, $rUserPos, strlen($comment));
			echo "<p><a href='http://www.relatablez.com/user/$rUser'>@$rUser</a> $comment</p>";
		}
		else
			echo "<p>$comment</p>";
			
		echo "</div>";
		return;
	}
	
	echo('0');
		