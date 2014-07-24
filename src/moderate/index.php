<?php  
	session_start();
	require_once('../userinfo.php');
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');

	if (mysqli_connect_errno())
	{
		echo 'Failed to connect to MySQL: ' . mysqli_connect_error();
	}	
	
	$index = getModerationIndex($_SESSION['id']);
	
	if($_POST['v'] !== null)
	{
		if($_SESSION['id'] !== null)
		{
			$submissions = mysqli_query($connection,'SELECT submission, id FROM submissions WHERE pending=1 ORDER BY submissions.date LIMIT '.$index.', '.($index+2));
			$submission = mysqli_fetch_array($submissions); // The submission that was voted on.
			
			if($_POST['v'] === 'Yes')
			{
				mysqli_query($connection, 'UPDATE submissions SET notalone=notalone+1 WHERE id=' . $submission['id']);
				incModerationIndex($_SESSION['id']);
			}
			else if($_POST['v'] === 'No')
			{
				mysqli_query($connection, 'UPDATE submissions SET alone=alone+1 WHERE id=' . $submission['id']);
				incModerationIndex($_SESSION['id']);
			}
		}
	}
	else
		$submissions = mysqli_query($connection,'SELECT submission, id FROM submissions WHERE pending=1 ORDER BY submissions.date LIMIT '.$index.', '.($index+1));
	
	$submission = mysqli_fetch_array($submissions);
?>
<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<html>
	<head>
		<title>Moderate Relatablez</title>
		<link rel='shortcut icon' href='favicon.ico'/>
		<link rel='stylesheet' type='text/css' href='moderate.css'>
		<link rel='stylesheet' type='text/css' href='/toolbartheme.css'>
	</head>
	<body>
		<?php require("../toolbar.php"); ?>
		<div id='main'>
			<div style='width:100%;background:#C4C4C4;'>
				<div class='pending-submission-wrapper' >
					<h2>Does this post obey the rules & guidelines?</h2>
					<p class='pending-submission'>
						<?php 
							if($submission['submission'] !== null)
								echo $submission['submission'];
							else
								echo '<b>There are no more posts to moderate!</b>';
						?>
					</p>
					<form method='POST'>
						<input type='submit' name='v' class='vote green' value='Yes' />
						<input type='submit' name='v' class='vote red' value='No' />
					</form>
				</div>
				<a id='help'>Help</a>
			</div>
			<div id='guidelines'>
				<span class='guideline-title'><b>Guidelines:</b></span>
				<ul>
					<li>Posts must be well written and original.</li>
					<li>Posts must start with "Am I the only one" and end with a question mark.</li>
				</ul>
				<span class='guideline-title'><b>Rules:</b></span>
				<ul>
					<li>Hate speech based on race, cultural origin, beliefs, disability or sexual orientation will NOT be tolerated.</li>
					<li>Posts should be suitable and appropriate for Relatablez users.</li>
				</ul>
				<i id='nsfw-notice'>Only check NSFW if the post follows the rules and guidelines but may not be suitable for younger users.</i>
			</div>
		</div>
	</body>
</html>