<?php  
	$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");

	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}	
	
	$submissions = mysqli_query($connection,"SELECT submission, id FROM submissions WHERE pending = 1 ORDER BY submissions.date DESC LIMIT 0, 20");
	
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
				<div class='pending-submission-wrapper' id='<?php echo $submission["id"]; ?>'>
					<h2>Does this post obey the rules & guidelines?</h2>
					<p class='pending-submission'><?php echo $submission["submission"]; ?></p>
					<button class='vote green' >Yes</button>
					<button class='vote red' >No</button>
				</div>
			</div>
			<div id='guidelines'>
				<span class='guideline-title'><b>Guidelines:</b></span>
				<ul>
					<li>Your post must be well written and original.</li>
					<li>Your post must start with "Am I the only one" and end with a question mark.</li>
				</ul>
				<span class='guideline-title'><b>Rules:</b></span>
				<ul>
					<li>Hate speech based on race, cultural origin, beliefs, disability or sexual orientation will NOT be tolerated.</li>
				</ul>
				<i id='nsfw-notice'>Only check NSFW if the post follows the rules and guidelines but may not be suitable for younger users.</i>
			</div>
		</div>
	</body>
</html>