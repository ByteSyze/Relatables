<?php
	/*Copyright (C) Tyler Hackett 2014*/
	require($_SERVER['DOCUMENT_ROOT'] . '/userinfo.php');

	$id 	= $_GET['i'];
	$index 	= $_GET['x'];
	$count 	= $_GET['c'] + $index;
	$type 	= $_GET['t'];
	
	$connection = getConnection();
	
	if($type == 0)
	{
		//Long ass MYSQL query ftw
		if($statement = $connection->prepare("SELECT cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS submitted, rid, reply, (SELECT SUM(vote) FROM comment_ratings WHERE comment_ratings.cid = cid) AS points FROM comments WHERE pid=(?) ORDER BY submitted LIMIT ?,?"))
		{
			$statement->bind_param('iii',$id,$index,$count);
			$statement->execute();
			
			$statement->bind_result($com['cid'],$com['comment'],$com['user'],$com['submitted'],$com['rid'],$com['reply'],$com['points']);
			$statement->store_result();
		}
	}
	
	echo $connection->error;
	
	while($statement->fetch())
	{
		echo "<div>";
		
		if($com['reply'])
			echo "<a href='http://www.relatablez.com/user/{$com['user']}' class='reply'>{$com['user']}</a>";
		else
			echo "<a href='http://www.relatablez.com/user/{$com['user']}'>{$com['user']}</a>";
			
		if($com['points'] < 0)
			echo "<span id='points' class='negative'>{$com['points']}</span>";
		else if($com['points'] > 0)
			echo "<span id='points' class='positive'>{$com['points']}</span>";
		if($com['points'] < 0)
			echo "<span id='points'>{$com['points']}</span>";
			
		$submitted = DateTime::createFromFormat("m d Y H i", $com['submitted']);
		$time_since_upload = $submitted->diff(new DateTime());
		
		echo $time_since_upload->i;
			
		echo "</div>\r\n";
	}
	