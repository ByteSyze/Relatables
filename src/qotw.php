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
	
	$query = $connection->query("SELECT id FROM qotw ORDER BY created LIMIT 1"); //Grab latest QOTW
	$qotw = $query->fetch_row();
	
	if($statement = $connection->prepare("REPLACE INTO qotw_votes (uid, v, qid) VALUES ({$_SESSION['id']}, ?, {$qotw[0]})"))
	{
		$statement->bind_param('i',$vote);
		$statement->execute();
	}
	
	$query = $connection->query("SELECT COUNT(v) AS total_votes, (SELECT answer FROM qotw_options WHERE qotw_options.id=v AND qotw_options.qid={$qotw[0]}) AS answer FROM qotw_votes WHERE qid={$qotw[0]} GROUP BY v");
	
	echo '<table><tr>';
	while($votes = $query->fetch_assoc())
	{
		echo "<td>{$votes['answer']} ({$votes['total_votes']})</td>";
	}
	echo '</table>';
	