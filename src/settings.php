<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<?php
	session_start();
	
	include($_SERVER['DOCUMENT_ROOT'].'/userinfo.php');
	
	$data = getProfileSettings($_SESSION['id']);
	
	if($data['description'] == null)
		$data['description'] = "You have not set a description.";

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
		<link rel="shortcut icon" href="favicon.ico">
		<link rel="stylesheet" type="text/css" href="../toolbartheme.css">
		<link rel="stylesheet" type="text/css" href="settingstheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
	</head>
	<body>
		<?php require($_SERVER["DOCUMENT_ROOT"] . '/toolbar.php'); ?>
		
		<div id='settings-wrapper'>
			<div id='left-panel'>
				<?php 
					if($settings == 1)
						echo "<a href='profile' class='selected'>Profile</a><br><a href='account'>Account</a>";
					else						
						echo "<a href='profile'>Profile</a><br><a href='account' class='selected'>Account</a>";
				?>
			</div>
			<div id='general-settings'>
				<?php
				
				if($settings == 1)
				{
					echo
					"
					<h3>Profile Settings</h3>
					<table id='general-settings-table'>
						<tr>
							<th class='settings-header'>Location</th>
							<td><span id='location'>".$data['country']."</span>"; include('locationdropdown.php'); getLocationDropdown($data['country_id']); echo "</td>
							<td class='change-wrapper'><a id='location-button' href='javascript:editLocation();'>Edit</a></td>
						<tr>
							<th class='settings-header'>Description<br><span>(130 characters)</span></th>
							<td><span id='description'>".htmlspecialchars($data['description'])."</span><form id='description-form' style='display:none;' method='GET' action='http://www.relatablez.com/updatedescription.php'><textarea name='description' onkeypress='keyPressed(this, event)'>".htmlspecialchars($data['description'])."</textarea></form></td>
							<td class='change-wrapper'><a id='description-button' href='javascript:editDescription();'>Edit</a></td>
					</table> 
					<h3>What To Show</h3>
					<table id='general-settings-table'>
						<tr>
							<th class='settings-header'>Related With</th>
							<td class='show-hide-selector'><a "; if($data['hiderelated'] == 0) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=show&amp;d=related'>Show</a> <a "; if($data['hiderelated'] == 1) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=hide&amp;d=related'>Hide</a></td>
						<tr>
							<th class='settings-header'>Location</th>
							<td class='show-hide-selector'><a "; if($data['hidelocation'] == 0) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=show&amp;d=location'>Show</a> <a "; if($data['hidelocation'] == 1) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=hide&amp;d=location'>Hide</a></td>
						<tr>
							<th class='settings-header'>Description</th>
							<td class='show-hide-selector'><a "; if($data['hidedescription'] == 0) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=show&amp;d=description'>Show</a> <a "; if($data['hidedescription'] == 1) echo "class='selected'"; echo" href='http://www.relatablez.com/showhide.php?t=hide&amp;d=description'>Hide</a></td>
					</table> 
					";
				}
				else
					echo
					"
					<h3>Account Settings</h3>
					<table id='general-settings-table'>
						<tr>
							<th class='settings-header'>Username</th>
							<td>".$_SESSION['username']."</td>
							<td class='change-wrapper'><a href='#ChangeUsername'>Edit</a></td>
						<tr>
							<th class='settings-header'>Password</th>
							<td>Password</td>
							<td class='change-wrapper'><a href='#ChangePassword'>Edit</a></td>
						<tr>
							<th class='settings-header'>Email</th>
							<td>".$data['email']."</td>
							<td class='change-wrapper'><a href='#ChangeEmail'>Edit</a></td>
					</table> 
					";
				?>
				<a href='#Delete'>Delete Account</a>
			</div>
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://relatablez.com/verify.js'></script>
		<script src='http://relatablez.com/popups.js'></script>
		<script src='http://relatablez.com/toolbar.js'></script>
		<script src='http://relatablez.com/settings.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
	</body>
</html>