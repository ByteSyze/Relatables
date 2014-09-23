<?php
	session_start();
?>
<!DOCTYPE html>
<!-- Copyright (C) Tyler Hackett 2014-->
<html>
	<head>
		<title>Relatablog</title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content="Relatablez – Is it Just You? Relatablez is website that connects people using the things we do in our life to see if others feel or do the same.">
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/toolbartheme.css">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/blog/theme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
	</head>
	<body>
		<?php require($_SERVER["DOCUMENT_ROOT"] . "/toolbar.php"); ?>
		<div id='main'>
			<nav id='navigation'>
				<a href='http://www.relatablez.com/blog/' class='selected'>Blog</a>
				<a href='http://www.relatablez.com/FAQ/'>FAQ</a>
				<a href='http://www.relatablez.com/privacy/'>Privacy</a>
				<a href='http://www.relatablez.com/terms/'>Terms</a>
			</nav>
			<div id='blog'>
				<h1>Blog</h1>
				<div id='content-wrapper'>
					<div class='article'>
						<h3>New features</h3>
						<span>Sunday September 21 2014</span>
						<p class='article-summary'>We've added a bunch of cool new features; here's a list of them.</p>
					</div>
					<hr>
				</div>
			</div>
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/toolbar.js'></script>
		<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
	</body>
</html>