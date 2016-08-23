<?php
	/*Copyright (C) Tyler Hacket 2015*/
	
	require_once $_SERVER['DOCUMENT_ROOT'] . '/global.php';
	
	$start		= $_POST['s'];
	$count 		= $_POST['x'];
	$order 		= $_POST['o'];
	$category 	= $_POST['c'];
	$nsfw		= $_POST['n'];
	
	$posts = Post::getPosts($start*$count, $count, $order, $category, $nsfw);
	
	$i = 0;
	
	foreach($posts as $post)
	{
		if($i++ % 10 == 0) {
		/*echo "<script type='text/javascript'>
  ( function() {
    if (window.CHITIKA === undefined) { window.CHITIKA = { 'units' : [] }; };
    var unit = {'calltype':'async[2]','publisher':'Relatables','width':728,'height':90,'sid':'Chitika Default'};
    var placement_id = window.CHITIKA.units.length;
    window.CHITIKA.units.push(unit);
    document.write(\"<div id='chitikaAdBlock-\" + placement_id + \"'></div>\");
}());
</script>";*/
		}
		
		$post->format();
	}	
	
