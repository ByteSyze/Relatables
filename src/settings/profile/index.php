<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<?php 
	session_start();
	
	include($_SERVER['DOCUMENT_ROOT'].'/userinfo.php');
	
	$data = getProfileSettings($_SESSION['id']);
		
	$fDescription = htmlspecialchars($data['description']); // Formatted Description
?>
<html>
	<head>
		<title>Profile Settings</title>
			
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
				<a href='http://www.relatablez.com/settings/profile' class='selected'>Profile</a>
				<br>
				<a href='http://www.relatablez.com/settings/account'>Account</a>
			</div>			
			<div id='general-settings'>
				<form id='settings-form' method='POST' action='http://www.relatablez.com/update.php'>
					<input type='hidden' name='t' value='profile' />
					<table id='general-settings-table' class='settings-module'>
						<tr>
							<th class='settings-header'>Location</th>
							<td><?php include($_SERVER['DOCUMENT_ROOT'].'/locationdropdown.php'); getLocationDropdown($data['country_id']); ?></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='user_verify_img' /></td>
						<tr>
							<th class='settings-header'>Description<br><span id='desc_char_counter'>(<?php echo (130-strlen($fDescription)); ?> characters left)</span></th>
							<td><textarea id='description-input' name='description' onkeypress='checkEntered(this, event)' oninput='checkLimit(event,this,130,true);charCount(this, desc_char_counter);' <?php if($data['description'] == null) echo 'placeholder=\'You have not set a description.\''; ?> ><?php if($data['description'] != null) echo $fDescription; ?></textarea></td>
							<td style='width:12px;'><img class='verify' src='http://www.relatablez.com/check_mark.png' id='user_verify_img' /></td>
					</table> 
					<table id='showhide-settings-table' class='settings-module'>
						<tr>
							<th class='settings-header'>Related With</th>
							<td class='show-hide-selector'><a <?php if($data['hiderelated'] == 0) echo "class='selected'"; ?> href='http://www.relatablez.com/showhide.php?t=show&amp;d=related'>Show</a> <a <?php if($data['hiderelated'] == 1) echo "class='selected'"; ?> href='http://www.relatablez.com/showhide.php?t=hide&amp;d=related'>Hide</a></td>
						<tr>
							<th class='settings-header'>Location</th>
							<td class='show-hide-selector'><a <?php if($data['hidelocation'] == 0) echo "class='selected'"; ?> href='http://www.relatablez.com/showhide.php?t=show&amp;d=location'>Show</a> <a <?php if($data['hidelocation'] == 1) echo "class='selected'"; ?> href='http://www.relatablez.com/showhide.php?t=hide&amp;d=location'>Hide</a></td>
					</table>
					<input id='save-button' type='submit' value='Save Settings' />
				</form>
			</div>
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/popups.js'></script>
		<script src='http://www.relatablez.com/settings.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
	</body>
</html>