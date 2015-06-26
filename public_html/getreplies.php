<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$cid 	= $_POST['i']; // The root comment to grab replies from
	$index 	= $_POST['x']; // The index of the first reply to grab
	$count 	= $index + 10;
	
	$reply = array();
	
	$connection = GlobalUtils::getConnection();
	
	if($statement = $connection->prepare("SELECT uid, rid, cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS f_submitted, (SELECT IFNULL(SUM(vote), 0) FROM comment_ratings WHERE comment_ratings.cid = comments.cid) AS points, reported, deleted FROM comments WHERE rid=(?) ORDER BY cid DESC LIMIT ?,?"))
	{
		$statement->bind_param('iii',$cid,$index,$count);
		$statement->execute();
		
		$statement->bind_result($reply['uid'], $reply['rid'], $reply['cid'], $reply['comment'], $reply['user'], $reply['submitted'], $reply['points'], $reply['reported'], $reply['deleted']);
		$statement->store_result();
	}
	
	$reply_index = 0;
	
	while($statement->fetch())
	{
		if($reply_index++ < 10)
			GlobalUtils::formatComment($reply);
		else
			break;
	}
	
	if($statement->num_rows == 10)
		echo "<span data-r-showmore='10'>Show More</span>";
	