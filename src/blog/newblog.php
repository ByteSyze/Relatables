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
				height:38px;
				font-size:26px;
				box-sizing:border-box;
			}
			#cheatsheet
			{
				position:absolute;
				width:310px;
				margin-left:-330px;
				background:white;
				box-shadow:0px 0px 10px #BFBFBF;
				text-align:center;
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
			table
			{
				margin:auto;
			}
			td
			{
				border: 1px solid #808080;
			}
		</style>
	</head>
	<body>
		<?php require($_SERVER["DOCUMENT_ROOT"] . "/toolbar.php"); ?>
	
		<div id='new-blog'>
			<h1 id='title' >Create a Blog Article</h1>
			
			<div id='cheatsheet'>
				<h3>HTML Cheatsheet</h3>
				
				<table>
					<tr>
						<td><b>Bold Text</b></td>
						<td> &#60b&#62Text&#60/b&#62 </td>
					<tr>
						<td><i>Italic Text</i></td>
						<td> &#60i&#62Text&#60/i&#62 </td>
					<tr>
						<td>Image</td>
						<td> &#60img src='example.com/img.jpg' /&#62 </td>
					<tr>
						<td>Paragraphs</td>
						<td> &#60p&#62Paragraph&#60/p&#62 </td>
					<tr>
						<td>Line<br>Break</td>
						<td>&#60br&#62</td>
					<tr>
						<td>Line<hr></td>
						<td>&#60hr&#62</td>
					<tr>
						<td><a style='cursor:pointer;'>Link</a></td>
						<td>&#60a href='example.com'&#62Link&#60/a&#62
				</table>
				
				<span class='footnote'>HTML elements can be combined. For example, you can make an image link with <br> <b>&#60a href='http://www.relatablez.com/'&#62 &#60img src='http://www.relatablez.com/logotextwhite.png'/&#62&#60/a&#62 </b> for this result:</span><br>
				<a href='http://www.relatablez.com/'><img style='background:#BFBFBF;' src='http://www.relatablez.com/logotextwhite.png' /></a>
			</div>
			
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
					
					<input id='preview' type='submit' value='Preview' />
					<input id='submit' type='submit' value='Create' />
				</form>
			</div>
		</div>
	
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src='http://www.relatablez.com/toolbar.js'></script>
	<script type='text/javascript'>
		var reader = new FileReader();
		
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

			reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
			reader.readAsDataURL(file);
		});
	</script>
	</body>
</html>