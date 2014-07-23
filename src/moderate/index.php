<?php  
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');

	if (mysqli_connect_errno())
	{
		echo 'Failed to connect to MySQL: ' . mysqli_connect_error();
	}	
	
	$index = $_POST['i'];
	
	if($index == null)
		$index = 0;
	
	$submissions = mysqli_query($connection,'SELECT submission, id FROM submissions WHERE pending = 1 ORDER BY submissions.date DESC LIMIT ' . $index . ', ' . ++$index);
	
	$submission = mysqli_fetch_array($submissions);
?>
<html>
	<head>
		<title>Moderate Relatablez</title>
		<link rel="shortcut icon" href="favicon.ico"/>
		<link rel='stylesheet' type='text/css' href='moderate.css'>
		<link rel="stylesheet" type="text/css" href="/toolbartheme.css">
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
						<input type='hidden' name='pid' value='<?php echo $submission['id']; ?>' />
						<input type='hidden' name='i' value='<?php echo $index; ?>' />
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