<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<?php 
	session_start();
	
	include($_SERVER['DOCUMENT_ROOT'].'/userinfo.php');
	
	$data = getProfileSettings($_SESSION['id']);
?>
<html>
	<head>
		<title>Account Settings</title>	
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content='Relatablez is a compilation of user-submitted posts starting with the phrase "Am I the only one". We offer users the opportunity to share their thoughts, secrets, fears; you name it, only to discover how connected we truly are.'>
		<link rel="shortcut icon" href="http://www.relatablez.com/favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/toolbartheme.css">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/settingstheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
	</head>
	<body>
	
		<?php require($_SERVER["DOCUMENT_ROOT"] . '/toolbar.php'); ?>
		
		<div id='settings-wrapper'>
			<div id='left-panel'>					
				<a href='http://www.relatablez.com/settings/profile'>Profile</a><br><a href='http://www.relatablez.com/settings/account' class='selected'>Account</a>
			</div>			
			<div id='general-settings'>
				<form id='settings-form' method='POST' action='http://www.relatablez.com/update.php'>
					<table id='general-settings-table' class='settings-module'>
						<tr>
							<th class='settings-header'>Username</th>
							<td><input data-type='username' id='username-input' name='username' onkeypress='keyPressed(this, event)' placeholder='<?php echo $_SESSION['username']; ?>'></input></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='user_verify_img' /></td>
						<tr>
							<th class='settings-header'>Password</th>
							<td><input id='old_pass_input' data-type='password' type='password' name='oldpassword' onkeypress='keyPressed(this, event)' /></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='password_verify_img' /></td>
						<tr id='newpassword'>
							<th class='settings-header'>New Password</th>
							<td><input id='new_pass_input' data-type='password' type='password' name='newpassword' onkeypress='keyPressed(this, event)' /></td>
							<td></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='new_password_verify_img' /></td>
						<tr id='renewpassword'>
							<th class='settings-header'>Retype New Password</th>
							<td><input id='renew_pass_input' data-type='password' type='password' name='renewpassword' onkeypress='keyPressed(this, event)' /></td>
							<td></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='renew_pass_verify_img' /></td>
						<tr>
							<th class='settings-header'>Email</th>
							<td><input data-type='email' id='email-input' name='email' onkeypress='keyPressed(this, event)' placeholder='<?php echo $data['email']; ?>'></input></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='email_verify_img' /></td>
					</table> 
				</form>		
				<a href='javascript:showDeletePopup()'>Delete Account</a>
				<div id='delete-account-popup'>
					<h2>Delete account?</h2>
					<form method='POST' action='http://www.relatablez.com/update.php'>
						<input type='hidden' name='t' value='delete'>
						<input class='dialoguebutton' style='float:left' type='submit' value='Confirm'>
						<input class='dialoguebutton' style='float:right' type='button' value='Cancel' onclick='hideDeletePopup()'>
					</form>
				</div>
			</div>
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/verify.js'></script>
		<script src='http://www.relatablez.com/popups.js'></script>
		<script src='http://www.relatablez.com/settings.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
	</body>
</html>