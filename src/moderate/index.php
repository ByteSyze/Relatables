<?php  
	session_start();
	include('../userinfo.php');
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');

	if (mysqli_connect_errno())
	{
		echo 'Failed to connect to MySQL: ' . mysqli_connect_error();
	}	
	
	$index = getModerationIndex($connection, $_SESSION['id']);
	
	if($_POST['v'] !== null)
	{
		if($_SESSION['id'] !== null)
		{
			$end = $index+2;
			
			$submissions = mysqli_query($connection, "SELECT submission, alone, notalone, id FROM submissions WHERE pending=1 ORDER BY submissions.date LIMIT $index, $end");
			$submission = mysqli_fetch_array($submissions); // The submission that was voted on.
			
			$alone	  = $submission['alone'];
			$notalone = $submission['notalone'];
			
			if($alone + $notalone >= 100)
			{
				//If there are atleast 1000 votes, decide the fate of the submission.
				if($notalone/$alone >= 3)
				{
					//If the yes-to-no vote ratio is atleast 3:1,
					//Send it to the front page.
					mysqli_query($connection, 'UPDATE submissions SET pending=0, alone=0, notalone=0 WHERE id='.$submission['id']);
				}
				else
				{
					//If the vast majority didn't say yes, play it safe
					//and discard it instead of posting it.
					mysqli_query($connection, 'DELETE FROM submissions WHERE id='.$submission['id']);
				}
			}
			else
			{
				if($_POST['v'] === 'Yes')
				{
					mysqli_query($connection, 'UPDATE submissions SET notalone=notalone+1 WHERE id=' . $submission['id']);
					incModerationIndex($connection, $_SESSION['id']);
				}
				else if($_POST['v'] === 'No')
				{
					mysqli_query($connection, 'UPDATE submissions SET alone=alone+1 WHERE id=' . $submission['id']);
					incModerationIndex($connection, $_SESSION['id']);
				}
			}
		}
	}
	else
	{
		$end = $index+1;
		$submissions = mysqli_query($connection, "SELECT submission, id FROM submissions WHERE pending=1 ORDER BY submissions.date LIMIT $index, $end");
	}
	
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