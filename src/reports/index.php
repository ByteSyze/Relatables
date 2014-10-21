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
		$reported_comments  = $connection->query("SELECT pid, cid, comment, (SELECT username FROM accounts WHERE id=comments.uid) AS author, (SELECT COUNT(id) FROM comment_reports WHERE comment_reports.id=comments.cid) AS reports FROM comments WHERE reported = 0 AND deleted = 0 ORDER BY reports DESC");
		$reported_posts		= $connection->query("SELECT id, submission, (SELECT username FROM accounts WHERE id=submissions.uid) AS author, (SELECT COUNT(submission_reports.id) FROM submission_reports WHERE submission_reports.id=submissions.id) AS reports FROM submissions WHERE reported = 0 AND pending = 0 ORDER BY reports DESC");
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
			td.but
			{
				cursor:pointer;
				font-weight:bold;
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
								echo "<tr><td><a href='http://www.relatablez.com/post/{$comment['pid']}#{$comment['cid']}'>{$comment['cid']}</a><td>{$comment['author']}<td>{$comment['comment']}<td>{$comment['reports']}<td class='but' data-creport='{$comment['cid']}'>Delete<td class='but' data-ckeep='{$comment['cid']}'>Keep";
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
								echo "<tr><td><a href='http://www.relatablez.com/post/{$post['id']}'>{$post['id']}</a><td>{$post['author']}<td>{$post['submission']}<td>{$post['reports']}<td class='but' data-sreport='{$post['id']}'>Delete<td class='but' data-skeep='{$post['id']}'>Keep";
							}
						?>
					</table>
				</div>
			</div>
		</div>
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/toolbar.js'></script>
		<script type='text/javascript'>
			$('[data-creport]').click(function()
			{
				cid = $(this).attr('data-creport');
				row = $(this).parent();
				
				$.post('/report.php', {c: cid}, function(){ row.remove(); });
			});
			$('[data-sreport]').click(function()
			{
				pid = $(this).attr('data-sreport');
				row = $(this).parent();
				
				$.post('/report.php', {s: pid}, function(){ row.remove(); });
			});
			$('[data-ckeep]').click(function()
			{
				cid = $(this).attr('data-ckeep');
				row = $(this).parent();
				
				$.post('/unreport.php', {c: cid}, function(){ row.remove(); });
			});
			$('[data-skeep]').click(function()
			{
				pid = $(this).attr('data-skeep');
				row = $(this).parent();
				
				$.post('/unreport.php', {s: pid}, function(){ row.remove(); });
			});
		</script>
	</body>
</html>
