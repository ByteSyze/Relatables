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
		<link rel="stylesheet" type="text/css" href="http://www.relatablez.com/blog/newblogtheme.css">
		<link rel="canonical" href="http://www.relatablez.com/">
	</head>
	<body>
		<?php require($_SERVER["DOCUMENT_ROOT"] . "/toolbar.php"); ?>
	
		<div id='new-blog'>
			<h1 class='header' >Create a Blog Article</h1>
			
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
			
			<div id='article-creation' class='content'>
				<form id='article-form' method='POST'>
					<h3>Title:</h3>
					<input id='article-title' type='text' name='title' />
					
					<hr>
					
					<h3>Front Page Image:</h3>
					<input id='article-img' type='file' name='image' /><br>
					<img id='img-preview' ></img><br>
					<span  class='footnote'>Image should be atleast 850x300 to avoid pixelation.</span>
					
					<hr>
					
					<h3>Contents:</h3>
					<textarea id='article-contents' name='contents'></textarea>
					
					<input id='submit' type='submit' value='Create' />
				</form>
			</div>
			
			<h1 class='header' id='preview-title'>Preview</h1>
			<div id='article-preview' class='content'>
				<img id='preview-img' width='100%' height='300px' />
				<div style='padding:20px;font-size:17px;' id='preview-contents'></div>
			</div>
		</div>
	
	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src='http://www.relatablez.com/toolbar.js'></script>
	<script type='text/javascript'>
		var reader = new FileReader();
		var preview = document.getElementById("preview-img");
		
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

			preview.file = file;	
			reader.onload = function (e) {
				$('#preview-img').attr('src', e.target.result);
			}

			reader.readAsDataURL(file);
				
		});
		
		$("#article-title").on('keyup change paste', function(event){ $('#preview-title').html($(this).val()); });
		$("#article-contents").keypress(function(event){ if(event.keyCode == 13) $('#article-contents').val($('#article-contents').val() + "<br>"); });
		$("#article-contents").on('keyup change paste', function(event){ $('#preview-contents').html($(this).val()); });
	</script>
	</body>
</html>