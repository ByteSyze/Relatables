<?php
	/*Copyright (C) Tyler Hackett 2014*/
	
	function sort2mysql($sort)
	{
		if($sort) //If sort == 1, sort by highest-to-lowest rated comment
			return 'points';
		else
			return 'cid';
	}
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';

	$pid 	= intval($_POST['i']);
	$index 	= $_POST['x'];
	$count 	= $_POST['c'] > 50 ? 50 + $index : $_POST['c'] + $index;
	$sort	= sort2mysql(($_POST['s']));
	
	$connection = GlobalUtils::getConnection();
	
	//Long ass MYSQL query ftw
	if($statement = $connection->prepare("SELECT uid, cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS submitted, rid, (SELECT IFNULL(SUM(vote), 0) FROM comment_ratings WHERE comment_ratings.cid = comments.cid) AS points, reported, deleted FROM comments WHERE pid=(?) ORDER BY IF(rid = 0, $sort, rid) DESC, rid!=0 LIMIT ?,?"))
	{
		$statement->bind_param('iii',$pid,$index,$count);
		$statement->execute();
		
		$statement->bind_result($uid, $cid, $comment, $user, $submitted, $rid, $points, $reported, $deleted);
		$statement->store_result();
		
		$comment = htmlspecialchars($comment);
	}
	
	$now = new DateTime();
	
	while($statement->fetch())
	{
		if($rid != 0)
			echo "<div class='comment reply' id='c$cid' data-uid='$uid' data-user='$user' data-c='$cid' data-r='$rid'>";
		else
			echo "<div class='comment' id='c$cid' data-uid='$uid' data-user='$user' data-c='$cid' data-r='$cid'>";
			
		if($user == GlobalUtils::$user->getUsername() && !$deleted)
			echo "<button class='delete'></button>";
		
		echo "<div class='comment-info'>";
			echo "<span><a class='user' href='/user/$user'>$user</a></span>";
			
			$points_class = "";
			if($points < 0) $points_class = "negative";
			else if($points > 0) $points_class = "positive";

			echo "<span id='points-$cid' class='points $points_class'>$points</span>";
				
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
		echo "</div>";
		
		echo "<div class='comment-body'>";
			if($deleted)
			{
				echo "<span class='muted'>Comment removed by author.</span>";
			}
			else if($reported > 0)
			{
				echo "<span class='muted'>Comment removed by an administrator.</span>";
			}
			else if($rid != 0)
			{
				$rUserPos = strpos($comment, ' ');
				$rUser = substr($comment, 0, $rUserPos);
				$comment = substr($comment, $rUserPos, strlen($comment));
				echo "<p><a class='at user' href='/user/$rUser'>@$rUser</a> $comment</p>";
			}
			else
				echo $comment;
		echo "</div>";
		
		echo "<div class='comment-actions'>";
			if($_SESSION['id'])
			{
				echo "<span data-reply>Reply</span>";
				echo "<span data-v='up' class='vote upvote'></span>";
				echo "<span data-v='down' class='vote downvote'></span>";
			}
			else
			{
				echo "<span data-show='#registerpopup'>Reply</span>";
				echo "<span data-show='#registerpopup' class='vote upvote'></span>";
				echo "<span data-show='#registerpopup' class='vote downvote'></span>";
			}
			echo "<span data-report>Report</span>";
		echo "</div>";

		echo "</div>";
	}
	
	$num_rows = $statement->num_rows;
	$remaining = $connection->query("SELECT COUNT(cid) FROM comments WHERE pid=$pid")->fetch_array()[0] - ($index+$num_rows);
	
	if($remaining)
	{
		$show_index = $num_rows+$index;
		echo "<div class='show'><span data-showmore='$show_index'>Show More...</span></div>";
	}
	