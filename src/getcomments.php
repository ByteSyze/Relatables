<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();

	$pid 	= $_POST['i'];
	$index 	= $_POST['x'];
	$count 	= $_POST['c'] > 50 ? 50 + $index : $_POST['c'] + $index;
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	//Long ass MYSQL query ftw
	if($statement = $connection->prepare("SELECT cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, (SELECT username FROM accounts WHERE 0) AS rUser, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS submitted, rid, (SELECT IFNULL(SUM(vote), 0) FROM comment_ratings WHERE comment_ratings.cid = comments.cid) AS points FROM comments WHERE pid=(?) ORDER BY IF(rid = 0, cid, rid) DESC, rid!=0, cid LIMIT ?,?"))
	{
		$statement->bind_param('iii',$pid,$index,$count);
		$statement->execute();
		
		$statement->bind_result($cid, $comment, $user, $rUser, $submitted, $rid, $points);
		$statement->store_result();
		
		$comment = htmlspecialchars($comment);
	}
	
	$now = new DateTime();
	
	echo $connection->error;
	
	while($statement->fetch())
	{
		if($rid != 0)
			echo "<div class='comment reply' data-uid='$cid' data-user='$user' data-c='$cid' data-r='$rid'>";
		else
			echo "<div class='comment' data-uid='$cid' data-user='$user' data-c='$cid'>";
			
		if($user == $_SESSION['username'])
			echo "<button id='delete-$cid' class='delete'></button>";
		
		echo "<a href='http://www.relatablez.com/user/$user'>$user</a>";
			
		if($points < 0)
			echo "<span id='points-$cid' class='points negative'>$points</span>";
		else if($points > 0)
			echo "<span id='points-$cid' class='points positive'>$points</span>";
		else
			echo "<span id='points-$cid' class='points'>$points</span>";
			
		$submitted = DateTime::createFromFormat("m d Y H i", $submitted);
		$time_diff = $submitted->diff($now);
		
		if($time_diff->y)
			$time_diff = $time_diff->y . ' years ago';
		else if($time_diff->m)
			$time_diff = $time_diff->m . ' months ago';
		else if($time_diff->d)
			$time_diff = $time_diff->d . ' days ago';
		else if($time_diff->h)
			$time_diff = $time_diff->h . ' hours ago';
		else
			$time_diff = $time_diff->i . ' minutes ago';
		
		echo "<span>$time_diff</span>";
		
		if($rid != 0)
		{
			$rUserPos = strpos($comment, ' ');
			$rUser = substr($comment, 0, $rUserPos);
			$comment = substr($comment, $rUserPos, strlen($comment));
			echo "<p><a href='http://www.relatablez.com/user/$rUser'>$rUser</a> $comment</p>";
		}
		else
			echo "<p>$comment</p>";
		
		if($rid == 0)
			echo "<span data-user='$user' data-reply='$cid'>Reply</span><button data-c='$cid' data-v='up' class='up vote'></button><button data-c='$cid' data-v='down' class='down vote'></button><span data-report='$cid'>Report</span>";
		else
			echo "<span data-user='$user' data-reply='$rid'>Reply</span><button data-c='$rid' data-v='up' class='up vote'></button><button data-c='$rid' data-v='down' class='down vote'></button><span data-report='$rid'>Report</span>";
			
		echo "</div>\r\n";
	}
	