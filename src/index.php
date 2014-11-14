<?php  
	session_start();
	
	include('userinfo.php');
	
	require_once 'Mobile_Detect.php';
	$detect = new Mobile_Detect;
	 
	if ( $detect->isMobile() ) {
		$_SESSION['mobile'] = 1;
		header('Location: http://m.relatablez.com/');
	}
	
	$connection = getConnection();
	
	if($_SESSION['username'] != null)
		$submissions = mysqli_query($connection,"SELECT *, DATE_FORMAT(date,'%M %d, %Y') AS fdate, (SELECT alone FROM related WHERE related.uid={$_SESSION['id']} AND related.pid = submissions.id) AS user_vote FROM submissions WHERE pending = 0 ORDER BY submissions.date DESC LIMIT 0, 20");
	else
		$submissions = mysqli_query($connection,"SELECT *, DATE_FORMAT(date,'%M %d, %Y') AS fdate FROM submissions WHERE pending = 0 ORDER BY submissions.date DESC LIMIT 0, 20");
?>
<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<html>
	<head>
		<title>Relatablez</title>
		
		<meta charset='UTF-8'>
		<meta name='keywords' content='Am I The Only One, Relatablez, Am I The Only One That'>
		<meta name='description' content='Relatablez is a compilation of user-submitted posts starting with the phrase "Am I the only one". We offer users the opportunity to share their thoughts, secrets, fears; you name it, only to discover how connected we truly are.'>
		<link rel='shortcut icon' href='http://www.relatablez.com/favicon.ico'>
		<link rel='stylesheet' type='text/css' href='toolbartheme.css'>
		<link rel='stylesheet' type='text/css' href='indextheme.css'>
		<link rel='canonical' href='http://www.relatablez.com/'>
	</head>
	<body>		
		<?php require($_SERVER["DOCUMENT_ROOT"] . "/toolbar.php"); ?>
		
		<div id='main'>
			<div id='content' style='float:left;width:700px;'>
				
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
						<option value='0'>All Categories</option>
						<option value='1'>Health</option>
						<option value='2'>Internet</option>
						<option value='3'>Funny</option>
						<option value='4'>People</option>
						<option value='5'>Family</option>
						<option value='6'>Food</option>
						<option value='7'>Personal</option>
						<option value='8'>Odd</option>
						<option value='9'>Other</option>
					</select>
					<span style='vertical-align:bottom;'>Display</span>
					<select id='display' style='vertical-align:bottom;'>
						<option>Continuously</option>
						<option>20 a page</option>
						<option>50 a page</option>
						<option>Cake</option>
					</select>
				<div id='submission-wrapper' class='dialogue submission' style='margin-top:13px;height:71px;'>
					<?php 
						if($_SESSION['username'] !== null)
						{
							echo "<form action='http://www.relatablez.com/submit.php' method='POST' >\r\n"; 
							echo "<textarea name='s' id='submission' class='dialogue showguides'>Am I the only one who</textarea>\r\n";
						}
						else
						{
							echo "	<textarea name='s' id='submission' class='dialogue' data-header='Please sign up to submit' onclick='showRegister(this)'>Am I the only one who</textarea>\r\n";
						}
					?>
						<div style='float:right'>
							<select name='c' id='submit_category'>
								<option value='0'>Select a Category</option>
								<option value='1'>Health</option>
								<option value='2'>Internet</option>
								<option value='3'>Funny</option>
								<option value='4'>People</option>
								<option value='5'>Family</option>
								<option value='6'>Food</option>
								<option value='7'>Personal</option>
								<option value='8'>Odd</option>
								<option value='9'>Other</option>
							</select>
							<span> Anonymous</span><input type='checkbox' name='a' value='true' id='anonymous' />
							<span id='post-counter'> 300 </span>
							<?php
								if($_SESSION['username'] !== null)
									echo "<button id='submit_form' class='submit-button' type='submit' ><b>Submit</b></button>";
								else
									echo "\r\n<button class='submit-button' data-header='Please sign up to submit' onclick='showRegister(this)'>Submit</button>";
							?>
						</div>
						<br>
					<?php if($_SESSION['username'] !== null) echo '</form>'; ?>
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
						if($row['anonymous'])
							$user='Anonymous';
						else
							$user = getUsername($connection, $row['uid']);
						
						echo "\r\n<div class='dialogue uppadding' id='{$row['id']}' data-category='{$row['category']}' data-nsfw='{$row['nsfw']}' data-date='{$row['fdate']}'>";
						echo "\r\n<p class='dialogue'>{$row['submission']}</p>";
						echo "\r\n<table class='vote-table'>";
						echo "\r\n<tr>";
						if($_SESSION["username"] != null)
						{
							if($row['user_vote'] === '0')
								echo "\r\n<td><button class='dialoguebutton' id='bna{$row['id']}' data-vid='{$row['id']}' data-v='{$row['verification']}' disabled>No, me too!</button></td>";
							else
								echo "\r\n<td><button class='dialoguebutton' id='bna{$row['id']}' data-vid='{$row['id']}' data-v='{$row['verification']}'>No, me too!</button></td>";
								
							if($row['user_vote'] === '1')
								echo "\r\n<td><button class='dialoguebutton' id='ba{$row['id']}'  data-vid='{$row['id']}' data-v='{$row['verification']}' disabled>You're alone.</button></td>";
							else
								echo "\r\n<td><button class='dialoguebutton' id='ba{$row['id']}'  data-vid='{$row['id']}' data-v='{$row['verification']}'>You're alone.</button></td>";
						}
						else
						{
							echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>No, me too!</button></td>";
							echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>You're alone.</button></td>";				
						}
						echo "\r\n<tr>";
						echo "\r\n<td><span class='vote-counter' id='na{$row['id']}'>(" . number_format($row["notalone"]) . ")</span></td>";
						echo "\r\n<td><span class='vote-counter' id='a{$row['id']}'>(" . number_format($row["alone"]) . ")</span></td>";
						echo "\r\n</table>";
						echo "\r\n<div style='text-align:right;'><span class='submissioninfo'><a ";
						
						if($row['anonymous'])
							echo ' >' . $user . "</a> - {$row['fdate']}</span></div>";
						else
						{
							if(isAdmin($connection, $row['uid']))
								echo 'class=\'admin\'';
							echo " href='http://www.relatablez.com/user/" . $user . "'>" . $user . "</a> - " . $row["fdate"] . "</span></div>";
						}
						echo "\r\n</div>";
					}
				?>	
			</div>	
			<div style='float:right;'>
				<div id='qotw'>
					<h4 id='qotw-header'>QOTW</h4>
					<div style='padding:10px;'>
						<span>Select a test option.</span>
						<form action='http://www.relatablez.com/kartik/qotw.php' method='POST'>
							<div id='qotw-wrapper'>
								<input type='radio' name='v' value='0' />Option A.<br>
								<input type='radio' name='v' value='1' />Option B.
								<input type='submit' value='vote' id='qotw-submit' />
							</div>
						</form>
					</div>
				</div>
			</div>	
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/index.js'></script>
		<script src='http://www.relatablez.com/toolbar.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
	</body>
</html>