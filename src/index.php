<?php  
	session_start();
	
	include 'userinfo.php';
	require_once 'Mobile_Detect.php';
	$detect = new Mobile_Detect;
	
	 
	if ( $detect->isMobile() ) {
		$_SESSION['mobile'] = 1;
		header('Location: http://m.relatablez.com/');
	}
	
	$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");

	if (mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}	
	
	$submissions = mysqli_query($connection,"SELECT *, DATE_FORMAT(date,'%M %d, %Y') AS fdate FROM submissions WHERE pending = 0 ORDER BY submissions.date DESC LIMIT 0, 20");
?>
<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<html>
	<head>
		<title>Relatablez</title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content='Relatablez is a compilation of user-submitted posts starting with the phrase "Am I the only one". We offer users the opportunity to share their thoughts, secrets, fears; you name it, only to discover how connected we truly are.'>
		<link rel="shortcut icon" href="http://www.relatablez.com/favicon.ico">
		<link rel="stylesheet" type="text/css" href="indextheme.css">
		<link rel="stylesheet" type="text/css" href="toolbartheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
		
	</head>
	<body>		
		<?php require($_SERVER["DOCUMENT_ROOT"] . "/toolbar.php"); ?>
		
		<div id='main'>
			<article id='content' style='float:left;width:700px;'>
				
					<span style='vertical-align:bottom;'>NSFW</span><input type='checkbox' id='nsfw' style='vertical-align:bottom;'>
					<span style='vertical-align:bottom;'>Sort by</span>
					<select id='sort' style='vertical-align:bottom;'>
						<option>New-To-Old</option>
						<option>Old-To-New</option>
						<option>Highest Rated</option>
						<option>Lowest Rated</option>
					</select>
					<span style='vertical-align:bottom;'>Category</span>
					<select id='category' style='vertical-align:bottom;'>
						<option>All Categories</option>
						<option>Health</option>
						<option>Family</option>
						<option>Funny</option>
						<option>Food</option>
						<option>Odd</option>
						<option>Other</option>
					</select>
					<span style='vertical-align:bottom;'>Display</span>
					<select id='display' style='vertical-align:bottom;'>
						<option>Continuously</option>
						<option>20 a page</option>
						<option>50 a page</option>
						<option>Cake</option>
					</select>
				<div class='dialogue submission' style='margin-top:13px;'>
					<?php
						if($loggedIn)
						{
							echo "\r\n<textarea class='dialogue' placeholder=' Am I the only one who...'></textarea>";
							echo "\r\n<input class='submit-button' type='submit' name='submit' value='Submit'></input>";
						}
						else
						{
							echo "\r\n<textarea class='dialogue' data-header='Please sign up to submit' onclick='showRegister(this)' placeholder=' Am I the only one who...'></textarea>";
							echo "\r\n<button class='submit-button' data-header='Please sign up to submit' onclick='showRegister(this)'>Submit</button>";
						}
					?>
					<hr>
					<span class='guideline-title'><b>Guidelines:</b></span>
					<ul>
						<li>Your post must be well written and original.</li>
						<li>Your post must start with "Am I the only one" and end with a question mark.</li>
					</ul>
					<span class='guideline-title'><b>Rules:</b></span>
					<ul>
						<li>Hate speech based on race, cultural origin, beliefs, disability or sexual orientation will NOT be tolerated.</li>
					</ul>
					<b class='warning'><i>Not following the rules may result in a warning and/or your account being terminated. Please use common sense.</i></b>
				</div>
				<?php
					while($row = mysqli_fetch_array($submissions))
					{	
						$user = getUsername($row['uid']);
						
						echo "\r\n<div class='dialogue uppadding' id='" . $row["id"] . "'>";
						echo "\r\n<p class='dialogue'>" . $row["submission"] . "</p>";
						echo "\r\n<table class='vote-table'>";
						echo "\r\n<tr>";
						if($_SESSION["username"] != null)
						{
							echo "\r\n<td><button class='dialoguebutton' onclick='vote(na" . $row["id"] . ")'>No, me too!</button></td>";
							echo "\r\n<td><button class='dialoguebutton' onclick='vote(a" . $row["id"] . ")'>You're alone.</button></td>";
						}
						else
						{
							echo "\r\n<td><button class='dialoguebutton' data-header='Please sign up to vote' onclick='showRegister(this)'>No, me too!</button></td>";
							echo "\r\n<td><button class='dialoguebutton' data-header='Please sign up to vote' onclick='showRegister(this)'>You're alone.</button></td>";				
						}
						echo "\r\n<tr>";
						echo "\r\n<td><span class='vote-counter' id='na" . $row["id"] . "'>(" . number_format($row["notalone"]) . ")</span></td>";
						echo "\r\n<td><span class='vote-counter' id='a" . $row["id"] . "'>(" . number_format($row["alone"]) . ")</span></td>";
						echo "\r\n</table>";
						echo "\r\n<div style='text-align:right;'><span class='submissioninfo'><a ";
						if(isAdmin($row['uid']))
							echo 'class=\'admin\'';
						echo " href='http://www.relatablez.com/user/" . $user . "'>" . $user . "</a> - " . $row["fdate"] . "</span></div>";
						echo "\r\n</div>";
					}
				?>	
			</article>		
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/vote.js'></script>
		<script src='http://www.relatablez.com/verify.js'></script>
		<script src='http://www.relatablez.com/popups.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
	</body>
</html>