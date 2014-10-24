<?php
	/*Copyright (C) Tyler Hackett 2014*/
	session_start();

	$pid 	= intval($_POST['i']);
	$index 	= $_POST['x'];
	$count 	= $_POST['c'] > 50 ? 50 + $index : $_POST['c'] + $index;
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	//Long ass MYSQL query ftw
	if($statement = $connection->prepare("SELECT uid, cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS submitted, rid, (SELECT IFNULL(SUM(vote), 0) FROM comment_ratings WHERE comment_ratings.cid = comments.cid) AS points, reported, deleted FROM comments WHERE pid=(?) ORDER BY IF(rid = 0, cid, rid) DESC, rid!=0, cid LIMIT ?,?"))
	{
		$statement->bind_param('iii',$pid,$index,$count);
		$statement->execute();
		
		$statement->bind_result($uid, $cid, $comment, $user, $submitted, $rid, $points, $reported, $deleted);
		$statement->store_result();
		
		$comment = htmlspecialchars($comment);
	}
	
	$now = new DateTime();
	
	echo $connection->error;
	
	while($statement->fetch())
	{
		if($rid != 0)
			echo "<div class='comment reply' data-uid='$uid' data-user='$user' data-c='$cid' data-r='$rid'>";
		else
			echo "<div class='comment' data-uid='$uid' data-user='$user' data-c='$cid' data-r='$cid'>";
			
		if($user == $_SESSION['username'] && !$deleted)
			echo "<button data-delete class='delete'></button>";
		
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
		
		if($deleted)
		{
			echo "<p><i>Comment removed by author.</i></p>";
		}
		else if($reported)
		{
			echo "<p><i>Comment removed by an administrator.</i></p>";
		}
		else if($rid != 0)
		{
			$rUserPos = strpos($comment, ' ');
			$rUser = substr($comment, 0, $rUserPos);
			$comment = substr($comment, $rUserPos, strlen($comment));
			echo "<p><a href='http://www.relatablez.com/user/$rUser'>$rUser</a> $comment</p>";
		}
		else
			echo "<p>$comment</p>";
		
		echo "<span data-reply>Reply</span><button data-v='up' class='up vote'></button><button data-v='down' class='down vote'></button><span data-report>Report</span>";
			
		echo "</div>\r\n";
	}
	
	$num_rows = $statement->num_rows;
	$remaining = $connection->query("SELECT COUNT(cid) FROM comments WHERE pid=$pid")->fetch_array()[0] - ($index+$num_rows);
	
	if($remaining)
	{
		$show_index = $num_rows+$index;
		echo "<div class='show'><span data-show='$show_index'>Show More</span></div>";
	}
	