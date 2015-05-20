<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
	require $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$index  = $_POST['i']; 
	$uid	= $_POST['u'];
	
	$related = Post::getRelated($uid, $index, 5);
	
	for($i = 0; $i < 5; $i++)
	{	
		if($related[$i])
			echo $related[$i]->format();
	}
	
	if($related[5]) //If a 6th post exists, that means there's atleast one more post to load.
		echo '<span class="button" data-getrel="' . $index+1 . '">Load More</span>';
		