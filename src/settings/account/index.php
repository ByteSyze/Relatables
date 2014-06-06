<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<?php 
	session_start();
	
	include($_SERVER['DOCUMENT_ROOT'].'/userinfo.php');
	
	$data = getProfileSettings($_SESSION['id']);
	
	if($data['description'] == null)
		$data['description'] = "You have not set a description.";
?>
<html>
	<head>
		<title>Account Settings</title>
	</head>
	<body>
		<h3>Account Settings</h3>
		<form id='settings-form' method='POST' action='http://www.relatablez.com/update.php'>
			<input id='type' name='t' type='text' style='display:none' />
			<table id='general-settings-table' class='settings-module'>
				<tr>
					<th class='settings-header'>Username</th>
					<td><span id='username'><?php echo $_SESSION['username']; ?></span><input data-type='username' id='username-input' name='username' onkeypress='keyPressed(this, event)' placeholder='<?php echo $_SESSION['username']; ?>' style='display:none;'></input></td>
					<td class='edit-button-wrapper'><a id='username-button' href='javascript:edit("username");'>Edit</a></td>
					<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='user_verify_img' /></td>
				<tr>
					<th class='settings-header'>Password</th>
					<td><span id='password'>********</span><input id='old_pass_input' data-type='password' type='password' name='oldpassword' onkeypress='keyPressed(this, event)' style='display:none;' /></td>
					<td class='edit-button-wrapper'><a id='password-button' href='javascript:edit("password");'>Edit</a></td>
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
					<td><span id='email'>".$data['email']."</span><input data-type='email' id='email-input' name='email' onkeypress='keyPressed(this, event)' placeholder='<?php echo $data['email']; ?>' style='display:none;'></input></td>
					<td class='edit-button-wrapper'><a id='email-button' href='javascript:edit("email");'>Edit</a></td>
					<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='email_verify_img' /></td>
			</table> 
		</form>
	</body>
</html>