<?php
	/*Copyright (C) Tyler Hackett 2015*/
	
	require $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$index = $_POST['i']; 
	
	$related = GlobalUtils::$user->getRelated($index, 6);
	
	for($i = 0; $i < 5; $i++)
	{	
		if($post[$i])
			echo $post[$i]->format();
	}
	
	if($related[5]) //If a 6th post exists, that means there's atleast one more post to load.
		echo '<span data-getrel="' . $index+1 . '">Load More</span>';
		