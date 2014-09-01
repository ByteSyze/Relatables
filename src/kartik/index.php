<?php  
	session_start();
	
	$_SESSION['username'] = 'kartik';
	$_SESSION['id'] = 1;
	
?>
<!DOCTYPE html>

<!-- Copyright (C) Tyler Hackett 2014-->
<html>
	<head>
		<title>Relatablez</title>
		
		<meta charset='UTF-8'>
		<meta name='keywords' content='Am I The Only One, Relatablez, Am I The Only One That'>
		<meta name='description' content='Relatablez is a compilation of user-submitted posts starting with the phrase "Am I the only one". We offer users the opportunity to share their thoughts, secrets, fears; you name it, only to discover how connected we truly are.'>
		<link rel='stylesheet' type='text/css' href='http://www.relatablez.com/kartik/indextheme.css'>
		<link rel='stylesheet' type='text/css' href='http://www.relatablez.com/kartik/toolbartheme.css'>
	</head>
	<body>		
		<?php require('toolbar.php'); ?>
		
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
					</select>
				<div id='submission-wrapper' class='dialogue submission' style='margin-top:13px;height:71px;'>
				
					<?php 
						if($_SESSION['username'] !== null)
						{
							echo "<form action='http://www.relatablez.com/kartik/submit.php' method='POST' >\r\n"; 
							echo "<textarea name='s' id='submission' class='dialogue showguides' placeholder=' Am I the only one who...'></textarea>\r\n";
						}
						else
						{
							echo "	<textarea name='s' id='submission' class='dialogue' data-header='Please sign up to submit' onclick='showRegister(this)' placeholder=' Am I the only one who...'></textarea>\r\n";
						}
					?>
						<div style='float:right'>
							<select name='c' id='category1'>
								<option value=''>Select a Category</option>
								<option value='1'>Category 1</option>
								<option value='2'>Category 2</option>
							</select>
							<span> Anonymous</span><input type='checkbox' name='a' value='true' id='anonymous'/>
							<span id='post-counter'> 300 </span>
							<?php
								if($_SESSION['username'] !== null)
									echo "<button id='submit_form' class='submit-button' type='submit' ><b>Submit</b></button>";
								else
									echo "\r\n<button id='submit_form' class='submit-button' data-header='Please sign up to submit' onclick='showRegister(this)'>Submit</button>";
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
			</div>		
			<div style='float:right;'>
				<div id='qotw'>
					<h4 id='qotw-header'>QOTW</h4>
					<div style='padding:10px;'>
						<span>Select a test option.</span>
						<form action='http://www.relatablez.com/kartik/qotw.php' method='POST'>
							<input type='radio' name='v' value='0' />Option A.<br>
							<input type='radio' name='v' value='1' />Option B.
							<input type='submit' value='vote' id='qotw-submit' />
						</form>
					</div>
				</div>
			</div>	
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/kartik/index.js'></script>
		<script src='http://www.relatablez.com/kartik/toolbar.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
	</body>
</html>