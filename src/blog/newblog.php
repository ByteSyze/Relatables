<?php
	session_start();
	
	require($_SERVER['DOCUMENT_ROOT'] . '/userinfo.php');
	
	$connection = getConnection();
		
	if(!isAdmin($connection, $_SESSION['id']) || $_SESSION['username'] == null)
		header('Location: http://www.relatablez.com/404/');
?>
<!-- Copyright (C) Tyler Hackett 2014 -->
<!DOCTYPE html>
<html>
	<head>
		<title>Relatablez - New Article</title>
		
		<meta charset="UTF-8">
		<meta name="keywords" content="Am I The Only One, Relatablez, Am I The Only One That">
		<meta name="description" content="Relatablez – Is it Just You? Relatablez is website that connects people using the things we do in our life to see if others feel or do the same.">
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/toolbartheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
		<style type='text/css'>
			#article-contents
			{
				width:100%;
				height:300px;
				box-sizing:border-box;
				resize:none;
			}
			#article-title
			{
				width:100%;
				height:25px;
				box-sizing:border-box;
			}
			#content
			{
				padding:20px;
			}
			#img-preview
			{
				width:150px;
				height:150px;
			}
			#new-blog
			{
				width:850px;
				margin:70px auto auto;
				background:white;
				box-shadow:0px 0px 10px #BFBFBF;
			}
			#title
			{
				margin:0px;
				padding:20px;
				color:#FFF;
				background-color:#4a66d8;
			}
			span.footnote
			{
				font-size:12px;
			}
		</style>
	</head>
	<body>
		<?php require($_SERVER["DOCUMENT_ROOT"] . "/toolbar.php"); ?>
	
		<div id='new-blog'>
			<h1 id='title' >Create a Blog Article</h1>
			
			<div id='content'>
				<form id='article-form' method='POST'>
					<h3>Title:</h3>
					<input id='article-title' type='text' name='title' />
					
					<hr>
					
					<h3>Front Page Image:</h3>
					<input id='article-img' type='file' name='image' /><br>
					<img id='img-preview' ></img><br>
					<span  class='footnote'>Accepts .png, .jpg, and .gif</span>
					
					<hr>
					
					<h3>Contents:</h3>
					<textarea id='article-contents' name='contents'></textarea>
					
					<hr>
					
					<input id='submit' type='submit' value='Create' />
				</form>
			</div>
		</div>
	
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src='http://www.relatablez.com/toolbar.js'></script>
	<script type='text/javascript'>
		$('#submit').click(function(event)
		{
			if(!$('#article-title').val())
				$('#article-title').css('box-shadow', '0px 0px 10px red');
			if(!$('#article-img').val())
				$('#article-img').css('box-shadow', '0px 0px 10px red');
			if(!$('#article-contents').val())
				$('#article-contents').css('box-shadow', '0px 0px 10px red');
			
			event.preventDefault();
		});
		$('#article-img').change(function(event)
		{
			var file = document.getElementById("article-img").files[0];

			if (!file.type.match(/image.*/))
				$('#article-img').css('box-shadow', '0px 0px 10px red');

			var img = document.getElementById("img-preview");
			img.file = file;

			var reader = new FileReader();
			reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
			reader.readAsDataURL(file);
		});
	</script>
	</body>
</html>