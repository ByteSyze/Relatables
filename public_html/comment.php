<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	if($_SESSION['id'] == null)
		die('1');
	
	$pid 	 	= $_POST['p'];
	$rid	 	= $_POST['r'];
	$rUsername	= $_POST['u'];
	
	$comment = $_POST['c'];
	$clen = strlen($comment);
	
	if($clen < 1 || $clen > 140)
		die('2');
	
	$connection = GlobalUtils::getConnection();
	
	if($rid != 0)
		$comment = "$rUsername $comment";
	
	if($statement = $connection->prepare("INSERT INTO comments (pid, comment, uid, rid) VALUES (?,?,?,?)"))
	{
		$statement->bind_param('isii',$pid, $comment, $_SESSION['id'], $rid);
		$statement->execute();
		
		$cid = $connection->insert_id;
		$user = GlobalUtils::$user->getUsername();
		
		if($rid != 0)
		{
			$rUser = new User($rUsername, User::TYPE_STRING);
			$rUser->notify("Reply from $user","/post/$pid&lc=$cid");
			echo "<div class='comment reply' id='c$cid' data-uid='$_SESSION[id]' data-user='$user' data-c='$cid' data-r='$rid'>";
		}
		else
			echo "<div class='comment' id='c$cid' data-uid='$_SESSION[id]' data-user='$user' data-c='$cid' data-r='$cid'>";
		
		echo "<div class='comment-info'>";
		echo "<span><a class='user' href='/user/$user'>$user</a></span>";
		echo "<span id='points-$cid' class='points'>0</span><span>Just Now</span>";
		echo "</div>";
		
		echo "<div class='comment-body'>";

		if($rid != 0)
		{
			$rUsernamePos = strpos($comment, ' ');
			$rUsername = substr($comment, 0, $rUsernamePos);
			$comment = substr($comment, $rUsernamePos, strlen($comment));
			echo "<p><a href='/user/$rUsername'>@$rUsername</a> $comment</p>";
		}
		else
			echo $comment;
			
		echo "</div>";
		
		echo "<div class='comment-actions'>";
			echo "<span data-reply>Reply</span>";
			echo "<span data-v='up' class='vote upvote'></span>";
			echo "<span data-v='down' class='vote downvote'></span>";
			echo "<span data-report>Report</span>";
		echo "</div>";

		echo "</div>";
		return;
	}
	
	echo('0');
		