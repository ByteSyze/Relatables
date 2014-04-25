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
		<title>Account Settings</title>
		
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
							<td>".$data['country']."</td>
							<td class='change-wrapper'><a href='#ChangeLocation'>change</a></td>
						<tr>
							<th class='settings-header'>Description<br><span>(130 characters)</span></th>
							<td>".$data['description']."</td>
							<td class='change-wrapper'><a href='#ChangePassword'>change</a></td>
					</table> 
					<h3>What To Show</h3>
					<table id='general-settings-table'>
						<tr>
							<th class='settings-header'>Related With</th>
							<td class='show-hide-selector'><a "; if($data['hiderelated'] == 0) echo "class='selected'"; echo"href='#ShowRelated'>Show</a> <a "; if($data['hiderelated'] == 1) echo "class='selected'"; echo"href='#HideRelated'>Hide</a></td>
						<tr>
							<th class='settings-header'>Location</th>
							<td class='show-hide-selector'><a "; if($data['hidelocation'] == 0) echo "class='selected'"; echo" href='#ShowLocation'>Show</a> <a "; if($data['hidelocation'] == 1) echo "class='selected'"; echo" href='#HideLocation'>Hide</a></td>
						<tr>
							<th class='settings-header'>Description</th>
							<td class='show-hide-selector'><a "; if($data['hidedescription'] == 0) echo "class='selected'"; echo" href='#ShowDescription'>Show</a> <a "; if($data['hidedescription'] == 1) echo "class='selected'"; echo" href='#HideDescription'>Hide</a></td>
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
							<td class='change-wrapper'><a href='#ChangeUsername'>change</a></td>
						<tr>
							<th class='settings-header'>Password</th>
							<td>Password</td>
							<td class='change-wrapper'><a href='#ChangePassword'>change</a></td>
						<tr>
							<th class='settings-header'>Email</th>
							<td>".$data['email']."</td>
							<td class='change-wrapper'><a href='#ChangeEmail'>change</a></td>
					</table> 
					";
				?>
				<a href='#Delete'>Delete Account</a>
			</div>
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://relatablez.com/vote.js'></script>
		<script src='http://relatablez.com/verify.js'></script>
		<script src='http://relatablez.com/popups.js'></script>
		<script src='http://relatablez.com/toolbar.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
	</body>
</html>