<?php
	session_start();
	
	require($_SERVER['DOCUMENT_ROOT'] . '/userinfo.php');
	
	$pid = $_GET['id'];
	
	//If post id ends in a /, remove it.
	$slashpos = strpos($pid,'/');
	
	if($slashpos != false)
		$pid = substr($pid,0,$slashpos);
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare("SELECT uid, verification, category, DATE_FORMAT(date,'%M %d, %Y') AS date, alone, notalone, pending, submission, anonymous FROM submissions WHERE id=(?)"))
	{
		$statement->bind_param('i',$pid);
		$statement->execute();
		$statement->bind_result($uid, $verif, $cat, $fdate, $alone, $notalone, $pending, $submission, $anon);
		$statement->fetch();
	}
?>
<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<html>
	<head>
		<title>Post</title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content="Relatablez – Is it Just You? Relatablez is website that connects people using the things we do in our life to see if others feel or do the same.">
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/toolbartheme.css">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/post.css">
		<link rel="canonical" href="http://www.relatablez.com/">
	</head>
	<body>
		<?php require($_SERVER["DOCUMENT_ROOT"] . "/toolbar.php"); ?>
		
		<div id='main'>
			<div id='content'>
				<?php
					if($anon)
						$user='Anonymous';
					else
						$user = getUsername($connection, $uid);
					
					echo "\r\n<div class='dialogue uppadding' id='{$pid}'>";
					echo "\r\n<p class='dialogue'>{$submission}</p>";
					echo "\r\n<table class='vote-table'>";
					echo "\r\n<tr>";
					if($_SESSION["username"] != null)
					{
						echo "\r\n<td><button class='dialoguebutton' id='bna{$pid}' data-vid='{$pid}' data-v='{$verif}'>No, me too!</button></td>";
						echo "\r\n<td><button class='dialoguebutton' id='ba{$pid}'  data-vid='{$pid}' data-v='{$verif}'>You're alone.</button></td>";
					}
					else
					{
						echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>No, me too!</button></td>";
						echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>You're alone.</button></td>";				
					}
					echo "\r\n<tr>";
					echo "\r\n<td><span class='vote-counter' id='na{$pid}'>(" . number_format($notalone) . ")</span></td>";
					echo "\r\n<td><span class='vote-counter' id='a{$pid}'>(" . number_format($alone) . ")</span></td>";
					echo "\r\n</table>";
					echo "\r\n<div style='text-align:right;'><span class='submissioninfo'><a ";
					
					if($anon)
						echo ' >' . $user . "</a> - {$fdate}</span></div>";
					else
					{
						if(isAdmin($connection, $uid))
							echo 'class=\'admin\'';
						echo " href='http://www.relatablez.com/user/" . $user . "'>" . $user . "</a>  " . $fdate . "</span></div>";
					}
					echo "\r\n</div>";
				?>
				<span>Comments (<span id='comment-count'>0</span>)</span>
				<div id='comments'>
					<div id='comment-submit-wrapper'>
						<form action='http://www.relatablez.com/comment.php' method='POST'>
							<textarea id='comment-submit-text' name='c'></textarea>
							<input id='comment-submit-button' type='submit' value='Post' />
						</form>
					</div>
				</div>
			</div>
		</div>
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/toolbar.js'></script>
		<script src='http://www.relatablez.com/vote.js'></script>
		<script type='text/css'>
			$('#comment-submit-button').click(function()
			{
				comment = $('#comment-submit-text').val();
				
				if(comment.length <= 140)
				{
					$.post('http://www.relatablez.com/comment.php', {c: comment}, function(result)
					{
						
					});
				}
				else
				{
					//TODO red border around comment box.
				}
			});
		</script>
	</body>
</html>