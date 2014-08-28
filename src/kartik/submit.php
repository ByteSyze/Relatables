<?php
	
	$submission = $_POST['s'];	
	$category = $_POST['c'];
	$anon = $_POST['a'];
	
	$sublen = strlen($submission);
	
	if($sublen < 10)
		echo('1');
	else if($sublen > 300)
		echo('2');
	else if(!$category)
		echo('3');
	else
		die('0');