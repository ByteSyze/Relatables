<?php
	session_start();
	
	if($_SESSION['username'] == null) die();
	
	$vote = $_POST['v'];
	
	$yes_votes 	= 1234;
	$no_votes 	= 4321;
	
	$total_votes = $yes_votes+$no_votes;
	
	$yes_prcnt = (int)($yes_votes/$total_votes*100);
	$no_prcnt  = (int)($no_votes/$total_votes*100);
	
	if($yes_votes > $no_votes)
		die("<b>Yes ($yes_votes) ($yes_prcnt%)</b><br><span>No ($no_votes) ($no_prcnt%)</span>");
	else
		die("<span>Yes ($yes_votes) ($yes_prcnt%)</span><br><b>No ($no_votes) ($no_prcnt%)</b>");