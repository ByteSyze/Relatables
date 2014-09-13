<?php
	session_start();
	
	if($_SESSION['username'] == null) die();
	
	$vote = $_POST['v'];
	
	$yes_votes 	= 1234;
	$no_votes 	= 4321;
	
	$total_votes = $yes_votes+$no_votes;
	
	$yes_prcnt = (int)($yes_votes/$total_votes*100);
	$no_prcnt  = (int)($no_votes/$total_votes*100);
	
	$connection = mysqli_connect('mysql.a78.org','u683362690_insom','10102S33K3R17','u683362690_rtblz');
	
	$query = $connection->query("SELECT id FROM qotw ORDER BY created LIMIT 1");
	$qotw = $query->fetch_row();
	
	if($statement = $connection->prepare("REPLACE INTO qotw_votes (uid, v, qid) VALUES ({$_SESSION['id']}, ?, {$qotw['id']})"))
	{
		$statement->bind_param('i',$vote);
		$statement->execute();
	}
	
	$query = $connection->query("SELECT option, id FROM qotw_options WHERE qid = {$qotw['id']}");
	
	if($yes_votes > $no_votes)
		die("<b>Yes ($yes_votes) ($yes_prcnt%)</b><br><span>No ($no_votes) ($no_prcnt%)</span>");
	else
		die("<span>Yes ($yes_votes) ($yes_prcnt%)</span><br><b>No ($no_votes) ($no_prcnt%)</b>");