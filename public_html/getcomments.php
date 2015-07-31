<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
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
	$count 	= $index+10;
	$sort	= sort2mysql($_POST['s']);
	
	$comment = array();
	
	$connection = GlobalUtils::getConnection();
	
	if($statement = $connection->prepare("SELECT uid, cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS f_submitted, (SELECT IFNULL(SUM(vote), 0) FROM comment_ratings WHERE comment_ratings.cid = comments.cid) AS points, reported, deleted, (SELECT vote FROM comment_ratings WHERE comment_ratings.cid=comments.cid AND uid=" . $_SESSION['id'] . ") FROM comments WHERE rid=0 AND pid=(?) ORDER BY $sort DESC LIMIT ?,?"))
	{
		$statement->bind_param('iii',$pid,$index,$count);
		$statement->execute();
		
		$statement->bind_result($comment['uid'], $comment['cid'], $comment['comment'], $comment['user'], $comment['submitted'], $comment['points'], $comment['reported'], $comment['deleted'], $comment['user_vote']);
		$statement->store_result();
	}
	
	$cid_array = array();
	
	while($statement->fetch())
		array_push($cid_array, $comment['cid']);
		
	$cid_array_str = implode(',', $cid_array);
		
	$replies = $connection->query("SELECT uid, cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS submitted, rid, (SELECT IFNULL(SUM(vote), 0) FROM comment_ratings WHERE comment_ratings.cid = comments.cid) AS points, reported, deleted AS total_replies FROM comments WHERE cid in (SELECT MAX(cid) FROM comments WHERE pid=$pid GROUP BY rid) AND rid IN ($cid_array_str) ORDER BY rid DESC");
	$reply_counts = $connection->query("SELECT COUNT(*) FROM comments WHERE rid IN ($cid_array_str) GROUP BY rid ORDER BY cid DESC"); //Total replies per comment
	
	if($replies)
	{
		$reply = $replies->fetch_assoc(); //Get first reply
		$reply_count = $reply_counts->fetch_array();
		$reply['total_replies'] = $reply_count[0];
	}
	
	$statement->data_seek(0); //Return to start of comment results.
	
	while($statement->fetch())
	{
		if($reply['rid'] == $comment['cid']) //If the current reply is a reply to this comment, pass it in.
		{
			$reply_count = $reply_counts->fetch_array();
			$reply['total_replies'] = $reply_count[0]; // Force "show more" on all replies.
			GlobalUtils::formatComment($comment, $reply);
			$reply = $replies->fetch_assoc(); //Grab next reply
		}
		else
		{
			GlobalUtils::formatComment($comment);
		}
	}
	
	$num_rows = $statement->num_rows;
	$remaining = $connection->query("SELECT COUNT(cid) FROM comments WHERE rid=0 AND pid=$pid")->fetch_array()[0] - ($index+$num_rows);
	
	if($remaining)
	{
		$show_index = $num_rows+$index;
		echo "<div class='show'><span data-showmore='$show_index'>Show More</span></div>";
	}
	