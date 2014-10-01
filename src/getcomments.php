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
		if($statement = $connection->prepare("SELECT cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS submitted, rid, (SELECT SUM(vote) FROM comment_ratings WHERE comment_ratings.cid = cid) AS points FROM comments WHERE pid=(?) ORDER BY IF(rid = 0, cid, rid) DESC, rid!=0, cid LIMIT ?,?"))
		{
			$statement->bind_param('iii',$id,$index,$count);
			$statement->execute();
			
			$statement->bind_result($com['cid'],$com['comment'],$com['user'],$com['submitted'],$com['rid'],$com['points']);
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
		$time_diff = $submitted->diff(new DateTime());
		
		if($time_diff->y)
			$time_diff = $time_diff->y . ' years ago';
		else if($time_diff->m)
			$time_diff = $time_diff->m . ' months ago';
		else if($time_diff->d)
			$time_diff = $time_diff->d . ' days ago';
		else if($time_diff->h)
			$time_diff = $time_diff->h . ' hours ago';
		else if($time_diff->i)
			$time_diff = $time_diff->i . ' minutes ago';
		else if($time_diff->s)
			$time_diff = $time_diff->s . ' seconds ago';
		
		echo "<span>$time_diff</span>";
		
		echo "<p>{$com['comment']}</p>";
			
		echo "</div>\r\n";
	}
	