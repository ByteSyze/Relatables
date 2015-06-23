<?php
	/*Copyright (C) Tyler Hacket 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$start		= $_POST['s'];
	$count 		= $_POST['x'];
	$order 		= $_POST['o'];
	$category 	= $_POST['c'];
	$nsfw		= $_POST['n'];
	
	$posts = Post::getPosts($start*$count, $count, $order, $category, $nsfw);
	
	foreach($posts as $post)
		$post->format();
		
	echo '
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- BannerUnit1 -->
<ins class="adsbygoogle"
	 style="display:block"
	 data-ad-client="ca-pub-6838532745872751"
	 data-ad-slot="8158747827"
	 data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
