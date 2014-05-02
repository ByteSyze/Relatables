<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014 -->
<html>
<?php 
	session_start();
	
	$user = $_GET['username'];
	
	//If username ends in a /, remove it.
	$slashpos = strpos($user,'/');
	
	if($slashpos != false)
	{
		$user = substr($user,0,$slashpos);
	}
	
	require($_SERVER['DOCUMENT_ROOT']."/userinfo.php");
	
	$data = getProfileData($user);
	
	if($data['username'] == false)
		header('Location: http://www.relatablez.com/notfound.php');
?>
	<head>
		<title id='title'><?php echo $data['username']; ?></title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content="Relatablez – Is it Just You? Relatablez is website that connects people using the things we do in our life to see if others feel or do the same.">
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://relatablez.com/toolbartheme.css">
		<link rel="stylesheet" type="text/css" href="http://relatablez.com/usertheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
			
	</head>
	<body style='margin:0px;'>
		<?php require($_SERVER['DOCUMENT_ROOT']."/toolbar.php"); ?>
	
		<div id='infobanner'>
			<div id='infolayout'>
				<div id='info'>
					<span id='username'><?php echo $data['username']; ?></span><br>
					<span id='location' class='right-spacer'><?php echo $data['country']; ?></span><span id='date'><?php echo $data['joined']; ?></span><br>
					<p id='user-description'><?php if($data['description'] == null) echo"I'm not the only one who hasn't bothered to change my description!"; else echo htmlspecialchars($data['description']); ?></p>
				</div>
			</div>
		</div>
		
		<div id='moduleslayout'>
			<div id='relatedmodule'>
				<h3>Related With</h3>
			</div>		
			<div id='statsmodule'>
				<h3>Stats</h3>
				<div id='stats'>
					<span>Posts: <?php echo $data['posts']; ?></span><br>
					<span>Comments: <?php echo $data['comments']; ?></span><br>
					<span>Moderated: <?php echo $data['moderated']; ?></span>
				</div>
			</div>
		</div>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src='http://relatablez.com/popups.js'></script>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>		
	</body>
</html>