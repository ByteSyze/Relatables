<?php
	/*Copyright (C) Tyler Hackett 2014*/

	$id 	= $_POST['i'];
	$rid	= $_POST['r'];
	$index 	= $_POST['x'];
	$count 	= $_POST['c'] + $index;
	$type 	= $_POST['t'];
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($type == 0)
	{
		//Long ass MYSQL query ftw
		if($statement = $connection->prepare("SELECT cid, comment, (SELECT username FROM accounts WHERE accounts.id=uid) AS user,(SELECT username FROM accounts WHERE accounts.id=rid) AS rUser, DATE_FORMAT(submitted,'%m %d %Y %H %i') AS submitted, rid, (SELECT IFNULL(SUM(vote), 0) FROM comment_ratings WHERE comment_ratings.cid = comments.cid) AS points FROM comments WHERE pid=(?) AND rid=(?) ORDER BY rid DESC, cid LIMIT ?,?"))
		{
			$statement->bind_param('iiii',$id,$rid,$index,$count);
			$statement->execute();
			
			$statement->bind_result($com['cid'],$com['comment'],$com['user'],$com['rUser'],$com['submitted'],$com['rid'],$com['points']);
			$statement->store_result();
			
			$com['comment'] = htmlspecialchars($com['comment']);
		}
	}
	
	echo $connection->error;
	
	$now = new DateTime();
	
	while($statement->fetch())
	{
		echo "<div> class='comment reply'";
		
		echo "<a href='http://www.relatablez.com/user/{$com['user']}'>{$com['user']}</a>";
			
		if($com['points'] < 0)
			echo "<span class='points negative'>{$com['points']}</span>";
		else if($com['points'] > 0)
			echo "<span class='points positive'>{$com['points']}</span>";
		if($com['points'] < 0)
			echo "<span class='points'>{$com['points']}</span>";
			
		$submitted = DateTime::createFromFormat("m d Y H i", $com['submitted']);
		$time_diff = $submitted->diff($now);
		
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
		
		echo "<p><a href='http://www.relatablez.com/user/{$com['rUser']}'>@{$com['rUser']}</a> {$com['comment']}</p>";
			
		echo "</div>\r\n";
	}
	