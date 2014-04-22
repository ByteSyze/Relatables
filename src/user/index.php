<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014 -->
<?php 
	//Predefined variables
	
	include($_SERVER['DOCUMENT_ROOT'] . '/userinfo.php');
	
	$user = getExactUsername($_GET["username"]);
	$joindate = getJoinDate($user);
	
?>
<html>
	<head>
		<title id='title'><?php echo $user; ?></title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content="Relatablez – Is it Just You? Relatablez is website that connects people using the things we do in our life to see if others feel or do the same.">
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://relatablez.com/toolbartheme.css">
		<link rel="stylesheet" type="text/css" href="http://relatablez.com/templates/usertheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
			
	</head>
	<body style='margin:0px;'>
		<?php require($_SERVER['DOCUMENT_ROOT']."/toolbar.php"); ?>
	
		<div id='infobanner'>
			<div id='infolayout'>
				<div id='info'>
					<span id='username'><?php echo $user; ?></span><br>
					<span id='location' class='right-spacer'>N/A</span><span id='date'><?php echo $joindate; ?></span><br>
					<p id='user-description'>[Description]</p>
				</div>
			</div>
		</div>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src='http://relatablez.com/popups.js'></script>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>		
	</body>
</html>