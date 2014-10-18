<?php
	session_start();
	
	require($_SERVER['DOCUMENT_ROOT'] . '/userinfo.php');
	
	$connection = getConnection();
	
	if(!isAdmin($connection, $_SESSION['id']))
	{
		echo "not admin";
		header("HTTP/1.0 404 Not Found");
		die();
	}
	else
	{
		$reported_comments = $connection->query("SELECT cid, comment, (SELECT username FROM accounts WHERE id=comments.uid) AS author, reports FROM comments WHERE reported = 0 AND deleted = 0");
	}
?>
<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014 -->
<html>
	<head>
		<title>Reported Content</title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content="Relatablez – Is it Just You? Relatablez is website that connects people using the things we do in our life to see if others feel or do the same.">
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
						<tr><th>Author</th><th>Comment</th><th>Reports</th>
						<?php
							while($comment = $reported_comments->fetch_array())
							{
								echo "<tr><td>{$comment['author']}<td>{$comment['comment']}<td>{$comment['reports']}";
							}
						?>
					</table>
				</div>
				
				<h3>Reported Posts:</h3>
				<div class='reported'>
				
				</div>
			</div>
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/toolbar.js'></script>
	</body>
</html>

