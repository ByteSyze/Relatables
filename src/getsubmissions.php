<?php
	
	require($_SERVER['DOCUMENT_ROOT'] . '/userinfo.php');
	
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
		if($cat >= 1 && $cat <= 100)
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
	
	$count+=$start;
	
	$order 		= order2mysql($_GET['o']);
	$category 	= cat2mysql($_GET['c']);
	$nsfw		= nsfw2mysql($_GET['n']);
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	if($statement = $connection->prepare("SELECT *, DATE_FORMAT(date,'%M %d, %Y') AS fdate FROM submissions WHERE pending = 0 $nsfw $category $order LIMIT ?, ?"))
	{
		$statement->bind_param('ii', $start, $count);
		$statement->execute();
		
		$statement->store_result();
		$statement->bind_result($pid, $uid, $verif, $cat, $date, $alone, $notalone, $pending, $submission, $anon, $s_nsfw, $fdate);

		while($statement->fetch())
		{
			if($anon)
				$user='Anonymous';
			else
				$user = getUsername($connection, $uid);
			
			echo "\r\n<div class='dialogue uppadding' id='{$pid}'>";
			echo "\r\n<p data-submission-id='{$pid}' data-category='{$cat}' data-nsfw='{$s_nsfw}' data-date='{$fdate}' class='dialogue'>{$submission}</p>";
			echo "\r\n<table class='vote-table'>";
			echo "\r\n<tr>";
			if($_SESSION["username"] != null)
			{
				echo "\r\n<td><button class='dialoguebutton' id='bna{$pid}' data-vid='{$pid}' data-v='{$verif}'>No, me too!</button></td>";
				echo "\r\n<td><button class='dialoguebutton' id='ba{$pid}'  data-vid='{$pid}' data-v='{$verif}'>You're alone.</button></td>";
			}
			else
			{
				echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>No, me too!</button></td>";
				echo "\r\n<td><button class='dialoguebutton showreg' data-header='Please sign up to vote'>You're alone.</button></td>";				
			}
			echo "\r\n<tr>";
			echo "\r\n<td><span class='vote-counter' id='na{$pid}'>(" . number_format($notalone) . ")</span></td>";
			echo "\r\n<td><span class='vote-counter' id='a{$pid}'>(" . number_format($alone) . ")</span></td>";
			echo "\r\n</table>";
			echo "\r\n<div style='text-align:right;'><span class='submissioninfo'><a ";
			
			if($anon)
				echo ' >' . $user . "</a> - {$fdate}</span></div>";
			else
			{
				if(isAdmin($connection, $uid))
					echo 'class=\'admin\'';
				echo " href='http://www.relatablez.com/user/" . $user . "'>" . $user . "</a> - " . $fdate . "</span></div>";
			}
			echo "\r\n</div>";
		}
	}
	else
		die($connection->error);