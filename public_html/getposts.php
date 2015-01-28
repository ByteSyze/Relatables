<?php
	/*Copyright (C) Tyler Hacket 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$start		= $_POST['s'];
	$count 		= $_POST['x'];
	$order 		= $_POST['o'];
	$category 	= $_POST['c'];
	$nsfw		= $_POST['n'];
	
	$posts = Post::getPosts($start, $count, $order, $category, $nsfw);
	
	if($posts === false)
		echo "NO POST TOAST TO ROAST";
	
	foreach($posts as $post)
		$post->format();