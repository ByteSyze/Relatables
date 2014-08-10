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
		
		$connection = mysqli_connect("mysql.a78.org","u683362690_insom","10102S33K3R17","u683362690_rtblz");
		$data = getProfileData($connection, $user);
	?>
	<head>
		<title id='title'><?php echo $data['username']; ?></title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content="Relatablez – Is it Just You? Relatablez is website that connects people using the things we do in our life to see if others feel or do the same.">
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/toolbartheme.css">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/usertheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
			
	</head>
	<body style='margin:0px;'>
		<?php require($_SERVER['DOCUMENT_ROOT']."/toolbar.php"); ?>
	
		<div id='infobanner'>
			<div id='infolayout'>
				<button id='message-button' onclick='showSendMessagePopup()'>Send Message</button>
				<div id='info'>
					<span id='username'><?php echo $data['username']; ?></span><br>
					<span id='location' class='right-spacer'>
						<?php if($data['hidelocation'] == 0){ echo $data['country']; } ?>
					</span>
					<span id='date' class='right-spacer'>
						<?php echo $data['joined']; ?>
					</span>
					<?php if($data['admin'] === 1) echo '<span style=\'color:red;font:bold 15px arial;\'>[Admin]</span>'; ?>
					<br>
					<p id='user-description'><?php echo htmlspecialchars($data['description']); ?></p>
				</div>
			</div>
		</div>
		
		<div id='moduleslayout'>
			<div id='relatedmodule'>
				<h3>Related With</h3>
				<?php
					if(!$data['hiderelated'])
					{
						$related_posts = getRelated($connection, $data['id']);
						
						while($related = mysqli_fetch_array($related_posts))
						{
							echo $related['submission'];
							echo "\r\n";
						}
					}
				?>
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
		<script src='http://www.relatablez.com/toolbar.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>		
	</body>
</html>