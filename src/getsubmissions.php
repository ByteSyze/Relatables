<?php
	
	//Convert a numerical code to MYSQL syntax for ordering a query.
	function order2mysql($order)
	{
		switch($order)
		{
			case 0:
				return 'ORDER BY submissions.date ASC';
			case 1:
				return 'ORDER BY submissions.date DESC';
			case 2:
				return 'ORDER BY submissions.notalone DESC';
			case 2:
				return 'ORDER BY submissions.alone DESC';
			default:
				return 'ORDER BY submissions.date DESC';
		}
	}
	
	//Convert a numerical code to MYSQL syntax for selecting a submission category.
	function cat2mysql($cat)
	{
		if($cat >= 0 && $cat <= 100)
			return 'AND category = '.$cat;
		else
			return '';
	}
	
	//Convert a numerical code to MYSQL syntax for including NSFW posts.
	function nsfw2mysql($nsfw)
	{
		if($nsfw == true)
			return '';
		else
			return 'AND nsfw=0';
	}
	
	$start		= $_GET['s'] ? $_GET['s'] : 0;
	$count 		= $_GET['x'] ? $_GET['x'] : 20;
	
	$order 		= order2mysql($_GET['o']);
	$category 	= cat2mysql($_GET['c']);
	$nsfw		= nsfw2mysql$_GET['n']);
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare("SELECT *, DATE_FORMAT(date,'%M %d, %Y') AS fdate FROM submissions WHERE pending = 0 $nsfw $category $order DESC LIMIT (?), (?)"))
	{
		$statement->bind_param('iii', $nsfw, $start, $start+$count);
		$statement->execute();

		$row = array();
		stmt_bind_assoc($statement, $row);

		while ($statement->fetch())
		{
			print_r($row);
			//TODO create the submission elements retrieved.
		}
	}