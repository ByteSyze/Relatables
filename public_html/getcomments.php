<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
	function sort2mysql($sort)
	{
		if($sort) //If sort == 1, sort by highest-to-lowest rated comment
			return 'points';
		else
			return 'cid';
	}
	
	
	function format_comment($comment, $reply = null)
	{
	
		$now = new DateTime();
		$comment['comment'] = htmlspecialchars($comment['comment']);
		
		if($comment['rid'] != 0)
			echo "<div class='comment reply' id='c{$comment[cid]}' data-uid='{$comment[uid]}' data-user='{$comment[user]}' data-c='{$comment[cid]}' data-r='{$comment[rid]}'>";
		else
			echo "<div class='comment' id='c{$comment[cid]}' data-uid='{$comment[uid]}' data-user='{$comment[user]}' data-c='{$comment[cid]}' data-r='{$comment[cid]}'>";
			
		if($comment['user'] == GlobalUtils::$user->getUsername() && !$comment['deleted'])
			echo "<button class='delete'></button>";
		
		echo "<div class='comment-info'>";
			echo "<span><a class='user' href='/user/{$comment[user]}'>{$comment[user]}</a></span>";
			
			$points_class = "";
			if($comment['points'] < 0) $points_class = "negative";
			else if($comment['points'] > 0) $points_class = "positive";

			echo "<span id='points-{$comment[cid]}' class='points $points_class'>{$comment[points]}</span>";
				
			$comment['submitted'] = DateTime::createFromFormat("m d Y H i", $comment['submitted']);
			$time_diff = $comment['submitted']->diff($now);
			
			if($time_diff->y)
				$time_diff = $time_diff->y . ' yr ago';
			else if($time_diff->m)
				$time_diff = $time_diff->m . ' mt ago';
			else if($time_diff->d)
				$time_diff = $time_diff->d . ' dy ago';
			else if($time_diff->h)
				$time_diff = $time_diff->h . ' hr ago';
			else
				$time_diff = $time_diff->i . ' mn ago';
			
			echo "<span>$time_diff</span>";
		echo "</div>";
		
		echo "<div class='comment-body'>";
			if($comment['deleted'] === 1)
			{
				echo "<span class='muted'>Comment removed by author.</span>";
			}
			else if($comment['reported'] > 0)
			{
				echo "<span class='muted'>Comment removed by an administrator.</span>";
			}
			
			echo $comment['comment'];			
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
		
		if($reply)
			format_comment($reply);

		echo "</div>";
	}
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';

	$pid 	= intval($_POST['i']);
	$index 	= $_POST['x'];
	$count 	= $_POST['c'] > 50 ? 50 + $index : $_POST['c'] + $index;
	$sort	= sort2mysql($_POST['s']);
	
	$comment = array();
	
	$connection = GlobalUtils::getConnection();
	
	if($statement = $connection->prepare("SELECT uid, cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS f_submitted, (SELECT IFNULL(SUM(vote), 0) FROM comment_ratings WHERE comment_ratings.cid = comments.cid) AS points, reported, deleted FROM comments WHERE rid=0 AND pid=(?) ORDER BY $sort DESC LIMIT ?,?"))
	{
		$statement->bind_param('iii',$pid,$index,$count);
		$statement->execute();
		
		//$statement->bind_result($uid, $cid, $comment, $user, $submitted, $points, $reported, $deleted);
		$statement->bind_result($comment['uid'], $comment['cid'], $comment['comment'], $comment['user'], $comment['submitted'], $comment['points'], $comment['reported'], $comment['deleted']);
		$statement->store_result();
	}
	
	$cid_array = array();
	
	while($statement->fetch())
		array_push($cid_array, $comment['cid']);
		
	$cid_array_str = implode(',', $cid_array);
		
	$replies = $connection->query("SELECT uid, cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS submitted, rid, (SELECT IFNULL(SUM(vote), 0) FROM comment_ratings WHERE comment_ratings.cid = comments.cid) AS points, reported, deleted FROM comments WHERE rid IN ($cid_array_str) GROUP BY rid ORDER BY FIELD(rid, $cid_array_str)");
	
	$reply = $replies->fetch_assoc(); //Get first reply
	
	$statement->data_seek(0); //Return to start of results.
	
	while($statement->fetch())
	{
		if($reply['rid'] == $comment['cid']) //If the current reply is a reply to this comment, pass it in.
		{
			format_comment($comment, $reply);
			$reply = $replies->fetch_assoc();
		}
		else
		{
			format_comment($comment);
		}
	}
	
	$num_rows = $statement->num_rows;
	$remaining = $connection->query("SELECT COUNT(cid) FROM comments WHERE rid=0 AND pid=$pid")->fetch_array()[0] - ($index+$num_rows);
	
	if($remaining)
	{
		$show_index = $num_rows+$index;
		echo "<div class='show'><span data-showmore='$show_index'>Show More...</span></div>";
	}
	