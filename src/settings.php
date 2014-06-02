<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<?php
	session_start();
	
	include($_SERVER['DOCUMENT_ROOT'].'/userinfo.php');
	
	$data = getProfileSettings($_SESSION['id']);
	
	if($data['description'] == null)
		$data['description'] = "You have not set a description.";
		
	$page = $_GET['s'];
	$pos = strpos($page,'/');
		
	if($pos !== false)
	{
		$page = substr(0,$pos-1);
	}

	if(strcasecmp($_GET['s'],'profile') == 0)
		$settings = 1;
	else
		$settings = 0;
?>
<html>
	<head>
		<title><?php if($settings == 1) echo 'Profile Settings'; else echo 'Account Settings'; ?></title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content='Relatablez is a compilation of user-submitted posts starting with the phrase "Am I the only one". We offer users the opportunity to share their thoughts, secrets, fears; you name it, only to discover how connected we truly are.'>
		<link rel="shortcut icon" href="http://www.relatablez.com/favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/toolbartheme.css">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/settings/settingstheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
	</head>
	<body>
		<?php require($_SERVER["DOCUMENT_ROOT"] . '/toolbar.php'); ?>
		
		<div id='settings-wrapper'>
			<div id='left-panel'>
				<?php 
					if($settings == 1)
						echo "<a href='http://www.relatablez.com/settings/profile' class='selected'>Profile</a><br><a href='http://www.relatablez.com/settings/account'>Account</a>";
					else						
						echo "<a href='http://www.relatablez.com/settings/profile'>Profile</a><br><a href='http://www.relatablez.com/settings/account' class='selected'>Account</a>";
				?>
			</div>
			<div id='general-settings'>
				<?php
				
				if($settings == 1)
				{
					$description = htmlspecialchars($data['description']);
					echo
					"
					<h3>Profile Settings</h3>
					<form id='settings-form' method='POST' action='http://www.relatablez.com/update.php'>
						<input id='type' name='t' type='text' style='display:none' />
						<table id='general-settings-table' class='settings-module'>
							<tr>
								<th class='settings-header'>Location</th>
								<td><span id='location'>".$data['country']."</span>"; include('locationdropdown.php'); getLocationDropdown($data['country_id']); echo "</td>
								<td class='edit-button-wrapper'><a id='location-button' href='javascript:edit(\"location\");'>Edit</a></td>
								<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='user_verify_img' /></td>
							<tr>
								<th class='settings-header'>Description<br><span id='desc_char_counter'>(".(130-strlen($description))." characters left)</span></th>
								<td><span id='description'>".$description."</span><textarea data-type='description' id='description-input' name='description' onkeypress='keyPressed(this, event)' oninput='charCount(this, desc_char_counter)' style='display:none;'>".$description."</textarea></td>
								<td class='edit-button-wrapper'><a id='description-button' href='javascript:edit(\"description\");document.getElementById(\"description-input\").focus();'>Edit</a></td>
								<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='user_verify_img' /></td>
						</table> 
						<h3>What To Show</h3>
						<table id='showhide-settings-table' class='settings-module'>
							<tr>
								<th class='settings-header'>Related With</th>
								<td class='show-hide-selector'><a "; if($data['hiderelated'] == 0) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=show&amp;d=related'>Show</a> <a "; if($data['hiderelated'] == 1) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=hide&amp;d=related'>Hide</a></td>
							<tr>
								<th class='settings-header'>Location</th>
								<td class='show-hide-selector'><a "; if($data['hidelocation'] == 0) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=show&amp;d=location'>Show</a> <a "; if($data['hidelocation'] == 1) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=hide&amp;d=location'>Hide</a></td>
						</table> 
					</form>
					";
				}
				else
					echo
					"
					<h3>Account Settings</h3>
					<form id='settings-form' method='POST' action='http://www.relatablez.com/update.php'>
						<input id='type' name='t' type='text' style='display:none' />
						<table id='general-settings-table' class='settings-module'>
							<tr>
								<th class='settings-header'>Username</th>
								<td><span id='username'>".$_SESSION['username']."</span><input data-type='username' id='username-input' name='username' onkeypress='keyPressed(this, event)' placeholder='".$_SESSION['username']."' style='display:none;'></input></td>
								<td class='edit-button-wrapper'><a id='username-button' href='javascript:edit(\"username\");'>Edit</a></td>
								<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='user_verify_img' /></td>
							<tr>
								<th class='settings-header'>Password</th>
								<td><span id='password'>********</span><input id='old_pass_input' data-type='password' type='password' name='oldpassword' onkeypress='keyPressed(this, event)' style='display:none;' /></td>
								<td class='edit-button-wrapper'><a id='password-button' href='javascript:edit(\"password\");'>Edit</a></td>
								<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='password_verify_img' /></td>
							<tr id='newpassword' style='display:none;'>
								<th class='settings-header'>New Password</th>
								<td><input id='new_pass_input' data-type='password' type='password' name='newpassword' onkeypress='keyPressed(this, event)' /></td>
								<td></td>
								<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='new_password_verify_img' /></td>
							<tr id='renewpassword' style='display:none;'>
								<th class='settings-header'>Retype New Password</th>
								<td><input id='renew_pass_input' data-type='password' type='password' name='renewpassword' onkeypress='keyPressed(this, event)' /></td>
								<td></td>
								<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='renew_pass_verify_img' /></td>
							<tr>
								<th class='settings-header'>Email</th>
								<td><span id='email'>".$data['email']."</span><input data-type='email' id='email-input' name='email' onkeypress='keyPressed(this, event)' placeholder='".$data['email']."' style='display:none;'></input></td>
								<td class='edit-button-wrapper'><a id='email-button' href='javascript:edit(\"email\");'>Edit</a></td>
								<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='email_verify_img' /></td>
						</table> 
					</form>
					";
				?>
				<a href='http://www.relatablez.com/update.php?t=delete'>Delete Account</a>
			</div>
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/verify.js'></script>
		<script src='http://www.relatablez.com/popups.js'></script>
		<script src='http://www.relatablez.com/toolbar.js'></script>
		<script src='http://www.relatablez.com/settings.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
	</body>
</html>