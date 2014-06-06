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
		<title>Profile Settings</title>
	</head>
	<body>
		<h3>Profile Settings</h3>
		<form id='settings-form' method='POST' action='http://www.relatablez.com/update.php'>
			<input id='type' name='t' type='text' style='display:none' />
			<table id='general-settings-table' class='settings-module'>
				<tr>
					<th class='settings-header'>Location</th>
					<td><span id='location'>"<?php echo $data['country']; ?></span><?php include($_SERVER['DOCUMENT_ROOT'].'locationdropdown.php'); getLocationDropdown($data['country_id']); ?></td>
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
	</body>
</html>