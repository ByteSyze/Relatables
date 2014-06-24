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
						<input type='hidden' name='t' value='account' />
						<tr>
							<th class='settings-header'>Username</th>
							<td><input id='user_input' name='username' onkeydown='checkLimit(event,this,32,false);' onkeyup='verifyUser()' placeholder='<?php echo $_SESSION['username']; ?>'></input></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='user_verify_img' /></td> 
						<tr>
							<th class='settings-header' style='text-align:right;'>Password</th>
							<td><input id='currentpass_input' type='password' name='oldpassword' onkeyup='verifyCurrentPassword()' autocomplete='off' /></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='currentpass_verify_img' /></td>
						<tr id='newpassword'>
							<th class='settings-header' style='text-align:right;'>New Password</th>
							<td><input id='pass_input' type='password' name='newpassword' onkeyup='verifyPassword()' autocomplete='off' /></td>
							<td><img class='verify' src='http://www.relatablez.com/check_mark.png' id='pass_verify_img' /></td>
						<tr id='renewpassword'>
							<th class='settings-header' style='text-align:right;'>Retype New Password</th>
							<td><input id='repass_input' type='password' name='renewpassword' onkeyup='verifyRePassword()'  autocomplete='off' /></td>
							<td><img class='verify' src='http://www.relatablez.com/check_mark.png' id='repass_verify_img' /></td>
						<tr>
							<th class='settings-header'>Email</th>
							<td><input id='email_input' name='email' onkeydown='checkLimit(event,this,32,false);' onkeyup='verifyEmail()' placeholder='<?php echo $data['email']; ?>'></input></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='email_verify_img' /></td>
					</table> 
					<input id='save-button' type='submit' value='Save Settings' />
				</form>		
				<a id='delete-button' href='javascript:showDeletePopup()'>Delete Account</a>
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