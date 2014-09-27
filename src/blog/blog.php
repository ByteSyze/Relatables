<?php
	session_start();
	
	require($_SERVER['DOCUMENT_ROOT'] . '/userinfo.php');
	
	$bid = $_GET['id'];
	$connection = getConnection();
	
	if($statement = $connection->prepare("SELECT *, DATE_FORMAT(created,'%W %M %d, %Y') AS fCreated FROM blog_articles WHERE id = (?)"))
	{
		$statement->bind_param('i', $bid);
		$statement->execute();
		
		$statement->bind_result($id, $uid, $title, $content, $image, $created, $fCreated);
		$statement->fetch();
		
	}
?>
<!-- Copyright (C) Tyler Hackett 2014 -->
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content="Relatablez – Is it Just You? Relatablez is website that connects people using the things we do in our life to see if others feel or do the same.">
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/toolbartheme.css">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/blog/blogtheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
	</head>
	<body>
		<?php require($_SERVER["DOCUMENT_ROOT"] . "/toolbar.php"); ?>
		<div id='main'>
			<h1 class='header' id='article-title'><?php echo $title; ?></h1>
			<div id='article' class='content'>
				<?php if($image != null) echo "<img id='article-image' src='$image' />"; ?>
				<div id='article-content' >	
					<?php echo $content; ?>
					<div id='footer'>
						<span id='created'><?php echo $fCreated; ?></span>
						
						<?php
							$author = getUsername($connection, $uid);
						?>
						
						<span id='author'>Written by <a href='http://www.relatablez.com/user/<?php echo $author; ?>/'><?php echo $author; ?></a></span>
					</div>
				</div>
			</div>
		</div>
		
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src='http://www.relatablez.com/toolbar.js'></script>
	</body>
</html>
