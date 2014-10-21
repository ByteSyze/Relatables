<?php
	session_start();
	
	require($_SERVER['DOCUMENT_ROOT'] . '/userinfo.php');
	
	$connection = getConnection();
	
	if(!isAdmin($connection, $_SESSION['id']))
	{
		header("HTTP/1.0 404 Not Found");
		die();
	}
	else
	{
		$reported_comments  = $connection->query("SELECT pid, cid, comment, (SELECT username FROM accounts WHERE id=comments.uid) AS author, (SELECT COUNT(cid) FROM comment_reports WHERE comment_reports.cid=comments.cid) AS reports FROM comments WHERE reported = 0 AND deleted = 0 ORDER BY reports DESC");
		$reported_posts		= $connection->query("SELECT id, submission, (SELECT username FROM accounts WHERE id=submissions.uid) AS author, (SELECT COUNT(pid) FROM submission_reports WHERE submission_reports.pid=submissions.id) AS reports FROM submissions WHERE reported = 0 AND reported = 0 ORDER BY reports DESC");
	}
?>
<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014 -->
<html>
	<head>
		<title>Reported Content</title>
		
		<meta charset="UTF-8">
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/toolbartheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
		<style type='text/css' >	
			#main
			{
				width:850px;
				margin:70px auto auto;
				background:white;
				box-shadow:0px 0px 10px #BFBFBF;
			}
			#reported-content
			{
				padding:20px;
				font-size:17px;
			}
			#title
			{
				margin:0px;
				padding:20px;
				color:#FFF;
				background-color:#4a66d8;
			}
			div.reported
			{
				max-height:300px;
				overflow-y:auto;
				padding:5px;
			}
			table
			{
				width:100%;
			}
			th
			{
				font-weight:bold;
				font-size:19px;
			}
			td
			{
				text-align:center;
				border:1px solid #BFBFBF;
			}
		</style>
	</head>
	<body>
		<?php require($_SERVER['DOCUMENT_ROOT']."/toolbar.php"); ?>
		<div id='main'>
			<h1 id='title'>Reported Content</h1>
			<div id='reported-content'>
			
				<h3>Reported Comments:</h3>
				<div class='reported'>
					<table>
						<tr><th>Link</th><th>Author</th><th>Comment</th><th>Reports</th>
						<?php
							while($comment = $reported_comments->fetch_array())
							{
								echo "<tr><td><a href='http://www.relatablez.com/post/{$comment['pid']}#{$comment['cid']}'>{$comment['cid']}</a><td>{$comment['author']}<td>{$comment['comment']}<td>{$comment['reports']}";
							}
						?>
					</table>
				</div>
				
				<h3>Reported Posts:</h3>
				<div class='reported'>
					<table>
						<tr><th>Link</th><th>Author</th><th>Comment</th><th>Reports</th>
						<?php
							while($post = $reported_posts->fetch_array())
							{
								echo "<tr><td><a href='http://www.relatablez.com/post/{$post['id']}'>{$post['id']}</a><td>{$post['author']}<td>{$post['submission']}<td>{$post['reports']}";
							}
						?>
					</table>
				</div>
			</div>
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/toolbar.js'></script>
	</body>
</html>
